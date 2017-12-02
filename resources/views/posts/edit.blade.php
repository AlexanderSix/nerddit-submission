@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="row">
                <h1 class="col-xs-11 post-heading contenttitle">
                    {{ $post->title }}
                </h1>
            </div>

            <div class="panel-body">

                <div class="panel-body">
                    <form method="POST" action="/post/{{ $post->id }}">
                        <input name="_method" type="hidden" value="PUT">
                        {{ csrf_field() }}
                        <label for="title">Title</label>
                        <br>
                        <textarea name="title" class="form-control" rows="2">{{ $post->title }}</textarea>
                        <br>

                        <label for="link">URL *optional*</label>
                        <br>
                        <textarea name="link" id="url" class="form-control" rows="1">{{ $post->link }}</textarea>
                        <br>

                        <label for="content">Content</label>
                        <br>
                        <textarea name="content" class="form-control" rows="5">{{ $post->content }}</textarea>
                        <br>
                        <br>
                        <button type="submit" class="btn btn-default">
                            Update Post
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
