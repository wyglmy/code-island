/**
 * Created by Administrator on 2017/5/27 0027.
 */
//是否Email
function isEmail(str)
{
    var reg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return reg.test(str);
}
//手机号码
function isMobile(v)
{
    if((/^1[345678]+\d{9}$/.test(v))){
        return true;
    }else{
        return false;
    }
}
//全选
function checkAll(obj, name){
    var flag = false;
    if(obj.checked == true){flag = true;}
    checkAllCheckBox(name, flag);
}
//全选
function checkAllCheckBox(name, flag)
{
    name = $.trim(name); if(name == ''){ return; }
    if(flag!== true && flag!==false){ flag = false; }
    var names = document.getElementsByName(name);
    var len = names.length;
    for(var i=0; i<len; i++){
        names[i].checked = flag;
    }
}
