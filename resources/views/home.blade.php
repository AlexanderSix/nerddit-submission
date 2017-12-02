@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <form method="POST" action="/search-posts">
                            {{ csrf_field() }}
                            <div class="col-xs-12 input-group marginleft">
                                <input name="searchString" class="form-control" aria-label="Text input with segmented button dropdown" value="" id="searchInput" onkeyup="checkSearch">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default" id="searchSubmit"><i class="glyphicon glyphicon-search"></i> Search</button>
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="/home/1/">Most Recent</a></li>
                                        <li><a href="/home/1/mostLikes">Most Likes</a></li>
                                        <li><a href="/home/1/leastLikes">Least Likes</a></li>
                                    </ul>
                                </div>
                                <div class="col-xs-1">
                                    <a type="button" class="btn btn-default" href="{{ env('APP_URL', 'http://localhost') . '/post/create' }}">
                                        <i class="glyphicon glyphicon-plus"></i> Create Post
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="panel-body post">
                    <ul class="list-group">
                        @foreach ($posts as $post)
                            <li class="list-group-item post col-md-12">
                                <div class="col-xs-2">
                                    <form method="POST" action="{{ '/vote/' . $post->id}}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="vote_type" value="upvote">
                                        <input type="hidden" name="page" value="{{ $page }}">
                                        <input type="hidden" name="querystring" value="{{ $query }}">
                                        <button type="submit" class="btn btn-default glyphicon glyphicon-chevron-up"></button>
                                    </form>
                                    <h4>{{ $post->likes }}</h4>
                                    <form method="POST" action="{{ '/vote/' . $post->id }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="vote_type" value="downvote">
                                        <input type="hidden" name="page" value="{{ $page }}">
                                        <input type="hidden" name="querystring" value="{{ $query }}">
                                        <button type="submit" class="btn btn-default glyphicon glyphicon-chevron-down"></button>
                                    </form>
                                </div>
                                <a type="button" class="postselectbtn col-xs-9" href="{{ env('APP_URL', 'http://localhost') . '/post/' . $post->id}}">
                                    <h2 class="postselectbtn title">{{ $post->title }}</h2>
                                    <p class="postselectbtn subtitle">posted by: {{ $post->username }}   --at--   {{ $post->created_at }}</p>
                                </a>
                            </li>

                        @endforeach
                    </ul>

                   @if (empty($posts))
                       <div class="panel-body post">
                           No Posts Right Now.
                       </div>
                   @endif
               </div>
            </div>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    @if ($page != 1)
                        <li class="page-item"><a class="page-link" href="{{ env('APP_URL', 'http://localhost') . '/' . ($page-1) . '/' . $query . '/' . $searchString }}">Previous</a></li>
                    @endif

                    @if ($page < $total_pages)
                        <li class="page-item"><a class="page-link" href="{{ env('APP_URL', 'http://localhost') . '/' . ($page+1) . '/' . $query . '/' . $searchString }}">Next</a></li>
                    @endif
              </ul>
          </nav>
        </div>
    </div>
</div>

<script>
    var searchInput = document.getElementById("searchInput");

    if (searchInput.value == "") {
        console.log("No match!");
        document.getElementById("searchSubmit").disabled = true;
    }

    var checkSearch = function() {
        if (searchInput.value == "") {
            console.log("No match!");
            document.getElementById("searchSubmit").disabled = true;
        } else {
            document.getElementById("searchSubmit").disabled = false;
        }
    }

    searchInput.addEventListener('keyup', checkSearch);

</script>
@endsection
