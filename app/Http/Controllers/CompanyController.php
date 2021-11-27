<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyGeolocation;
use App\Models\License;
use App\Rules\validateRut;

use App\Models\CompanyLicense;
use LicenseHelper;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('toCompany')->except([
            'getListSelector', 
            'getList',
            'get',
            'store',
            'status',
            'storeGeoLocation',
            'getLicencesFormatBox',
            'addSelectionsLicense',
            'storeLicenses'

            ]);
        $this->middleware('toAvailableGeolocation')->only(['geolocations', 'storeGeoLocation']);

    }
    
    public function getListSelector (Request $request) 
    {
        $companies = Company::all();
        return response($companies, 200);
    }

    public function index()
    {
        return view('master');
    }

    public function geolocations ()
    {
        return view('master');
    }

    public function get (Request $request) 
    {
        $company = Company::with('region', 'commune', 'companyGeolocation.region', 'companyGeolocation.commune')->find($request->input('id'));
        return response($company, 200);
    }

    public function getList () 
    {
        $companies = Company::withTrashed()  
                        ->where('name', '!=','ORGANIZADOR_DE_CLIENTES')                   
                        ->orderBy('name', 'desc')
                        ->get();

        $response = [];
        foreach ($companies as $company) {
            $response[] = [
                "id" => $company->id ,
                "rut" => $company->rut ,
                "name" => $company->name ,
                "contact" => $company->contact ,
                "email" => $company->email ,
                "phone" => $company->phone ,
                "address" => $company->address ,
                "region_id" => $company->region_id ,
                "commune_id" => $company->commune_id ,
                "created_at" => date('d-m-Y H:i:s', strtotime($company->created_at)),
                "updated_at" => date('d-m-Y H:i:s', strtotime($company->updated_at)),
                "deleted_at"       => $company->deleted_at ? date('d-m-Y H:i:s', strtotime($company->deleted_at)) : $company->deleted_at,
                "l_geolocations" => LicenseHelper::has('L_GEOLOCATION')
            ]; 
        }

        return response($response, 200);
    }

    public function store (Request $request)
    {
        if ( $request->input('post_event') == 'store' ) {
            $request->validate([
                'rut'        =>  ['required','unique:companies', 'max:12', new validateRut],
                'name'       => ['required'],
                'contact'    => ['required'],
                'email'      => ['required'],
                'phone'      => ['required'],
                'address'    => ['required'],
                'region_id'  => ['required'],
                'commune_id' => ['required'],
            ]);
            $data_company['rut']        = $request->input('rut');
            $data_company['name']       = $request->input('name'); 
            $data_company['contact']    = $request->input('contact'); 
            $data_company['email']      = $request->input('email'); 
            $data_company['phone']      = $request->input('phone'); 
            $data_company['address']    = $request->input('address'); 
            $data_company['region_id']  = $request->input('region_id'); 
            $data_company['commune_id'] = $request->input('commune_id');        
            $company              = Company::firstOrCreate($data_company);            
            return $company;
        } else {
            $id = $request->input('id');
            $request->validate([
                'name' => ['required'],
                'contact' => ['required'],
                'email' => ['required'],
                'phone' => ['required'],
                'address' => ['required'],
                'region_id' => ['required'],
                'commune_id' => ['required'],
            ]);
            $company       = Company::find($id);
            $company->name = $request->input('name');
            $company->contact = $request->input('contact');
            $company->email = $request->input('email');
            $company->phone = $request->input('phone');
            $company->address = $request->input('address');
            $company->region_id = $request->input('region_id');
            $company->commune_id = $request->input('commune_id');
            $company->save();
            return $company;
        }               
    }

    public function status (Request $request)
    {
        $id      = $request->input('id');
        $company = Company::where('id', '=', $request->input('id'))->withTrashed()->first();        
        if ($company->deleted_at) {
            $company->restore();
        } else {
            $company->delete();
        }
        return response(Company::withTrashed()->get(), 200);
    }

    public function storeGeoLocation (Request $request)
    {
        $request->validate([
            'company_id' => ['required'],
            'region_id'  => ['required'],
            'commune_id' => ['required'],
            'address'    => ['required'],
            'lat'        => ['required'],
            'lng'        => ['required'],
        ]);

        $geolacation = CompanyGeolocation::create([
            "company_id" => $request->input('company_id'),
            "region_id"  => $request->input('region_id'),
            "commune_id" => $request->input('commune_id'),
            "address"    => $request->input('address'),
            "lat"        => $request->input('lat'),
            "lng"        => $request->input('lng'),
        ]);

        return response($geolacation, 200);
    }

    public function getLicencesFormatBox (Request $request) 
    {
        $licenses = License::all();
        $format_licenses = [];
        foreach($licenses as $license){
            $format_licenses[$license->type][$license->id] = [
                'data'     => $license,                
                'checked'  => $this->addSelectionsLicense($request, $license->id) ? true: false                
            ];
        }
        \Log::info($format_licenses['REPORT']);
        return response($format_licenses, 200);
    }

    private function addSelectionsLicense ($request, $license_id) 
    {
        $license = License::where('id', '=', $license_id)    
                        ->whereHas('companies', function ($query) use ($request) {
                            $query->where('companies.id', '=', $request->input('id'));
                            $query->whereNull('company_licenses.deleted_at');
                        })
                        ->first();     
        return $license;                        
    }

    public function storeLicenses (Request $request) 
    {     
        $company_license = CompanyLicense::where('company_id', '=', $request->input('company_id'))
                                        ->where('license_id', '=', $request->input('license')['data']['id'])
                                        ->withTrashed()
                                        ->first();
        \Log::info($company_license);
        if (!$company_license) {
            \Log::info('NO EXISTE, SE CREA');
            CompanyLicense::create([
                "company_id" => (int)$request->input('company_id'),
                "license_id" => (int)$request->input('license')['data']['id']
            ]);
        } else {
            if ($company_license->deleted_at) {
                \Log::info('EXISTE, SE RESTAURA');
                $company_license->restore();
            } else {
                \Log::info('EXISTE, SE DESACTIVA');
                $company_license->delete();
            }
        }
        return response($company_license, 200);
    }
}
