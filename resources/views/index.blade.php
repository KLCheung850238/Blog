<!DOCTYPE html>
<html lang='us'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css"
        integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap-theme.min.css"
        integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js"
        integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous">
    </script>

    <title>
        Index
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
                <li class='active'>Home</li>
            </ol>
            @foreach ($blogs as $blog)
            <div class="media">
                <div class="media-body">
                    <h3 class="media-heading"><a
                            href="/blog/public/article/{{ $blog->article_no }}">{{ $blog->title }}</a></h3>
                    <h4><b>Create Time:</b>{{ $blog->create_time }}</h4>
                    <h5><b>Click:</b><span style="color: green">{{ $blog->click }}</span></h5>
                </div>
            </div>
            @endforeach


        </div>
        {{ $blogs->links() }}

    </div>
    @include('message')
    @include('footer')
</body>

</html>
<script type="text/javascript">
/**
 * Use a progress bar while loading data
 */
document.onreadystatechange = function() {
    var state = document.readyState;
    if (state == "complete") {
        $(".loading").fadeOut("slow");
    }
}
</script>