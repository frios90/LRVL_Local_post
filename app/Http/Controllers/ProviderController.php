<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Code;
use App\Models\Company;
use App\Models\Provider;
use App\Models\ProviderGeolocation;
use App\Rules\validateRut;
use LicenseHelper;

use Auth;
class ProviderController extends Controller
{
    public function __construct()
    {
        $this->middleware('toProvider')->except(
            [
                'get', 
                'getList',
                'getSelector',
                'getList',
                'store',
                'status',
                'storeGeoLocation'
            ]
        ); 
    }
    
    public function index()
    {
        return view('master');
    }

    public function get (Request $request) 
    {
        $provider = Provider::with('region', 'commune', 'providerGeolocation.region', 'providerGeolocation.commune')
                            ->find($request->input('id'));
        
        return response($provider, 200);
    }

    public function getSelector () 
    {
        return response(Provider::all(), 200);
    }
    
    public function getList () 
    {
        $providers = Provider::withTrashed()                       
                        ->orderBy('name', 'desc')
                        ->get();

        $response = [];
        foreach ($providers as $ptovider) {
            $response[] = [
                "id" => $ptovider->id ,
                "rut" => $ptovider->rut ,
                "name" => $ptovider->name ,
                "email" => $ptovider->email ,
                "phone" => $ptovider->phone ,
                "profile" => $ptovider->profile ,
                "address" => $ptovider->address ,
                "region_id" => $ptovider->region_id ,
                "commune_id" => $ptovider->commune_id ,
                "providerGeolocation" => $ptovider->providerGeolocation ,
                "created_at" => date('d-m-Y H:i:s', strtotime($ptovider->created_at)),
                "updated_at" => date('d-m-Y H:i:s', strtotime($ptovider->updated_at)),
                "deleted_at" => date('d-m-Y H:i:s', strtotime($ptovider->deleted_at)),
                "deleted_at" => $ptovider->deleted_at ,
                "l_geolocations" => LicenseHelper::has('L_GEOLOCATION')
            ]; 
        }
        return response($response, 200);
    }

    public function store (Request $request)
    {
        if ( $request->input('post_event') == 'store' ) {
            $request->validate([
                'rut'        => ['required','unique:providers', 'max:12', new validateRut],
                'name'       => ['required'],
                'phone'       => ['required'],
                'address'    => ['required'],
                'region_id'  => ['required'],
                'commune_id' => ['required'],
                'email'      => ['required', 'unique:providers', 'email','regex:/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/'],
            ]);
            $data_provider['rut']        = $request->input('rut');
            $data_provider['name']       = $request->input('name');
            $data_provider['email']      = $request->input('email');
            $data_provider['phone']      = $request->input('phone');
            $data_provider['address']    = $request->input('address'); 
            $data_provider['region_id']  = $request->input('region_id'); 
            $data_provider['commune_id'] = $request->input('commune_id');   
            
            $provider                    = Provider::firstOrCreate($data_provider);            
            return $provider;
        } else {
            $id = $request->input('id');
            $request->validate([
                'id'         => ['required'],
                'name'       => ['required'],
                'phone'      => ['required'],
                'address'    => ['required'],
                'region_id'  => ['required'],
                'commune_id' => ['required'],
                'email'      => ['required', 'unique:providers,email,'.$id,"regex:\w+([-+.']\w+)@\w+([-.]\w+).\w+([-.]\w+)*"],
            ]);
            $provider             = Provider::find($id);
            $provider->name       = $request->input('name');
            $provider->email      = $request->input('email');
            $provider->phone      = $request->input('phone');
            $provider->address    = $request->input('address');
            $provider->region_id  = $request->input('region_id');
            $provider->commune_id = $request->input('commune_id');
            $provider->save();
            return $provider;
        }               
    }

    public function status (Request $request)
    {
        $id = $request->input('id');
        $provider = Provider::where('id', '=', $request->input('id'))->withTrashed()->first();        
        if ($provider->deleted_at) {
            $provider->restore();
        } else {
            $provider->delete();
        }
        return response(Provider::withTrashed()->get(), 200);
    }

    public function storeGeoLocation (Request $request)
    {
        $request->validate([
            'provider_id' => ['required'],
            'region_id'   => ['required'],
            'commune_id'  => ['required'],
            'address'     => ['required'],
            'lat'         => ['required'],
            'lng'         => ['required'],
        ]);

        $geolacation = ProviderGeolocation::create([
            "provider_id" => $request->input('provider_id'),
            "region_id"   => $request->input('region_id'),
            "commune_id"  => $request->input('commune_id'),
            "address"     => $request->input('address'),
            "lat"         => $request->input('lat'),
            "lng"         => $request->input('lng'),
        ]);

        return response($geolacation, 200);
    }
}
