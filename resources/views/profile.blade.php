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
        {{ $user->nick_name}}
    </title>
</head>

<body>
    @include('nav')

    <div class="container" style="min-height: 600px;">
        <div class="container">
            <ol class="breadcrumb">
                <li><a href='/blog/public/index'>Home</a></li>
                <li class="active">User's Blogs and Comments</li>
            </ol>
            <hr>
            <h3 class='text-center'>The Blogs of <b>{{ $user->nick_name}}</b></h3>
            <div class="bs-example" data-example-id="striped-table">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Create Time</th>
                            <th>Last Update Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($articles as $k=>$v): ?>
                        <tr>
                            <th scope="row"><?php echo ++$k; ?></th>
                            <td><a href="/blog/public/article/{{$v->article_no}}">{{$v->title}}</td>
                            <td><?php echo $v->create_time; ?></td>
                            <td><?php echo $v->last_update_time; ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                    </tbody>
                </table>
                {{ $articles->links()}}
            <hr>
            <h3 class='text-center'>The comments of <b>{{ $user->nick_name}}</b></h3>
            <div class="bs-example" data-example-id="striped-table">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Comment Content</th>
                            <th>Blog Title</th>
                            <th>Create Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($comments as $k=>$v): ?>
                        <tr>
                            <th scope="row"><?php echo ++$k; ?></th>
                            <td><?php echo $v->content; ?></td>
                            <td><a href="/blog/public/article/<?php echo $v->article_no; ?>"><?php echo $v->title;?></a>
                            </td>
                            <td><?php echo $v->create_time; ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                    </tbody>
                </table>
                {{ $comments->links()}}
            </div>
            

        </div>


    </div>
    @include('message')
    @include('footer')
</body>

</html>
<script type="text/javascript">

</script>