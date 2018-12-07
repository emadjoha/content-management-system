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
    <link rel="stylesheet" href="{!! asset('css/comment.css'); !!}">

    <?php $desc_id = $item->description->id ?>
    <?php
        $lang_id = session('lang');
        $word = \App\Translator::whereLanguageId($lang_id)->whereTranslatorsId($desc_id)->whereTranslatorsType('App\Metadata')->first();
        if( !$word ) {
            $word = new stdClass();
            $word->content = "????";
        }
    ?>
    <title>{!! $word->content !!}| BracelonaMubasher </title>
    <meta name='description' content="{!! $word->content !!}"> 
@stop



@section('header')
@stop



@section('content')






    {{-- Display Item --}}
    <div class="row">
            <div  class="col">
                {{-- Item's Picture --}}

                 <img src="{!! asset('item_pics')."/". $item->pic !!}" alt="{!!$item->pic!!}">
    
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
                    <h6> {!!$word->content!!} </h6>
                    <?php
                    $lang_id = session('lang');
                    $word = \App\Translator::whereLanguageId($lang_id)->whereTranslatorsId($item->id)->whereTranslatorsType('App\Item')->first();
                    if( !$word ) $word->content = "????";
                    ?>
            
                    <?=$word->content?>
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
    </div>
@stop


@section('footer')
    @include('layouts.comments')
@stop

