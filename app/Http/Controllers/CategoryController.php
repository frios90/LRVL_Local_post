<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('toCategory')->except([ 'getList',
                                                     'get',
                                                    'store',
                                                    'status', 
                                                    'getSubList',
                                                    'storeSubcategories']);
    }

    public function index()
    {
        return view('master');
    }

    public function get (Request $request) 
    {
        $category = Code::with('allSons')->find($request->input('id'));
        return response($category, 200);
    }

    public function getList () 
    {
        $categories = Code::withTrashed()  
                    ->with(['allSons.products' => function ($query) {
                        $query->take(1);
                    }])
                    ->where('type_id', '=', Code::where('code', '=', 'PRODUCT_CATEGORY')->first()->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
        $response = [];
        foreach ($categories as $category) {
            $has_products = false;
            foreach ($category->allSons as $subcategory) {
                if (count($subcategory->products) > 0) {
                    $has_products = true;
                } 
            }

            $response[] = [
                "id"          => $category->id ,
                "code"        => $category->code ,
                "label"       => $category->label,
                "type_id"     => $category->type_id,
                "company_id"  => $category->company_id , 
                "has_products" => $has_products,              
                "created_at"  => date('d-m-Y H:i:s', strtotime($category->created_at)),
                "updated_at"  => date('d-m-Y H:i:s', strtotime($category->updated_at)),
                "deleted_at"  => $category->deleted_at ? date('d-m-Y H:i:s', strtotime($category->deleted_at)) : $category->deleted_at,
            ]; 
        }
        return response($response, 200);
    }

    public function getSubList (Request $request) 
    {
        $categories = Code::with('allSons')->find($request->input('id'));
        $response = [];
        
        foreach ($categories->allSons as $subcategory) {
            $has_products = false;  
            if (count($subcategory->products) > 0) {
                $has_products = true;
            } 
            $response[] = [
                "parent"     => $categories->label,
                "id"         => $subcategory->id ,
                "code"       => $subcategory->code ,
                "label"      => $subcategory->label,
                "type_id"    => $subcategory->type_id,
                "company_id" => $subcategory->company_id , 
                "has_products" => $has_products,               
                "created_at" => date('d-m-Y H:i:s', strtotime($subcategory->created_at)),
                "updated_at" => date('d-m-Y H:i:s', strtotime($subcategory->updated_at)),
                "deleted_at" => $subcategory->deleted_at ? date('d-m-Y H:i:s', strtotime($subcategory->deleted_at)) : $subcategory->deleted_at,
            ]; 
        }
        return response($response, 200);
    }

    public function store (Request $request)
    {
        if ( $request->input('post_event') == 'store' ) {
            $request->validate([
                'label' => ['required', 'unique:codes'],                
            ]);             
            $data_category['code']    = str_replace(" ", "", mb_strtoupper($request->input('label')));  
            $data_category['label']   = $request->input('label');
            $data_category['type_id'] = Code::where('code', '=', 'PRODUCT_CATEGORY')->first()->id;     
            $category                 = Code::firstOrCreate($data_category);            
            return $category;
        } else {
            $id = $request->input('id');
            $request->validate([
                'label' => ['required', 'unique:codes,label,'.$id],              
            ]);
            $category        = Code::find($id);
            $category->code  = str_replace(" ", "", mb_strtoupper($request->input('label')));  
            $category->label = $request->input('label');
            $category->save();
            return $category;
        }               
    }

    public function status (Request $request)
    {
        $id = $request->input('id');
        $category = Code::where('id', '=', $request->input('id'))->withTrashed()->first();        
        if ($category->deleted_at) {
            $category->restore();
        } else {
            $category->delete();
        }
        return response(Code::withTrashed()->get(), 200);
    }

    public function storeSubcategories (Request $request)
    {
        if ( $request->input('post_event') == 'store' ) {
            $request->validate([
                'label' => ['required', 'unique:codes'],                
            ]);           
            $data_category['code']    = str_replace(" ", "", mb_strtoupper($request->input('label')));  
            $data_category['label']   = $request->input('label');
            $data_category['type_id'] = $request->input('category_id');     
            $category                 = Code::firstOrCreate($data_category);            
            return $category;
        } else {
            $id = $request->input('id');
            $request->validate([
                'label' => ['required', 'unique:codes,label,'.$id],              
            ]);
            $category        = Code::find($id);
            $category->code  = str_replace(" ", "", mb_strtoupper($request->input('label')));  
            $category->label = $request->input('label');
            $category->save();
            return $category;
        }      
    }
   
}
