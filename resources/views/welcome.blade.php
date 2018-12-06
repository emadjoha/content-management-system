
@extends('layouts.default')




@section('content')
    <h1 class="text-center" style="color: red" >
        @if(\Illuminate\Support\Facades\Auth::check())
            Welcome {{ \Illuminate\Support\Facades\Auth::user()->name    }}
            <span style="color: blue;" >
                ({{ \App\Language::find(session()->get('lang'))->name  }})
            </span>
            {{--{{phpinfo()}}--}}
        @else
            You are logged out
        @endif
    </h1>
@endsection
