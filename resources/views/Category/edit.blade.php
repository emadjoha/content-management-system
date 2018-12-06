

@extends('layouts.default')


@section('head')
@stop



@section('header')
@stop


@section('content')
    {!! Form::open(['action' => ['CategoryController@update', $cat->id] ,'method'=>'put' ,'class' =>'col-md-6 col-md-offset-3' , 'onsubmit'=>'onSubmitFun()']) !!}
    <div class="form-group">

        <div class="text-center"
             style="border-left-color:saddlebrown;text-align: center; font-size: large;
                padding: 20px;margin: 20px 0;border: 1px solid saddlebrown;
   border-left-width:33px;border-radius: 100px;">Edit</div>
        <hr>
        {!! Form::label('mm_parent_id' ,'Belong To (Main Menu) : ' ) !!}
        @if( $cat->mainMenu )
            {!! Form::select('mm_parent_id',$mmList ,$cat->mainMenu->id, ['id'=>'main_menu_list','class'=>'form-control']) !!}
        @else
            {!! Form::select('mm_parent_id',$mmList ,'1', ['id'=>'main_menu_list','class'=>'form-control']) !!}
        @endif

        {!! Form::label('cat_parent_id' ,'Belong To (Category) : ' ) !!}
        @if( $cat->parent )
            {!! Form::select('cat_parent_id',$catList,$cat->parent->id, ['id'=>'categories_list','class'=>'form-control']) !!}
        @else
            {!! Form::select('cat_parent_id',$catList ,'1', ['id'=>'categories_list','class'=>'form-control']) !!}
        @endif
        {!! Form::label('name' , 'Actual Name' ) !!}
        {!! Form::text('name' , $cat->name ,['class'=>'form-control' , 'placeholder'=>'type name ...' ] ) !!}

        <?php $langs = \App\Language::all(); ?>
        @foreach( $langs as $lang )
            <div class="form-group">
                <hr>
                <h3 style="color: saddlebrown;" > {{$lang->name}} </h3>
                <?php $word = \App\Translator::whereLanguageId($lang->id)->whereTranslatorsId($cat->id)->whereTranslatorsType('App\Category')->first(); 
                    if( !$word ){
                        $word = new \stdClass();
                        $word->content = "????";
                    } ?>
                {!! Form::label("display_name_{$lang->id}" , 'Displayed Name (what user see)' ) !!}
                {!! Form::text("display_name_{$lang->id}", $word->content , ['class'=>'form-control' , 'placeholder'=>'type name ...' ] ) !!}
            </div>
        @endforeach
        <hr>
        <h4 style="color: saddlebrown;" class="text-center" ><b>Attributes for Category </b> </h4>

        <input type="hidden" name="updated_attributes"  id="updated_attributes" >

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
        <div class="row">
            <div class="col-md-6">
                <button type="button"  class="btn btn-primary" id="add-to-list"  onclick="clickFun()" >Add Attribute</button>
            </div>
        </div>
        <ul   id="list_of_old_attributes" style="display: inline-flex;padding-right:2%" class="list-group text-center" >
            @foreach($cat->attributes as $attribute )
                <?php $pivot = \App\Attributable::whereAttributeId($attribute->id)->whereAttributableId($cat->id)->whereAttributableType('App\Category')->first()?>
                @if($pivot->own)
                    <li class="list-group-item list-group-item-primary" id="{!!$attribute->name!!}_{!!$attribute->type!!}" >
                        {{$attribute->name}} ( {{$attribute->type}} )
                        <span class="glyphicon glyphicon-remove" onclick="removeAtt(this,false)" ></span>
                    </li>
                @endif
            @endforeach
        </ul>
        <br>
        <url  id="list_of_new_attributes" style="display: inline-flex;padding-right:2%" class="list-group text-center">
        </url>
        <br>

        <input type="hidden" name="attributes"  id="attributes" >
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

    <script>
        var allTags = [] ;
        var count =  0 ;
        var oldAttr = document.getElementById('list_of_old_attributes').childNodes;
        oldAttr.forEach(function (item) {
            if(item.id != undefined) {
                var keyValArr = item.id.split('_');
                allTags[count++] = {name : keyValArr[0] , type : keyValArr[1]};
            }
        });


        allTags.forEach(function (item) {
            console.log( item.name + " -- " + item.type );
        })

        function clickFun(){

            var newTagName = document.getElementById( 'attribute_name' ).value ;
            if( newTagName ) {

                for (var i=0 ; i < allTags.length; i++ ){
                    if(allTags[i].name == newTagName) {
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


        function  removeAtt(X,New=true) {
            if(!New){
                var key = X.parentElement.id.split('_')[0] ;
            }else {
                var key = X.parentElement.id ;
            }
            for( var i = 0; i < allTags.length; i++){
                if ( allTags[i].name == key ) {
                    allTags.splice(i, 1);
                    count--;
                }
            }
            X.parentElement.remove();
        }

        function onSubmitFun () {
            document.getElementById('updated_attributes').value = JSON.stringify(allTags)  ;
        }
    </script>
@stop
