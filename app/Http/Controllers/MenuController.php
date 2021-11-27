<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\License;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('toMenu')->except(    
            [
                'getMenus', 
                'getListSelector', 
                'getListParent', 
                'get', 
                'getList',
                'store',
                'status',
                'truncate'
            ]
        );
    }

    public function index()
    {
        return view('master');
    }

    public function getMenus (Request $request) 
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

        $menus = Menu::whereHas('profile', function ($query) {
                        $query->where('codes.id', '=', \Auth::user()->profile_id);
                    })
                   
                    ->with(['subMenus' => function ($query) use ($license_menus) {
                        $query->whereHas('profile', function ($query) {
                            $query->where('codes.id', '=', \Auth::user()->profile_id);
                        });
                        $query->whereIn('menus.id', $license_menus);
                    }])                    
                    ->whereNull('parent_id')
                    ->whereIn('menus.id', $license_menus)
                    ->get();
        return response($menus, 200);
    }

    public function getListSelector (Request $request) 
    {
        $menus = Menu::all();
        return response($menus, 200);
    }

    public function getListParent (Request $request) 
    {
        $menus = Menu::whereNull('parent_id')->get();
        return response($menus, 200);
    }

    public function get (Request $request) 
    {       
        $menu = Menu::find($request->input('id'));           
        return response($menu, 200);
    }
 
    public function getList () 
    {
        $menus = Menu::withTrashed() 
                    ->with('parentMenu')                    
                    ->orderBy('parent_id', 'asc')
                    ->get();
        \Log::info($menus);
        return response($menus, 200);
    }

    public function store (Request $request)
    {
        if ( $request->input('post_event') == 'store' ) {
            $request->validate([
                'name'           => ['required'],
                'description'    => ['required'],
                'path_icon'      => ['required'],
            ]);
            $data_menu['name']           = $request->input('name');
            $data_menu['description']    = $request->input('description');
            $data_menu['path_icon']      = $request->input('path_icon');   
            $data_menu['route']          = $request->input('route'); 
            $data_menu['parent_id']      = $request->input('parent_id'); 
            $menu                        = Menu::create($data_menu);             
            
        } else {
            $id = $request->input('id');
            $request->validate([
                'name'           => ['required'],
                'description'    => ['required'],
                'path_icon'      => ['required'],
            ]);
            $menu              = Menu::find($id);
            $menu->name        = $request->input('name');
            $menu->description = $request->input('description');
            $menu->path_icon   = $request->input('path_icon');
            $menu->route       =  $request->input('route') ;
            $menu->parent_id = $request->input('parent_id');

            $menu->save();
        }          
        return $menu;          
    }

    public function status (Request $request)
    {
        $id      = $request->input('id');
        $menu = Menu::where('id', '=', $request->input('id'))->withTrashed()->first();        
        if ($menu->deleted_at) {
            $menu->restore();
        } else {
            $menu->delete();
        }
        return response(Menu::withTrashed()->get(), 200);
    }

    public function truncate (Request $request)
    {
        
        $menu = Menu::where('id', '=', $request->input('id'))->withTrashed()->first(); 
        
        if ($menu->parent_id) {
            $parent_id   = $menu->parent_id;   
            $menu->profile()->detach();         
            $menu->forceDelete();
            $parent_menu = Menu::with('subMenus')->where('id', '=', $parent_id)->withTrashed()->first(); 
            if (count($parent_menu->subMenus) == 0) { 
                $parent_menu->profile()->detach();                
                $parent_menu->forceDelete();
            }
        } else {
            $parent_menu = Menu::with('subMenus')->where('id', '=', $menu->id)->withTrashed()->first(); 
            foreach ($parent_menu->subMenus as $submenus) {
                $destroy_sub_menu = Menu::where('id', '=', $submenus->id)->withTrashed()->first(); 
                $destroy_sub_menu->profile()->detach();    
                $destroy_sub_menu->forceDelete();
            }
            $parent_menu->profile()->detach();    
            $parent_menu->forceDelete();
        }
        return response(Menu::withTrashed()->get(), 200);
    }
}
