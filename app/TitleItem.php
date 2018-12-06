<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TitleItem extends Model
{
    protected $fillable = ['item_id'] ;

    public function item(){
        return $this->belongsTo('App\Item');
    }

    public function translators(){
        return $this->morphToMany('App\Language' , 'translators' );
    }
}
