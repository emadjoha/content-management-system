

@extends('layouts.default')


@section('head')
@stop



@section('header')
@stop


@section('content')
    {!! Form::open(['url' => '/main_menu' ,'class' =>'col-md-6 col-md-offset-3'  ]) !!}
    <div class="form-group">
        <div class="text-center"
             style="border-left-color:saddlebrown;text-align: center; font-size: large;
                padding: 20px;margin: 20px 0;border: 1px solid saddlebrown;
   border-left-width:33px;border-radius: 100px;">New Main Menu Item</div>
        <hr>
        {!! Form::label('parent_id' ,'Belong To (Main Menu) : ' ) !!}
        {!! Form::select('parent_id',$parentList ,'1', ['id'=>'main_menu_list','class'=>'form-control']) !!}
        {!! Form::label("name" , 'Actual Name' ) !!}
        {!! Form::text("name" ,'' ,['class'=>'form-control' , 'placeholder'=>'type name ...' ] ) !!}

    <?php $langs = \App\Language::all(); ?>
        <hr>
        {!! Form::label("display_name_1" , 'Displayed Name (what user see)' ) !!}
        @foreach( $langs as $lang )
            <div class="form-group">
                 @if($lang->align == 0)
                        <div class="form-group" dir="ltr" >
                            <h3 style="color: saddlebrown;" > {{$lang->name}} </h3>
                            {!! Form::text("display_name_{$lang->id}",'' , ['class'=>'form-control'   ] ) !!}
                        </div>
                @else
                        <div class="form-group"  dir="rtl" style="text-align: right" >
                            <h3 style="color: saddlebrown;" > {{$lang->name}} </h3>
                            {!! Form::text("display_name_{$lang->id}",'' , ['class'=>'form-control'   ] ) !!}
                        </div>
                @endif
            </div>
        @endforeach
        <hr>
        {!! Form::label('sort' , 'Sort ' ) !!}
        {!! Form::number('sort','',['min'=>1,'max'=>900,'class'=>'form-control']); !!}
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
