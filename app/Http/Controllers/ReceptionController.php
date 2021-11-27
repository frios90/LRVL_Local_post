<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Company;
use App\Models\Reception;
use App\Models\Code;
use App\Models\DetailReception;
use App\Models\CompanyProduct;
use App\Models\CompanyProductStock;
use App\Models\CompanyProductProvider;

use Auth;
class ReceptionController extends Controller
{
    public function index()
    {
        return view('master');
    }

    public function getList () 
    {   
        $receptions = Reception::with('receptionStatus', 'provider', 'user')
                            ->where('company_id', '=', \Auth::user()->company_id)
                            ->withTrashed()
                            ->orderBy('created_at', 'desc')
                            ->get();
                       
        $response = [];
        foreach ($receptions as $reception) {
           
            $response[] = [
                "id"                  => $reception->id ,
                "user_id"             => $reception->user_id ,
                "reception_status_id" => $reception->reception_status_id ,
                "provider_id"         => $reception->provider_id ,
                "company_id"          => $reception->company_id ,
                "guide"               => $reception->guide ,
                "guide_date"          => $reception->guide_date ,
                "reception_status"    => $reception->receptionStatus ,
                "provider"            => $reception->provider ,
                "user"                => $reception->user ,
                "created_at"          => date('d-m-Y H:i:s', strtotime($reception->created_at)),
                "updated_at"          => date('d-m-Y H:i:s', strtotime($reception->updated_at)),
                "deleted_at"          => $reception->deleted_at ? date('d-m-Y H:i:s', strtotime($reception->deleted_at)) : $reception->deleted_at,
                "loaded_at"           => $reception->loaded_at ? date('d-m-Y H:i:s', strtotime($reception->loaded_at)) : $reception->loaded_at,
            ]; 
        }
        return response($response, 200);
    }  
    
    public function get (Request $request) 
    {
        return response(Reception::find($request->input('id')), 200);
    }

    public function status (Request $request)
    {
        $id = $request->input('id');
        $row = Reception::where('id', '=', $request->input('id'))->withTrashed()->first();        
        if ($row->deleted_at) {
            $row->restore();
        } else {
            $row->delete();
        }
        return response(Reception::withTrashed()->get(), 200);
    }  
    
    public function finish (Request $request)
    {
        $request->validate([           
            'guide'      => ['required'],
            'guide_date' => ['required'],
            'provider_id'=> ['required']            
        ]);
        $reception = Reception::create([
            'user_id'             => Auth::user()->id,
            'company_id'          => Auth::user()->company_id,
            'provider_id'         => $request->input('provider_id'),
            'reception_status_id' => Code::where('type_id','=', Code::where('code', '=', 'RECEPTIONS')->first()->id)
                                            ->where('code', '=', 'OPEN')
                                            ->first()->id,
            'guide'               => $request->input('guide'),
            'guide_date'          => $request->input('guide_date'),
        ]);
        $list = $request->input('list');
        
        foreach ($list as $product) {           
            $company_product = CompanyProduct::where('product_id', '=', $product['id'])
                                            ->where('company_id', '=', \Auth::user()->company_id)
                                            ->first();            
            if ($product['id'] != 'null' && $company_product) {
                $detail = DetailReception::create([
                    'reception_id'       => $reception->id,
                    'product_company_id' => $company_product->id,
                    'qty'                => $product['qty']
                ]);
                $this->setCostCompanyProductProvider($request->input('provider_id'), $company_product->id, $product['cost_provider']);      
            }
        }
        return response('ok', 200);
    } 
    
    private function setCostCompanyProductProvider ($provider_id, $company_product_id, $cost_provider ) 
    {
        if (!$cost_provider) {
            return false;
        } 

        $cpp = CompanyProductProvider::where('provider_id', '=', $provider_id)
                                        ->where('company_product_id', '=', $company_product_id)
                                        ->where('company_id', '=', \Auth::user()->company_id)
                                        ->whereNull('deleted_at')
                                        ->first();

        if ( $cpp && (int)$cost_provider == (int)$cpp->provider_price ) {
            return false;
        } 

        if ( $cpp && (int)$cost_provider != (int)$cpp->provider_price ) {
            $cpp->delete();
        } 

        $new_cost = CompanyProductProvider::create([
            'company_product_id' => $company_product_id,
            'provider_id'        => $provider_id,
            'company_id'         => \Auth::user()->company_id,
            'provider_price'     => $cost_provider
        ]);
    }

    public function getReception (Request $request) 
    {
        $reception = Reception::with('detailReception.companyProduct.product')->where('id', '=', $request->input('id'))->first();
        $detail_items = [];      
        foreach ($reception->detailReception as $detail) {
            $product_provider = CompanyProductProvider::where('company_id', '=', \Auth::user()->company_id)
                                                        ->where('company_product_id', '=', $detail->companyProduct->id)
                                                        ->where('provider_id', '=', $reception->provider_id)
                                                        ->first();
            $detail_items[] = [
                "barcode"        => $detail->companyProduct->product->barcode,
                "name"           => $detail->companyProduct->product->name,
                "description"    => $detail->companyProduct->product->description,
                "cost_provider"  => $product_provider ? $product_provider->provider_price : '',
                "base_price"     => $detail->companyProduct->product->base_price,
                "id"             => $detail->companyProduct->product->id,
                "qty"            => $detail->qty,                
            ];
        }
        $data['reception'] = [
            "id"          => $reception->id,
            "provider_id" => $reception->provider_id,
            "guide"       => $reception->guide,
            "guide_date"  => $reception->guide_date
        ];
        $data['details'] = $detail_items; 
        return response($data, 200);
    }

    public function getReceptionToSee (Request $request) 
    {
        $reception = Reception::with('detailReception.companyProduct.product', 'provider')->where('id', '=', $request->input('id'))->first();
        $detail_items = [];      
        foreach ($reception->detailReception as $detail) {
            $product_provider = CompanyProductProvider::where('company_id', '=', \Auth::user()->company_id)
                                                        ->where('company_product_id', '=', $detail->companyProduct->id)
                                                        ->where('provider_id', '=', $reception->provider_id)
                                                        ->first();
            $detail_items[] = [
                "barcode"     => $detail->companyProduct->product->barcode,
                "name"        => $detail->companyProduct->product->name,
                "description" => $detail->companyProduct->product->description,
                "cost_provider"  => $product_provider ? $product_provider->provider_price : '',
                "base_price"  => $detail->companyProduct->product->base_price,
                "id"          => $detail->companyProduct->product->id,
                "qty"         => $detail->qty,                
            ];
        }
        $data['reception'] = [
            "id"          => $reception->id,
            "provider"    => $reception->provider->name,
            "guide"       => $reception->guide,
            "guide_date"  => date('d-m-Y', strtotime($reception->guide_date)),
            "updated_at"  => date('d-m-Y', strtotime($reception->updated_at))
        ];
        $data['details'] = $detail_items; 
        return response($data, 200);
    }

    public function update (Request $request)
    {
        $reception              = Reception::find($request->input('id'));
        $reception->guide       = $request->input('guide') ;
        $reception->guide_date  = $request->input('guide_date') ;
        $reception->provider_id = $request->input('provider_id');
        $reception->user_id     = Auth::user()->id;
        $reception->company_id  = Auth::user()->company_id;
        $reception->save();
        $list                   = $request->input('list');
        DetailReception::where('reception_id', '=', $reception->id)->forcedelete();
        foreach ($list as $product) {
            $company_product = CompanyProduct::where('product_id', '=', $product['id'])
                                ->where('company_id', '=', \Auth::user()->company_id)
                                ->first();
           
            if ($product['id'] != 'null' && $company_product) {
                $detail = DetailReception::create([
                    'reception_id'       => $reception->id,
                    'product_company_id' => $company_product->id,
                    'qty'                => $product['qty']
                ]);
                $this->setCostCompanyProductProvider($request->input('provider_id'), $company_product->id, $product['cost_provider']);
            }                     
        }
        return response('ok', 200);
    }  

    public function uploadMaster (Request $request) 
    {
        $reception  = Reception::with('detailReception')->find($request->input('id'));   
    
        foreach ($reception->detailReception as $detail) {
            $company_product = CompanyProduct::find($detail->product_company_id);
            $company_product->qty = $company_product->qty + $detail->qty;
            $company_product->save();          
        }       
        $reception->reception_status_id = Code::where('type_id','=', Code::where('code', '=', 'RECEPTIONS')->first()->id)
                                                ->where('code', '=', 'CLOSED')
                                                ->first()
                                                ->id;
        $reception->loaded_at = date('Y-m-d H:i:s');
        $reception->save();
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
