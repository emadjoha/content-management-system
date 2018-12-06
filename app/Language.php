<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{

    protected  $fillable = ['name'];
    public function categories(){
        return $this->morphToMany('App\Category' , 'translators' );
    }
    public function mainMenus(){
        return $this->morphToMany('App\MainMenu' , 'translators' );
    }
    public function items(){
        return $this->morphToMany('App\Item' , 'translators' );
    }
    public function keyWords(){
        return $this->morphToMany('App\KeyWords' , 'translators' );
    }

    public function Attributables(){
        return $this->morphToMany('App\Attributable' , 'translators' );
    }
}
