<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Capability extends Model
{
    protected $fillable = ['role_id' , 'name' ];

    public static $capabilities = [
        'access_assign' => ['message'=>'Access To Role-Assigning Management ' ] ,
        'create_assign' => ['message'=>'Assigning Role To User ' ] ,
        'edit_assign'   => ['message'=> 'Edit a Role Assignment To User '] ,
        'delete_assign' => ['message'=>'Delete a Role Assignment To User ' ] ,

        'access_role' => ['message'=>'Access To Role Management ' ] ,
        'create_role' => ['message'=>'Create A New Role ' ] ,
        'edit_role'   => ['message'=> 'Edit a Role '] ,
        'delete_role' => ['message'=>'Delete A Role  ' ] ,

        'access_dashboard' => ['message'=>'Access To Dashboard ' ] ,

        'access_cat' => ['message'=>'Access To Category ' ] ,
        'create_cat' => ['message'=>'Create A New Category ' ] ,
        'edit_cat' => ['message'=> 'Edit a Category '] ,
        'delete_cat' => ['message'=>'Delete A Category ' ] ,

        'access_item' => ['message'=>'Access To Item ' ] ,
        'create_item' => ['message'=>'Create A New Item ' ] ,
        'edit_item' => ['message'=> 'Edit a Item '] ,
        'delete_item' => ['message'=>'Delete A Item ' ] ,

        'access_lang' => ['message'=>'Access To List of Languages ' ] ,
        'create_lang' => ['message'=>'Create A New Language ' ] ,
        'edit_lang' => ['message'=> 'Edit a Language '] ,
        'delete_lang' => ['message'=>'Delete A Language ' ],

        'access_keyword' => ['message'=>'Access To List of keywords ' ] ,
        'create_keyword' => ['message'=>'Create A New keyword ' ] ,
        'edit_keyword' => ['message'=> 'Edit a keyword '] ,
        'delete_keyword' => ['message'=>'Delete A keyword ' ]
    ];

    public static $errors = [
        'access' => 'you don\'t have privilege to access ' ,
        'delete' => 'you don\'t have privilege to delete  ' ,
        'edit' => 'you don\'t have privilege to edit  ' ,
        'create' => 'you don\'t have privilege to create  ' ,
    ];

    public function roles(){
        return $this->belongsTo('App\Role');
    }


    /**
     * check whether user has capability or not
     * @param $verb refer to (access , edit , create , delete , assign ) action
     * @param $side refer to effected side (category , item , role , ...etc.  )
     * @return boolean value (true/false) whether user(logged in user) has or not
     */

    public static function hasCapability($verb , $side){
        $capability_name = strtolower(trim($verb)) . ":" . strtolower(trim($side));
        $roles = Auth::user()->roles;
        foreach ($roles as $role){
           $capabilities =  $role->capabilities;
           foreach ($capabilities as $capability){
               //var_dump($capability);
               if($capability->name == $capability_name ) return true ;
           }
        }

        //dd('done');
        return false ;
    }


    public static function getCapabList($role_id){
        $list = [];
        $role = Role::find($role_id);
        foreach ($role->capabilities as $capability ){
            $name = str_replace(':' , '_' , $capability->name );
            $list[] = $name;
        }
        return $list;
    }
}
