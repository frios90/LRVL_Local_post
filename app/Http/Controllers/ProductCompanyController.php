<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\CompanyProduct;
use App\Models\Code;
use App\Models\Company;

use Auth;
class ProductCompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('toProductCompany')->except(    
            [                
                'get', 
                'getList', 
                'status', 
                'getCategorySubcategories',
                'getProductToAllocation',
                'companyProductAllocationStore',
                'setCheckCompanyProduct',
                'getProductReception',
                'getProductInventory',
                'getProductToAddSale',
                'getProductsAutoSearch',
            ]
        );
    }

    public function index()
    {
        return view('master');
    }

    public function get (Request $request) 
    {       
        $company_product = CompanyProduct::with('product',
                                    'product.category.parent', 
                                    'product.brand',
                                    'product.images' )
                            ->find($request->input('id'));
        $data['company_product'] = $company_product;  
        $data['image_main']      = $company_product->product->images->where('principal', true)->first();
        $data['array_images']    = [];
        foreach ($company_product->product->images as $image ) {
            $data['array_images'][] = $image->path;
        }       
        return response($data, 200);
    }
 
    public function getList () 
    {
        $company_id = Auth::user()->company_id;
        $mono_company_product = CompanyProduct::with('product')
                        ->withTrashed()
                        ->where('company_id', '=', $company_id)  
                        ->orderBy('created_at', 'desc')
                        ->get(); 
        $response = [];
        foreach ($mono_company_product as $mono) {
            $response[] = [
                "id"             => $mono->id ,
                "alternative_barcode" => $mono->alternative_barcode ,
                "product_id"           => $mono->product_id ,
                "company_id"   => $mono->company_id ,
                "qty"          => $mono->qty ? $mono->qty : 0 ,
                "critical_qty" => $mono->critical_qty ? $mono->critical_qty : 0,
                "price"        => $mono->price ,
                "product"      => $mono->product ,                
                "created_at"   => date('d-m-Y H:i:s', strtotime($mono->created_at)),
                "updated_at"   => date('d-m-Y H:i:s', strtotime($mono->updated_at)),
                "deleted_at"   => $mono->deleted_at ? date('d-m-Y H:i:s', strtotime($mono->deleted_at)) : $mono->deleted_at,
               
            ]; 
        }
        return response($response, 200);
    }

    public function getProductsAutoSearch (Request $request) 
    {
        $company_id = Auth::user()->company_id;
        $company_products = Product::where(function ($query) use ($request) {
                                $query->where('products.barcode', 'like', '%'.$request->input('value').'%');
                                $query->orWhere('products.name', 'like', '%'.$request->input('value').'%');
                            })
                            ->whereHas('companies', function ($query) use ($company_id) {
                                $query->where('company_id', '=', $company_id);
                                $query->where('price', '>', 0);
                            })
                        ->get();
        $list = [];
        foreach ($company_products as $product) {
            $list[] = $product->barcode . '-' . $product->name;
        }
        return response($list, 200);
    }

    public function status (Request $request)
    {
        $id      = $request->input('id');
        $product = CompanyProduct::where('id', '=', $request->input('id'))->withTrashed()->first();        
        if ($product->deleted_at) {
            $product->restore();
        } else {
            $product->delete();
        }
        return response(Product::withTrashed()->get(), 200);
    }

    public function getCategorySubcategories (Request $request) 
    {
        $subcategories = Code::where('type_id', '=', $request->input('category_id'))                    
                                ->get();
        return response($subcategories, 200);
    }   

    public function getProductReception (Request $request) 
    {
        $product = Product::with('companies')->where('barcode', '=', $request->input('barcode'))->first();
        
        $has                = false;  
        $product_in_company = false; 
        if ($product) {
            $product_in_company = CompanyProduct::where('product_id', '=', $product->id)
            ->where('company_id', '=', \Auth::user()->company_id)
            ->first();
        }
        
         
        if ($product_in_company) {
            $has = true; 
        }
            
        if (!$product) {
            $product = [
                'barcode'       => $request->input('barcode'), 
                'name'          => 'Producto desconocido',
                'description'   => 'El producto no se encuentra registrado en la base de datos',
                'base_price'    => '000',
                'cost_provider' => '',
                'id'            => 'null',
                'flag'          => 'not_exist'
            ];
        } else if (!$has) {
            $product = [
                'barcode'       => $product->barcode, 
                'name'          => $product->name,
                'description'   => $product->description,
                'base_price'    => $product->base_price,
                'cost_provider' => '',
                'id'            => $product->id,
                'flag'          => 'not_allocation'
            ];
        } else {
            $product = [
                'barcode'       => $product->barcode, 
                'name'          => $product->name,
                'description'   => $product->description,
                'cost_provider' => '',
                'base_price'    => $product->base_price,
                'id'            => $product->id,
                'flag'          => 'ok'
            ];
        } 

        return response($product, 200);
    }

    public function getProductInventory (Request $request) 
    {
        $product            = Product::with('companies')->where('barcode', '=', $request->input('barcode'))->first();        
        $has                = false;  
        $product_in_company = false; 
       
        if ($product) {
            $product_in_company = CompanyProduct::where('product_id', '=', $product->id)
                                                ->where('company_id', '=', \Auth::user()->company_id)
                                                ->first();
        } 
                
        if ($product_in_company) {
            $has = true; 
        }
            
        if (!$product) {
            $product = [
                'barcode'     => $request->input('barcode'), 
                'name'        => 'Producto desconocido',
                'description' => 'El producto no se encuentra registrado en la base de datos',
                'base_price'  => '000',
                'id'          => 'null',
                'flag'        => 'not_exist'
            ];
        } else if (!$has) {
            $product = [
                'barcode'     => $product->barcode, 
                'name'        => $product->name,
                'description' => $product->description,
                'base_price'  => $product->base_price,
                'id'          => $product->id,
                'flag'        => 'not_allocation'
            ];
        } else {
            $product = [
                'barcode'     => $product->barcode, 
                'name'        => $product->name,
                'description' => $product->description,
                'base_price'  => $product->base_price,
                'id'          => $product->id,
                'flag'        => 'ok'
            ];
        } 

        return response($product, 200);
    }

    public function postChangePrice (Request $request)
    {
        $product = CompanyProduct::where('id', '=', $request->input('id'))->withTrashed()->first();        
        $product->price = $request->input('price');
        $product->save();
        return response($product, 200); 
    }

    public function postChangeQty (Request $request)
    {
        $product = CompanyProduct::where('id', '=', $request->input('id'))->withTrashed()->first();        
        $product->qty = $request->input('qty');
        $product->save();
        return response($product, 200); 
    }

    public function getProductToAddSale (Request $request) 
    {
        $barcode = $request->input('barcode');
        $product = CompanyProduct::whereHas('product',function ($query) use ($barcode){
                                        $query->where('barcode', $barcode);
                                    })
                                    ->where('company_id', '=', \Auth::user()->company_id)
                                    ->with(['product'])
                                    ->first();
        \Log::info($product );
        $response = false;    
        if ($product) {
            $response = [
                'barcode'     => $product->product->barcode, 
                'name'        => $product->product->name,
                'description' => $product->product->description,
                'base_price'  => $product->price,
                'total_price' => $product->price,
                'id'          => $product->id,
                'stock_able'  => $product->qty,
                'qty'         => 1
            ];
        } 

        return response($response, 200);
    }

    
}
