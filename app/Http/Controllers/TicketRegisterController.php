<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaleTicket;
use App\Models\SaleTicketDetail;
use App\Models\Folio;
use App\Models\Code;
use App\Models\CompanyProduct;


class TicketRegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('toTicket');        
    }

    public function index ()
    {
        return view('master');
    }

    public function store (Request $request)
    {
        // $request->validate([            
                   
        // ]);

        $errors        = [];
        $totals        = $request->input('totals');
        $type_ticket   = Code::where('type_id', '=', Code::where('code', '=', 'TICKET')->first()->id)->where('code','=','SALE')->first()->id;
        $current_folio = $request->input('current_folio');
        $product_list  = $request->input('product_list');

        if (empty($product_list)) {
            return response(['error'=>"No ha ingresado productos"], 200);
        }
        $ticket = SaleTicket::create([
            "number"      => $current_folio['current'],
            "base"        => $totals['base'],
            "iva"         => $totals['iva'],
            "total"       => $totals['total'],
            "company_id"  => \Auth::user()->company_id,
            "user_id"     => \Auth::user()->id,
            // "customer_id" => $customer,
            "code_id"     => $type_ticket,
            "step_id"     => Code::where('type_id', '=', Code::where('code', '=', 'STEP_TICKET')->first()->id)
                            ->where('code', '=', 'PENDING')
                            ->first()
                            ->id
        ]);
        $folio = Folio::where('type_folio_id', '=', Code::where('code', '=', 'TICKET')->first()->id)->first();
        $folio->current = $folio->current + 1;
        $folio->save();

        $code_type_ticket = Code::find($type_ticket);
        foreach ($product_list as $product) {
            \Log::info($product);
            $ticket_detail = SaleTicketDetail::create([
                "sale_ticket_id"     => $ticket->id,
                "product_company_id" => $product['id'],
                "barcode"            => $product['barcode'],
                "name"               => $product['name'],
                "qty"                => $product['qty'],
                "base"               => $product['base_price'],
                "iva"                => ($product['base_price'] / 100) * 19,
                "total"              => $product['total_price'],
            ]);
            
            if ($code_type_ticket->code == "SALE") {               
                $company_product = CompanyProduct::find($product['id']);
                $company_product->qty = (int)$company_product->qty - (int)$product['qty'];
                $company_product->save();
            }
            
        }    

        return response($folio, 200);
    }
}
