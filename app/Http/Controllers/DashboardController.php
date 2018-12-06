<?php

namespace App\Http\Controllers;

use App\Capability;
use App\Category;
use App\Item;
use App\MainMenu;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(){
        if( Capability::hasCapability('access' , 'dashboard' ) ){
            return view('Dashboard.show' , [
                'mm_list' => MainMenu::all() ,
                'cat_list' => Category::all() ,
                'item_list'=> Item::all()
            ]);
        }else{
            return view('warning' , ['message' => Capability::$errors['access'] . 'Dashboard' ] );
        }
    }
}
