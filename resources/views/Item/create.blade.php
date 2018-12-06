@extends('layouts.default')


@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">

@stop


@section('header')
@stop


@section('content')

    {!! Form::open(['url' => '/item' ,'class' =>'col-md-10 col-md-offset-1' ,'enctype'=>"multipart/form-data" ]) !!}
    <div class="form-group">
        <div class="text-center"
                style="border-left-color:saddlebrown;text-align: center; font-size: large;
                padding: 20px;margin: 20px 0;border: 1px solid saddlebrown;
   border-left-width:33px;border-radius: 100px;">New Item</div>

        <hr>
        {!! Form::label('cat_id' ,'Belong To (Category) : ' ) !!}
        {!! Form::select('cat_id',$catList , '1', ['id'=>'category_list','onchange'=>"getAttr(this)", 'class'=>'form-control' ]) !!}
        {!! Form::label('name' , 'Actual Name' ) !!}
        {!! Form::text('name','' , ['class'=>'form-control' , 'placeholder'=>'type name ...' ] ) !!}
        {{--{!! Form::label('display_name' , 'Displayed Name (what user see)' ) !!}--}}
        {{--{!! Form::text('display_name','' , ['class'=>'form-control' , 'placeholder'=>'type name ...' , 'required'] ) !!}--}}
        {{--extra code lines --}}
        <?php $langs = \App\Language::all(); ?>
        <hr>
        {!! Form::label('title' , 'Title' ) !!}
        @foreach( $langs as $lang )
            @if($lang->align == 0)
                <div class="form-group" dir="ltr" >
                    <h3 style="color: saddlebrown;" > {{$lang->name}} </h3>
                    <input name="title_<?=$lang->id?>" id="title"  class="form-control" placeholder="{!! $lang->name !!}"  >
                </div>
            @else
                <div class="form-group" dir="rtl"  style="text-align: right" >
                    <h3 style="color: saddlebrown;" > {{$lang->name}} </h3>
                    <input name="title_<?=$lang->id?>" id="title"  class="form-control" placeholder="{!! $lang->name !!}"  >
                </div>
            @endif
        @endforeach
        {!! Form::label('pic' , 'Picture' ) !!}
        <div class="form-group" >
            <input type="file" id="pic" name="pic" accept="Image/*" class="form-control" >
        </div>
        <div class="preview form-group">
            <p></p>
        </div>
        {{--extra code lines --}}
        
        <hr>
        {!! Form::label('description' , 'Description (refer to content )' ) !!}

        @foreach( $langs as $lang )
            @if($lang->align == 0)
                <div class="form-group" dir="ltr" >
                    <h3 style="color: saddlebrown;" > {{$lang->name}} </h3>
                    <input name="description_<?=$lang->id?>" id="description"  class="form-control" placeholder="{!! $lang->name !!}"  >
                </div>
            @else
                <div class="form-group" dir="rtl"  style="text-align: right" >
                    <h3 style="color: saddlebrown;" > {{$lang->name}} </h3>
                    <input name="description_<?=$lang->id?>" id="description"  class="form-control" placeholder="{!! $lang->name !!}" >
                </div>
            @endif
        @endforeach


        <hr>
        {!! Form::label('input' , 'The Content ' ) !!}

        @foreach( $langs as $lang )
            @if($lang->align == 0)
                <div class="form-group" dir="ltr" >
                    <h3 style="color: saddlebrown;" > {{$lang->name}} </h3>
                    <textarea name="content_<?=$lang->id?>" id="input"   class="form-control" >{{$lang->name}} </textarea>
                </div>
            @else
                <div class="form-group" dir="rtl"  style="text-align: right" >
                    <h3 style="color: saddlebrown;" > {{$lang->name}} </h3>
                    <textarea name="content_<?=$lang->id?>" id="input"   class="form-control" >{{$lang->name}} </textarea>
                </div>
            @endif
        @endforeach


        <div class="form-group" id="list_of_old_attributes">
        </div>
    </div>


    <hr>
    <h4 style="color: saddlebrown;" class="text-center" ><b>Attributes for Item </b> </h4>
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
    <button type="button"  class="btn btn-primary" id="add-to-list"  onclick="addAttr()" >Add Attribute</button>

    <hr>
    <div id="list_of_new_attributes" class="form-group"  >
    </div>
    <br>

    {!! Form::submit('Add !' ,['class'=>'btn btn-success pull-right' ,'style'=>'margin-top:5px' ] ); !!}
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

    <script src="{{asset('js/tinymce/js/tinymce/tinymce.min.js')}}"></script>
    <?php 
       $abs_path = \Illuminate\Support\Facades\URL::to('/') . '/';
    ?>
    <script>
        console.log("<?= $abs_path ?>");
        var editor_config = {
            path_absolute : "<?= $abs_path ?>" ,
            selector      : "textarea" ,
            plugins       : [
                "autolink lists link image charmap print preview hr anchor pagebreak" ,
                "searchreplace wordcount visualblocks visualchars code fullscreen codesample" ,
                "insertdatetime media nonbreaking save table contextmenu directionality" ,
                "emoticons template paste textcolor colorpicker textpattern"
            ] ,
            toolbar       : "insertfile undo redo | styleselect forecolor backcolor | bold italic codesample textpattern| alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | emoticons" ,
            relative_urls : false ,
            file_browser_callback : function(field_name, url, type, win) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
                if (type == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.open({
                    file : cmsURL,
                    title : 'Filemanager',
                    width : x * 0.8,
                    height : y * 0.8,
                    resizable : "yes",
                    close_previous : "no"
                });
            }
        };
        tinymce.init( editor_config );
    </script>



    <script>
        var allTags = [] ;
        var count =  0 ;
        var langs = JSON.parse('<?=$langs?>');

        function getAttr(X){
            var children  = X.childNodes ;
            var list = document.getElementById('list_of_old_attributes');
            list.innerHTML = "";
            children.forEach( function (child) {
                if(child.selected){
                    if( child.value ){
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type:'POST',
                            data:{
                                _token :"<?php echo csrf_token() ?>" ,
                                category : child.value
                            },
                            dataType : 'json',
                            url:"<?php echo  url('/item/get_attr')?>",
                            success:function (response) {
                                count-=allTags.length;
                                allTags.splice(0,allTags.length);
                                response.attributes.forEach(function (item) {

                                    var newLabel = document.createElement('label');
                                    newLabel.innerHTML =  item.name ;

                                    var newDiv = document.createElement('div');
                                    newDiv.className = 'form-group';

                                    var newHidden = document.createElement('input');
                                    newHidden.type = 'hidden' ;
                                    newHidden.name = 'attribute_old_type_'+ item.name ;
                                    newHidden.value = item.type ;

                                    newDiv.style.margin = '2%' ;
                                    list.appendChild(newDiv);
                                    newDiv.appendChild(newLabel);
                                    newDiv.appendChild(newHidden);

                                    for (var i = 0 ; i < langs.length ; i++ ) {

                                        var newH4 = document.createElement('h4');
                                        newH4.style.color = 'saddlebrown';
                                        newH4.className = 'text-center';
                                        newH4.innerHTML =  langs[i].name ;

                                        var newInpute = Typeswitch(item.type , 'attribute_old_name_'+ item.name + "_*language*_" +  langs[i].id  );

                                        if( langs[i].align == 1 )
                                        {
                                            newInpute.style.textAlign = 'right';
                                        }

                                        allTags[count++] = { name : item.name, type : item.type } ;

                                        if( !(item.type == 'text' ||item.type == 'string') ){
                                            newDiv.appendChild(newInpute);
                                            return 'done';
                                        }else{
                                            newDiv.appendChild(newH4);
                                            newDiv.appendChild(newInpute);
                                        }
                                    }

                                });
                            }
                        });
                    }
                }
            } );
        }

        function addAttr() {
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
                var list = document.getElementById('list_of_new_attributes');

                var newLabel = document.createElement('label');
                newLabel.innerHTML =  newTagName ;

                var newDiv = document.createElement('div');
                newDiv.id = 'attribute_div_'+ newTagName ;
                newDiv.className = 'form-group';

                var newHidden = document.createElement('input');
                newHidden.type = 'hidden';
                newHidden.name = 'attribute_new_type_' + newTagName;
                newHidden.id = 'attribute_new_type_' + newTagName;
                newHidden.value = newTagType;


                list.appendChild(newDiv);
                newDiv.appendChild(newLabel);
                newDiv.appendChild(newHidden);

                for (var i = 0 ; i < langs.length ; i++ ) {
                    var newInpute = Typeswitch(newTagType, 'attribute_new_name_' + newTagName + "_*language*_" + langs[i].id);
                    if( langs[i].align == 1 )
                    {
                        newInpute.style.textAlign = 'right';
                    }
                    var newH4 = document.createElement('h4');
                    newH4.style.color = 'saddlebrown';
                    newH4.className = 'text-center';
                    newH4.innerHTML =  langs[i].name ;

                    var newSpan = document.createElement('span');
                    newSpan.className = 'glyphicon glyphicon-remove pull-right';
                    newSpan.style.color = "red";
                    newSpan.id = newTagName + "|" + newTagType;
                    newSpan.onclick = function () {
                        var Id = this.id;
                        var key = Id.split('|');
                        for (var i = 0; i < allTags.length; i++) {
                            console.log(allTags[i].name);
                            if (allTags[i].name == key[0]) {
                                allTags.splice(i, 1);
                                count--;
                            }
                        }
                        document.getElementById('attribute_div_' + key[0]).remove();
                    };


                    allTags[count++] = {name: newTagName, type: newTagType};
                    document.getElementById('attribute_name').value = "";
                    if( !(newTagType == 'text' ||newTagType == 'string') ){
                        newDiv.appendChild(newSpan);
                        newDiv.appendChild(newInpute);
                        return 'done';
                    }else {
                        newDiv.appendChild(newH4);
                        newDiv.appendChild(newSpan);
                        newDiv.appendChild(newInpute);
                    }
                }
            }
        }

        function Typeswitch(type , submitedName ) {
            var newInpute = document.createElement('input') ;


            switch (type) {
                case 'number':
                    newInpute.type = 'number' ;
                    break;
                case 'date':
                    newInpute.type = 'date' ;
                    break;
                case 'time':
                    newInpute.type = 'time' ;
                    break;
                case 'text':
                    newInpute = document.createElement('textarea') ;
                    break;
                case 'string': newInpute.type = 'text' ;
                    break;
            }

            newInpute.name = submitedName ;
            newInpute.className = 'form-control';
            newInpute.required = 'required';
            return newInpute;
        }
    </script>


    <script>
        var input = document.getElementById('pic');
        var preview = document.querySelector('.preview');

        input.addEventListener('change', updateImageDisplay);
        function updateImageDisplay() {
            while(preview.firstChild) {
            preview.removeChild(preview.firstChild);
        }

        var curFiles = input.files;
        if(curFiles.length === 0) {
            var para = document.createElement('p');
            para.textContent = 'No files currently selected for upload';
            preview.appendChild(para);
        } else {
                var list = document.createElement('div');
                list.className = 'form-group';
                preview.appendChild(list);
                for(var i = 0; i < curFiles.length; i++) {
                    var listItem = document.createElement('div');
                    var para = document.createElement('p');
                    para.textContent = 'File name ' + curFiles[i].name + ', file size ' + returnFileSize(curFiles[i].size) + '.';
                    var image = document.createElement('img');
                    image.src = window.URL.createObjectURL(curFiles[i]);
                    listItem.appendChild(image);
                    listItem.appendChild(para);
                    list.appendChild(listItem);

                    console.log(curFiles[i].size);
                }
            }
        }
        
        function returnFileSize(number) {
            if(number < 1024) {
                return number + 'bytes';
            } else if(number >= 1024 && number < 1048576) {
                return (number/1024).toFixed(1) + 'KB';
            } else if(number >= 1048576) {
                return (number/1048576).toFixed(1) + 'MB';
            }
        }
    </script>

@stop
