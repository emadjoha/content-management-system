

@extends('layouts.default')


@section('head')
@stop



@section('header')
@stop


@section('content')

    <div id="languages_menu">
        <div>
            <table class="table table-striped col-md-10 table-responsive">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Text Align</th>
                    <th>Created at </th>
                    <th>Updated at </th>
                </tr>
                </thead>

                <tbody>
                <?php $cont=1; ?>
                @foreach($languages as $language )
                    <tr>
                        <td>{{$cont++}}</td>
                        <td>{{$language->name}}</td>
                        @if($language->align == 0)
                            <td>Right To Left</td>
                        @else
                            <td>Left To Right</td>
                        @endif

                        <td>{{$language->created_at}}</td>
                        <td>{{$language->updated_at}}</td>
                        @if( $language->name !== 'english' )
                            <td>
                                @if(\App\Capability::hasCapability('edit' , 'lang' ))
                                    {!! Form::open(['action' => ['LanguageController@edit', $language->id] ]) !!}
                                    <input type="hidden" name="_method" value="GET" >
                                    <button type="submit" >
                                        <span class='glyphicon glyphicon-cog'></span>
                                    </button>
                                    {!! Form::close() !!}
                                @endif
                            </td>
                            <td>
                                @if(\App\Capability::hasCapability('delete' , 'lang' ))
                                    {!! Form::open(['action' => ['LanguageController@destroy', $language->id] ]) !!}
                                    <input type="hidden" name="_method" value="DELETE" >
                                    <button type="submit" >
                                        <span class='glyphicon glyphicon-trash'></span>
                                    </button>
                                    {!! Form::close() !!}
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        @if(\App\Capability::hasCapability('create' , 'lang' ))
            <a href="{{route('lang.create')}}" >
                <button class="btn btn-primary" >
                    Create a new Language
                </button>
            </a>
        @endif
        <br><br><br>
    </div>
@stop



@section('footer')
@stop
