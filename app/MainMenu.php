<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MainMenu extends Model
{
    private static $viewBaseUrl = "http://localhost/cms/public/view" ;

    protected $fillable = ['parent_id' , 'name' , 'sort' , 'url' , 'display_name'  ];

    // one-to-one relationship with categories table ....
    public function category(){
        return $this->hasOne('App\Category' , 'mm_parent_id' );
    }

    // one-to-many relationship with itself (main_menus table ) ...
    public function children(){
        return $this->hasMany('App\MainMenu' , 'parent_id')->orderBy('sort');
    }


    // Inverse one-to-many relationship with itself (main_menus table ) ...
    public function parent(){
        return $this->belongsTo('App\MainMenu' , 'parent_id' );
    }


    public function translators(){
        return $this->morphToMany('App\Language' , 'translators' );
    }

    /// get Category list ...
    public static function getCatList(){
        $list = [] ;
        $cats = parent::all();

        $list[null] = 'non-selected category ...' ;


        foreach ($cats as $cat ){
           /// if($cat->name === 'dashboard') continue;
            $list[$cat->id] = $cat->display_name ;
        }
        return $list ;
    }

    public static function getNavBarList(){
       $html = "" ;
       $buttons = "";

       /// first level ....
        $cats = parent::whereParentId(null)->orderBy('sort');

        foreach ($cats->get() as $cat) {

            $buttons .= "\n<span class='icon-bar'></span>";
            $url = self::$viewBaseUrl . $cat->url ;

            $lang_id = session('lang');
            $word = Translator::whereLanguageId($lang_id)->whereTranslatorsId($cat->id)->whereTranslatorsType('App\MainMenu')->first();

            if( !$word ){
                $word = new \stdClass();
                $word->content = "????";
            }


            if( count($cat->children) ){
                $html .= "\n<li>
                            <a href='{$url}' class='dropdown-toggle' data-toggle='dropdown'>{$word->content} <b class='caret'></b></a>
                            <ul class='dropdown-menu'>";
                foreach ($cat->children as $child){
                    self::downLevel( $child , $html);
                }
                $html .= "\n</ul>\n</li>\n";
            }else{
                $html .="\n<li><a href='{$url}'>{$word->content}</a></li>";
            }
        }
        return ['list' => $html , 'buttons' => $buttons ];
    }

    protected static function downLevel( $cat , &$html ){

        $url = self::$viewBaseUrl . $cat->url ;
        $lang_id = session('lang');
        $word = Translator::whereLanguageId($lang_id)->whereTranslatorsId($cat->id)->whereTranslatorsType('App\MainMenu')->first();

        if( !$word ) {
            $word = new \stdClass();
            $word->content = "????";
        }
        if( count($cat->children) ) {

            $html .= "\n<li class='dropdown-submenu' >
                            <a href='{$url}' class='dropdown-toggle' data-toggle='dropdown'>{$word->content}</a>
                            <ul class='dropdown-menu'>";
            foreach ($cat->children as $item ){
                self::downLevel($item ,$html );
            }
            $html .= "\n</ul>\n</li>\n" ;
        }else{
            $html .="\n<li><a href='{$url}'>{$word->content}</a></li>";
        }
        return true;
    }

}
