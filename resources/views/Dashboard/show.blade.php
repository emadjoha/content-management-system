@extends('layouts.default')


@section('head')
    <title> Dashboard </title>
    <script src="{{asset('../resources/js/dashboard.js')}}"></script>
@stop



@section('header')
    <?php
    function getTranslate($id , $type ){
        $lang_id = session('lang');
        $word = \App\Translator::whereLanguageId($lang_id)->whereTranslatorsId($id)->whereTranslatorsType($type)->first();
        if( !$word ){
            $word = new stdClass();
            $word->content = "????";
        }
        return $word;
}; ?>
@stop



@section('content')
    <div class="text-center">
        <h1>
            <button type="button" onclick="left()" class="btn btn-default">
                <span class="glyphicon glyphicon-chevron-left"> </span>
            </button>
                View
            <button type="button" onclick="right()" class="btn btn-default">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </button>
        </h1>
    </div>




    <div id="menu" style="display: block;" >
        <div>
            <table class="table table-striped col-md-10 table-responsive">
                <thead>
                <tr>
                    <th>#</th>
                    <th><?= \App\KeyWords::getKeyWord('name')->content ?></th>
                    <th><?= \App\KeyWords::getKeyWord('belong_to_mm')->content ?></th>
                    <th><?= \App\KeyWords::getKeyWord('create_at')->content ?></th>
                    <th><?= \App\KeyWords::getKeyWord('update_at')->content ?></th>
                </tr>
                </thead>

                <tbody>
                <?php $cont=1; ?>
                @foreach($mm_list as $item )
                    <tr>
                        <td>{{$cont++}}</td>
                        <td> {!! getTranslate($item->id , 'App\MainMenu' )->content !!}</td>
                        <td>
                            @if($item->parent_id)
                                {!! getTranslate($item->parent->id , 'App\MainMenu' )->content !!}
                            @else
                                ---
                        @endif
                        <td>{{$item->created_at}}</td>
                        <td>{{$item->updated_at}}</td>
                        <td>
                            {!! Form::open(['action' => ['MainMenuController@edit', $item->id] ]) !!}
                            <input type="hidden" name="_method" value="GET" >
                            <button type="submit" class="btn btn-success btn-sm">
                                <span class='glyphicon glyphicon-pencil'></span>
                            </button>
                            {!! Form::close() !!}
                        </td>
                        <td>
                            {!! Form::open(['action' => ['MainMenuController@destroy', $item->id] ]) !!}
                            <input type="hidden" name="_method" value="DELETE" >
                            <button type="submit" class="btn btn-danger btn-sm">
                                <span class='glyphicon glyphicon-trash'></span>
                            </button>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- for adding main menu item  -->
        <a style="float: right"  href="{{route('main_menu.create')}}" >
            <button class="btn btn-primary" >
                <?= \App\KeyWords::getKeyWord('create_new_mm')->content ?>
            </button>

        </a>
        <br><br><br>
    </div>



    <div id="category"  style="display: none;" >
        <div>
            <table class="table table-striped col-md-10 table-responsive">
                <thead>
                <tr>
                    <th>#</th>
                    <th><?= \App\KeyWords::getKeyWord('name')->content ?></th>
                    <th><?= \App\KeyWords::getKeyWord('belong_to_mm')->content ?></th>
                    <th><?= \App\KeyWords::getKeyWord('belong_to_cat')->content ?></th>
                    <th><?= \App\KeyWords::getKeyWord('attributes')->content ?></th>
                    <th><?= \App\KeyWords::getKeyWord('create_at')->content ?></th>
                    <th><?= \App\KeyWords::getKeyWord('update_at')->content ?> </th>
                </tr>
                </thead>
                <tbody>
                <?php $count=1 ;?>
                @foreach($cat_list as $item)
                <tr>
                    <td><?=$count++?></td>
                    <td>{!! getTranslate($item->id , 'App\Category' )->content !!}</td>
                    <td>
                        <?php if($item->mainMenu):?>
                            {!! getTranslate($item->mainMenu->id , 'App\MainMenu' )->content !!}
                        <?php else: ?>
                        ---
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($item->parent):?>
                            {!! getTranslate($item->parent->id , 'App\Category' )->content !!}
                        <?php else: ?>
                        ---
                        <?php endif; ?>
                    </td>
                    <?php $cat = \App\Category::find($item->id);?>
                    <td>
                        [
                        @foreach($cat->attributes as $attribute)
                            <span style="color: blue">
                                    {{$attribute->name}}
                            </span>
                        @endforeach
                        ]
                    </td>
                    <td>{{$item->created_at}}</td>
                    <td>{{$item->updated_at}}</td>
                    <td>
                        {!! Form::open(['action' => ['CategoryController@edit', $item->id]]) !!}
                        <input type="hidden" name="_method" value="GET" >
                        <button type="submit"class="btn btn-success btn-sm" >
                            <span class='glyphicon glyphicon-pencil'></span>
                        </button>
                        {!! Form::close() !!}
                    </td>
                    <td>
                        {!! Form::open(['action' => ['CategoryController@destroy', $item->id] , 'method' => 'delete'   ]) !!}
                        <input type="hidden" name="_method" value="DELETE" >
                        <button type="submit" class="btn btn-danger btn-sm">
                            <span class='glyphicon glyphicon-trash'></span>
                        </button>
                        {!! Form::close() !!}
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- for adding category item  -->
        <a style="float: right"  href="{{route('category.create')}}" >
            <button class="btn btn-primary" >
                <?= \App\KeyWords::getKeyWord('create_new_cat')->content ?>
            </button>
        </a>
        <br>
        <br>
    </div>


    <div id="item"  style="display: none;" >
        <div>
            <table class="table table-striped col-md-10 table-responsive">
                <thead>
                <tr>
                    <th>#</th>
                    <th><?= \App\KeyWords::getKeyWord('name')->content ?></th>
                    <th><?= \App\KeyWords::getKeyWord('belong_to_cat')->content ?></th>
                    <th><?= \App\KeyWords::getKeyWord('attributes')->content ?></th>
                    <th><?= \App\KeyWords::getKeyWord('create_at')->content ?></th>
                    <th><?= \App\KeyWords::getKeyWord('update_at')->content ?></th>
                </tr>
                </thead>
                <tbody>
                <?php $count = 1; ?>
                @foreach($item_list as $item )
                    <tr>
                        <td>{{$count++}}</td>
                        <td>{{$item->name}}</td>
                        <td>
                            @if($item->category)
                                {!! getTranslate($item->category->id , 'App\Category' )->content !!}
                            @else
                                ---
                            @endif
                        </td>
                        <td>
                            <?php $cat = \App\Item::find($item->id);?>
                            [
                            @foreach($cat->attributes as $attribute)
                                <span style="color: blue">
                                    {{$attribute->name}}
                                </span>
                            @endforeach
                            ]
                        </td>
                        <td>{{$item->created_at}}</td>
                        <td>{{$item->updated_at}}</td>
                        <td>
                            {!! Form::open(['action' => ['ItemController@edit', $item->id]]) !!}
                            <input type="hidden" name="_method" value="GET" >
                            <button type="submit" class="btn btn-success btn-sm">
                                <span class='glyphicon glyphicon-pencil'></span>
                            </button>
                            {!! Form::close() !!}
                        </td>
                        <td>
                            {!! Form::open(['action' => ['ItemController@destroy', $item->id ]]  ) !!}
                            <input type="hidden" name="_method" value="DELETE" >
                            <button type="submit" class="btn btn-danger btn-sm" >
                                <span class='glyphicon glyphicon-trash'></span>
                            </button>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- for adding Item  -->
        <a style="float: right" href="{{route('item.create')}}">
            <button class=" btn btn-primary" >
                <?= \App\KeyWords::getKeyWord('create_new_item')->content ?>
            </button>
        </a>
        <br><br><br>
    </div>
@stop


@section('footer')
@stop
