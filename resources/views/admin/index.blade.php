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
                <a href="/blog/public/admin/index"  class="list-group-item active">Blogs</a>
                <a href="/blog/public/admin/comments" class="list-group-item">Comments</a>
            </div>

        </div>
        <div class="container pull-left" style="width: 1200px; height: auto;">
            <ol class="breadcrumb">
                <li><a href="/blog/public/admin/index">Home</a></li>
                <li class="active">Blog List</li>
            </ol>
            <a href="#" id="write_blog_btn" class="btn btn-primary btn-sm">Add New Blog</a>
            <hr>
            <div class="bs-example" data-example-id="striped-table">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Category Name</th>
                            <th>Click</th>
                            <th>Verify</th>
                            <th>Create Time</th>
                            <th>Last Update Time</th>
                            <th>Operation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($blog as $k=>$v): ?>
                        <tr>
                            <th scope="row"><?php echo ++$k; ?></th>
                            <td><a href="/blog/public/article/<?php echo $v->article_no; ?>"><?php echo $v->title;?></a>
                            </td>
                            <td><?php echo $v->cate_name; ?></td>
                            <td><?php echo $v->click; ?></td>
                            <td><?php if($v->verify>0){echo "Confirm";}else{echo "Wait";} ?></td>
                            <td><?php echo $v->create_time; ?></td>
                            <td><?php echo $v->last_update_time; ?></td>
                            <td>
                            @if(Session::get('type') == 'admin' && $v->verify == 0)
                            <button class="btn btn-success btn-sm verify_blog_btn"
                                    data-id='{{$v->article_no}}'>Verify</button>
                            @endif
                                <button class="btn btn-primary btn-sm edit_blog_btn"
                                    data-id='{{$v->article_no}}'>Edit</button>
                                <button class="btn btn-warning btn-sm delete_blog_btn"
                                    data-id='{{$v->article_no}}'>Delete</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                    </tbody>
                </table>
                {{ $blog->links()}}
            </div>
        </div>

    </div>

    <div class="modal fade bs-example-modal-lg" id="write_blog" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Write Blog</h4>
                </div>
                <div class="modal-body">
                    <form action="/blog/public/admin/add_blog" method="POST" class="form-horizontal"
                        style="margin-top: 40px;">
                        <div class="form-group">
                            <label for="nickname" class="col-sm-3 control-label"></i>Blog Title</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" required="required" id="title"
                                    placeholder="Title">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Category</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="cate_id" required="required">
                                    <?php foreach($cates as $k=>$v): ?>
                                    <option value="<?php echo $v['cate_id']; ?>"><?php echo $v['cate_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">Content</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="6" placeholder="Blog Content"
                                    id="content"></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="nickname" class="col-sm-3 control-label"></i>Blog Photo</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control"  id="photo">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-2">
                                <button type="button" class="btn btn-success" style="height: 30px;"
                                    id="add_blog_btn">Submit</button>
                            </div>
                            <div class="col-sm-offset-4 col-sm-2">
                                <button type="button" class="btn btn-primary" data-dismiss="modal"
                                    style="height: 30px;">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bs-example-modal-lg" id="edit_blog" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Edit Blog</h4>
                </div>
                <div class="modal-body">
                    <form action="/blog/public/admin/edit_blog" method="POST" id="edit_form" class="form-horizontal"
                        style="margin-top: 40px;">
                        <input type="hidden" class="form-control" id="edit_id">
                        <div class="form-group">
                            <label for="nickname" class="col-sm-3 control-label"></i>Blog Title</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" required="required" name="title" id="edit_title"
                                    placeholder="Title">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Category</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="cate_id" required="required" id="edit_cate">
                                    <?php foreach($cates as $k=>$v): ?>
                                    <option value="<?php echo $v['cate_id']; ?>"><?php echo $v['cate_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">Content</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" rows="6" placeholder="Blog Content"
                                    id="edit_content"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-2">
                                <button type="button" class="btn btn-success" id="edit_submit_btn"
                                    style="height: 30px;">Submit</button>
                            </div>
                            <div class="col-sm-offset-4 col-sm-2">
                                <button type="button" class="btn btn-primary" data-dismiss="modal"
                                    style="height: 30px;">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
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
    $("#write_blog_btn").click(function() {
        $("#write_blog").modal('show');
    });

    $("#add_blog_btn").click(function() {
        $.ajax({
            type: "post",
            url: "/blog/public/admin/add_blog",
            async: false,
            data: {
                "title": $("#title").val(),
                "photo": $("#photo").val(),
                "cate_id": $("#cate_id").val(),
                "content": $("#content").val()
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


    $(".delete_blog_btn").click(function() {
        confirm("Do you want to delete this blog.");
        var id = $(this).data("id");
        console.log(id);
        $.ajax({
            type: "delete",
            url: "/blog/public/admin/blog",
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

    $(".edit_blog_btn").click(function() {
        var id = $(this).data("id");
        $("#edit_form")[0].reset();
        $.ajax({
            type: "get",
            url: "/blog/public/admin/blog",
            async: false,
            data: {
                "id": id
            },
            success: function(data) {
                if (data.code == 1) {
                    $("#edit_id").val(id);
                    $("#edit_title").val(data.blog.title);
                    $("#edit_cate").val(data.blog.cate_id);
                    $("#edit_content").val(data.blog.content);
                    $("#edit_blog").modal('show');
                } else {
                    show_msg("error", data.msg);
                    disMsgDelay(3000);
                }
            }
        });
    });

    $("#edit_submit_btn").click(function() {
        confirm("Do you want to edit this blog.");
        $.ajax({
            type: "put",
            url: "/blog/public/admin/blog",
            async: false,
            data: {
                "article_no": $("#edit_id").val(),
                "title": $("#edit_title").val(),
                "cate_id": $("#edit_cate").val(),
                "content": $("#edit_content").val()
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

    $(".verify_blog_btn").click(function() {
        var id = $(this).data("id");
        $.ajax({
            type: "put",
            url: "/blog/public/admin/verify",
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


});

var remind = 0;
$.ajax({
    type: "get",
    url: "/blog/public/admin/count_comment",
    async: false,
    success: function(data) {
        console.log(data);
        remind = data.num;
    }
});


setInterval(function() {
    $.ajax({
        type: "get",
        url: "/blog/public/admin/count_comment",
        async: false,
        success: function(data) {
            if(remind < data.num){
                remind = data.num;
                show_msg("notice", 'There are new comment in your blog');
            }
        }
    })
}, 10000);

</script>

</html>