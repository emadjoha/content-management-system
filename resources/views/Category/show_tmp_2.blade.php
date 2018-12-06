

@extends('layouts.default')


@section('head')

    <style>
        @font-face {
        font-family: ostrichSans_black;
        src: url("<?=asset('fonts/ostrich-sans/OstrichSans-Black.otf')?>");
        }
        @font-face {
        font-family: ostrichSans_light;
        src: url("<?=asset('fonts/ostrich-sans/OstrichSans-Light.otf')?>");
        }
        @font-face {
        font-family: ostrichSans_heavy;
        src: url("<?=asset('fonts/ostrich-sans/OstrichSans-Heavy.otf')?>");
        }
    </style>
    <link rel="stylesheet" href="{!! asset('css/template_2.css'); !!}">

   {{-- title of page  --}}
   <?php
    $lang_id = session('lang');
    $word = \App\Translator::whereLanguageId($lang_id)->whereTranslatorsId($cat)->whereTranslatorsType('App\Category')->first();
    if( !$word ) {
        $word = new stdClass();
        $word->content = "category belong to BracelonaMubasher site";
    }
    ?>
   <title>{!! $word->content !!}| BracelonaMubasher </title>

   {{-- description of page  --}}
   <meta name="description" content="{!! $word->content !!}"> 
@stop



@section('header')
@stop



@section('content')

        {{--  Display sub-categories  --}}
       <div style="display: inline;" >

           @foreach( $children as $child )
               <div class="row col-md-2" style="margin: 20px">
                   <div class="thumbnail">

                       <a      style='text-decoration:none;'
                               href="{{route('view.index',['path'=>ltrim($child->url,'/')])}}"
                               >
                           <img src="{{asset('../resources/pics/cat.png')}}" alt="Lights" style="">
                           <div class="caption" >
                               <?php
                               $lang_id = session('lang');
                               $word = \App\Translator::whereLanguageId($lang_id)->whereTranslatorsId($child->id)->whereTranslatorsType('App\Category')->first();
                               if( !$word ) {
                                   $word = new stdClass();
                                   $word->content = "????";
                               }
                               ?>
                               <p>{{$word->content}}</p>
                           </div>
                       </a>
                   </div>
               </div>
           @endforeach
       </div>



        {{-- Display Item --}}
       <div class="row">

        @foreach( $items as $item )
            <div  class="col-md-9">
                <a style='text-decoration:none;'
                href="{{route('view.index',['path'=>ltrim($item->url,'/')])}}"
                >
                        {{-- Item's Picture --}}
                    <h3> Picture </h3>
                    <img src="{!! asset('item_pics')."/". $item->pic !!}" alt="{!!$item->pic!!}"  style="width:100px;height:100px" >

                    {{-- Item's Title --}}
                    <?php
                    $lang_id = session('lang');
                    $title_id = $item->title->id ; 
                    $word = \App\Translator::whereLanguageId($lang_id)->whereTranslatorsId($title_id)->whereTranslatorsType('App\TitleItem')->first();
                    if( !$word ) {
                        $word = new stdClass();
                        $word->content = "????";
                    }
                    ?>
                    <h3> Title </h3>
                    <h4> {!!$word->content!!} </h4>

                    {{-- Item's Description --}}
                    <?php
                    $lang_id = session('lang');
                    $desc_id = $item->description->id ; 
                    $word = \App\Translator::whereLanguageId($lang_id)->whereTranslatorsId($desc_id)->whereTranslatorsType('App\Metadata')->first();
                    if( !$word ) {
                        $word = new stdClass();
                        $word->content = "????";
                    }
                    ?>
                    <h3> Description </h3>
                    <h4> {!!$word->content!!} </h4>
                </a>
            </div>

           <div class="col-md-3 flex justify-content-center" style="margin-top: 20px;border-radius:20px; min-height: 400px;padding-top: 20px">
              @if( count($item->attributes) )
                <div class="panel panel-default" style="background-color: #666666" >
                    <div class="panel-body">
                        <h3 class="text-center attr__header"> Attribute(s)</h3>
                    </div>
                    <div class="panel-footer" style="background-color: #4E6162" >

                        @foreach( $item->attributes as $attribute )
                            <?php $attributable = \App\Attributable::whereAttributeId($attribute->id)->whereAttributableId($item->id)->whereAttributableType('App\Item')->first() ?>
                            <div class="text-center attr__panel" >
                                <h4 style="color: #FFF38B; text-align: right" dir="ltr" >{{$attribute->name}}

                                </h4>
                                <h4 style="color: #fff;" >
                                    <?php $word = \App\Translator::whereLanguageId($lang_id)->whereTranslatorsId($attributable->id)->whereTranslatorsType('App\Attributable')->first();
                                    if( !$word ) {
                                        $word = new stdClass();
                                        $word->content = "????";
                                    }
                                    ?>
                                    : {{$word->content}}
                                </h4>
                            </div>
                            <br>
                        @endforeach
                    </div>
                </div>
              @endif

                 <hr>

           </div>


        @endforeach
       </div>

@stop



@section('footer')
@stop
