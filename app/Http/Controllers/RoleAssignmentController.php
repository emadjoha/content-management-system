<?php

namespace App\Http\Controllers;

use App\Capability;
use App\Http\Requests\RoleAssignStore;
use App\Role;
use App\RoleAssignment;
use App\User;
use Illuminate\Http\Request;

class RoleAssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {

        if( Capability::hasCapability('access' , 'assign' ) ){
            $list = [] ;
            $roles = Role::all();
            foreach ($roles as $role ){
                foreach ($role->users as $user ){
                    $assign = RoleAssignment::whereUserId($user->id)->whereRoleId($role->id)->first() ;
                    $list[] = ['role_name'=>$role->name , 'user_name'=>$user->name , 'assign' => $assign ];
                }
            }
            return view('assign.management' , ['list' => $list ] ) ;
        }else{
            return view('warning' , ['message' => Capability::$errors['access'] . 'Role-Assignment Management' ] );
        }

    }



    public function create()
    {
        if( Capability::hasCapability('create' , 'assign' ) ){
            return view('assign.create' , ['roles'=>Role::getRoleList()] );
        }else{
            return view('warning' , ['message' => Capability::$errors['create'] ] );
        }
    }

    public function store(RoleAssignStore $request)
    {
            //dd( $request->all() );
            $input = $request->all();
            $user = User::whereName($input['name'])->first();

            $user->roles()->save(Role::find($input['role']));
            return redirect('/assign');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        if( Capability::hasCapability('edit' , 'assign' ) ){
            $assign = RoleAssignment::find($id);
            $user = User::find($assign->user_id)->name;
            $role = $assign->role_id;
            $roles = Role::getRoleList();

            return view('assign.edit' , ['user' => $user , 'role' => $role , 'roles' => $roles , 'assign' => $id  ] );

        }else{
            return view('warning' , ['message' => Capability::$errors['edit'] . 'Role Assignment' ] );
        }
    }


    public function update(RoleAssignStore $request, $id)
    {

        $input = $request->all();
        unset($input['_token'] , $input['_method']);

        $user = User::whereName($input['name'])->first()->id;
        $role = Role::find($input['role'])->id;
        RoleAssignment::find($id)->update(['user_id'=>$user,'role_id'=>$role]);
        return redirect('/assign');
    }


    public function destroy($id)
    {
        RoleAssignment::find($id)->delete();
        return redirect('/assign');
    }
}
