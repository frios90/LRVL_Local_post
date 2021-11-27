<?php //b4f2e6d787e3632e35b6465fb958eef5

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commune;

class CommuneController extends Controller
{    
    public function getList (Request $request) 
    {       
        return response(Commune::where('region_id', '=', $request->input('region'))->get(), 200);
    }
   
}
