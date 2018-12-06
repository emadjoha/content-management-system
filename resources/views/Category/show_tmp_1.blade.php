

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
    <link rel="stylesheet" href="{!! asset('css/template_1.css'); !!}">
    {{-- title of page  --}}
    <?php
        $lang_id = session('lang');
        $word = \App\Translator::whereLanguageId($lang_id)->whereTranslatorsId($cat)->whereTranslatorsType('App\Category')->first();
        if( !$word ) {
            $word = new stdClass();
            $word->content = "category belong to BracelonaMubasher site";
        }
    ?>
    <title> {!! $word->content !!} | BracelonaMubasher </title>

    {{-- description of page  --}}
    <meta name="description" content="{!! $word->content !!}"> 
 @stop



@section('header')
@stop



@section('content')

    {{-- Display sub-categories  --}}

           @foreach( $children as $child )
               <div class="col-md-4">
                   <div class="panel panel-primary" >
                       <div class="panel-body" style="background:#1b4b72;color:white"></div>
                           <div class="panel-footer">
                       <a href="{{route('view.index',['path'=>ltrim($child->url,'/')])}}">
                               <?php
                               $lang_id = session('lang');
                               $word = \App\Translator::whereLanguageId($lang_id)->whereTranslatorsId($child->id)->whereTranslatorsType('App\Category')->first();
                               if( !$word ) {
                                   $word = new stdClass();
                                   $word->content = "????";
                               }
                               ?>
                               <p>{{$word->content}}</p>
                       </a>
                           </div>
                   </div>
                   </div>
           @endforeach



    {{-- Display Items --}}
       <div class="container">
        @foreach( $items as $item )
            <div  class="card" style="width: 18rem;">

                    <img class="card-img-top" style="width: 286px;height: 180px;"    src="{!! asset('item_pics')."/".$item->pic !!}"
                          alt="{!!$item->pic!!}">
                    {{-- Item's Title --}}
                <div class="card-body">
                    <?php
                    $lang_id = session('lang');
                    $title_id = $item->title->id ; 
                    $word = \App\Translator::whereLanguageId($lang_id)->whereTranslatorsId($title_id)->whereTranslatorsType('App\TitleItem')->first();
                    if( !$word ) {
                        $word = new stdClass();
                        $word->content = "????";
                    }
                    ?>
                    <h5 class="card-title"> {!!$word->content!!} </h5>

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
                    <p class="card-text"> {!!$word->content!!} </p>
                    <a class="btn btn-primary" href="{{route('view.index',['path'=>ltrim($item->url,'/')])}}">
                More Info</a>
                </div>
            </div>
              @if( count($item->attributes) )
                   @foreach( $item->attributes as $attribute )
                            <?php $attributable = \App\Attributable::whereAttributeId($attribute->id)->whereAttributableId($item->id)->whereAttributableType('App\Item')->first() ?>

                                <span class="badge badge-danger" style="background: #ee9900">
                                    {{$attribute->name}}
                                </span>
                                <span class="badge badge-pill badge-light">
                                    <?php $word = \App\Translator::whereLanguageId($lang_id)->whereTranslatorsId($attributable->id)->whereTranslatorsType('App\Attributable')->first();
                                    if( !$word ) {
                                        $word = new stdClass();
                                        $word->content = "????";
                                    }
                                    ?>
                                    : {{$word->content}}
                                </span>
                        @endforeach
              @endif
                 <hr>
           </div>
        @endforeach
       </div>
@stop



@section('footer')
@stop
