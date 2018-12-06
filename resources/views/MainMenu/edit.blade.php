

@extends('layouts.default')


@section('head')
@stop



@section('header')
@stop


@section('content')
    {!! Form::open(['action' => ['MainMenuController@update', $cat->id] ,'method'=>'put' ,'class' =>'col-md-6 col-md-offset-3'  ]) !!}
    <div class="form-group">
        <div class="text-center"
             style="border-left-color:saddlebrown;text-align: center; font-size: large;
                padding: 20px;margin: 20px 0;border: 1px solid saddlebrown;
   border-left-width:33px;border-radius: 100px;">Edit</div>
        <hr>
        {!! Form::label('parent_id' ,'Belong To (Main Menu) : ' ) !!}
        @if( $cat->parent )
            {!! Form::select('parent_id',$parentList ,$cat->parent->id, ['id'=>'main_menu_list','class'=>'form-control']) !!}
        @else
            {!! Form::select('parent_id',$parentList ,'1', ['id'=>'main_menu_list','class'=>'form-control']) !!}
        @endif
        {!! Form::label('name' , 'Actual Name' ) !!}
        {!! Form::text('name' , $cat->name ,['class'=>'form-control' , 'placeholder'=>'type name ...' ] ) !!}
        <?php $langs = \App\Language::all(); ?>
        {!! Form::label("display_name_1" , 'Displayed Name (what user see)' ) !!}
        <hr>
        @foreach( $langs as $lang )
            <div class="form-group">
                <?php $word = \App\Translator::whereLanguageId($lang->id)->whereTranslatorsId($cat->id)->whereTranslatorsType('App\MainMenu')->first(); ?>
                @if($lang->align == 0)
                    <div class="form-group" dir="ltr" >
                        <h3 style="color: saddlebrown;" > {{$lang->name}} </h3>
                        {!! Form::text("display_name_{$lang->id}", $word->content  , ['class'=>'form-control' , 'placeholder'=>'type name ...' ] ) !!}
                    </div>
                @else
                    <div class="form-group"  dir="rtl" style="text-align: right" >
                        <h3 style="color: saddlebrown;" > {{$lang->name}} </h3>
                        {!! Form::text("display_name_{$lang->id}", $word->content  , ['class'=>'form-control' , 'placeholder'=>'type name ...' ] ) !!}
                    </div>
                @endif
            </div>
        @endforeach

        {!! Form::label('sort' , 'Sort ' ) !!}
        {!! Form::number('sort', $cat->sort ,['min'=>1,'max'=>900,'class'=>'form-control']); !!}
        {!! Form::submit('Edit !' ,['class'=>'btn btn-success pull-right' ,'style'=>'margin-top:5px' ] ); !!}
    </div>
    {!! Form::close() !!}
@stop


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
