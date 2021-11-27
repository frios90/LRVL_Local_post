<?php //b4f2e6d787e3632e35b6465fb958eef5

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Code;
use App\Models\Company;
use App\Models\License;
use App\Models\CompanyLicense;

use App\Models\ProductImage;

use Auth;
use LicenseHelper;

class ProductController extends Controller
{ 
    public function __construct()
    {
        $this->middleware('toProduct')->except(    
            [                
                'get', 
                'getList', 
                'getCategorySubcategories',
                'getProductToAllocation',
                'store',
                'status',
                'getCategorySubcategories',
                'getProductToAllocation',
                'companyProductAllocationStore',
                'companyProductAllocationAllStore' ,
                'storePhoto',
                'postDeletePhoto',
                'getProductToUploadPhotos',
                'postPrincipalPhoto'
            ]
        );
    }

    public function index()
    {
        $license = LicenseHelper::has('L_AUTO_ADD_PRODUCT_TO_COMPANY');
        \Log::info($license);
        return view('master');
    }

    public function get (Request $request) 
    {       
        $product                   = Product::with('category', 'brand')->find($request->input('id'));
        $data['product']           = $product;
        $data['selected_category'] = Code::find($product->category_id)->type_id;
        $data['subcategories']     = Code::where('type_id', '=', $data['selected_category'])
                                            ->get();    
        return response($data, 200);
    }
 
    public function getList () 
    {
        $products = Product::withTrashed()                     
                        ->orderBy('name', 'desc')
                         ->get();    
    
        return response($products, 200);
    }

    public function store (Request $request)
    {
        if ( $request->input('post_event') == 'store' ) {
            $request->validate([
                'barcode'        => ['required','unique:products'],
                'name'           => ['required'],
                'description'    => ['required'],
                'brand_id'       => ['required'],
                'category_id'    => ['required'],
                'subcategory_id' => ['required'],
               
            ]);
            $data_product['barcode']        = $request->input('barcode');
            $data_product['name']           = $request->input('name');
            $data_product['description']    = $request->input('description');
            $data_product['brand_id']       = $request->input('brand_id');   
            $data_product['category_id']    = $request->input('subcategory_id'); 
            $product                        = Product::firstOrCreate($data_product); 
            
            $company_licenses = CompanyLicense::where('license_id', '=', License::where('code', '=', 'L_AUTO_ADD_PRODUCT_TO_COMPANY')->first()->id)
                                             ->get()
                                             ->pluck('company_id')
                                             ->toArray();
            $sync_company_licenses = [];
            foreach ($company_licenses as $company) {
                $sync_company_licenses[$company] = ['qty' => 0, 'price' => 0];
                
            }   
                                     
            $product->companies()->sync($sync_company_licenses);            
        } else {
            $id = $request->input('id');
            $request->validate([
                'barcode'        => ['required', 'unique:products,barcode,'.$id],
                'id'             => ['required'],
                'name'           => ['required'],
                'description'    => ['required'],
                'category_id'    => ['required'],
                'brand_id'       => ['required'],
                'subcategory_id' => ['required'],
            ]);
            $product              = Product::find($id);
            $product->barcode     = $request->input('barcode');
            $product->name        = $request->input('name');
            $product->description = $request->input('description');
            $product->brand_id    = $request->input('brand_id');
            $product->category_id = $request->input('subcategory_id');
            $product->save();
        }          
        return $product;          
    }

    public function status (Request $request)
    {
        $id      = $request->input('id');
        $product = Product::where('id', '=', $request->input('id'))->withTrashed()->first();   
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

    public function getProductToAllocation (Request $request) 
    {

        $products = Product::with('category.parent', 'brand', 'companies', 'images')      
                        ->where(function ($query) use ($request) {
                            $query->where('products.name' ,'like', '%'.$request->input('name').'%');
                        } ) 
                        ->where(function ($query) use ($request) {
                            $query->where('products.barcode' ,'like', '%'.$request->input('barcode').'%');
                        } ) 
                        ->where(function ($query) use ($request) {
                            if ($request->input('brand_id')) {
                                $query->where('products.brand_id' ,'=', $request->input('brand_id'));
                            }                            
                        } ) 
                        ->where(function ($query) use ($request) {
                            if ($request->input('subcategory_id')) {
                                $query->where('products.category_id' ,'=', $request->input('subcategory_id'));
                            }                            
                        } )                        
                        ->whereHas('category.parent', function ($query) use ($request){
                            if ($request->input('category_id')) {
                                $query->where('id', '=', $request->input('category_id'));
                            }
                        })
                        ->orderBy('name', 'desc')
                        ->get();
        $list_to_select = [];
        foreach ($products as $product ) {
            $list_to_select[] = [
                'id'          => $product->id,
                'barcode'     => $product->barcode,
                'name'        => $product->name,
                'description' => $product->description,
                'category'    => $product->category->parent->label,
                'subcategory' => $product->category->label,
                'brand'       => $product->brand->label,
                'main_image'  => $product->images->where('principal', true)->first(),
                'check'       => $this->setCheckCompanyProduct($product->companies, $request->input('company_id')),
            ];

        }                
        return response($list_to_select, 200);
    }

    private function setCheckCompanyProduct ($companies, $company_id) 
    {
        if (count($companies) == 0) {
            return false;
        } 
        foreach ($companies as $company) {
            if ($company->id == $company_id) {
                 return true ;     
            }            
        }
        return false;
    }

    public function companyProductAllocationStore (Request $request)
    {
       $company = Company::find($request->input('company'));
       $product = $request->input('product');
       if ($product['check']) {
        $company->products()->detach([$product['id']]);
       } else {
        $company->products()->attach([$product['id'] => ['qty' => 0, 'price' => 0]]);
       }
       return response($company, 200);       
    }

    public function companyProductAllocationAllStore (Request $request)
    {
        $filters  = $request->input('filters');
        $products = Product::with('category.parent', 'brand', 'companies', 'images')      
                        ->where(function ($query) use ($filters) {
                            $query->where('products.name' ,'like', '%'.$filters['name'].'%');
                        } ) 
                        ->where(function ($query) use ($filters) {
                            $query->where('products.barcode' ,'like', '%'.$filters['barcode'].'%');
                        } ) 
                        ->where(function ($query) use ($filters) {
                            if ($filters['brand_id']) {
                                $query->where('products.brand_id' ,'=', $filters['brand_id']);
                            }                            
                        } ) 
                        ->where(function ($query) use ($filters) {
                            if ($filters['subcategory_id']) {
                                $query->where('products.category_id' ,'=', $filters['subcategory_id']);
                            }                            
                        } )                        
                        ->whereHas('category.parent', function ($query) use ($filters){
                            if ($filters['category_id']) {
                                $query->where('id', '=', $filters['category_id']);
                            }
                        })                      
                        ->get()
                        ->pluck('id')
                        ->toArray();
        $array_products = [];
        foreach ($products as $product) {
            $array_products[$product] = ["qty" => 0, "price" => 0];
        }
        
        $company = Company::find($filters['company_id']);
        $company->products()->sync($array_products);
       

    }

    public function storePhoto(Request $request)
    {
        if ($request->hasFile('file')) {
            $product_id  = $request->input('product_id');
            $size        = $request->file->getSize();
            $file_name   = $request->file->getClientOriginalName();
            $extension   = $request->file->getClientOriginalExtension();
            $new_name    = $product_id .'_'.time() . '.' . $extension;
            $upload_path = public_path('upload/product_'. $product_id);
            $path        = 'upload/product_'. $product_id.'/'. $new_name;
            if ($extension != 'jpg' && $extension != 'jpeg' ) {
                return response()->json(['error' => '*Debe cargar un Archivo valido (.jpg, .jpeg)']);
            }
            if ( empty($size) || $size >= 2097152) {
                return response()->json(['error' => '*El Archivo es demasiado grande(2MB max.)']);
            }        
            $request->file->move($upload_path, $new_name);
            ProductImage::create([
                'product_id'    => $product_id,
                'original_name' => $file_name,
                'name'          => $new_name,
                'size'          => $size,
                'extension'     => $extension,
                'path'          => $path,
                'principal'     => false         
           ]);
        } else {
            return response()->json(['error' => '*Debe seleccionar un Archivo valido ([2MB max.], [.jpg, .jpeg])']);
        }        
    }

    public function postDeletePhoto (Request $request) 
    {        
        $photo_id  = $request->input('photo_id');       
        $photo     = ProductImage::find($photo_id);     
        unlink(public_path($photo->path));
        $photo->forceDelete();
        return response($photo, 200);
    }

    public function getProductToUploadPhotos (Request $request) 
    {
        $product = Product::with('images')->find($request->input('id'));
        return response( $product , 200);
    }

    public function postPrincipalPhoto (Request $request) 
    {        
        $photo_id  = $request->input('photo_id');       
        $images    = Product::with('images')->find($request->input('product_id'))->images;
        \Log::info($images);
        foreach($images as $image) {
            $image = ProductImage::find($image->id);
            if ($image->id == $photo_id) {
                $image->principal = true;
            } else {
                $image->principal= false;
            }
            $image->save();            
        }
        return response($photo_id, 200);
    }

}