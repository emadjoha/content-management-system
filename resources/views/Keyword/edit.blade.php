

@extends('layouts.default')


@section('head')
@stop


@section('header')
@stop

{{-- id   name  align--}}

@section('content')

    {!! Form::open(['action' => ['KeywordController@update', $keyword->id] ,'method'=>'put','class' =>'col-md-6 col-md-offset-3']) !!}
    <div class="form-group"  >
        <h2 class="text-center" style="color: red" > New Keyword  </h2>
        {!! Form::label('name' , 'Name' ) !!}
        {!! Form::text('name' , $keyword->name ,['placeholder'=>'type name of language here ..' , 'class'=>'form-control' , 'required' ] ) !!}
        <h3 class="text-center" style="color: blueviolet" > Its Translates  </h3>
        <?php $langs = \App\Language::all(); ?>
        @foreach( $langs as $lang )
            <?php $word = \App\Translator::whereLanguageId($lang->id)->whereTranslatorsId($keyword->id)->whereTranslatorsType('App\KeyWords')->first();
            if( !$word ){
                $word = new stdClass();
                $word->content = "????";
            }
            ?>
             @if($lang->align == 0)
                <div class="form-group" dir="ltr" >
                    <hr>
                    <h3 style="color: blueviolet;" > {{$lang->name}} </h3>
                    <textarea name="content_<?=$lang->id?>" id="input" required class="form-control" >{{$word->content}} </textarea>
                </div>
            @else
                <div class="form-group"  dir="rtl" >
                    <hr>
                    <h3 style="color: blueviolet;" > {{$lang->name}} </h3>
                    <textarea name="content_<?=$lang->id?>" id="input" required class="form-control" >{{$word->content}} </textarea>
                </div>
            @endif
        @endforeach
    </div>
    {!! Form::submit('Edit !' ,['class'=>'btn btn-success pull-right' ,'style'=>'margin-top:5px' ] ); !!}
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
