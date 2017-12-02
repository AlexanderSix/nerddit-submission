@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1 class="title text-center">Nerddit</h1>
            <div><img class="center-block" src="{{ asset('img/nerdditDude.png') }}" alt="nerddit dude was here"></div>

            <div>
                <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-md-3 control-label">E-Mail</label>

                        <div class="col-md-6">
                            <input id="email" placeholder="Email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="col-md-3 control-label">Password</label>

                        <div class="col-md-6">
                            <input id="password" placeholder="Password" type="password" class="form-control" name="password" required>

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="center-block">
                            <button type="submit" class="btn btn-default center-block">
                                Login
                            </button><br>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
