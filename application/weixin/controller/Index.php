<?php
namespace application\weixin\controller;

use application\weixin\model\WeixinUser;
use think\Config;
use think\Db;
use think\View;
class Index extends Common
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }
    public function dafeijiScores(){
        session_start();
        $WeixinUserModel = new WeixinUser();
        $scores = $_POST['scores'];
        $openid = $_SESSION['weixin']['openid'];
        $userinfo  = $WeixinUserModel->getRow(['openid'=>$openid]);

        $data = [
            'id' => '',
            'user_id' => $userinfo['id'],
            'openid' => $openid,
            'scores' => $scores,
            'money' => 0,
            'updatetime' => time(),
            'ip' => request()->ip(),
        ];
        $result = model('WeixinScores')->saveData($data);
        if($result){
            echo json_encode(['status'=>'success','msg'=>'更新分数成功']);
        }else{
            echo json_encode(['status'=>'error','msg'=>'出现错误，本次分数上传失败o(╥﹏╥)o']);
        }
        exit;
    }
}
