@extends('layouts.app')

@section('content')

<div class="container">
    @if (!is_null($sql_error))
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-danger">
                    <p>{{ $sql_error }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="col-xs-12 panel-heading">
                    <a type="submit" class="btn btn-default col-xs-1 glyphicon glyphicon-arrow-left" href="{{ env('APP_URL', 'http://localhost') . '/admin/'}}"></a>
                    <h5 class="col-xs-2">Query Posts</h5>
                    <div class="col-xs-9 form-group">
                        <form method="POST" action="/query/posts">
                            {{ csrf_field() }}
                            <div class="input-group">
                                <div class="input-group-addon">SELECT * FROM posts WHERE</div>
                                <input name="searchString" class="form-control col-xs-6">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default">Submit Query</button>
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
                                    <h4>likes: {{ $post->likes }}</h4>
                                    <form method="POST" action="{{ '/post/' . $post->id }}">
                                        {{ csrf_field() }}
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button type="submit" class="btn btn-danger">Delete</a>
                                    </form>
                                </div>
                                <div class="postselectbtn col-xs-10">
                                    <h2 class="postselectbtn title">{{ $post->title }}</h2>
                                    <p class="postselectbtn subtitle">posted by: {{ $post->user_id }}   --at--   {{ $post->created_at }}</p>
                                </div>
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
                        <li class="page-item"><a class="page-link" href="{{ env('APP_URL', 'http://localhost') . '/queryposts/' . ($page-1) . '/' . $query . '/' . $searchString }}">Previous</a></li>
                    @endif

                    @if ($page < $total_pages)
                        <li class="page-item"><a class="page-link" href="{{ env('APP_URL', 'http://localhost') . '/queryposts/' . ($page+1) . '/' . $query . '/' . $searchString }}">Next</a></li>
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
