<?php
namespace application\weixin\controller;

use application\admin\model\WeixinBind;
use think\Cache;
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

        if(Cache::get('access_token') ==1){
            $access_token = Cache::get('access_token');
        }else {
            $access_tokenData = $this->access_token($code);
            if(isset($access_tokenData['errcode'])){
                $this->echoError();
            }
            Cache::set('access_token', $access_tokenData['access_token'], $access_tokenData['expires_in']);
            $access_token = $access_tokenData['access_token'];
        }


    }
    public function access_token($code){
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appid."&secret=".$this->appsecret."&code=".$code."&grant_type=authorization_code";
        $data = Curl::get($url);
        return json_decode($data,true);
    }
    public function setLogin(){

    }
    //统一界面输出错误信息
    public function echoError(){
        $url = url('/wap/weixin/dafeiji');
        echo <<<error
        <html>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no">
</html>
        <script type="text/javascript" src="/static/admin/layer_mobile/layer.js"></script>
	    <link rel="stylesheet" type="text/css" href="/static/admin/layer_mobile/need/layer.css" >
        <script>
                layer.open({
                    content: '不允许点击遮罩关闭',
                    btn: '我知道了',
                    shadeClose: false,
                    yes: function(){
                    layer.open({
                    content: '好的'
                    ,time: 2
                    ,skin: 'msg'
                });
            }
        });
        </script>
error;
        exit;
    }
}
