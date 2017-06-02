<?php
namespace Login\Controller;
use Common\Logic\Member\MemberLogic;
use Think\Controller;
class LoginController extends Controller {
    public $L_mem;

    public function __construct()
    {
        parent::__construct();//执行父类的构造函数，否则会被覆盖的。
        $this->L_mem = new MemberLogic();
    }

    //登录页面
    public function login(){
        $a = 111;
        $this->display();
    }

    //验证码
    public function verify(){
        $config =    array(
            'fontSize'    =>    15,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    true, // 关闭验证码杂点
            'imageW'    =>    0, // 验证码宽度 设置为0为自动计算
            'imageH'    =>    34, // 验证码高度 设置为0为自动计算

        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();

    }

    //登录信息检查
    public function check_login()
    {
        if (IS_POST){
            $params = I('request.');
            $verify = new \Think\Verify();
            if ($verify->check($params['verify'])){

                $this->ajaxReturn(genReturn('y','ok'));
            }else{
                $this->ajaxReturn(genReturn('n','no'));
            }

        }
    }
}