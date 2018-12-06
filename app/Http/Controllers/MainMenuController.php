<?php

namespace App\Http\Controllers;

use App\Attributable;
use App\Capability;
use App\Category;
use App\Http\Requests\MainMenuStore;
use App\Language;
use App\MainMenu;
use App\Translator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class MainMenuController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //
    }


    public function create()
    {

        if(Capability::hasCapability('create' , 'cat' )){
            return view('MainMenu.create' , [
                'parentList' => MainMenu::getCatList()
            ]);

        }else{
            return view('warning' , ['message' => Capability::$errors['create'] . 'Category' ] );
        }

    }


    public function store(MainMenuStore $request)
    {

        $input = $request->all() ;

        if(isset($input['parent_id'])){
            $input['url'] =  MainMenu::whereId($input['parent_id'])->first()->url  ;
        }else{
            $input['url'] =  ""  ;
        }


        $input['url'] .= '/' . $input['name'] ;
        /// need change
        /// ........
        $input['display_name'] = $input['display_name_1'];

        $cats = MainMenu::whereName($input['name']);

        foreach ($cats->get() as $cat ){
            if( $cat->url == $input['url'] ){
                /// duplicate url ...
                return redirect('/main_menu/create');
            }
        }

        $mm = MainMenu::create($input);
        $langs = Language::all();
        foreach ($langs as $lang ){
            $mm->translators()->save($lang);
            $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($mm->id)->whereTranslatorsType('App\MainMenu')->first();
            $pivot->update(['content'=> $input['display_name_' . $lang->id ] ]);
        }

        return redirect()->action(
           'CategoryController@create', ['name'=>$input['name']
                                            , 'mm_parent_id' => $mm->id ,'mm_parent_name'=> $mm->name  ]
        );

        //dd($input);
        ///$validated = $request->validated();
        ///dd($validated);
    }


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
        if(Capability::hasCapability('edit' , 'cat' )){
            $cat = MainMenu::find($id);
            $parentList = MainMenu::getCatList();
            return view('MainMenu.edit' ,compact('cat' , 'parentList') ) ;

        }else{
            return view('warning' , ['message' => Capability::$errors['edit'] . 'Category' ] );
        }
    }


    public function update(MainMenuStore $request, $id)
    {
        $input = $request->all();

        if(isset($input['parent_id'])){
            $input['url'] =  MainMenu::whereId($input['parent_id'])->first()->url  ;
        }else{
            $input['url'] =  ""  ;
        }

        $input['url'] .= '/' . $input['name'] ;

        /// need changing ....
        /// ...............
        $input['display_name'] = $input['display_name_1'];

        $cats = MainMenu::whereUrl($input['url']);


        foreach ($cats->get() as $cat  ){
            if( $cat->id != $id ) return redirect()->back();
        }

        unset($input['_token'] , $input['_method']);
        $updatedCat = MainMenu::find($id);
        $updatedCat->update( $input );



        $langs = Language::all();
        foreach ($langs as $lang ){
            $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($updatedCat->id)->whereTranslatorsType('App\MainMenu')->first();
            if( !$pivot ){
               $updatedCat->translators()->save($lang);
               $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($updatedCat->id)->whereTranslatorsType('App\MainMenu')->first();
            }
            $pivot->update(['content'=> $input['display_name_' . $lang->id ] ]);
        }


        return redirect('/dashboard');
    }

    public function destroy($id , $dontRedirect = false )
    {
        if(Capability::hasCapability('delete' , 'cat' )){

            $deletedCat = MainMenu::find($id);
            $ids = [] ;
            $this->downLevelIds($deletedCat , $ids );

            foreach ($ids as $id_) {

                /// delete its own categories ...
                if(!$dontRedirect){
                    $childCat = Category::where('mm_parent_id', $id_ )->first();
                    if($childCat){
                        $handller = new CategoryController();
                        $handller->destroy($childCat->id , true );
                    }
                }

                MainMenu::find($id_)->delete() ;

                /// delete its own translates ...
                $deletedTrans = Translator::whereTranslatorsId($id_)->whereTranslatorsType('App\MainMenu')->get();
                foreach ($deletedTrans as $tran ){
                    $tran->delete();
                }

            }
            if(!$dontRedirect) return redirect('/dashboard');
        }else{
            return view('warning' , ['message' => Capability::$errors['delete'] . 'Category' ] );
        }

    }

    private function  downLevelIds($cat , &$ids){
        $ids[] = $cat->id ;
        foreach ( $cat->children as $child ){
            $this->downLevelIds($child , $ids);
        }
        return true;
    }

//    public static function testing($id_){
//
//    }

}
