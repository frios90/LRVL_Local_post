<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('toBrand')->except([
            'getList',
            'get', 
        ]);
    }

    public function index()
    {
        return view('master');
    }

    public function get (Request $request) 
    {
        $brand = Code::find($request->input('id'));
        return response($brand, 200);
    }

    public function getList () 
    {
        $brands = Code::withTrashed()
                    ->with('brandProducts')  
                    ->where('type_id', '=', Code::where('code', '=', 'PRODUCT_BRAND')->first()->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        foreach ($brands as $brand) {
            $has_products = false;
            if (count($brand->brandProducts) > 0) {
                $has_products = true;
            } 
            $response[] = [
                "id"         => $brand->id ,
                "code"       => $brand->code ,
                "label"      => $brand->label,
                "type_id"    => $brand->type_id,
                "company_id" => $brand->company_id , 
                "has_products" => $has_products,               
                "created_at" => date('d-m-Y H:i:s', strtotime($brand->created_at)),
                "updated_at" => date('d-m-Y H:i:s', strtotime($brand->updated_at)),
                "deleted_at" => $brand->deleted_at ? date('d-m-Y H:i:s', strtotime($brand->deleted_at)) : $brand->deleted_at,
            ]; 
        }
        return response($response, 200);
        return response($brands, 200);
    }

    public function store (Request $request)
    {
        if ( $request->input('post_event') == 'store' ) {
            $request->validate([
                'label' => ['required', 'unique:codes'],                
            ]);           
            $data_brand['code']    = str_replace(" ", "", mb_strtoupper($request->input('label')));  
            $data_brand['label']   = $request->input('label');
            $data_brand['type_id'] = Code::where('code', '=', 'PRODUCT_BRAND')->first()->id;     
            $brand                 = Code::firstOrCreate($data_brand);            
            return $brand;
        } else {
            $id = $request->input('id');
            $request->validate([
                'label' => ['required', 'unique:codes,label,'.$id],              
            ]);
            $brand        = Code::find($id);
            $brand->code  = str_replace(" ", "", mb_strtoupper($request->input('label')));  
            $brand->label = $request->input('label');
            $brand->save();
            return $brand;
        }               
    }

    public function status (Request $request)
    {
        $id = $request->input('id');
        $brand = Code::where('id', '=', $request->input('id'))->withTrashed()->first();        
        if ($brand->deleted_at) {
            $brand->restore();
        } else {
            $brand->delete();
        }
        return response(Code::withTrashed()->get(), 200);
    }

 
}
