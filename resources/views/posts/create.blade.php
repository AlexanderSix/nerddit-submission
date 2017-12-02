@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1 class="panel-heading">Create Your Post!</h1>

            <div class="panel-body">
                <form method="POST" action="/post">
                    {{ csrf_field() }}
                    <label for="title">Title</label>
                    <br>
                    <textarea name="title" class="form-control" rows="2" value="" id="titleInput" onkeyup="checkTitle"></textarea>
                    <br>

                    <label for="link">URL *optional*</label>
                    <br>
                    <textarea name="link" id="url" class="form-control" rows="1"></textarea>
                    <br>

                    <label for="content">Content</label>
                    <br>
                    <textarea name="content" class="form-control" rows="5" value="" id="contentInput" onkeyup="checkContent"></textarea>
                    <br>
                    <br>
                    <button type="submit" class="btn btn-default" id="postSubmit">
                        Submit Post
                    </button>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var titleInput = document.getElementById("titleInput");
    var contentInput = document.getElementById("contentInput");

    if ((titleInput.value == "" || contentInput.value == "")) {
        console.log("No match!");
        document.getElementById("postSubmit").disabled = true;
    }

    var checkSubmit = function() {
        if ((titleInput.value == "" || contentInput.value == "")) {
            console.log("No match!");
            document.getElementById("postSubmit").disabled = true;
        } else {
            console.log("match!");
            document.getElementById("postSubmit").disabled = false;
        }
    }

    titleInput.addEventListener('keyup', checkSubmit);
    contentInput.addEventListener('keyup', checkSubmit);

</script>
@endsection
