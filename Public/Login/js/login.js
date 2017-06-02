/**
 * Created by Administrator on 2017/5/27 0027.
 */
    $(function(){
        //获取登陆用户名input 密码input
        var user_name_input = $('#user_name').val();
        var pwd_input = $('#pwd').val();
        if (user_name_input == ''){
            $('#user_name').focus();
        }else {
            $('#pwd').focus();
        }
    });




