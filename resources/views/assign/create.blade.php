

@extends('layouts.default')


@section('head')
@stop



@section('header')
@stop

@section('content')
    {!! Form::open(['url'=>'/assign', 'method'=>'post' ,'class' =>'col-md-6 col-md-offset-3'  ]) !!}
    <div class="form-group">
        <h2 class="text-center" style="color: red" > Role Assignment   </h2>
        {!! Form::label('name' , 'User\'s Name' ) !!}
        {!! Form::text('name' , '' ,['placeholder'=>'type name of user here ..' , 'class'=>'form-control' ] ) !!}
        {!! Form::label('role' ,'Role (Belong To User) : ' ) !!}
        {!! Form::select('role',$roles ,'0', ['id'=>'role_list','class'=>'form-control']) !!}
        {!! Form::submit('Assign !' ,['class'=>'btn btn-success pull-right' ,'style'=>'margin-top:5px' ] ); !!}
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
