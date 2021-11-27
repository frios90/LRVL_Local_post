<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Code;
use App\Models\Company;
use App\Models\Customer;
use App\Rules\validateRut;
use LicenseHelper;

use Auth;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('toCustomer')->except(
            [
                'get', 
                'getList',
                'getSelector',
                'store',
                'status',
                'getCustomersAutoSearch',
                'rapidStore',
                'getRut'
            ]
        ); 
    }
    
    public function index()
    {
        return view('master');
    }

    public function get (Request $request) 
    {
        $customer = Customer::with('region', 'commune')
                            ->find($request->input('id'));
        return response($customer, 200);
    }

    public function getSelector () 
    {
        return response(Customer::all(), 200);
    }
    
    public function getList () 
    {
        $customers = Customer::withTrashed()                       
                        ->orderBy('created_at', 'desc')
                        ->get();

        $response = [];
        foreach ($customers as $customer) {
            $response[] = [
                "id" => $customer->id ,
                "rut" => $customer->rut ,
                "name" => $customer->name ,
                "email" => $customer->email ,
                "phone" => $customer->phone ,
                "profile" => $customer->profile ,
                "address" => $customer->address ,
                "region_id" => $customer->region_id ,
                "commune_id" => $customer->commune_id ,
                "customerGeolocation" => $customer->customerGeolocation ,
                "created_at" => date('d-m-Y H:i:s', strtotime($customer->created_at)),
                "updated_at" => date('d-m-Y H:i:s', strtotime($customer->updated_at)),
                "deleted_at" => date('d-m-Y H:i:s', strtotime($customer->deleted_at)),
                "deleted_at" => $customer->deleted_at ,
                "l_geolocations" => LicenseHelper::has('L_GEOLOCATION')
            ]; 
        }
        return response($response, 200);
    }

    public function store (Request $request)
    {
        if ( $request->input('post_event') == 'store' ) {
            $request->validate([
                'rut'        => ['required','unique:customers', 'max:12', new validateRut],
                'name'       => ['required'],
                'phone'      => ['required'],
                'address'    => ['required'],
                'region_id'  => ['required'],
                'commune_id' => ['required'],
                'email'      => ['required', 'unique:customers', 'email','regex:/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/'],
            ]);
            $customer_data['rut']        = $request->input('rut');
            $customer_data['name']       = $request->input('name');
            $customer_data['email']      = $request->input('email');
            $customer_data['phone']      = $request->input('phone');
            $customer_data['address']    = $request->input('address'); 
            $customer_data['region_id']  = $request->input('region_id'); 
            $customer_data['commune_id'] = $request->input('commune_id');   
            $customer_data['company_id'] = \Auth::user()->company_id;   
            $customer                    = Customer::firstOrCreate($customer_data);            
            return $customer;
        } else {
            $id = $request->input('id');
            $request->validate([
                'id'         => ['required'],
                'name'       => ['required'],
                'phone'      => ['required'],
                'address'    => ['required'],
                'region_id'  => ['required'],
                'commune_id' => ['required'],
                'email'      => ['required', 'unique:customers,email,'.$id,'regex:/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/'],
            ]);
            $customer             = Customer::find($id);
            $customer->name       = $request->input('name');
            $customer->email      = $request->input('email');
            $customer->phone      = $request->input('phone');
            $customer->address    = $request->input('address');
            $customer->region_id  = $request->input('region_id');
            $customer->commune_id = $request->input('commune_id');
            $customer->company_id = \Auth::user()->company_id;   
            $customer->save();
            return $customer;
        }               
    }

    public function status (Request $request)
    {
        $id = $request->input('id');
        $customer = Customer::where('id', '=', $request->input('id'))->withTrashed()->first();        
        if ($customer->deleted_at) {
            $customer->restore();
        } else {
            $customer->delete();
        }
        return response(Customer::withTrashed()->get(), 200);
    }

    public function getCustomersAutoSearch (Request $request) 
    {
        $customers  = Customer::with('region','commune')
                            ->where(function ($query) use ($request) {
                                $query->where('rut', 'like', '%'.$request->input('value').'%');
                                $query->orWhere('name', 'like', '%'.$request->input('value').'%');                            
                            })                            
                            ->get();
        $list = [];
        foreach ($customers as $customer) {
            $list[] = $customer->rut . ' ' . $customer->name;
        }
        return response($list, 200);
    }

    public function rapidStore (Request $request)
    {
        $request->validate([
            'rut'        => ['required','unique:customers', 'max:12', new validateRut],
            'name'       => ['required'],
            'phone'      => ['required'],
            'address'    => ['required'],
            'region_id'  => ['required'],
            'commune_id' => ['required'],
            'email'      => ['required', 'unique:customers', 'email'],
        ]);
        $customer_data['rut']        = $request->input('rut');
        $customer_data['name']       = $request->input('name');
        $customer_data['email']      = $request->input('email');
        $customer_data['phone']      = $request->input('phone');
        $customer_data['address']    = $request->input('address'); 
        $customer_data['region_id']  = $request->input('region_id'); 
        $customer_data['commune_id'] = $request->input('commune_id');   
        $customer_data['company_id'] = \Auth::user()->company_id;   
        $customer                    = Customer::firstOrCreate($customer_data);
        $customer = Customer::with('region', 'commune')->where('id', '=', $customer->id)->first();
        return $customer;     
    }

    public function getRut (Request $request) 
    {
        $user = Customer::with('region', 'commune')
                ->where('rut', '=', $request->input('rut'))
                ->first();
        return response($user, 200);
    }

    
}
