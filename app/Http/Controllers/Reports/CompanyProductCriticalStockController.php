<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
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
class CompanyProductCriticalStockController extends Controller
{

    public function __construct()
    {
        $this->middleware('toReportCriticalStock')->except(    
            [                
                'getListCriticalStock', 
            ]
        );
    }

    public function index()
    {
        return view('master');
    }

    public function getListCriticalStock () 
    {
        $company_id = Auth::user()->company_id;
        $mono_company_product = CompanyProduct::with('product')
                        ->withTrashed()
                        ->where('company_id', '=', $company_id)  
                        ->orderBy('created_at', 'desc')
                        ->get(); 
        $response = [];
        foreach ($mono_company_product as $mono) {
            $qty = $mono->qty ? $mono->qty : 0;
            $critical_qty = $mono->critical_qty ? $mono->critical_qty : 0;
            if ($qty <= $critical_qty) {
                $response[] = [
                    "id"                   => $mono->id ,
                    "product_id"           => $mono->product_id ,
                    "qty"                  => $qty,
                    "critical_qty"         => $critical_qty,
                    "product"              => $mono->product ,                
                   
                   
                ]; 
            }
           
        }
        return response($response, 200);       
    }
}
