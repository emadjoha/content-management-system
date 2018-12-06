

@extends('layouts.default')


@section('head')
    <style>
        label {
            width : 70%
        }
    </style>
@stop



@section('header')
@stop

{{--name	description--}}

@section('content')
    {!! Form::open(['action' => ['RoleController@update', $role->id] ,'method'=>'put' ,'class' =>'col-md-6 col-md-offset-3'  ]) !!}
    <div class="form-group">
        <h2 class="text-center" style="color: red" > New Role  </h2>
        {!! Form::label('name' , 'Name' ) !!}
        {!! Form::text('name' , $role->name ,['placeholder'=>'type name of role here ..' , 'class'=>'form-control' ] ) !!}
        {!! Form::label('description' , 'Description' ) !!}
        {!! Form::text('description' , $role->description ,['placeholder'=>'type description about role here ..' , 'class'=>'form-control' ] ) !!}
        <h3 class="text-center" style="color: blueviolet" > Its Capabilities </h3>
        @foreach(\App\Capability::$capabilities as $name => $msg )
            @if(!in_array($name , $capabilities_check))
                {!! Form::label( $name , $msg['message'] ) !!}
                {!! Form::checkbox($name, $name); !!}<hr>
            @else
                {!! Form::label( $name , $msg['message'] ) !!}
                {!! Form::checkbox($name, $name , true ); !!}<hr>
            @endif
        @endforeach
        {!! Form::submit('Add !' ,['class'=>'btn btn-success pull-right' ,'style'=>'margin-top:5px' ] ); !!}
    </div>
    {!! Form::close() !!}
@stop
{{--,--}}


@section('footer')


    @if ($errors->any())
        <br>
        <br>
        <div class="container">
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
@stop
