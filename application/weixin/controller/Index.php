<?php
namespace application\weixin\controller;

use application\weixin\model\WeixinUser;
use think\Config;
use think\Curl;
use think\Db;
use think\View;
class Index extends Common
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }
    public function dafeijiScores(){
//        session_start();
        $WeixinUserModel = new WeixinUser();
        $scores = $_POST['scores'];
        $openid = $_SESSION['weixin']['openid'];
        $userinfo  = $WeixinUserModel->getRow(['openid'=>$openid]);

        $data = [
            'id' => '',
            'user_id' => $userinfo['id'],
            'openid' => $openid,
            'scores' => $scores,
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
    public function isguanzhu(){
//        session_start();
        $openid = $_SESSION['weixin']['openid'];
        $access_token = action('weixin/Login/basic_token');
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
        $data = Curl::get($url);
        echo $data;exit;
        /*$data = json_decode($data,true);
        return $data['subscribe'];*/
    }
    public function hongbao(){
        $WeixinUserModel = new WeixinUser();
        $money = $_POST['money'];
        $openid = $_SESSION['weixin']['openid'];
        $data = [
            'openid' => $openid,
            'money' => $money,
        ];
        $result = model('WeixinScores')->saveHongbao($data);
        if($result){
            echo json_encode(['status'=>'success','msg'=>'恭喜你，红包已成功进入你的余额~']);
        }else{
            echo json_encode(['status'=>'error','msg'=>'出现错误，红包溜走了o(╥﹏╥)o']);
        }
        exit;
    }
}
