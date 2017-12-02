@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="row">
                <div class="col-xs-1">
                    <form method="POST" action="{{ '/vote/' . $post->id }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="vote_type" value="upvote">
                        <input type="hidden" name="return_destination" value="showpost">
                        <button type="submit" class="glyphicon glyphicon-chevron-up"></button>
                    </form>
                    <h4>{{ $post->likes }}</h4>
                    <form method="POST" action="{{ '/vote/' . $post->id }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="vote_type" value="downvote">
                        <input type="hidden" name="return_destination" value="showpost">
                        <button type="submit" class="glyphicon glyphicon-chevron-down"></button>
                    </form>
                </div>
                <h1 class="col-xs-11 post-heading contenttitle">
                    {{ $post->title }}
                </h1>
            </div>

            <div class="panel-body">
                <p>posted by: {{ $post->username }} --at-- {{ $post->created_at }}</p>
                <p class="contentBox">{{ $post->content }}</p>
                @if(Auth::user()->id == $post->user_id)
                    <a class="glyphicon glyphicon-edit" href="{{ env('APP_URL', 'http://localhost') . '/post/' . $post->id . '/edit/'}}">Edit</a>
                @endif
                @if($post->link != NULL)
                    <p>Link: <a href="{{ $post->link }}">{{ $post->link }}</a></p>
                @endif

                <br><br><p style="border-bottom: 1px solid #BABABA;"></p><br>


                <form method="POST" action="/comment">
                    {{ csrf_field() }}
                    <label for="content">Comment</label>
                    <br>
                    <textarea name="content" class="form-control" rows="2" id="commentInput" onkeyup="checkComment"></textarea>
                    <br>
                    <input name="post_id" type="hidden" value="{{ $post->id }}">
                    <button id="commentSubmit" type="submit" class="btn btn-default">
                        Submit Comment
                    </button>

                </form>
                <br><br>
                <div class="panel-body post">
                    <ul class="list-group">
                        @foreach ($comments as $comment)
                                <div class="col-xs-12" value="{{ env('APP_URL', 'http://localhost') . '/comment/' . $comment->id}}">
                                    <p class="contentBox">
                                        submitted by: {{ $comment->username }} --at-- {{ $comment->created_at }}
                                        <br>
                                        {{ $comment->content }}
                                    </p>
                                    @if(Auth::user()->id == $comment->user_id)
                                        <a class="glyphicon glyphicon-edit" href="{{ env('APP_URL', 'http://localhost') . '/comment/' . $comment->id . '/edit/'}}">Edit</a>
                                    @endif
                                </div>
                        @endforeach

                    </ul>

                   @if (empty($comments))
                       <div class="panel-body post">
                           No Comments Right Now.
                       </div>
                   @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var commentInput = document.getElementById("commentInput");

    if (commentInput.value == "") {
        console.log("No match!");
        document.getElementById("commentSubmit").disabled = true;
    }

    var checkComment = function() {
        if (commentInput.value == "") {
            console.log("No match!");
            document.getElementById("commentSubmit").disabled = true;
        } else {
            document.getElementById("commentSubmit").disabled = false;
        }
    }

    commentInput.addEventListener('keyup', checkComment);


</script>

@endsection
