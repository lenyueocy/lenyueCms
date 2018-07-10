<?php
namespace application\wap\controller;


use application\admin\model\WeixinBind;
use think\Controller;
use think\model;
use think\Db;
use think\Request;
use think\Session;
use think\Config;
class Weixin extends Common
{
    public function index(){
        die('');
        $this->assign('demo_time',$this->request->time());
        $this->assign('head_on','index');
            $template = $this->theme .'/wap/weixin_index.html';
        return $this->fetch($template);
    }
    public function checkoutLogin(){
        //检测登录
    }
    public function min5(){
        $template = $this->theme .'/wap/weixin_5min.html';
        return $this->fetch($template);
    }
    public function caifuAuto(){
        $template = $this->theme .'/wap/weixin_caifuauto.html';
        return $this->fetch($template);
    }
    public function niugu(){
        $template = $this->theme .'/wap/weixin_niugu.html';
        return $this->fetch($template);
    }
    public function upstop(){
        $template = $this->theme .'/wap/weixin_upstop.html';
        return $this->fetch($template);
    }
    public function choujiang(){
        $template = $this->theme .'/wap/weixin_choujiang.html';
        return $this->fetch($template);
    }
    public function dafeiji(){
        $model = new WeixinBind();
        $data = $model->table('admin_weixin_bind')->find();
        echo "<pre>";
        print_r($_SESSION['weixin']['openid']);
        exit;
        $is_login = isset($_SESSION['weixin']['openid'])?1:0;
        $this->assign('data',$data);
        $this->assign('is_login',$is_login);
        $template = $this->theme .'/wap/weixin_dafeiji.html';
        return $this->fetch($template);
    }
}