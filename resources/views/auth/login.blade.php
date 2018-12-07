@extends('layouts.default')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-body col-md-12 col-md-offset-4">
    <h2 class="text-center" style="color:red;">Login</h2>
    <form method="POST" action="{{ route('login') }}" >
        @csrf
        <div class="form-group col-md-6 col-lg-12">
            <label for="email" >{{ __('E-Mail Address') }}</label>
            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
            @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('email') }}</strong></span>
            @endif
        </div>
        <div class="form-group col-md-6 col-lg-12">
            <label for="password">{{ __('Password') }}</label>
            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
            @if ($errors->has('password'))
            <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('password') }}</strong></span>
            @endif
        </div>
       <div class="form-group col-md-12 col-lg-12">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
       </div>
    <div class="form-group col-md-12 col-lg-12" >
        <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
        <a class="btn btn-link" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
    </div>
    </form>

</div>
</div>
</div>
        </div>
    </div>

@endsection
