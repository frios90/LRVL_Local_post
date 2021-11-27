<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaleTicket;
use App\Models\SaleTicketDetail;
use App\Models\Folio;
use App\Models\Code;
use App\Models\CompanyProduct;

class SaleController extends Controller
{
    public function __construct()
    {
       
    }

    public function index ()
    {
        return view('master');
    }

    public function getSale (Request $request) 
    {
        $sale = SaleTicket::withTrashed()->with('user', 'customer.region', 'customer.commune', 'code', 'detail', 'step')          
                    ->where('id', '=', $request->input('id'))
                    ->first();
        
        return response($sale, 200);
    }

    public function getList () 
    {
        $sales = SaleTicket::withTrashed()->with('user', 'customer', 'code', 'step')          
                    ->orderBy('created_at', 'desc')
                    ->get();
        $response = [];
        foreach ($sales as $sale) {
            $response[] = [
                "id"         => $sale->id,
                "number"     => $sale->number,
                "base"       => $sale->base,
                "iva"        => $sale->iva,
                "total"      => $sale->total,
                "user"       => $sale->user,    
                "code"       => $sale->code, 
                "step"       => $sale->step,
                "later_associated_document" => $sale->later_associated_document,    
                "customer"   => $sale->customer,               
                "created_at" => date('d-m-Y H:i:s', strtotime($sale->created_at)),
                "updated_at" => date('d-m-Y H:i:s', strtotime($sale->updated_at)),
                "deleted_at" => $sale->deleted_at ? date('d-m-Y H:i:s', strtotime($sale->deleted_at)) : $sale->deleted_at,
            ]; 
        }
        return response($response, 200);
    }

    public function status (Request $request)
    {
        $id  = $request->input('id');
        $ticket = SaleTicket::where('id', '=', $id)
                                ->with('code', 'detail')
                                ->withTrashed()
                                ->first();         
       
        if ($ticket->deleted_at) {
            if ($ticket->code->code == 'SALE') {         
                foreach ($ticket->detail as $product) {
                    $company_product = CompanyProduct::find($product->product_company_id);

                    $company_product->qty = (int)$company_product->qty - (int)$product->qty;
                    $company_product->save();
                }
            }
            $ticket->step_id = Code::where('type_id', '=', Code::where('code', '=', 'STEP_TICKET')->first()->id)
                                    ->where('code', '=', 'PENDING')
                                    ->first()
                                    ->id;
            $ticket->save();
            $ticket->restore();
        } else {
            if ($ticket->code->code == 'SALE') {         
                foreach ($ticket->detail as $product) {
                    $company_product = CompanyProduct::find($product->product_company_id);
                    $company_product->qty = (int)$company_product->qty + (int)$product->qty;
                    $company_product->save();
                }
            }
            $ticket->step_id = Code::where('type_id', '=', Code::where('code', '=', 'STEP_TICKET')->first()->id)
                                    ->where('code', '=', 'CANCEL')
                                    ->first()
                                    ->id;
            $ticket->save();
            $ticket->delete();
        }
        return response($ticket, 200);
    }  

    public function pdfQuotation (Request $request, $id) 
    {
        $ticket =SaleTicket::where('id', '=', $id)
                                ->with('code', 'detail', 'customer', 'user')
                                ->withTrashed()
                                ->first(); 
        $pdf = \App::make("dompdf.wrapper");
        $pdf = \PDF::loadView('pdf.cotizacion', ['data' => $ticket]);
        return $pdf->stream('Cotización N°: '.$ticket->number.'.pdf');
    }

    public function postQuotationToSale (Request $request) 
    {
        $quotation = SaleTicket::where('id', '=', $request->input('id'))
                            ->with('code', 'detail', 'customer', 'user')
                            ->withTrashed()
                            ->first(); 

        $folio = Folio::where('type_folio_id', '=', Code::where('code', '=', 'TICKET')->first()->id)->first();

        $ticket = SaleTicket::create([
            "number"      => $folio->current,
            "base"        => $quotation->base,
            "iva"         => $quotation->iva,
            "total"       => $quotation->total,
            "company_id"  => \Auth::user()->company_id,
            "user_id"     => \Auth::user()->id,
            "customer_id" => $quotation->customer->id,
            "code_id"     => Code::where('type_id', '=', Code::where('code', '=', 'TICKET')->first()->id)
                            ->where('code', '=', 'SALE')
                            ->first()
                            ->id,
            "step_id"     => Code::where('type_id', '=', Code::where('code', '=', 'STEP_TICKET')->first()->id)
                            ->where('code', '=', 'PENDING')
                            ->first()
                            ->id
        ]);

        $quotation->later_associated_document = $ticket->number;
        $quotation->step_id = Code::where('type_id', '=', Code::where('code', '=', 'STEP_TICKET')->first()->id)
                                ->where('code', '=', 'QUOTATION_ACEPTED')
                                ->first()
                                ->id;
        $quotation->save();

        $folio->current = $folio->current + 1;
        $folio->save();
        
        foreach ($quotation->detail as $product) {
            $ticket_detail = SaleTicketDetail::create([
                "sale_ticket_id"     => $ticket->id,
                "product_company_id" => $product->product_company_id,
                "barcode"            => $product->barcode,
                "name"               => $product->name,
                "qty"                => $product->qty,
                "base"               => $product->base,
                "iva"                => $product->iva,
                "total"              => $product->total,
            ]);
            
            $company_product = CompanyProduct::find($product->product_company_id);
            $company_product->qty = (int)$company_product->qty - (int)$product->qty;
            $company_product->save();           
            
        }    

        return response($folio, 200);
    }

    public function postSaleEventComplete (Request $request) 
    {

        $request->validate([
            
            'type'       => ['required'],
            'identifier' => ['required'],
            
        ]);
    
        $sale = SaleTicket::where('id', '=', $request->input('id'))                          
                                ->withTrashed()
                                ->first(); 
        $sale->later_associated_document = $request->input('identifier');    
        $sale->step_id                   = Code::where('type_id', '=', Code::where('code', '=', 'STEP_TICKET')->first()->id)
                                                ->where('code', '=', $request->input('type'))
                                                ->first()
                                                ->id; 
        $sale->save();
        return response($sale, 200);
        
    }
}
