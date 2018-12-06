

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
                    <th><?= \App\KeyWords::getKeyWord('name')->content ?></th>
                    <th><?= \App\KeyWords::getKeyWord('description')->content ?></th>
                    <th><?= \App\KeyWords::getKeyWord('create_at')->content ?></th>
                    <th><?= \App\KeyWords::getKeyWord('update_at')->content ?></th>
                </tr>
                </thead>

                <tbody>
                <?php $cont=1; ?>
                @foreach($roles as $role )
                    <tr>
                        <td>{{$cont++}}</td>
                        <td>{{$role->name}}</td>
                        <td>{{$role->description}}</td>
                        <td>{{$role->created_at}}</td>
                        <td>{{$role->updated_at}}</td>
                        @if( $role->name !== 'admin' )
                            <td>
                                @if(\App\Capability::hasCapability('edit' , 'role' ))
                                    {!! Form::open(['action' => ['RoleController@edit', $role->id] ]) !!}
                                    <input type="hidden" name="_method" value="GET" >
                                    <button type="submit" >
                                        <span class='glyphicon glyphicon-cog'></span>
                                    </button>
                                    {!! Form::close() !!}
                                @endif
                            </td>
                            <td>
                                @if(\App\Capability::hasCapability('delete' , 'role' ))
                                    {!! Form::open(['action' => ['RoleController@destroy', $role->id] ]) !!}
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

        @if(\App\Capability::hasCapability('create' , 'role' ))
            <a href="{{route('role.create')}}" >
                <button class="btn btn-primary" >
                    <?= \App\KeyWords::getKeyWord('create_new_role')->content ?>
                </button>
            </a>
        @endif
        @if(\App\Capability::hasCapability('create' , 'assign' ))
            <a href="{{route('assign.create')}}" >
                <button class="btn btn-success" >
                    <?= \App\KeyWords::getKeyWord('create_new_assignment')->content ?>
                </button>
            </a>
        @endif
        <br><br><br>
    </div>
@stop



@section('footer')
@stop
