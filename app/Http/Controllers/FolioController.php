<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folio;
use App\Models\Code;

class FolioController extends Controller
{
    public function __construct()
    {
        $this->middleware('toFolio')->except([
            'getListToConfig',
            'getListSelector',
            'getCurrentFolio',
            'activeFolioType',
            'store',
            'status'
        ]);
    }
    public function index()
    {
        return view('master');
    }

    public function getListToConfig()
    {
        $folios = Folio::with('document')
                        ->where('company_id', '=', \Auth::user()->company_id)
                        ->withTrashed()
                        ->orderBy('created_at', 'desc')
                        ->get();
        $response = [];
        foreach ($folios as $folio) {
            $response[] = [
                "id"               => $folio->id ,
                "since"            => $folio->since ,
                "current"          => $folio->current,
                "until"            => $folio->until ,
                "type_folio_id"    => $folio->type_folio_id ,
                "document"         => $folio->document ,   
                "company_id"       => $folio->company_id ,                
             
                "created_at"       => date('d-m-Y H:i:s', strtotime($folio->created_at)),
                "updated_at"       => date('d-m-Y H:i:s', strtotime($folio->updated_at)),
                "deleted_at"       => $folio->deleted_at ? date('d-m-Y H:i:s', strtotime($folio->deleted_at)) : $folio->deleted_at
            ]; 
        }
        return response(collect($response), 200);
    }

    public function getListSelector()
    {
        $folios = Folio::with('document')
                        ->where('company_id', '=', \Auth::user()->company_id)
                        ->withTrashed()
                        ->get();
        return response($folios, 200);
    }

    public function getCurrentFolio (Request $request) 
    {
        $current_folio = Folio::where('type_folio_id', '=', Code::where('code', '=', $request->input('type'))->first()->id)
                                ->where('company_id', '=', \Auth::user()->company_id)
                                ->first();
        return response($current_folio, 200); 
    }

    public function activeFolioType (Request $request) 
    {
        $active_folio = Folio::where('type_folio_id', '=', Code::where('id', '=', $request->input('type'))->first()->id)
                                ->where('company_id', '=', \Auth::user()->company_id)
                                ->first();
        return response($active_folio, 200); 
    }

    public function store (Request $request)
    {
        if ( $request->input('post_event') == 'store' ) {
            $request->validate([
                'type'  => ['required'],
                'since' => ['required'],
                'until' => ['required']
            ]);

            $data_folio['type_folio_id'] = $request->input('type');
            $data_folio['since']         = $request->input('since'); 
            $data_folio['current']       = $request->input('since'); 
            $data_folio['until']         = $request->input('until'); 
            $data_folio['company_id']    = \Auth::user()->company_id;

            $active_folio = Folio::where('type_folio_id', '=', Code::where('id', '=', $request->input('type'))->first()->id)
                            ->where('company_id', '=', \Auth::user()->company_id)
                            ->first();
            if ($active_folio) {
                $active_folio->delete();
            }
            $folio              = Folio::firstOrCreate($data_folio);            
            return $folio;
        } else {
            $id = $request->input('id');
            $request->validate([
                'since' => ['required'],
                'until' => ['required']
            ]);
            $folio        = folio::find($id);
            $folio->since = $request->input('since');
            $folio->current = $request->input('since');
            $folio->until = $request->input('until');            
            $folio->save();
            return $folio;
        }               
    }

    public function status (Request $request)
    {
        $id = $request->input('id');
        $user = Folio::where('id', '=', $request->input('id'))->withTrashed()->first();        
        if ($user->deleted_at) {
            $user->restore();
        } else {
            $user->delete();
        }
        $folios = Folio::with('document')
                        ->withTrashed()
                        ->where('company_id', '=', \Auth::user()->company_id)                        
                        ->get();
        return response($folios , 200);
    }
}
