<?php

namespace App\Http\Controllers;

use App\Attributable;
use App\Attribute;
use App\Capability;
use App\Category;
use App\Http\Requests\CategoryStoreRequest;
use App\Item;
use App\Language;
use App\MainMenu;
use App\Translator;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return redirect()->back();
    }

    public function create(Request $request )
    {

        if(Capability::hasCapability('create' , 'cat' )){
            $name = null;
            $mm_parent_id = null;
            $mm_parent_name = null ;
            ///dd($request->all());
            if(count($request->all())){
                $name = $request->input('name');
                $mm_parent_id = $request->input('mm_parent_id');
                $mm_parent_name = $request->input('mm_parent_name');
            }

            $cats = Category::getCatList() ;
            $mmlst= MainMenu::getCatList() ;
            if( count($cats) || count($mmlst) ){
                return view('Category.create' , [
                    'catList' => $cats ,
                    'mmList'  => $mmlst,
                    'name'    => $name ,
                    'mm_parent_id' => $mm_parent_id ,
                    'mm_parent_name' => $mm_parent_name
                ]);
            }
            return view('warning' , [
                'message' => "Warning: You couldn't add category , there is no previous category !!"
            ] );
        }else{
            return view('warning' , ['message' => Capability::$errors['create'] . 'Category' ] );
        }

    }

    public function store(CategoryStoreRequest $request)
    {
        ///$request->filled('name')
        /// attributes
        /// $tags = json_decode($input['attributes']) ;
        $input = $request->all() ;

        if(isset($input['mm_parent_id'])){
            $input['url'] =  MainMenu::whereId($input['mm_parent_id'])->first()->url  ;
        }else{
            $input['url'] =  Category::whereId($input['cat_parent_id'])->first()->url  ;
        }

        if( !$request->has('same_url') ){
            $input['url'] .= '/' . $input['name'] ;
        }
        /// need change ....
        /// ..............
        $input['display_name'] = $input['display_name_1'];

        $cats = Category::whereName($input['name']);

        foreach ($cats->get() as $cat ){
            if( $cat->url == $input['url'] ){
                /// duplicate url ...
                return redirect('/category/create');
            }
        }

        ///$validated = $request->validated();
        ///dd($validated);

        $newCat = Category::create($input);

        $langs = Language::all();
        foreach ($langs as $lang ){
            $newCat->translators()->save($lang);
            $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($newCat->id)->whereTranslatorsType('App\Category')->first();
            $pivot->update(['content'=> $input['display_name_' . $lang->id ] ]);
        }


        if(  $input['attributes'] ) {

            /// add the own attributes ...
            $attributes = json_decode($input['attributes']) ;

            foreach ($attributes as $attribute ){
                $att = Attribute::firstOrCreate([ 'name' => $attribute->name ],
                    ['type'=>$attribute->type]);
                $newCat->attributes()->save($att);
            }
        }

        /// add the fathers attributes ....
        if( $newCat->parent) {
            foreach ($newCat->parent->attributes as $att) {
                $newCat->attributes()->save($att);
                $pivot = Attributable::whereAttributeId($att->id)->whereAttributableId($newCat->id)->whereAttributableType('App\Category')->first();
                $pivot->own = 0;
                $pivot->save();
            }
        }

        return redirect('/dashboard');
    }

    public function show($id)
    {

    }

    public function edit($id)
    {
        if(Capability::hasCapability('edit' , 'cat' )){
            $cat = Category::find($id);
            $mmList = MainMenu::getCatList();
            $catList = Category::getCatList();
            return view('Category.edit' ,compact('cat' , 'catList' , 'mmList' ) ) ;
        }else{
            return view('warning' , ['message' => Capability::$errors['edit'] . 'Category' ] );
        }
    }

    public function update(CategoryStoreRequest $request, $id)
    {
        $input = $request->all() ;

        if( isset($input['mm_parent_id']) && isset($input['cat_parent_id']) ){
            /// re-enter data to form
            return redirect()->back();
        }
        if(isset($input['mm_parent_id'])){
            $input['url'] =  MainMenu::whereId($input['mm_parent_id'])->first()->url  ;
        }else{
            $input['url'] =  Category::whereId($input['cat_parent_id'])->first()->url  ;
        }

        $input['url'] .= '/' . $input['name'] ;
        /// need changing .....
        /// ..............
        $input['display_name'] = $input['display_name_1'];

        $cats = Category::whereName($input['name']);
        foreach ( $cats->get() as $cat  ){
            if( $cat->id != $id ) return redirect()->back();
        }

        unset($input['_token'] , $input['_method']);
        $updatedCat = Category::find($id) ;
        $updatedCat->update( $input );


        $langs = Language::all();
        foreach ($langs as $lang ){
            ///$updatedCat->translators()->save($lang);
            $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($updatedCat->id)->whereTranslatorsType('App\Category')->first();
            if( !$pivot ){
                $updatedCat->translators()->save($lang);
                $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($updatedCat->id)->whereTranslatorsType('App\Category')->first();
            }
            $pivot->update(['content'=> $input['display_name_' . $lang->id ] ]);
        }

        ///dd($input['updated_attributes']);


        /// delete all previous attributes ...
        $deletedAttr = Attributable::whereAttributableId($updatedCat->id)->whereAttributableType('App\Category')->get();
        foreach ($deletedAttr as $attr) {
            $attr->delete();
        }

        if(  $input['updated_attributes'] ) {

            /// add the own attributes ...
            $attributes = json_decode($input['updated_attributes']) ;

            foreach ($attributes as $attribute ){
                $att = Attribute::firstOrCreate([ 'name' => $attribute->name ],
                    ['type'=>$attribute->type]);
                $updatedCat->attributes()->save($att);
            }
        }

        /// add the fathers attributes ....
        if( $updatedCat->parent) {
            foreach ($updatedCat->parent->attributes as $att) {
                $updatedCat->attributes()->save($att);
                $pivot = Attributable::whereAttributeId($att->id)->whereAttributableId($updatedCat->id)->whereAttributableType('App\Category')->first();
                $pivot->own = 0;
                $pivot->save();
            }
        }


        return redirect('/dashboard');
    }

    public function destroy($id , $dontRedirect = false)
    {
        if(Capability::hasCapability('delete' , 'cat' )){

            $deletedCat = Category::find($id) ;
            $ids = [] ;
            /// get All belong categories (children).
            $this->downLevelIds($deletedCat , $ids );

            /// for each element wanted to be deleted ...
            foreach ($ids as $id_) {

                //delete category's Structure
                $deletedCat = Category::find($id_);

                /// get the main menu cat the belong to
                if($deletedCat->mainMenu && !$dontRedirect ){
                    $handller = new MainMenuController();
                    $handller->destroy($deletedCat->mainMenu->id , true);
                }

                /// delete category
                $deletedCat->delete() ;

                //delete its Own Items
                $items = Item::whereCatId($id_)->get();
                foreach ( $items as $item ){
                    $handller = new ItemController();
                    $handller->destroy($item->id , true );
                }

                //delete its Own Attributes
                $deletedAttr = Attributable::whereAttributableId($id_)->whereAttributableType('App\Category')->get();
                foreach ($deletedAttr as $attr){
                    $attr->delete();
                }

                //delete its Own Translates ...
                $deletedTrans = Translator::whereTranslatorsId($id_)->whereTranslatorsType('App\Category')->get();
                foreach ($deletedTrans as $tran){
                    $tran->delete();
                }

                /// check for clean ....
                $cleanCats = Category::whereMmParentId(null)->whereCatParentId(null)->get();
                foreach ( $cleanCats as $cat ){
                    $this->destroy($cat->id);
                }

            }

            if(!$dontRedirect) {return redirect('/dashboard');}
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
