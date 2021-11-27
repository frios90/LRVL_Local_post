<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\profile;
use App\Models\Code;
use App\Models\Menu;
use App\Models\License;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('toProfile')->except([
            'getList',
            'getMenuFormatBox',
            'storeMenus',
            'addSelectionsProfile',
            'store',
            'status',
            'storeMenus'
        ]);
    }

    public function index()
    {
        return view('master');
    }

    public function get (Request $request) 
    {
        $profile = Code::find($request->input('id'));
        return response($profile, 200);
    }

    public function getList () 
    {
        $profiles = Code::withTrashed() 
                    ->with('profileUsers') 
                    ->where('type_id', '=', Code::where('code', '=', 'PROFILES')->first()->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
        $response = [];
        foreach ($profiles as $profile) {
            $has_products = false;  
            if (count($profile->profileUsers) > 0) {
                $has_products = true;
            } 
            $response[] = [
                "id"             => $profile->id ,
                "code"            => $profile->code ,
                "label"           => $profile->label,
                "type_id"          => $profile->type_id,
                "has_products" => $has_products,
                "company_id"        => $profile->company_id ,               
                "created_at"     => date('d-m-Y H:i:s', strtotime($profile->created_at)),
                "updated_at"     => date('d-m-Y H:i:s', strtotime($profile->updated_at)),
                "deleted_at"       => $profile->deleted_at ? date('d-m-Y H:i:s', strtotime($profile->deleted_at)) : $profile->deleted_at,
            ]; 
        }
        return response($response, 200);
    }

    public function getMenuFormatBox (Request $request) 
    {
        $licenses = License::whereHas('companies', function ($query) {
                    $query->where('company_id', '=', \Auth::user()->company_id);
                    $query->whereNull('company_licenses.deleted_at');
                })
                ->whereHas('menus')
                ->with('menus')
                ->get();
        $license_menus = [];
        foreach ($licenses as $license) {
            foreach ($license->menus as $menu) {
                $license_menus[] = $menu->id;
            }
        }

        $license_menus = array_unique($license_menus);

        $menus = Menu::with(['subMenus' => function ($query) use ($license_menus) {                           
                            $query->whereIn('menus.id', $license_menus);
                        }])   
                        ->whereNull('parent_id')
                        ->get();
        $list_to_select = [];
        foreach($menus as $menu){
            $submenus = [];
            foreach($menu->subMenus as $submenu) {
                $submenus[$submenu->id] = [
                    'id'      => $submenu->id,
                    'checked' => $this->addSelectionsProfile($request, $submenu->id) ? true: false
                ];
            }
            $list_to_select[$menu->id] = [
                'id'       => $menu->id,                
                'checked'  => $this->addSelectionsProfile($request, $menu->id) ? true: false,
                'submenus' => $submenus
            ];
        }
        $data = [   
            'menus'          => $menus,
            'list_to_select' => $list_to_select,
        ];
        return response($data, 200);
    }

    private function addSelectionsProfile ($request, $menu_id) 
    {
        $profile = Code::where('id', '=', $request->input('id'))    
                        ->whereHas('menu', function ($query) use ($menu_id) {
                            $query->where('menus.id', '=', $menu_id);
                        })
                        ->first();    
        \Log::info('profile');
        \Log::info($profile);
        return $profile;                        
    }

    public function store (Request $request)
    {
        if ( $request->input('post_event') == 'store' ) {
            $request->validate([
                'label' => ['required', 'unique:codes'],                
            ]);           
            $data_profile['code']    = str_replace(" ", "", mb_strtoupper($request->input('label')));  
            $data_profile['label']   = $request->input('label');
            $data_profile['type_id'] = Code::where('code', '=', 'PROFILES')->first()->id;     
            $profile                 = Code::firstOrCreate($data_profile);            
            return $profile;
        } else {
            $id = $request->input('id');
            $request->validate([
                'label' => ['required', 'unique:codes,label,'.$id],              
            ]);
            $profile        = Code::find($id);
            $profile->label = $request->input('label');
            $profile->save();
            return $profile;
        }               
    }

    public function status (Request $request)
    {
        $id = $request->input('id');
        $profile = Code::where('id', '=', $request->input('id'))->withTrashed()->first();        
        if ($profile->deleted_at) {
            $profile->restore();
        } else {
            $profile->delete();
        }
        return response(Code::withTrashed()->get(), 200);
    }

    public function storeMenus (Request $request)
    {
        $array_menus = [];
        $profile = Code::find($request->input('id'));
        foreach ($request->input('selected_menus') as $menu) {
            if ($menu['checked']){
                $array_menus[] = $menu['id'];
            } 
            foreach ($menu['submenus'] as $submenu) {
                if ($submenu['checked']){
                    $array_menus[] = $submenu['id'];
                }
            }
        }
        $profile->menu()->sync($array_menus);
    }
}
