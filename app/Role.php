<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    protected $fillable = ['name', 'description'];

    public function users(){
        return $this->belongsToMany('App\User' , 'role_assignments');
    }
/*
    /// many-to-many relationship (category-attribute) using (privileges as pivot table)
    public function categories(){
        return $this->morphToMany('App\Category' , 'privilege' );
    }

    /// many-to-many relationship (item-attribute) using (privileges as pivot table)
    public function items(){
        return $this->morphToMany('App\Item' , 'privilege' );
    }

    */
    public function capabilities(){
        return $this->hasMany('App\Capability');
    }


    /// get Roles list ...
    public static function getRoleList(){
        $list = [] ;
        $cats = parent::all();

        $list[null] = 'non-selected role ...' ;


        foreach ($cats as $cat ){
            $list[$cat->id] = $cat->name ;
        }
        return $list ;
    }
}
