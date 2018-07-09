<?php
namespace application\wap\controller;


use think\Controller;
use think\Request;
class Pdf extends Common
{
    public function index(){
        $this->assign('demo_time',$this->request->time());
        $this->assign('head_on','index');
            $template = $this->theme .'/wap/pdf_index.html';
        return $this->fetch($template);
    }
}