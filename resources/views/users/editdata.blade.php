@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1 class="panel-heading">Edit User Data</h1>

            <div class="panel-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <form method="POST" action="/edit-user-data">
                                {{ csrf_field() }}
                                <label for="email">Email</label>
                                <input class="form-control" type="text" name="email" value="{{ $user->email }}">
                                <input type="hidden" name="user_data_type" value="email">
                                <button class="btn btn-default" type="submit" name="Submit">Update Email</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <form method="POST" action="/edit-user-data">
                                {{ csrf_field() }}
                                <label for="password">Password</label>
                                <input class="form-control" type="text" name="password" value="" id="passwordInput" onkeyup="checkPassword">
                                <input type="hidden" name="user_data_type" value="password">
                                <button class="btn btn-default" id="passwordSubmit" type="submit" name="Submit">Update Password</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <form method="POST" action="/edit-user-data">
                                {{ csrf_field() }}
                                <label for="first_name">First Name</label>
                                <input class="form-control" type="text" name="first_name" value="{{ $user->first_name }}">
                                <input type="hidden" name="user_data_type" value="first_name">
                                <button class="btn btn-default" type="submit" name="Submit">Update First Name</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12">
                            <form method="POST" action="/edit-user-data">
                                {{ csrf_field() }}
                                <label for="last_name">Last Name</label>
                                <input class="form-control" type="text" name="last_name" value="{{ $user->last_name }}">
                                <input type="hidden" name="user_data_type" value="last_name">
                                <button class="btn btn-default" type="submit" name="Submit">Update Last Name</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var passwordInput = document.getElementById("passwordInput");

    if (passwordInput.value == "") {
        console.log("No match!");
        document.getElementById("passwordSubmit").disabled = true;
    }

    var checkPassword = function() {
        if (!passwordInput.value.match(/^(?=.*[a-z])(?=.*[A-Z]).+$/)) {
            console.log("No match!");
            document.getElementById("passwordSubmit").disabled = true;
        } else {
            console.log("Match!");
            document.getElementById("passwordSubmit").disabled = false;
        }
    }

    passwordInput.addEventListener('keyup', checkPassword);


</script>

@endsection
