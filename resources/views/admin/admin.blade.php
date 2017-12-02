@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="col-xs-12 panel-heading">
                    <h5 class="col-xs-2">Admin Controls</h5>
                </div>

                <div class="panel-body post">
                    <a type="submit" class="btn btn-default col-xs-12" href="{{ env('APP_URL', 'http://localhost') . '/manageusers/'}}">
                        Manage User
                    </a>
                    <a type="submit" class="btn btn-default col-xs-12" href="{{ env('APP_URL', 'http://localhost') . '/queryposts/'}}">
                        Query Posts
                    </a>
                    <a type="submit" class="btn btn-default col-xs-12" href="{{ env('APP_URL', 'http://localhost') . '/backup/'}}">
                        Backup Options
                    </a>
               </div>
            </div>
        </div>
    </div>
</div>
@endsection
