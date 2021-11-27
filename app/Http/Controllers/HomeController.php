<?php //b4f2e6d787e3632e35b6465fb958eef5

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\SaleTicket;
use App\Models\Code;
use App\Models\Customer;
use App\Models\Provider;

class HomeController extends Controller
{  
    public function index()
    {
        return view('master');
    }

    public function getPersonsList () {
        return response(Region::All(), 200);
    }

    public function getMainWidget () 
    {
        $data = [
            'sales' => $this->getSalesDay(),
            'quotations' => $this->getQuotationDay(),
            'customers' => $this->getTotalCustomers(),
            'providers' => $this->getTotalProviders()
        ];
        return response($data, 200);
    }

    private function getSalesDay () 
    {
        $code = Code::where('code', '=', 'SALE')->first()->id;
                    
        $sales = SaleTicket::where('code_id', '=', $code)
                                ->whereDate('created_at', \Carbon\Carbon::today())
                                ->count(); 
        return $sales;
    }

    private function getQuotationDay () 
    {
        $code = Code::where('code', '=', 'QUOTATION')->first()->id;
       
        $quotations = SaleTicket::where('code_id', '=', $code)
                                ->whereDate('created_at', \Carbon\Carbon::today()) 
                                ->count(); 
        return $quotations;
    }

    private function getTotalCustomers () 
    {
        $customers = Customer::where('company_id', '=', \Auth::user()->company_id)->count(); 
        return $customers;
    }

    private function getTotalProviders () 
    {
        $providers = Provider::count(); 
        return $providers;
    }
}
