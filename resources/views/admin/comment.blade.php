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
        Admin
    </title>
</head>

<body>
    @include('/nav')
    <div class="contaniner" style="min-height: 600px;">
        <div class="container pull-left" style="width: 200px;margin-left: 10%; height: auto;">
            <div class="list-group">
                <a href="#" style="font-weight: bolder;" class="list-group-item list-group-item-info">
                    Admin Page
                </a>
                <a href="/blog/public/admin/index" class="list-group-item">Blogs</a>
                <a href="/blog/public/admin/comments" class="list-group-item active">Comments</a>
            </div>

        </div>
        <div class="container pull-left" style="width: 1200px; height: auto;">
            <ol class="breadcrumb">
                <li><a href="/blog/public/admin/index">Home</a></li>
                <li class="active">Comment List</li>
            </ol>
            <div class="bs-example" data-example-id="striped-table">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nick Name</th>
                            <th>Comment Content</th>
                            <th>Blog Title</th>
                            <th>Create Time</th>
                            <th>Operation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($comments as $k=>$v): ?>
                        <tr>
                            <th scope="row"><?php echo ++$k; ?></th>
                            <td><?php echo $v->nick_name; ?></td>
                            <td><?php echo $v->content; ?></td>
                            <td><a href="/blog/public/article/<?php echo $v->article_no; ?>"><?php echo $v->title;?></a>
                            </td>
                            <td><?php echo $v->create_time; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm delete_comment_btn"
                                    data-id='{{$v->comment_no}}'>Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                    </tbody>
                </table>
                {{ $comments->links()}}
            </div>
        </div>

    </div>

    @include('/message')
    @include('/footer')
</body>

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
$(function() {
    $(".delete_comment_btn").click(function() {
        confirm("Do you want to delete this comment.");
        var id = $(this).data("id");
        console.log(id);
        $.ajax({
            type: "delete",
            url: "/blog/public/admin/comment",
            async: false,
            data: {
                "id": id
            },
            success: function(data) {
                if (data.code == 1) {
                    show_msg("success", data.msg);
                    disMsgDelay(3000);
                    window.location.reload();
                } else {
                    show_msg("error", data.msg);
                    disMsgDelay(3000);
                }
            }
        });

    });




})
</script>

</html>