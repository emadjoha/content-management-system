

@extends('layouts.default')


@section('head')
@stop



@section('header')
@stop

{{-- id   name  align--}}

@section('content')

    {!! Form::open(['url' => '/lang' ,'class' =>'col-md-6 col-md-offset-3']) !!}
    <div class="form-group"  >
        <h2 class="text-center" style="color: red" > New Language  </h2>
        {!! Form::label('name' , 'Name' ) !!}
        {!! Form::text('name' , '' ,['placeholder'=>'type name of language here ..' , 'class'=>'form-control' , 'required' ] ) !!}
        <h3 class="text-center" style="color: blueviolet" > Text Align  </h3>
        {!! Form::label('align_lef_to_right' , 'From Left To Right' , ['style'=>'width: 30%;'] ) !!}
        {!! Form::radio('align' , 'false' , true ) !!} <br>
        {!! Form::label('align_right_to_left' , 'From right To left' ,['style'=>'width: 30%;']) !!}
        {!! Form::radio('align' , 'true'  ) !!}
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
