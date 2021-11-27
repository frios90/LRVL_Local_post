<?php //b4f2e6d787e3632e35b6465fb958eef5

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('web.weblogin');
    }

    public function login () {
        return view('login');
    }

    public function singIn(Request $request)
    {
        $this->validate(request() , [
            'email' => 'email|required',
            'password' => 'required'
        ]);
        if ( Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')]) && !Auth::user()->deleted_at) {
            return response()->json('/dashboard', 200);
        }
        return response()->json( 'false', 422);    

    }

    public function logOut() {
        Auth::logout();
        return response('/login', 200);
    }

    public function salir() {
        Auth::logout();
        $data['auth'] = \Auth::user();        
        return view('web.weblogin', $data);
    }
}
