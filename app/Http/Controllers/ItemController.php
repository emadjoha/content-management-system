<?php

namespace App\Http\Controllers;

use App\Attributable;
use App\Attribute;
use App\Capability;
use App\Category;
use App\Item;
use App\Language;
use App\Metadata;
use App\TitleItem ;
use App\Translator;
use Illuminate\Http\Request;
use  App\Http\Requests\ItemStore;

class ItemController extends Controller
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
        if(Capability::hasCapability('create' , 'item' )){
            return view('Item.create', [
                'catList' => Category::getCatList()
            ]);
        }else{
            return view('warning' , ['message' => Capability::$errors['create'] . 'Item' ] );
        }

    }

    public function store(ItemStore $request)
    {
        $input = $request->all() ;
        $input['url'] =  Category::whereId($input['cat_id'])->first()->url  ;

        $input['url'] .= '/' . $input['name'] ;

        $cats = Item::whereName($input['name']);
        /// need deleting  ........
        /// ..............
        $input['display_name'] = $input['name'];

        /// need changing .....
        /// ................
        $input['content'] = $input['content_1'];

        foreach ($cats->get() as $cat ){
            if( $cat->url == $input['url'] ){
                /// duplicate url ...
                return redirect('/item/create');
            }
        }

        /// add item's image 
        if($request->pic ){
            $picName = $request->pic->getClientOriginalName();
            $picExtn = $request->pic->getClientOriginalExtension() ; 
           
            $chkPath = public_path('item_pics') . "\\" .$picName ; 
            if ( !(\File::exists($chkPath )) ){
                $request->pic->move(public_path('item_pics'),$request->pic->getClientOriginalName());
            }
        }else {
            $picName = 'default.jpg';
        }
       
        $input['pic'] = $picName ;
        //dd($input);

        $crtItem = Item::create($input);
        $createdMD = Metadata::create(['item_id'=>$crtItem->id]);
        $createdTtl = TitleItem::create(['item_id'=>$crtItem->id]);

        $langs = Language::all();
        foreach ($langs as $lang ){
            $crtItem->translators()->save($lang);
            $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($crtItem->id)->whereTranslatorsType('App\Item')->first();
            $pivot->update(['content'=> $input['content_' . $lang->id ] ]);

            /// add meta data (Description) ...
            $createdMD->translators()->save($lang);
            $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($createdMD->id)->whereTranslatorsType('App\Metadata')->first();
            $pivot->update(['content'=> $input['description_' . $lang->id ] ]);

            /// add title's item 
            $createdTtl->translators()->save($lang);
            $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($createdTtl->id)->whereTranslatorsType('App\TitleItem')->first();
            $pivot->update(['content'=> $input['title_' . $lang->id ] ]);
            
        }


        $keys = array_keys($input);

        foreach ($keys as $key) {
            if( strpos($key , 'attribute_old_name_') !== false ){

                    $value = $input[$key];
                    $name = substr($key , 19 , strlen($key)-19) ;
                    $delim = strpos($name , '_*language*_') ;
                    $lanId = substr($name , $delim + 12  , strlen($name) - $delim  - 12 );
                    $name  = substr($name , 0 ,  $delim);
                    $type =  $input['attribute_old_type_'.$name] ;

                    // fetch then assign
                    $attr = Attribute::whereName($name)->whereType($type)->first();

                    if( $lanId == 1 ) {
                        $crtItem->attributes()->save($attr);
                        $attributable = Attributable::whereAttributeId($attr->id)->whereAttributableId($crtItem->id)->whereAttributableType('App\Item')->first();

                        /// need changing ......
                        /// ....................
                        $attributable->update(['value' => $value, 'own' => 0 ]);
                    }else{
                        $attributable = Attributable::whereAttributeId($attr->id)->whereAttributableId($crtItem->id)->whereAttributableType('App\Item')->first();
                    }
                    if( !($type == 'text' || $type == 'string' ) ){
                        foreach ($langs as $lang){
                            if( $lang->id != 1 ) {
                                $attributable->translators()->save(Language::find($lang->id));
                                $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($attributable->id)->whereTranslatorsType('App\Attributable')->first();
                                $pivot->update(['content' => $value]);
                            }
                        }
                    }
                    $attributable->translators()->save(Language::find($lanId));
                    $pivot = Translator::whereLanguageId($lanId)->whereTranslatorsId($attributable->id)->whereTranslatorsType('App\Attributable')->first();
                    $pivot->update(['content'=> $value]);

            }else if(strpos($key , 'attribute_new_name_') !== false){

                    $value = $input[$key];
                    $name = substr($key , 19 , strlen($key)-19) ;
                    $delim = strpos($name , '_*language*_') ;
                    $lanId = substr($name , $delim + 12  , strlen($name) - $delim  - 12 );
                    $name  = substr($name , 0 ,  $delim);
                    $type =  $input['attribute_new_type_'.$name] ;

                    // create then assign
                    $attr = Attribute::firstOrCreate([ 'name' => $name ], ['type'=>$type ]);

                    if( $lanId== 1 ) {
                        $crtItem->attributes()->save($attr);
                        $attributable = Attributable::whereAttributeId($attr->id)->whereAttributableId($crtItem->id)->whereAttributableType('App\Item')->first();

                        /// need changing ......
                        /// ....................
                        $attributable->update(['value' => $value, 'own' => 1 ]);
                    }else {
                        $attributable = Attributable::whereAttributeId($attr->id)->whereAttributableId($crtItem->id)->whereAttributableType('App\Item')->first();
                    }

                    if( !($type == 'text' || $type == 'string' ) ){
                        foreach ($langs as $lang){
                            if( $lang->id != 1 ) {
                                $attributable->translators()->save(Language::find($lang->id));
                                $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($attributable->id)->whereTranslatorsType('App\Attributable')->first();
                                $pivot->update(['content' => $value]);
                            }
                        }
                    }

                    $attributable->translators()->save(Language::find($lanId));
                    $pivot = Translator::whereLanguageId($lanId)->whereTranslatorsId($attributable->id)->whereTranslatorsType('App\Attributable')->first();
                    $pivot->update(['content'=> $value]);
            }
        }

    return redirect( 'dashboard' ) ;
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        if(Capability::hasCapability('edit' , 'item' )){
            $item     = Item::find($id);
            $catList = Category::getCatList();
            return view('Item.edit' ,compact('item' , 'catList') ) ;

        }else{
            return view('warning' , ['message' => Capability::$errors['edit'] . 'Item' ] );
        }
    }

    public function getAttributes(Request $request){

        $attributes = Category::find($request->input('category'))->attributes;

        if( $request->has('item') ){
            $list = [];
            /// get attributes (belong to category ) with their own (value + own) columns' value
            foreach ($attributes as $attribute){
                $pivot = Attributable::whereAttributeId($attribute->id)->whereAttributableId($request->input('item'));
                $valObj = $pivot->whereAttributableType('App\Item')->first();
                $obj = new \stdClass() ;
                $obj->id   = $attribute->id ;
                $obj->name = $attribute->name;
                $obj->type = $attribute->type;
                if($valObj){ /// same cat parent  ....
                    $obj->value = $valObj->value ;
                    $obj->own   = $valObj->own ;
                    $obj->attributable = Translator::whereTranslatorsId($valObj->id )->whereTranslatorsType('App\Attributable')->get();
                }else{ /// new cat parent ...
                    $obj->value = "" ;
                    $obj->own   = 0 ;
                    $obj->attributable = [] ;
                }
                $list[] = $obj;
            }

            /// after create item (using for update status )
            $attributes = Item::find($request->input('item'))->attributes ;
            /// get attributes (belong to Item ) with their own (value + own) columns' value
            foreach ($attributes as $attribute){
                $pivot = Attributable::whereAttributeId($attribute->id)->whereAttributableId($request->input('item'));
                $valObj = $pivot->whereAttributableType('App\Item')->whereOwn(1)->first();
                /// where own = 1 (take only what attribute belong to this Item and ignore inherited Attributes)
                if( $valObj ){
                    $obj = new \stdClass() ;
                    $obj->id   = $attribute->id ;
                    $obj->name = $attribute->name;
                    $obj->type = $attribute->type;
                    $obj->value = $valObj->value ;
                    $obj->own   = $valObj->own ;
                    $obj->attributable = Translator::whereTranslatorsId($valObj->id )->whereTranslatorsType('App\Attributable')->get();
                    $list[] = $obj;
                }

            }

            $msg = ['attributes'=>$list ];
            return response()->json($msg);
        }

        $msg = ['attributes'=>$attributes  ];
        //return $msg;
        return response()->json($msg);
    }

    public function update(Request $request, $id)
    {

        ///dd($request->all() );
        $input = $request->all() ;
        $input['url'] =  Category::whereId($input['cat_id'])->first()->url  ;

        $input['url'] .= '/' . $input['name'] ;
        /// need deleting  ........
        /// ..............
        $input['display_name'] = $input['name'];

        /// need changing .....
        /// ................
        $input['content'] = $input['content_1'];

        $cats = Item::whereName($input['name']);

        foreach ( $cats->get() as $cat  ){
            if( $cat->id != $id ) return redirect()->back();
        }

        unset($input['_token'] , $input['_method']);

        /// Update item's image 
        if($request->pic ){
            $picName = $request->pic->getClientOriginalName();
            $picExtn = $request->pic->getClientOriginalExtension() ; 
           
            $chkPath = public_path('item_pics') . "\\" .$picName ; 
            if ( !(\File::exists($chkPath )) ){
                $request->pic->move(public_path('item_pics'),$request->pic->getClientOriginalName());
            }
            $input['pic'] = $picName ;
        }

        

        $updatedItem =  Item::find($id);
        $updatedItem->update( $input );
        
        $updatedMD = $updatedItem->description ;
        $updatedTtl = $updatedItem->title ;

          
        $langs = Language::all();
        foreach ($langs as $lang ){
            ////$updatedItem->translators()->save($lang);
            $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($updatedItem->id)->whereTranslatorsType('App\Item')->first();
            if( !$pivot ){
                $updatedItem->translators()->save($lang);
                $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($updatedItem->id)->whereTranslatorsType('App\Item')->first();
            }
            $pivot->update(['content'=> $input['content_' . $lang->id ] ]);

            /// Update meta data (Description) ...
            $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($updatedMD->id)->whereTranslatorsType('App\Metadata')->first();
            if( !$pivot ){
                $updatedMD->translators()->save($lang);
                $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($updatedMD->id)->whereTranslatorsType('App\Metadata')->first();
            }
            $pivot->update(['content'=> $input['description_' . $lang->id ] ]);


            /// Update item's title  ...
            $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($updatedTtl->id)->whereTranslatorsType('App\TitleItem')->first();
            if( !$pivot ){
                $updatedTtl->translators()->save($lang);
                $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($updatedTtl->id)->whereTranslatorsType('App\TitleItem')->first();
            }
            $pivot->update(['content'=> $input['title_' . $lang->id ] ]);
        }

        // delete previous attributes  ...
        $deleteAttr = Attributable::whereAttributableId($id)->whereAttributableType('App\Item')->get();
        foreach ( $deleteAttr as $attr ){
            /// delete its translates ...
            $deleteTrans = Translator::whereTranslatorsId($attr->id)->whereTranslatorsType('App\Attributable')->get();
            foreach ($deleteTrans as $tran){
                $tran->delete();
            }
            $attr->delete();
        }


        $keys = array_keys($input);

        foreach ($keys as $key) {
            if( strpos($key , 'attribute_old_name_') !== false ){

                $value = $input[$key];
                $name = substr($key , 19 , strlen($key)-19) ;
                $delim = strpos($name , '_*language*_') ;
                $lanId = substr($name , $delim + 12  , strlen($name) - $delim  - 12 );
                $name  = substr($name , 0 ,  $delim);
                $type =  $input['attribute_old_type_'.$name] ;

                // fetch then assign
                $attr = Attribute::whereName($name)->whereType($type)->first();

                if( $lanId == 1 ) {
                    $updatedItem->attributes()->save($attr);
                    $attributable = Attributable::whereAttributeId($attr->id)->whereAttributableId($updatedItem->id)->whereAttributableType('App\Item')->first();

                    /// need changing ......
                    /// ....................
                    $attributable->update(['value' => $value, 'own' => 0 ]);
                }else{
                    $attributable = Attributable::whereAttributeId($attr->id)->whereAttributableId($updatedItem->id)->whereAttributableType('App\Item')->first();
                }

                if( !($type == 'text' || $type == 'string' ) ){
                    foreach ($langs as $lang){
                        if( $lang->id != 1 ) {
                            $attributable->translators()->save(Language::find($lang->id));
                            $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($attributable->id)->whereTranslatorsType('App\Attributable')->first();
                            $pivot->update(['content' => $value]);
                        }
                    }
                }

                $attributable->translators()->save(Language::find($lanId));
                $pivot = Translator::whereLanguageId($lanId)->whereTranslatorsId($attributable->id)->whereTranslatorsType('App\Attributable')->first();
                $pivot->update(['content'=> $value]);


            }else if(strpos($key , 'attribute_new_name_') !== false){

                $value = $input[$key];
                $name = substr($key , 19 , strlen($key)-19) ;
                $delim = strpos($name , '_*language*_') ;
                $lanId = substr($name , $delim + 12  , strlen($name) - $delim  - 12 );
                $name  = substr($name , 0 ,  $delim);
                $type =  $input['attribute_new_type_'.$name] ;

                // create then assign
                $attr = Attribute::firstOrCreate([ 'name' => $name ], ['type'=>$type ]);
                if( $lanId== 1 ) {
                    $updatedItem->attributes()->save($attr);
                    $attributable = Attributable::whereAttributeId($attr->id)->whereAttributableId($updatedItem->id)->whereAttributableType('App\Item')->first();

                    /// need changing ......
                    /// ....................
                    $attributable->update(['value' => $value, 'own' => 1 ]);
                }else {
                    $attributable = Attributable::whereAttributeId($attr->id)->whereAttributableId($updatedItem->id)->whereAttributableType('App\Item')->first();
                }

                if( !($type == 'text' || $type == 'string' ) ){
                    foreach ($langs as $lang){
                        if( $lang->id != 1 ) {
                            $attributable->translators()->save(Language::find($lang->id));
                            $pivot = Translator::whereLanguageId($lang->id)->whereTranslatorsId($attributable->id)->whereTranslatorsType('App\Attributable')->first();
                            $pivot->update(['content' => $value]);
                        }
                    }
                }


                $attributable->translators()->save(Language::find($lanId));
                $pivot = Translator::whereLanguageId($lanId)->whereTranslatorsId($attributable->id)->whereTranslatorsType('App\Attributable')->first();
                $pivot->update(['content'=> $value]);
            }
        }
        return redirect('/dashboard');
    }

    public function destroy($id , $dontRedirect = false )
    {
        $deletedItem = Item::find($id);

        /// delete its own attributes ...
        $deleteAttr = Attributable::whereAttributableId($id)->whereAttributableType('App\Item')->get();

        foreach ($deleteAttr as $attr) {
            /// delete translates' attributable ...
            $deleteTrans = Translator::whereTranslatorsId($attr->id)->whereTranslatorsType('App\Attributable')->get();
            foreach ($deleteTrans as $tran) {
                $tran->delete();
            }
            $attr->delete();
        }

        /// delete its own Translates ...
        $deleteTrans = Translator::whereTranslatorsId($id)->whereTranslatorsType('App\Item')->get();
        foreach ($deleteTrans as $tran) {
            $tran->delete();
        }

        /// delete ites own metadata & thier translate...
        $desc = $deletedItem->description;
        $deleteTrans = Translator::whereTranslatorsId($desc->id)->whereTranslatorsType('App\Metadata')->get();
        foreach ($deleteTrans as $tran ) {
            $tran->delete();
        }
        $desc->delete();

        /// delete ites own title & thier translate...
        $title = $deletedItem->title;
        $deleteTrans = Translator::whereTranslatorsId($title->id)->whereTranslatorsType('App\TitleItem')->get();
        foreach ($deleteTrans as $tran ) {
            $tran->delete();
        }
        $title->delete();

        $deletedItem->delete();

        if (!$dontRedirect) {
            return redirect()->back();
        }
    }
}
