<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\UserImage;

class MyController extends Controller
{
    public function __construct()
    {
        
    }

    public function index()
    {
        return view('master');
    }

    public function getMyProfileData () {
        $user = User::where('id', '=', Auth::user()->id)
                    ->with(['images' => function ($query) {
                                $query->where('principal', '=', true);
                               
                            },
                            'profile',
                            'company'])
                    ->first();
        return response($user, 200);
    }

    public function storeMyPhoto(Request $request)
    {
        if ($request->hasFile('file')) {
            $user_id  = $request->input('user_id');
            $size        = $request->file->getSize();
            $file_name   = $request->file->getClientOriginalName();
            $extension   = $request->file->getClientOriginalExtension();
            $new_name    = $user_id .'_'.time() . '.' . $extension;
            $upload_path = public_path('upload/user_'. $user_id);
            $path        = 'upload/user_'. $user_id.'/'. $new_name;
            if ($extension != 'jpg' && $extension != 'jpeg' ) {
                return response()->json(['error' => '*Debe cargar un Archivo valido (.jpg, .jpeg)']);
            }
            if ( empty($size) || $size >= 2097152) {
                return response()->json(['error' => '*El Archivo es demasiado grande(2MB max.)']);
            }                 
            $old_photos = UserImage::where('user_id', '=', $user_id)->get();  
            foreach ($old_photos as $photo) {
                $old_photo = UserImage::find($photo->id);   
                unlink(public_path($old_photo->path));
                $old_photo->forceDelete();
            }            
            $request->file->move($upload_path, $new_name);
            UserImage::create([
                'user_id'       => $user_id,
                'original_name' => $file_name,
                'name'          => $new_name,
                'size'          => $size,
                'extension'     => $extension,
                'path'          => $path,
                'principal'     => true 
           ]);
        } else {
            return response()->json(['error' => '*Debe seleccionar un Archivo valido ([2MB max.], [.jpg, .jpeg])']);
        }        
    }

    public function storeMyInfo (Request $request)
    {
        $id = $request->input('id');
        $request->validate([
            'id'         => ['required'],
            'name'       => ['required'],
            'email'      => ['required', 'unique:users,email,'.$id],
            'address'    => ['required'],
            'phone'      => ['required'],
            'region_id'  => ['required'],
            'commune_id' => ['required'],
        ]);
            $user             = User::find($id);
            $user->name       = $request->input('name');
            $user->email      = $request->input('email');
            $user->phone      = $request->input('phone');
            $user->address    = $request->input('address');
            $user->region_id  = $request->input('region_id');
            $user->commune_id = $request->input('commune_id');
            $user->save();
        $errors = [];
        if ($request->input('current_pass') && $request->input('new_pass') && $request->input('repeat_pass')) {
            
            $attemp = [
                'email'    => $user->email,
                'password' => $request->input('current_pass')
            ];
            if ( !Auth::attempt( $attemp) ){           
                $errors['errors']['current_pass']   = ['Contraseña actual no es valida'];
            }
    
            if ($request->input('new_pass') != $request->input('repeat_pass')) {
                $errors['errors']['repeat_pass']   = ['No coincide con nueva contraseña'];
            }        
    
            if (!isset($errors['errors'])) {                    
                $user->password = bcrypt($request->input('new_pass'));            
                $user->save();
                return response('change_pass', 200);
            }   
        }
     
        return $errors;
                      
    }
}
