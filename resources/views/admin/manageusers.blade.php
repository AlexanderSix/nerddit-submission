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
                    <div class="row">
                        <a type="submit" class="btn btn-default col-xs-1 glyphicon glyphicon-arrow-left" href="{{ env('APP_URL', 'http://localhost') . '/admin/'}}"></a>
                        <h5 class="col-xs-2">Manage Users</h5>
                        <div class="col-xs-9 form-group">
                            <form method="POST" action="/query/users">
                                {{ csrf_field() }}
                                <div class="input-group">
                                    <div class="input-group-addon">SELECT * FROM users WHERE</div>
                                    <input name="searchString" class="form-control col-xs-6">
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-default">Submit Query</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="panel-body post">
                    <ul class="list-group">
                        @foreach ($users as $user)
                            <li class="list-group-item post col-xs-12">
                                <div class="postselectbtn col-xs-10">
                                    <h2 class="postselectbtn title">{{ $user->first_name }} {{ $user->last_name }}</h2>
                                    <p class="col-xs-11 postselectbtn subtitle">
                                        username: {{ $user->username }},
                                        user id: {{ $user->id }},
                                        created at: {{ $user->created_at }},
                                        updated at: {{ $user->updated_at }}
                                    </p>
                                    <a class="btn btn-default pull-right" href="{{ env('APP_URL', 'http://localhost') . '/adminuseredit/' . $user->id}}"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                   @if (empty($users))
                       <div class="panel-body post">
                           No Users Right Now.
                       </div>
                   @endif
               </div>
            </div>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    @if ($page != 1)
                        <li class="page-item"><a class="page-link" href="{{ env('APP_URL', 'http://localhost') . '/manageusers/' . ($page-1) . '/' . $query . '/' . $searchString }}">Previous</a></li>
                    @endif

                    @if ($page < $total_pages)
                        <li class="page-item"><a class="page-link" href="{{ env('APP_URL', 'http://localhost') . '/manageusers/' . ($page+1) . '/' . $query . '/' . $searchString }}">Next</a></li>
                    @endif
              </ul>
          </nav>
        </div>
    </div>
</div>
@endsection
