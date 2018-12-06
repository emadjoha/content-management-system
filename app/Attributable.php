<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attributable extends Model
{

    protected $fillable = ['attribute_id' , 'attributable_id' , 'attributable_type' , 'value' , 'own'  ] ;




    public function translators(){
        return $this->morphToMany('App\Language' , 'translators' );
    }

}
