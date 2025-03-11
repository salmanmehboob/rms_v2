@extends('layouts.login')

@section('content')

<form action="{{ route('login') }}" class=" dz-form pb-3" method="post">
    @csrf
    <h3 class="form-title m-t0">Login</h3>
    <div class="dz-separator-outer m-b5">
        <div class="dz-separator bg-primary style-liner"></div>
    </div>
    <p>Enter your e-mail address and your password. </p>
    <div class="form-group mb-3">
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
            value="{{ old('email') }}" required autocomplete="email" autofocus>
        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group mb-3">
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
            name="password" required autocomplete="current-password">

        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group text-left mb-5 forget-main">
        <button type="submit" class="btn btn-primary">Sign Me In</button>

    </div>

</form>
<!-- </div>
            </div>
        </div>
    </div>
</div> -->
@endsection