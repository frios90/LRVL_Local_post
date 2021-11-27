<?php //b4f2e6d787e3632e35b6465fb958eef5

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code;

class CodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('toCode')->except(
            [
                'getList', 
                'getListForType', 
                'get',
                'getListForTypeProfileSudoFalse'    
            ]);
    }
    
    public function index ()
    {
        return view('master');
    }

    public function getList () 
    {
        return response(Code::withTrashed()->get(), 200);
    }

    public function getListForType (Request $request)
    {
        $codes = Code::where('type_id', '=', Code::where('code', '=', $request->input('type'))->first()->id)
                        ->where(function ($query) {
                            if (\Auth::user()->profile_id != Code::where('code', '=', 'SUDO')->first()->id) {
                                $query->where('code', '!=', 'SUDO');
                            }                                
                        })
                        ->get();
        return response($codes, 200);
    }  
    
    public function getListForTypeProfileSudoFalse (Request $request)
    {
        $profiles = $codes = Code::where('type_id', '=', Code::where('code', '=', $request->input('type'))->first()->id)
                            ->where('code', '!=', 'SUDO')
                            ->where('code', '!=', 'ADMIN')
                            ->get();
        return response($profiles, 200);
    } 
    
    public function get (Request $request) 
    {
        return response(Code::find($request->input('id')), 200);
    }

  
}
