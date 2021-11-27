<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\License;
use App\Models\Menu;

class LicenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('toLicense')->except([
            'getList',
            'get',
            'getForFront',
            'store',
            'status',
            'getMenuFormatBox',
            'addSelectionsLicense',
            'storeMenus'
        ]);
    }

    public function index()
    {
        return view('master');
    }

    public function get (Request $request) 
    {
        $license = License::find($request->input('id'));
        return response($license, 200);
    }

    public function getForFront (Request $request) 
    {
        $licenses = License::with('companies')->withTrashed()->get();
        $response = [];
        foreach ($licenses as $license) {
            $has_company_license = false;
            foreach ($license->companies as $company) {                
                if ($company->id == \Auth::user()->company_id) {
                    $has_company_license = true;
                }
            }
            $response[$license->code] = [
                "id" => $license->id,
                "code" => $license->code,
                "label" => $license->label,
                "description" => $license->description,
                "has" => $has_company_license
            ];
        }
        return response($response, 200);
    }

    public function getList () 
    {
        $licenses = License::withTrashed()  
                    ->orderBy('created_at', 'desc')
                    ->get();
        return response($licenses, 200);
    }  

    public function store (Request $request)
    {
        if ( $request->input('post_event') == 'store' ) {
            $request->validate([
                'name'        => ['required'],
                'description' => ['required']                  
            ]);           
            $data_license['code']    = str_replace(" ", "", mb_strtoupper($request->input('name')));  
            $data_license['label']   = $request->input('name');
            $data_license['description'] = $request->input('description');            
            $license                 = License::firstOrCreate($data_license);            
            return $license;
        } else {
            $id = $request->input('id');
            $request->validate([
                'name'        => ['required'], 
                'description' => ['required']             
            ]);
            $license        = License::find($id);
            $license->code  = str_replace(" ", "", mb_strtoupper($request->input('name')));  
            $license->label = $request->input('name');
            $license->description = $request->input('description');
            $license->save();
            return $license;
        }               
    }

    public function status (Request $request)
    {
        $id = $request->input('id');
        $license = License::where('id', '=', $request->input('id'))->withTrashed()->first();        
        if ($license->deleted_at) {
            $license->restore();
        } else {
            $license->delete();
        }
        return response(License::withTrashed()->get(), 200);
    }
    public function getMenuFormatBox (Request $request) 
    {
        $menus = Menu::with('subMenus')
                        ->select('*')
                        ->whereNull('parent_id')
                        ->get();
        $list_to_select = [];
        foreach($menus as $menu){
            $submenus = [];
            foreach($menu->subMenus as $submenu){
                $submenus[$submenu->id] = [
                    'id' => $submenu->id,                
                    'checked' => $this->addSelectionsLicense($request, $submenu->id) ? true: false
                ];
            }

            $list_to_select[$menu->id] = [
                'id'       => $menu->id,                
                'checked'  => $this->addSelectionsLicense($request, $menu->id) ? true: false,
                'submenus' => $submenus
            ];
        }
        $data = [   
            'menus'          => $menus,
            'list_to_select' => $list_to_select,
        ];
        return response($data, 200);
    }

    private function addSelectionsLicense ($request, $menu_id) 
    {
        $license = License::where('id', '=', $request->input('id'))    
                        ->whereHas('menus', function ($query) use ($menu_id) {
                            $query->where('menus.id', '=', $menu_id);
                        })
                        ->first();     
        return $license;                        
    }
    public function storeMenus (Request $request)
    {
        $array_menus = [];
        $license = License::find($request->input('id'));
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
        \Log::info($array_menus);
        $license->menus()->sync($array_menus);
    }
}
