<!DOCTYPE html>
<html lang='us'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css"
        integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap-theme.min.css"
        integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js"
        integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous">
    </script>

    <title>
        {{$article->title}}
    </title>
    <style>
    .loading {
        width: 100%;
        height: 100%;
        position: fixed;
        left: 0px;
        top: px;
        z-index: 100;
        background: #fff;
    }

    .loading .pic {
        width: 50px;
        height: 50px;
        /*border:1px solid red;*/

        position: absolute;
        top: 0px;
        bottom: 0px;
        left: 0px;
        right: 0px;
        margin: auto;
    }

    .loading .pic i {
        display: block;
        float: left;
        width: 6px;
        height: 50px;
        background: #399;
        margin: 0 2px;
        -webkit-transform: scaleY(0.4);
        -ms-transform: scaleY(0.4);
        transform: scaleY(0.4);
        -webkit-animation: load 1.2s infinite;
        animation: load 1.2s infinite;
    }

    .loading .pic i:nth-child(2) {
        -webkit-animation-delay: 0.1s;
        animation-delay: 0.1s;
    }

    .loading .pic i:nth-child(3) {
        -webkit-animation-delay: 0.2s;
        animation-delay: 0.2s;
    }

    .loading .pic i:nth-child(4) {
        -webkit-animation-delay: 0.3s;
        animation-delay: 0.3s;
    }

    .loading .pic i:nth-child(5) {
        -webkit-animation-delay: 0.4s;
        animation-delay: 0.4s;
    }

    @-webkit-keyframes load {

        0%,
        40%,
        100% {
            -webkit-transform: scaleY(0.4);
            transform: scaleY(0.4);
        }

        20% {
            -webkit-transform: scaleY(1);
            transform: scaleY(1);
        }
    }

    @keyframes load {

        0%,
        40%,
        100% {
            -webkit-transform: scaleY(0.4);
            transform: scaleY(0.4);
        }

        20% {
            -webkit-transform: scaleY(1);
            transform: scaleY(1);
        }
    }
    </style>
</head>

<body>
    @include('nav')

    <div class="container" style="min-height: 600px;">
        <!-- loading -->
        <div class="loading">
            <div class="pic">
                <i></i>
                <i></i>
                <i></i>
                <i></i>
                <i></i>
            </div>
        </div>
        <div class="container">
            <ol class="breadcrumb">
                <li><a href='/blog/public/index'>Home</a></li>
                <li class='active'>Detail</li>
            </ol>
            <hr>
            <div class="text-center">
                <h1> {{$article->title}}</h1>
            </div>
            <h4>Category:<a href="/blog/public/category/{{ $article->cate_id }}">{{ $article->cate_name }}</a>
            </h4>

            <hr>
            {!! $article->content !!}
            <hr>
            <h4> Last Updated by:<mark><a href="/blog/public/profile/{{ $article->uid }}">{{ $article->nick_name }}</a>
                </mark> In <mark> {{ $article->create_time }}</mark>
            </h4>
            <hr />
            <h3>Comments in this Blog</h3>
            <hr>
            <div class="bs-example" data-example-id="striped-table">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Nick Name</th>
                            <th>Content</th>
                            <th>Create Time</th>
                        </tr>
                    </thead>
                    <tbody id='content'>
                        @foreach ($comments as $k=>$comment)
                        <tr>
                            <td><a href="/blog/public/profile/{{ $comment->uid }}">{{ $comment->nick_name }}</a></td>
                            <td>{{ $comment->content }}</td>
                            <td>{{ $comment->create_time }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(Session::has('uid'))
            <form>
                <input type="hidden" name="article_no" id="article_no" value="{{$article->article_no}}">
                <input type="hidden" id="nickname" value=" {{ Session::get('nickname')}}">

                <div class="form-group">
                    <label for="comment">Write your comment</label>
                    <input type="text" class="form-control" id="user_comment">
                </div>
                <button id="addcomment" type="button" class="btn btn-success">Add Comment</button>
            </form>
            @endif



        </div>


    </div>
    @include('message')
    @include('footer')
</body>

</html>
<script type="text/javascript">
/**
 * popup message box
 */
function show_msg(title, content) {
    $("#msg-title").html(title);
    $("#msg-content").html(content);
    $('#msg-modal').modal('show');
}
/**
 * close message box
 */
function disMsg() {
    $('#msg-modal').modal('hide');

}
/**
 * Delay closing the message prompt box
 */
function disMsgDelay(time) {
    setTimeout(disMsg, time);
}

/**
 * Use a progress bar while loading data
 */
document.onreadystatechange = function() {
    var state = document.readyState;
    if (state == "complete") {
        $(".loading").fadeOut("slow");
    }
}
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$("#addcomment").click(function() {
    var article_no = $("#article_no").val();
    var user_comment = $("#user_comment").val();
    console.log(article_no);
    console.log(user_comment);
    $.ajax({
        type: "post",
        url: "/blog/public/admin/addcomment",
        async: false,
        data: {
            "article_no": article_no,
            "comment": user_comment
        },
        success: function(data) {
            console.log(data);
            if (data.code == 1) {
                show_msg("success", data.msg);
                disMsgDelay(3000);
                $("#user_comment").val('');
                var nickname = $("#nickname").val();
                var commentHtml = "<tr><td>" + nickname + "</td><td>" + user_comment + "</td><td>" +
                    data.create_time + "</td></tr>";
                $("#content").append(commentHtml);
            } else {
                show_msg("error", data.msg);
                disMsgDelay(3000);
            }
        }
    });
});
</script>