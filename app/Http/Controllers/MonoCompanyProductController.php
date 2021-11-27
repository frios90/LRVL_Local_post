<?php //b4f2e6d787e3632e35b6465fb958eef5

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Code;
use App\Models\Company;
use App\Models\License;
use App\Models\CompanyLicense;
use App\Models\CompanyProduct;
use App\Models\ProductImage;
use App\Models\CompanyProductProvider;

use Auth;
use LicenseHelper;

class MonoCompanyProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('toMonoCompanyProduct')->except(    
            [                
                'get', 
                'getList', 
                'getCategorySubcategories',
                'getProductToAllocation',    
                'storePhoto',
                'postDeletePhoto',
                'getProductToUploadPhotos',
                'postPrincipalPhoto',
                'postChangePrice',
                'postChangeQty',
                'postChangeCriticalQty'


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
        $data['subcategories']   = Code::where('type_id', '=', $company_product->product->category->parent->id)
                                            ->get();
        
      
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

    public function store (Request $request)
    {
        if ( $request->input('post_event') == 'store' ) {
            $request->validate([
                'barcode'        => ['required','unique:products'],
                'name'           => ['required'],
                'description'    => ['required'],
                'brand_id'       => ['required'],
                'category_id'    => ['required'],
                'subcategory_id' => ['required']               
            ]);
            $data_product['barcode']        = $request->input('barcode');
            $data_product['name']           = $request->input('name');
            $data_product['description']    = $request->input('description');
            $data_product['brand_id']       = $request->input('brand_id');   
            $data_product['category_id']    = $request->input('subcategory_id'); 
            $product                        = Product::firstOrCreate($data_product); 
            
            $sync_company_licenses[\Auth::user()->company_id] = ['qty' => 0, 'price' => 0];            
            $product->companies()->sync($sync_company_licenses);            
        } else {
            $id = $request->input('id');
            $company_product = CompanyProduct::find($id);
            $id = $company_product->product_id;
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

    public function postChangeCriticalQty (Request $request)
    {
        $product = CompanyProduct::where('id', '=', $request->input('id'))->withTrashed()->first();        
        $product->critical_qty = $request->input('critical_qty');
        $product->save();
        return response($product, 200); 
    }

    public function getCompanyProductProvider (Request $request) 
    {
        $company_product = CompanyProduct::with('product', 'productProvider.provider')
                                        ->withTrashed()
                                        ->find($request->input('id'));  
        
        $products_provider = [];
        foreach ( $company_product->productProvider as $key => $ppvd ) {
            $products_provider[] = [
                "id"                 => $ppvd->id ,
                "company_product_id" => $ppvd->company_product_id ,
                "provider_id"        => $ppvd->provider_id,
                "company_id"         => $ppvd->company_id,
                "provider_price"     => $ppvd->provider_price, 
                "provider"           => $ppvd->provider,           
                "created_at"         => date('d-m-Y H:i:s', strtotime($ppvd->created_at)),
                "updated_at"         => date('d-m-Y H:i:s', strtotime($ppvd->updated_at)),
                "deleted_at"         => $ppvd->deleted_at ? date('d-m-Y H:i:s', strtotime($ppvd->deleted_at)) : $ppvd->deleted_at,
            ]; 
        }
        $data = [
            "name" => $company_product->product->name,
            "barcode" => $company_product->product->barcode,
            "product_provider" => $products_provider,
        ];
        
        return response($data, 200);
    }

    public function postProviderCost (Request $request)
    {
       
        $request->validate([
            'provider_id' => ['required'],
            'cost'        => ['required']                    
        ]);

        $provider_costs = CompanyProductProvider::where('provider_id', '=', $request->input('provider_id'))
                                                ->where('company_id', '=', \Auth::user()->company_id)
                                                ->where('company_product_id', '=', $request->input('product_id'))
                                                ->delete();

        $new_cost_provider = CompanyProductProvider::create([
            "company_product_id" => $request->input('product_id'),
            "provider_id"        => $request->input('provider_id'),
            "company_id"         => \Auth::user()->company_id,
            "provider_price"     => $request->input('cost'),
        ]);
       
             
        return response($new_cost_provider);          
    }

    
}
