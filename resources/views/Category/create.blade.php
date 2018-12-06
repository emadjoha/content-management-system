

@extends('layouts.default')


@section('head')
@stop



@section('header')
@stop


@section('content')

    {!! Form::open(['url' => '/category' ,'class' =>'col-md-6 col-md-offset-3' , 'onsubmit'=>'onSubmitFun()' ]) !!}
    <div class="form-group">
        <div class="text-center"
             style="border-left-color:saddlebrown;text-align: center; font-size: large;
                padding: 20px;margin: 20px 0;border: 1px solid saddlebrown;
   border-left-width:33px;border-radius: 100px;">New Category</div>
        <hr>
        @if($mm_parent_id)
            {!! Form::label('mm_parent_id' ,'Belong To (Main Menu) : ' ) !!}
            {!! Form::text('mm_parent_id' , $mm_parent_name,['class'=>'form-control', 'disabled' ] ) !!}
            <input type="hidden" name="mm_parent_id" value="{{$mm_parent_id}}"  >
            {!! Form::label('cat_parent_id' ,'Belong To (Category) : ' ) !!}
            {!! Form::text('cat_parent_display' , 'non-selected category ...' ,['class'=>'form-control', 'disabled' ] ) !!}
            <input type="hidden" name="cat_parent_id">
            <input type="hidden" name="same_url">
        @else
            {!! Form::label('mm_parent_id' ,'Belong To (Main Menu) : ' ) !!}
            {!! Form::text('mm_parent_id' , 'non-selected category ...' ,['class'=>'form-control', 'disabled' ] ) !!}
            <input type="hidden" name="mm_parent_id" >
            {!! Form::label('cat_parent_id' ,'Belong To (Category) : ' ) !!}
            {!! Form::select('cat_parent_id',$catList , '1', ['id'=>'category_list','class'=>'form-control']) !!}

        @endif
        {!! Form::label('name' , 'Actual Name' ) !!}
        @if($name)
            {!! Form::text('name' , $name ,['class'=>'form-control', 'readonly'=>"readonly" ] ) !!}
        @else
            {!! Form::text('name','' , ['class'=>'form-control' , 'placeholder'=>'type name ...' ] ) !!}
        @endif
        <?php $langs = \App\Language::all(); ?>
        @foreach( $langs as $lang )
            <div class="form-group">
                <hr>
                <h3 style="color: saddlebrown;" > {{$lang->name}} </h3>
                {!! Form::label("display_name_{$lang->id}" , 'Displayed Name (what user see)' ) !!}
                {!! Form::text("display_name_{$lang->id}",'' , ['class'=>'form-control' , 'placeholder'=>'type name ...' ] ) !!}
            </div>
        @endforeach

        {!! Form::label('sort' , 'Sort ' ) !!}
        {!! Form::number('sort','',['min'=>1,'max'=>900,'class'=>'form-control']); !!}
        {{--add attributes to this category --}}
        <hr>
        <h4 style="color: saddlebrown;" class="text-center" ><b>Attributes for Category </b> </h4>
        <input type="hidden" name="attributes"  id="attributes" >
        {!! Form::label('attribute_name' , '' ) !!}
        <input type="text"  class="form-control" id="attribute_name" autocomplete="off" >
        <br>
        {!! Form::label('attribute_type' , 'Attribute type ' ) !!}
        <select id="attribute_type" class="form-control"   >
            <option value="number" id="number" > Number </option>
            <option value="date" id="date" > Date </option>
            <option value="time" id="time" > Time </option>
            <option value="string" id="string" selected > String </option>
            <option value="text" id="text" > Text </option>
        </select>
        <br>
        <ul   id="list_of_new_attributes" style="display: inline-flex;padding-right:2%" class="list-group text-center" >
        </ul>
        <div class="row">
            <div class="col-md-6">
                <button type="button"  class="btn btn-primary" id="add-to-list"  onclick="clickFun()" >Add Attribute</button>
            </div>
        </div>
        <br>
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



    <script>
        var allTags = [] ;
        var count =  0 ;
        function clickFun(){

            var newTagName = document.getElementById( 'attribute_name' ).value ;
            if( newTagName ) {

                for (var i=0 ; i < allTags.length; i++ ){
                    if(allTags[i].name == newTagName)
                    {
                        window.alert("attribute's name ( "+ newTagName + " ) is repeated " );
                        document.getElementById( 'attribute_name' ).value = "";
                        return false;
                    }
                }

                var newTagType = 'string';

                if( document.getElementById('number').selected ){
                    newTagType = 'number';
                }else if( document.getElementById('date').selected ){
                    newTagType = 'date'
                }else if( document.getElementById('time').selected ){
                    newTagType = 'time'
                }else if( document.getElementById('text').selected ){
                    newTagType = 'text';
                }
                var remove =  "  <span class='glyphicon glyphicon-remove' onclick='removeAtt(this)' ></sapn>";

                var list = document.getElementById('list_of_new_attributes');
                var newLI = document.createElement('li');
                newLI.innerHTML = newTagName + '( ' + newTagType + " )" + remove  ;
                newLI.className = 'list-group-item list-group-item-success';
                newLI.id = newTagName;
                list.appendChild(newLI);

                allTags[count++] = { name: newTagName , type : newTagType  };
                document.getElementById( 'attribute_name' ).value = "";
            }
        }



        function  removeAtt(X) {
            var key = X.parentElement.id ;
            for( var i = 0; i < allTags.length; i++){
                console.log(allTags[i].name);
                if ( allTags[i].name == key ) {
                    allTags.splice(i, 1);
                    count--;
                }
            }
            X.parentElement.remove();
        }
        function onSubmitFun () {
            document.getElementById('attributes').value = JSON.stringify(allTags)  ;
        }
    </script>

@stop
