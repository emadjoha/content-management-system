

@extends('layouts.default')


@section('head')
@stop



@section('header')
@stop

{{-- id   name  align--}}

@section('content')
    {{--<p dir="rtl" lang="ar" style="color:#e0e0e0;font-size:20px;">رَبٍّ زِدْنٍي عِلمًا</p>--}}

    <div class='col-md-6 col-md-offset-3'>
        <div class="form-group"  >

            {!! Form::open(['action' => ['LanguageController@update', $lang->id] ,'method'=>'put' ]) !!}
            <h2 class="text-center" style="color: red" >Edit </h2>
            {!! Form::label('name' , 'Name' ) !!}
            {!! Form::text('name' , $lang->name ,['placeholder'=>'type name of language here ..' , 'class'=>'form-control' , 'required' ] ) !!}
            <h3 class="text-center" style="color: blueviolet" > Text Align  </h3>
            {!! Form::label('align_lef_to_right' , 'From Left To Right' , ['style'=>'width: 30%;'] ) !!}
            @if( $lang->align == 0)
                {!! Form::radio('align' , 'false' , true ) !!} <br>
            @else
                {!! Form::radio('align' , 'false' ) !!} <br>
            @endif
            {!! Form::label('align_right_to_left' , 'From right To left' ,['style'=>'width: 30%;']) !!}
            @if( $lang->align == 1)
                {!! Form::radio('align' , 'true' , true ) !!} <br>
            @else
                {!! Form::radio('align' , 'true' ) !!} <br>
            @endif
            {!! Form::submit('Add !' ,['class'=>'btn btn-success pull-right' ,'style'=>'margin-top:5px' ] ); !!}
            <button class="btn btn-danger pull-right" style='margin:5px'   onclick="" >Cancel</button>

            {!! Form::close() !!}
        </div>
    </div>
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
