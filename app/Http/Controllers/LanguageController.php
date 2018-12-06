<?php

namespace App\Http\Controllers;

use App\Capability;
use App\Http\Requests\LanguageStore;
use App\Language;
use App\Translator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( Capability::hasCapability('access' , 'lang' ) ){
            $languages = Language::all();
            return view('language.management' , compact('languages') );
        }else{
            return view('warning' , ['message' => Capability::$errors['access'] . 'Language Management' ] );
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( Capability::hasCapability('create' , 'lang' ) ){
            return view( 'Language.create' ) ;
        }else{
            return view('warning' , ['message' => Capability::$errors['create'] . ' Language' ] );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $searchLang = Language::whereName($request->input('name'))->first();
        if( $searchLang ) return redirect()->back();
        $align =  $request->input('align') == 'true' ? 1: 0 ;
        Language::create(['name'=>$request->input('name') , 'align' => $align ]);
        return redirect('lang');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if( Capability::hasCapability('edit' , 'lang' ) ){
            return view('Language.edit' , ['lang' => Language::find($id) ] );
        }else{
            return view('warning' , ['message' => Capability::$errors['edit'] . ' Language' ] );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $searchLang = Language::whereName($request->input('name'))->WhereNotIn('id',[$id])->get();
        if( count($searchLang) ) return redirect()->back();

        $updatedLang = Language::find($id) ;
        $updatedLang->align =  $request->input('align') == 'true' ? 1: 0 ;
        $updatedLang->name = $request->input('name');
        $updatedLang->save();

        return redirect('lang');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deletedLang = Language::find($id) ;
        $deletedTranslates = Translator::whereLanguageId($id)->get() ;
        foreach ($deletedTranslates as $translate){
            $translate->delete();
        }
        $deletedLang->delete();
        return redirect('lang');
    }
}
