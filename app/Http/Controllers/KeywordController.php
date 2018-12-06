<?php

namespace App\Http\Controllers;

use App\Capability;
use App\Http\Requests\KeywordStore;
use App\KeyWords;
use App\Language;
use App\Translator;
use Illuminate\Http\Request;

class KeywordController extends Controller
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
        if( Capability::hasCapability('access' , 'keyword' ) ){
            $keywords = KeyWords::all();
            return view('Keyword.management' , compact('keywords') );
        }else{
            return view('warning' , ['message' => Capability::$errors['access'] . 'Keyword Management' ] );
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( Capability::hasCapability('create' , 'keyword' ) ){
            return view( 'Keyword.create' ) ;
        }else{
            return view('warning' , ['message' => Capability::$errors['create'] . ' Keyword' ] );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KeywordStore $request)
    {
        $input = $request->all() ;
        $createdKW = new KeyWords;
        $createdKW->name = $input['name'];
        $createdKW->save();

        $langs = Language::all();
        foreach ($langs as $lang ){
            $createdKW->translators()->save($lang);
            $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($createdKW->id)->whereTranslatorsType('App\KeyWords')->first();
            $pivot->update(['content'=> $input['content_' . $lang->id] ]);
        }
        return redirect('keyword');
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


    public function edit($id)
    {
        if( Capability::hasCapability('edit' , 'keyword' ) ){
            return view('Keyword.edit' , ['keyword' => KeyWords::find($id) ] );
        }else{
            return view('warning' , ['message' => Capability::$errors['edit'] . ' Keyword' ] );
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

       // dd($request->all());
        $searchKW = KeyWords::whereName($request->input('name'))->WhereNotIn('id',[$id])->get();
        if( count($searchKW) ) return redirect()->back();

        $updatedKW = KeyWords::find($id) ;
        $updatedKW->name = $request->input('name');
        $updatedKW->save();

        $langs = Language::all();
        foreach ($langs as $lang ){
            $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($updatedKW->id)->whereTranslatorsType('App\KeyWords')->first();
            if( !$pivot ){
              $updatedKW->translators()->save($lang);
              $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($updatedKW->id)->whereTranslatorsType('App\KeyWords')->first();
            }
            $pivot->update(['content'=> $request->input('content_' . $lang->id) ]);
        }

        return redirect('keyword');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deletedKW = KeyWords::find($id) ;
        $deletedTranslates = Translator::whereTranslatorsId($id)->whereTranslatorsType('App\KeyWords')->get() ;
        foreach ($deletedTranslates as $translate){
            $translate->delete();
        }
        $deletedKW->delete();
        return redirect('lang');
    }
}
