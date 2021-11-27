<?php //b4f2e6d787e3632e35b6465fb958eef5

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Code;
use App\Models\Company;
use App\Models\UserGeolocation;
use App\Models\UserImage;

use App\Rules\validateRut;
use LicenseHelper;

use Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('toUser')->except(
            [
                'userAuth',
                'get', 
                'getList',
                'getUserCompanyList',
                'dataAuth',
                'getRut',
                'store',
                'status',
                'getUserCompanyList',
                'storeGeoLocation',
                'storePhoto',
                'postDeletePhoto',
                'getUserToUploadPhotos',
                'postPrincipalPhoto'

            ]
        ); 
        $this->middleware('toAvailableGeolocation')->only(['geolocations', 'storeGeoLocation']);
    }
    
    public function userAuth() {
        return response(Auth::user(), 200);
    }

    public function index()
    {
        return view('master');
    }

    public function geolocations()
    {
        return view('master');
    }

    public function get (Request $request) 
    {
        $user = User::with('profile', 'region', 'commune', 'userGeolocation.region', 'userGeolocation.commune')
                    ->find($request->input('id'));
        return response($user, 200);
    }

    public function dataAuth (Request $request) 
    {
        $user = User::with('profile', 'userGeolocation.region', 'userGeolocation.commune')
                    ->find(\Auth::user()->id);
        return response($user, 200);
    }

    public function getRut (Request $request) 
    {
        $user = User::with('profile', 'userGeolocation.region', 'userGeolocation.commune')
                ->where('rut', '=', $request->input('rut'))
                ->where('profile_id', '!=', Code::where('code', '=', 'SUDO')->first()->id)
                ->first();
        return response($user, 200);
    }

    public function getList () 
    {
        $users = User::withTrashed()->with('profile', 'company')          
                    
                    ->orderBy('name', 'desc')
                    ->get();
        $response = [];
        foreach ($users as $user) {
            $response[] = [
                "id"             => $user->id ,
                "rut"            => $user->rut ,
                "name"           => $user->name ,
                "email"          => $user->email ,
                "phone"          => $user->phone ,
                "profile"        => $user->profile ,
                "address"        => $user->address ,
                "region_id"      => $user->region_id ,
                "commune_id"     => $user->commune_id ,
                "company"        => $user->company ,
                "created_at"     => date('d-m-Y H:i:s', strtotime($user->created_at)),
                "updated_at"     => date('d-m-Y H:i:s', strtotime($user->updated_at)),
                "deleted_at"       => $user->deleted_at ? date('d-m-Y H:i:s', strtotime($user->deleted_at)) : $user->deleted_at,
                "l_geolocations" => LicenseHelper::has('L_GEOLOCATION')
            ]; 
        }
        return response($response, 200);
    }

    public function store (Request $request)
    {
        if ( $request->input('post_event') == 'store' ) {
            $request->validate([
                'rut'        => ['required','unique:users', 'max:12', new validateRut],
                'name'       => ['required'],
                'email'      => ['required', 'unique:users', 'email', 'regex:/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/'],
                'profile_id' => ['required'],
                'address'    => ['required'],
                'region_id'  => ['required'],
                'commune_id' => ['required'],
            ]);
            $data_user['rut']        = $request->input('rut');
            $data_user['name']       = $request->input('name');
            $data_user['email']      = $request->input('email');
            $data_user['phone']      = $request->input('phone');
            $data_user['profile_id'] = $request->input('profile_id');
            $data_user['address']    = $request->input('address'); 
            $data_user['region_id']  = $request->input('region_id'); 
            $data_user['commune_id'] = $request->input('commune_id');   
            $data_user['company_id'] = $request->input('company_id') ? $request->input('company_id') : \Auth::user()->company_id;
            $pass                    = str_replace(".", "",$request->input('rut'));
            $pass                    = str_replace("-", "",$pass);
            $pass                    = strrev($pass);
            $data_user['password']   = bcrypt($pass);
            $user                    = User::firstOrCreate($data_user);            
            return $user;
        } else {
            $id = $request->input('id');
            $request->validate([
                'id'         => ['required'],
                'name'       => ['required'],
                'email'      => ['required', 'unique:users,email,'.$id, 'regex:/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/'],
                'profile_id' => ['required'],
                'address'    => ['required'],
                'region_id'  => ['required'],
                'commune_id' => ['required'],
            ]);
            $user             = User::find($id);
            $user->name       = $request->input('name');
            $user->email      = $request->input('email');
            $user->phone      = $request->input('phone');
            $user->profile_id = $request->input('profile_id');
            $user->address    = $request->input('address');
            $user->region_id  = $request->input('region_id');
            $user->commune_id = $request->input('commune_id');
            $user->company_id = $request->input('company_id') ? $request->input('company_id') : \Auth::user()->company_id;
            $user->save();
            return $user;
        }               
    }

    public function status (Request $request)
    {
        $id = $request->input('id');
        $user = User::where('id', '=', $request->input('id'))->withTrashed()->first();        
        if ($user->deleted_at) {
            $user->restore();
        } else {
            $user->delete();
        }
        return response(User::withTrashed()->get(), 200);
    }

    public function getUserCompanyList (Request $request) 
    {   
        $company = Company::find($request->input('company_id'));
        $users = User::with('profile')                    
                    ->where('company_id', '=', $company->id)               
                    ->orderBy('name', 'desc')
                    ->get();
        $response = [];
        foreach ($users as $user) {
            $response[] = [
                "id"             => $user->id ,
                "rut"            => $user->rut ,
                "name"           => $user->name ,
                "email"          => $user->email ,
                "phone"          => $user->phone ,
                "profile"        => $user->profile ,
                "address"        => $user->address ,
                "region_id"      => $user->region_id ,
                "commune_id"     => $user->commune_id ,
                "created_at"     => date('d-m-Y H:i:s', strtotime($user->created_at)),
                "updated_at"     => date('d-m-Y H:i:s', strtotime($user->updated_at)),
                "deleted_at"     => $user->deleted_at ? date('d-m-Y H:i:s', strtotime($user->deleted_at)) : $user->deleted_at,
                "l_geolocations" => LicenseHelper::has('L_GEOLOCATION')
            ]; 
        }
        return response($response, 200);
    }

    public function storeGeoLocation (Request $request)
    {
        $request->validate([
            'user_id'    => ['required'],
            'region_id'  => ['required'],
            'commune_id' => ['required'],
            'address'    => ['required'],
            'lat'        => ['required'],
            'lng'        => ['required'],
        ]);

        $geolacation = UserGeolocation::create([
            "user_id"    => $request->input('user_id'),
            "region_id"  => $request->input('region_id'),
            "commune_id" => $request->input('commune_id'),
            "address"    => $request->input('address'),
            "lat"        => $request->input('lat'),
            "lng"        => $request->input('lng'),
        ]);

        return response($geolacation, 200);
    }

    public function storePhoto(Request $request)
    {
        if ($request->hasFile('file')) {
            $user_id  = $request->input('user_id');
            $size        = $request->file->getSize();
            $file_name   = $request->file->getClientOriginalName();
            $extension   = $request->file->getClientOriginalExtension();
            $new_name    = $user_id .'_'.time() . '.' . $extension;
            $upload_path = public_path('upload/user_'. $user_id);
            $path        = 'upload/user_'. $user_id.'/'. $new_name;
            if ($extension != 'jpg' && $extension != 'jpeg' ) {
                return response()->json(['error' => '*Debe cargar un Archivo valido (.jpg, .jpeg)']);
            }
            if ( empty($size) || $size >= 2097152) {
                return response()->json(['error' => '*El Archivo es demasiado grande(2MB max.)']);
            }        
            $request->file->move($upload_path, $new_name);
            UserImage::create([
                'user_id'       => $user_id,
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
        $photo   = UserImage::find($photo_id);     
        unlink(public_path($photo->path));
        $photo->forceDelete();
        return response($photo, 200);
    }

    public function getUserToUploadPhotos (Request $request) 
    {
        $user = User::with('images')->find($request->input('id'));
        return response( $user , 200);
    }

    public function postPrincipalPhoto (Request $request) 
    {        
        $photo_id  = $request->input('photo_id');       
        $images    = User::with('images')->find($request->input('user_id'))->images;
        foreach($images as $image) {
            $image = UserImage::find($image->id);
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
