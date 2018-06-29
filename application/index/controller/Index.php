<?php
namespace app\index\controller;

use think\Config;
use think\Db;
use think\View;

class Index extends Common
{
    public function index()
    {

        $this->assign('demo_time',$this->request->time());
        $this->assign('head_on','index');
        $template = 'template/'. $this->theme .'/index_index.html';
        return $this->fetch($template);
    }

    public function hello(){
        /*$prev_year = strtotime('-1 year');
        $pyear = date('Y',$prev_year);
        $cyear = date('Y',time());

        echo $pyear.'--'.$cyear;*/
        $a = request()->root(true);
        str_replace('index.php/','',$a);
        echo str_replace('/index.php','',$a);
        //print_r(url('index/index',['id' => 2]));
    }
    public function companyinfo(){
        $this->assign('demo_time',$this->request->time());
        $this->assign('head_on','companyinfo');
        $template = 'template/'. $this->theme .'/companyinfo.html';
        return $this->fetch($template);
    }
    public function shortLineNuggets(){
        $this->assign('demo_time',$this->request->time());
        $this->assign('head_on','shortLineNuggets');
        $template = 'template/'. $this->theme .'/shortLineNuggets.html';
        return $this->fetch($template);
    }
    public function team(){
        $this->assign('demo_time',$this->request->time());
        $this->assign('head_on','team');
        $template = 'template/'. $this->theme .'/team.html';
        return $this->fetch($template);
    }

    public function contact(){
        $this->assign('demo_time',$this->request->time());
        $this->assign('head_on','contact');
        $template = 'template/'. $this->theme .'/contact.html';
        return $this->fetch($template);
    }

}
