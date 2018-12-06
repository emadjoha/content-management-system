<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['mm_parent_id' , 'cat_parent_id' , 'name' ,
                            'sort' , 'url' , 'display_name'  ];

    // Inverse one-to-one relationship with main_menus table ....
    public function mainMenu(){
        return $this->belongsTo('App\MainMenu' , 'mm_parent_id' );
    }

    // one-to-many relationship with itself (categories table ) ...
    public function children(){
        return $this->hasMany('App\Category' , 'cat_parent_id' )->orderBy('sort');
    }

    // Inverse one-to-many relationship with itself (categories table ) ...
    public function parent(){
        return $this->belongsTo('App\Category' , 'cat_parent_id');
    }

    // one-to-many relationship with items table ...
    public function items(){
        return $this->hasMany('App\Item' , 'cat_id' );
    }

    /// many-to-many relationship (category-attribute) using (attributable as pivot table)
    public function attributes(){
        return $this->morphToMany('App\Attribute' , 'attributable' );
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
            if($cat->name === 'dashboard') continue;
            $list[$cat->id ] = $cat->display_name ;
        }
        return $list ;
    }

}
