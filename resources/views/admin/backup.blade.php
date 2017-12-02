@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="col-xs-12 panel-heading">
                    <a type="submit" class="btn btn-default col-xs-1 glyphicon glyphicon-arrow-left" href="{{ env('APP_URL', 'http://localhost') . '/admin/'}}"></a>
                    <h5 class="col-xs-2">Backup Options</h5>
                </div>

                <div class="panel-body post">
                    <a type="submit" class="btn btn-default col-xs-12" href="{{ env('APP_URL', 'http://localhost') . '/backup/users'}}">
                        Backup Users
                    </a>
                    <a type="submit" class="btn btn-default col-xs-12" href="{{ env('APP_URL', 'http://localhost') . '/backup/posts'}}">
                        Backup Posts
                    </a>
                    <a type="submit" class="btn btn-default col-xs-12" href="{{ env('APP_URL', 'http://localhost') . '/backup/comments'}}">
                        Backup Comments
                    </a>
                    <a type="submit" class="btn btn-default col-xs-12" href="{{ env('APP_URL', 'http://localhost') . '/backup/all'}}">
                        Backup All Tables
                    </a>

                    <div class="col-xs-12">
                        <h3>Restore from backup:</h3>
                        <form method="POST" action="/restore-backup" enctype="multipart/form-data">
                            {{  csrf_field() }}
                            <input type="file" name="backupfile" style="margin-bottom: 10px;">
                            <button class="btn btn-primary" type="submit" style="margin-bottom: 10px;">Restore</button>
                        </form>
                    </div>
               </div>
            </div>
        </div>
    </div>
</div>
@endsection
