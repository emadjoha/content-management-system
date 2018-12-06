<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = ['name' , 'type'  ] ;
    /// many-to-many relationship (category-attribute) using (attributable as pivot table)
    public function categories(){
        return $this->morphToMany('App\Category' , 'attributable' );
    }

    /// many-to-many relationship (item-attribute) using (attributable as pivot table)
    public function items(){
        return $this->morphToMany('App\Item' , 'attributable' );
    }


}
