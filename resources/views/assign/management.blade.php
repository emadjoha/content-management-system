@extends('layouts.default')
@section('head')
@stop
@section('header')
@stop
@section('content')

    <div id="role_menu">
        <div>
            <table class="table table-striped col-md-10 table-responsive">
                <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Created at </th>
                    <th>Updated at </th>
                </tr>
                </thead>

                <tbody>
                <?php $cont=1; ?>
                @foreach($list as $item )
                    <tr>
                        <td>{{$cont++}}</td>
                        <td>{{$item['user_name']}}</td>
                        <td>{{$item['role_name']}}</td>
                        <td>{{$item['assign']->created_at}}</td>
                        <td>{{$item['assign']->updated_at}}</td>
                        @if( $item['role_name'] !== 'admin' )
                            <td>
                                @if(\App\Capability::hasCapability('edit' , 'assign' ))
                                    {!! Form::open(['action' => ['RoleAssignmentController@edit', $item['assign']->id] ]) !!}
                                    <input type="hidden" name="_method" value="GET" >
                                    <button type="submit" >
                                        <span class='glyphicon glyphicon-cog'></span>
                                    </button>
                                    {!! Form::close() !!}
                                @endif
                            </td>
                            <td>
                                @if(\App\Capability::hasCapability('delete' , 'assign' ))
                                    {!! Form::open(['action' => ['RoleAssignmentController@destroy', $item['assign']->id] ]) !!}
                                    <input type="hidden" name="_method" value="DELETE" >
                                    <button type="submit" >
                                        <span class='glyphicon glyphicon-trash'></span>
                                    </button>
                                    {!! Form::close() !!}
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        @if(\App\Capability::hasCapability('create' , 'assign' ))
            <a href="{{route('assign.create')}}" >
                <button class="btn btn-primary" >
                    Assign Role To User
                </button>
            </a>
        @endif
        <br><br><br>
    </div>
@stop



@section('footer')
@stop
