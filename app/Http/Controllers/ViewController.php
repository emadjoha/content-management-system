<?php

namespace App\Http\Controllers;

use App\Capability;
use App\Category;
use App\Item;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index($path){

        $result =  $this->check($path);

        if( $result ){
           // session(['template'=>1]);
            //dd(session('template'));
            $page = 'show_tmp_'. session('template');
            if( $result instanceof Category ){
                /// check capability (privilege) to access (view)
                if( Capability::hasCapability('access' , 'cat' ) ){
                    return view('Category.'.$page ,[
                        'children' => $result->children ,
                        'items'    => $result->items , 
                        'cat'      => $result->id 
                    ]);
                }else{
                    return view('warning' , ['message' => Capability::$errors['access'] . 'this category' ] );
                }

            }else{
                if(Capability::hasCapability('access' , 'item' )){
                    return view('Item.'.$page ,[
                        'item' => $result
                    ]);
                }else{
                    return view('warning' , ['message' => Capability::$errors['access'] . 'this Item' ] );
                }
            }
        } else{
            return view('warning' , ['message' => 'Warning : typing wrong url!!'] );
        }
    }

    public function check($id){

        $url = '/' . $id ;
        $isCat = Category::whereUrl($url)->get();
        if( count($isCat) ){
            $isCat = $isCat->first();
            return $isCat;
        }else{
            $isItem = Item::whereUrl($url)->get() ;
            if( count($isItem) ){
                $isItem = $isItem->first() ;
                return $isItem;
            }
        }
        return false ;
    }

}
