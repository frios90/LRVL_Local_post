<?php

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

class UserCompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('toUserCompany')->except(
            [                
                'get', 
                'getList'
            ]
        ); 
        $this->middleware('toAvailableGeolocation')->only(['geolocations', 'storeGeoLocation']);
    }

    public function index()
    {
        return view('master');
    }

    public function geolocations()
    {
        return view('master');
    }

    public function getList () 
    {
        $users = User::withTrashed()->with('profile')          
                    ->where('company_id', '=', \Auth::user()->company_id)
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
                "deleted_at"       => $user->deleted_at ? date('d-m-Y H:i:s', strtotime($user->deleted_at)) : $user->deleted_at,
                "l_geolocations" => LicenseHelper::has('L_GEOLOCATION')
            ]; 
        }
        return response($response, 200);
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

    
}
