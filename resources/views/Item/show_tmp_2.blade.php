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
            <div  class="col-md-9">
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
                    <h3> Content </h3>
                    <?php
                    $lang_id = session('lang');
                    $word = \App\Translator::whereLanguageId($lang_id)->whereTranslatorsId($item->id)->whereTranslatorsType('App\Item')->first();
                    if( !$word ) $word->content = "????";
                    ?>
            
                    <?=$word->content?>
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
    </div>
@stop


@section('footer')
    @include('layouts.comments')
@stop

