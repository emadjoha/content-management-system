<?php

use App\Http\Requests\RoleAssignStore;
use App\Role;
use App\User;
use Illuminate\Http\Request;


Route::group( ['middleware' => 'auth' ] , function (){

    Route::get('/', function () {
        return view('welcome');
    });
	
    Route::post('langchange' , function (Request $request){
        session()->forget('lang');
        session(['lang'=>$request->input('lang')]);
        return redirect()->back();
        })->name('lang.change');


    Route::post('templatechange' , function (Request $request){
        session()->forget('template');
        session(['template'=>$request->input('temp')]);
        return redirect()->back();
        })->name('template.change');

});


Route::get('dashboard' , 'DashboardController@show'  )->name('dashboard');
Route::resource('main_menu' ,  'MainMenuController' );
Route::resource('category' ,  'CategoryController' );
Route::resource('item' , 'ItemController' );
Route::post('item/get_attr' , 'ItemController@getAttributes' );
Route::get('view/{path}' , 'ViewController@index'  )->where( 'path' , '(.)+' )->name('view.index') ;

Route::resource('role' , 'RoleController' );
Route::resource('assign' , 'RoleAssignmentController' );
Route::resource('lang' , 'LanguageController' );
Route::resource('keyword' , 'KeywordController' );
// Route::resource('tran' , 'TranController' );


Auth::routes();
