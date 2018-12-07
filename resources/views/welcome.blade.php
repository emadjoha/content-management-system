
@extends('layouts.default')




@section('content')
    <div class="alert alert-danger" role="alert">
        @if(\Illuminate\Support\Facades\Auth::check())
            Welcome
        <span class="alert-link">
            {{ \Illuminate\Support\Facades\Auth::user()->name    }}
        </span>
    </div>
            <img src="{{asset('../resources/pics/traffic-sign.png')}}" alt="Lights" style="padding-left:25%">
            {{--{{phpinfo()}}--}}
        @else
            You are logged out
        @endif


    <h1 class="text-center" style="color: red" >

    </h1>
@endsection
