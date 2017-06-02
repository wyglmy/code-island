<?php
namespace Admin\Behaviors;

use Think\Behavior;

class CheckAuthBehavior extends Behavior
{
    public function run(&$params)
    {
        $this->checkAuth();
    }

    public function checkAuth()
    {
        if (!S('level') || S('level') < 3){
            //第五个参数 自动匹配URL的子域名
            redirect(U('Login/login', array(), false, true));
            exit;
        }
    }
}