<?php
namespace app\wap\controller;


use think\Controller;
use think\Request;
class Weixin extends Common
{
    public function index(){
        die('');
        $this->assign('demo_time',$this->request->time());
        $this->assign('head_on','index');
            $template = $this->theme .'/wap/weixin_index.html';
        return $this->fetch($template);
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
}