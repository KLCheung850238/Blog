<!DOCTYPE html>
<html lang='us'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">
    <script src="https://cdn.bootcdn.net/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
    
    <title>
        Login
    </title>
</head>

<body>
@include('nav')
    
    <div class="main" style="min-height: 600px;">
        <div class="container-fluid" style="height: 400px; width: 500px;">
            <h2 class="text-center">Login Page</h2>
            <form action="#" id="loginForm" method="POST" class="form-horizontal" style="margin-top: 40px;">
                <div class="form-group">
                    <label for="email" class="col-sm-3 control-label"><i class="glyphicon glyphicon-envelope" aria-hidden="true"></i>Email</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email"><span id="warn-email" style="color:red;display:none;" ">Email can't be empty or invalid</span>
                    </div>
                </div>
                <div class=" form-group">
                            <label for="psd" class="col-sm-3 control-label"><i class="glyphicon glyphicon-lock"></i>Password</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" name="password" id="psd" placeholder="Password"><span id="warn-password" style="color:red;display:none;">password can't be empty</span>
                            </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-2">
                            <button type="button" id="sub" class="btn btn-success" style="height: 30px;">Login</button>
                        </div>
                        <div class="col-sm-offset-4 col-sm-2">
                            <a type="button" id="back" href="/blog/public/register" class="btn btn-primary" style="height: 30px;">Register</a>
                        </div>
                    </div>
            </form>
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
$(function() {
    $("#sub").click(function() {
        var email = $("#email").val();
        var psd = $("#psd").val();

        var reg = /^([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/;
        if (email == null || email == undefined || email == "" || !reg.test(email)) {
            $("#warn-email").css("display", "block");
            return;
        } else {
            $("#warn-email").css("display", "none");
        }
        if (psd == null || psd == undefined || psd == "") {
            $("#warn-password").css("display", "block");
            return;
        } else {
            $("#warn-password").css("display", "none");
        }
        $.ajax({
            type: "post",
            url: "/blog/public/login_verify",
            async: false,
            data: { "email": email, "password": psd},
            success: function(data) {
                console.log(data);
                if (data.code == 1) {
                    show_msg("success", "login successfully");
                    window.location.href = '/blog/public/admin/index';
                }else{
                    show_msg("error", data.msg);
                    disMsgDelay(3000);
                }
            }
        });
    });
})
</script>



