<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    protected $fillable = [ 'cat_id' , 'pic' ,  'name' , 'url' , 'content' , 'display_name'  ];

    // Inverse one-to-many relationship with categories table ...
    public function category(){
        return $this->belongsTo('App\Category' , 'cat_id' );
    }


    /// many-to-many relationship (item-attribute) using (attributable as pivot table)
    public function attributes(){
        return $this->morphToMany('App\Attribute' , 'attributable' );
    }

    public function translators(){
        return $this->morphToMany('App\Language' , 'translators' );
    }

    public  function description(){
        return $this->hasOne('App\Metadata' );
    }
    
    public  function title(){
        return $this->hasOne('App\TitleItem' );
    }
}
