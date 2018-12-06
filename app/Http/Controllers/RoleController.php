<?php

namespace App\Http\Controllers;

use App\Capability;
use App\Http\Requests\RoleStore;
use App\Role;
use App\RoleAssignment;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }



    public function index()
    {
        if( Capability::hasCapability('access' , 'role' ) ){
            return view('Role.management' , ['roles'=>Role::all()] );
        }else{
            return view('warning' , ['message' => Capability::$errors['access'] . 'Role Management' ] );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( Capability::hasCapability('create' , 'role' ) ){
            return view('Role.create');
        }else{
            return view('warning' , ['message' => Capability::$errors['create'] . 'Role' ] );
        }
    }


    public function store(RoleStore $request)
    {

        $crtRole = Role::create($request->all());

        $capabilities = $request->all();
        unset($capabilities['_token'],$capabilities['name'],$capabilities['description']);

        foreach ($capabilities as $item ){
            $storedKey =  str_replace('_' , ':' , $item) ;
            Capability::firstOrCreate(['name' => $storedKey , 'role_id'=>$crtRole->id ]);
        }

        return redirect('/role');
    }



    public function show($id)
    {
        //
    }

    public function edit($id)
    {

        if( Capability::hasCapability('edit' , 'role' ) ){
            $role = Role::find($id);
            $capabilities_check = Capability::getCapabList($id);
            return view('Role.edit' , compact('role' , 'capabilities_check' ) );

        }else{
            return view('warning' , ['message' => Capability::$errors['edit'] . 'Role' ] );
        }

    }



    public function update(RoleStore $request, $id)
    {
        $input = $request->all();
        Role::find($id)->update(['name'=>$input['name'],'description' => $input['description']]);

        unset($input['_token'], $input['_method'] ,$input['name'],$input['description']);
        Capability::whereRoleId($id)->delete();

        foreach ($input as $item ){
            $storedKey =  str_replace('_' , ':' , $item) ;
            Capability::firstOrCreate(['name' => $storedKey , 'role_id'=>$id ]);
        }

        return redirect('role');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $role = Role::find($id);
        foreach ($role->users as $user ){
            RoleAssignment::whereRoleId($id)->whereUserId($user->id)->delete();
        }
        $role->delete();
        return redirect('role');
    }
}
