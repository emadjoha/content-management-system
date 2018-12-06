<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KeyWords extends Model
{
    protected  $fillable = [ 'content'];

    public function translators(){
        return $this->morphToMany('App\Language' , 'translators' );
    }

    public static function getKeyWord($content){
        $lang_id = session('lang');
        $word_id = KeyWords::whereName($content)->first()->id;
        //dd($word_id);
        $word = Translator::whereLanguageId($lang_id)->whereTranslatorsId($word_id)->whereTranslatorsType('App\KeyWords')->first();

        if( !$word ){
            $word = new \stdClass();
            $word->content = "????";
        }
        return $word;
    }
}
