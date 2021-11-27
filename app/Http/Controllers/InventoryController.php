<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Company;
use App\Models\Inventory;
use App\Models\Code;
use App\Models\DetailInventory;
use App\Models\CompanyProduct;
use App\Models\CompanyProductStock;
use Auth;

class InventoryController extends Controller
{
    public function index()
    {
        return view('master');
    }

    public function getList () 
    {   
        $inventories = Inventory::with('inventoryStatus', 'user')
                            ->where('company_id', '=', \Auth::user()->company_id)
                            ->withTrashed()
                            ->orderBy('created_at', 'desc')
                            ->get();

        $response = [];
        foreach ($inventories as $inventory) {
           
            $response[] = [
                "id"             => $inventory->id ,
                "user_id"            => $inventory->user_id ,
                "inventory_status_id" => $inventory->inventory_status_id ,
                "company_id"          => $inventory->company_id ,
                "inventory_status"      => $inventory->inventoryStatus ,
                "provider"     => $inventory->provider ,
                "user"        => $inventory->user ,
                "created_at"     => date('d-m-Y H:i:s', strtotime($inventory->created_at)),
                "updated_at"     => date('d-m-Y H:i:s', strtotime($inventory->updated_at)),
                "deleted_at"       => $inventory->deleted_at ? date('d-m-Y H:i:s', strtotime($inventory->deleted_at)) : $inventory->deleted_at,
                "loaded_at"       => $inventory->loaded_at ? date('d-m-Y H:i:s', strtotime($inventory->loaded_at)) : $inventory->loaded_at,
            ]; 
        }
        return response($response, 200);
    }  
    
    public function get (Request $request) 
    {
        $inventory = Inventory::find($request->input('id'))->withTrashed();
        return response($inventory, 200);
    }

    public function status (Request $request)
    {
        $id = $request->input('id');
        $row = Inventory::where('id', '=', $request->input('id'))->withTrashed()->first();        
        if ($row->deleted_at) {
            $row->restore();
        } else {
            $row->delete();
        }
        return response(Inventory::withTrashed()->get(), 200);
    }  
    
    public function finish (Request $request)
    {       
        $inventory = Inventory::create([
            'user_id'             => Auth::user()->id,
            'company_id'          => Auth::user()->company_id,
            'inventory_status_id' => Code::where('type_id','=', Code::where('code', '=', 'INVENTORIES')->first()->id)
                                        ->where('code', '=', 'OPEN')
                                        ->first()->id            
        ]);
        $list = $request->input('list');
        foreach ($list as $product) {
            $company_product = CompanyProduct::where('product_id', '=', $product['id'])->where('company_id', '=', \Auth::user()->company_id)->first();
            if ($product['id'] != 'null' && $company_product) {
                $detail = DetailInventory::create([
                    'inventory_id'       => $inventory->id,
                    'product_company_id' => $company_product->id,
                    'qty'                => $product['qty']
                ]);
            }            
        }
        return response(true, 200);
    }  

    public function getinventory (Request $request) 
    {
        $inventory = Inventory::with('detailInventory.companyProduct.product')
                                ->withTrashed()
                                ->where('id', '=', $request->input('id'))
                                ->first();
        $detail_items = [];      
        foreach ($inventory->detailInventory as $detail) {
            $detail_items[] = [
                "barcode"     => $detail->companyProduct->product->barcode,
                "name"        => $detail->companyProduct->product->name,
                "description" => $detail->companyProduct->product->description,
                "base_price"  => $detail->companyProduct->product->base_price,
                "id"          => $detail->companyProduct->product->id,
                "qty"         => $detail->qty,                
            ];
        }
        $data['inventory'] = [
            "id"          => $inventory->id            
        ];
        $data['details'] = $detail_items; 
        return response($data, 200);
    }

    public function update (Request $request)
    {
        $inventory = Inventory::where('id', '=', $request->input('id'))->first();
        $inventory->user_id     = \Auth::user()->id;
        $inventory->company_id  = \Auth::user()->company_id;
        $inventory->save();
        $list                   = $request->input('list');
        DetailInventory::where('inventory_id', '=', $inventory->id)->forcedelete();
        foreach ($list as $product) {
            $company_product = CompanyProduct::where('product_id', '=', $product['id'])->where('company_id', '=', \Auth::user()->company_id)->first();
            if ($product['id'] != 'null' && $company_product) {
                $detail = DetailInventory::create([
                    'inventory_id'       => $inventory->id,
                    'product_company_id' => $company_product->id,
                    'qty'                => $product['qty']
                ]);
            }            
        }
        return response('ok', 200);
    }  

    public function uploadMaster (Request $request) 
    {
        $inventory  = Inventory::with('detailInventory')->find($request->input('id'));   
    
        foreach ($inventory->detailInventory as $detail) {
            \Log::info($detail);
            $company_product      = CompanyProduct::find($detail->product_company_id);
            $company_product->qty = $detail->qty;
            $company_product->save();          
        }       
        $inventory->inventory_status_id = Code::where('type_id','=', Code::where('code', '=', 'inventories')->first()->id)
                                                ->where('code', '=', 'CLOSED')
                                                ->first()
                                                ->id;
        $inventory->save();
        return response('ok', 200);
    }

    public function addToMaster (Request $request)
    {
        $company    = Company::find((int)\Auth::user()->company_id);
        $product_id = $request->input('product_id');
        $has_company_product = CompanyProduct::where('product_id', '=', $product_id)
                                            ->withTrashed()
                                            ->where('company_id', '=', $company->id)
                                            ->first();
        if (!$has_company_product) {
            $company->products()->attach([$product_id => ['qty' => 0, 'price' => 0]]);
            return response($company, 200); 
        } else if ($has_company_product->deleted_at) {
            $has_company_product->restore();
        } else {
            $has_company_product->delete();
        } 
    }
}
