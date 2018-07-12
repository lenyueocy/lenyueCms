<?php
namespace application\wap\controller;


use application\admin\model\WeixinBind;
use application\weixin\model\WeixinUser;
use application\weixin\model\WeixinScores;
use think\Cache;
use think\Controller;
use think\Curl;
use think\model;
use think\Db;
use think\Request;
use think\Session;
use think\Config;
use think\Url;

class Weixin extends Common
{
    public function index(){
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
        $WeixinUserModel = new WeixinUser();
        $WeixinScoresModel = new WeixinScores();

        $data = $model->table('admin_weixin_bind')->find();
        $is_login = isset($_SESSION['weixin']['openid'])?1:0;


        //登录才该触发的动作
        if ($is_login){
            $userinfo = $WeixinUserModel->getRow(['openid'=>$_SESSION['weixin']['openid']]);
            $this->assign('userinfo',$userinfo);
            $scoresData = $WeixinScoresModel->getRow(['openid'=>$_SESSION['weixin']['openid']]);
            if(empty($scoresData)){
                $scoresData = [
                    'scores' => 0,
                    'money' => 0,
                ];
            }
            $this->assign('scores',$scoresData);

            //微信jssdk分享配置信息
//            $signPackage = $this->getJssdkData();
            Vendor ( 'jssdk.jssdk' );
            $jssdk = new \JSSDK ('wxf1b35fc53dc1cf55', 'd34c8152a3a3cc69aff201b7122ed398' );
            $signPackage = $jssdk->getsignPackage();
            $this->assign('signPackage', $signPackage);

            $signPackage['appid'] = 'wxf1b35fc53dc1cf55';
            $this->assign('jssdk',$signPackage);

        }

        $this->assign('data',$data);
        $this->assign('is_login',$is_login);
        $template = $this->theme .'/wap/weixin_dafeiji.html';
        return $this->fetch($template);
    }

    public function getJssdkData(){
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $nonceStr = $this->createNonceStr();
        $jsapiTicket = $this->getJsApiTicket();
        $timestamp = time();

        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);

        $signPackage = array(
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "rawString" => $string,
            "signature" => $signature
        );
        return $signPackage;
    }
    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    private function getJsApiTicket()
    {
        if(Cache::get('ticket')) {
            $ticket = Cache::get('ticket');
        }else{
            $accessToken = action('weixin/Login/basic_token');
            //企业
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token={$accessToken}";
            $data = Curl::get($url);
            $data = json_decode($data, true);
            if(isset($data['errcode']) && $data['errcode'] != '0'){
                action('weixin/Login/echoError');
            }
            Cache::set('ticket', $data['ticket'], 7000);
            $ticket = $data['ticket'];
        }
        return $ticket;
    }
}