<?php
namespace application\weixin\controller;

use application\admin\model\WeixinBind;
use think\Config;
use think\Curl;
use think\Db;
use think\Request;
use think\View;
class Login extends Common
{
    public $appid;
    public $appsecret;
    public function _initialize()
    {
        parent::_initialize();
        $model = new WeixinBind();
        $data = $model->table('admin_weixin_bind')->find();
        $this->appid = $data['appid'];
        $this->appsecret = $data['appsecret'];
    }

    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }
    public function callback(){
        $code = $_GET['code'];
        $access_token = $this->access_token($code);
        echo "<pre>";
        print_r($access_token);
        exit;
    }
    public function access_token($code){
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appid."&secret=".$this->appsecret."&code=".$code."&grant_type=authorization_code";
        $access_token = Curl::get($url);
        return $access_token;
    }
}
