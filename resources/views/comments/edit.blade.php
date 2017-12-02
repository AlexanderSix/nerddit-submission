@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="row">
                <h1 class="col-xs-11 post-heading contenttitle">
                    Edit Your Comment
                </h1>
            </div>

            <div class="panel-body">

                <div class="panel-body">
                    <form method="POST" action="/comment/{{ $comment->id }}">
                        <input name="_method" type="hidden" value="PUT">
                        {{ csrf_field() }}
                        <label for="content">Content</label>
                        <br>
                        <textarea name="content" class="form-control" rows="5">{{ $comment->content }}</textarea>
                        <br>
                        <br>
                        <button type="submit" class="btn btn-default">
                            Update Comment
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
