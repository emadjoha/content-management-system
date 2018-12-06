

@extends('layouts.default')


@section('head')
@stop



@section('header')
@stop


@section('content')

    <div id="keywords_menu">
        <div>
            <table class="table table-striped col-md-10 table-responsive">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Translates</th>
                    <th>Created at </th>
                    <th>Updated at </th>
                </tr>
                </thead>

                <tbody>
                <?php $cont=1; ?>
                @foreach($keywords as $keyword )
                    <tr>
                        <td>{{$cont++}}</td>
                        <td>{{$keyword->name}}</td>
                        <?php $trans = \App\Translator::whereTranslatorsId($keyword->id)->whereTranslatorsType('App\KeyWords')->get();?>
                        <td>
                            [
                            @foreach($trans as $tran)
                                <span style="color: deepskyblue;margin: 7px"  >{{$tran->content}}</span>
                            @endforeach
                            ]
                        </td>
                        <td>{{$keyword->created_at}}</td>
                        <td>{{$keyword->updated_at}}</td>
                        <td>
                            @if(\App\Capability::hasCapability('edit' , 'keyword' ))
                                {!! Form::open(['action' => ['KeywordController@edit', $keyword->id] ]) !!}
                                <input type="hidden" name="_method" value="GET" >
                                <button type="submit" >
                                    <span class='glyphicon glyphicon-cog'></span>
                                </button>
                                {!! Form::close() !!}
                            @endif
                        </td>
                        <td>
                            {{--@if(\App\Capability::hasCapability('delete' , 'keyword' ))--}}
                                {{--{!! Form::open(['action' => ['KeywordController@destroy', $keyword->id] ]) !!}--}}
                                {{--<input type="hidden" name="_method" value="DELETE" >--}}
                                {{--<button type="submit" >--}}
                                    {{--<span class='glyphicon glyphicon-trash'></span>--}}
                                {{--</button>--}}
                                {{--{!! Form::close() !!}--}}
                            {{--@endif--}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        @if(\App\Capability::hasCapability('create' , 'keyword' ))
            <a href="{{route('keyword.create')}}" >
                <button class="btn btn-primary" >
                    Create a new Keyword
                </button>
            </a>
        @endif
        <br><br><br>
    </div>
@stop



@section('footer')
@stop
