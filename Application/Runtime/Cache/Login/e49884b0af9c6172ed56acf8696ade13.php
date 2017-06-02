<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="/Public/bs/css/bootstrap.min.css" rel="stylesheet">
    <script src="/Public/bs/js/jquery-2.1.4.min.js"></script>
    <script src="/Public/bs/js/bootstrap.min.js"></script>
    <script src="/Public/common/js/js.js"></script>
    <title>MyBlog后台</title>
</head>
<body>

<link href="/Public/Login/css/login.css" rel="stylesheet">
<script src="/Public/Login/js/login.js"></script>

<div class="container">
    <div class="login">
        <form action="javascript:;" id="login_form">
            <div class="form-group">
                <label for="">用户名：</label>
                <input type="text" class="form-control" id="user_name" name="user_name" placeholder="请输入用户名/手机/邮箱" >
            </div>
            <div class="info_u">
                <span>请输入用户均名</span>
            </div>
            
            <div class="form-group">
                <label for="">密码：</label>
                <input type="password" class="form-control" id="pwd" name="pwd" placeholder="请输入密码">
            </div>
            <div class="info_p">
                <span>请输入用户均名</span>
            </div>
            <div class="form-group">
                <label for="">验证码：</label>
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="verify" name="verify" placeholder="请输入验证码">
                    </div>
                    <div class="col-md-4">
                        <img src="/Login/verify" class="verify" onclick="this.src='/Login/verify/rand='+Math.random()" alt="验证码" title="点击换一个">
                    </div>
                </div>
            </div>
            <div class="info_v">
                <span>请输入用户均名</span>
            </div>
            <div class="form-group button_line">
                <input type="button" value="登录" class="btn btn-primary" id="do_submit" onclick="check_value()">
                <input type="reset" value="重置" class="btn btn-danger reset">
            </div>
        </form>
    </div>
</div>
<script>
    //检测登录信息
    function check_value() {
        var u = $.trim($('#user_name').val());
        var p = $.trim($('#pwd').val());
        var v = $.trim($('#verify').val());
        $('.info_p, .info_u, .info_v').hide();
        if (u == ''){
            $('.info_u').show();
            $('.info_u span').html("用户名不能为空！");
            $('#user_name').focus();
            return false;
        }
        if (p == ''){
            $('.info_p').show();
            $('.info_p span').html("密码不能为空！");
            $('#info_p').focus();
            return false;
        }
        if (v == ''){
            $('.info_v').show();
            $('.info_v span').html("验证码不能为空！");
            $('#info_v').focus();
            return false;
        }

        $.post("<?php echo U('login/check_login'); ?>", {user_name:u, pwd:p, verify:v}, function (data) {
                alert(data.note);
        });

    }
</script>
</body>
</html>