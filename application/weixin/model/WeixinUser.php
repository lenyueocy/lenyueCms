<?php
namespace application\weixin\model;

use think\Model;

class WeixinUser extends Model
{
    public function saveUserinfo($data){
        $saveData = [
            'openid' => $data['openid'],
            'nickname' => $data['nickname'],
            'sex' => $data['sex'],
            'country' => $data['country'],
            'province' => $data['province'],
            'city' => $data['city'],
            'headimgurl' => $data['headimgurl'],
            'createtime' => time(),
            'updatetime' => time(),
            'ip' => request()->ip(),
        ];
        $data = model('weixin_user')->where(['openid'=>$saveData['openid']])->select();
        echo "<pre>";
        print_r(empty($data));
        exit;
    }
    public function add(){

    }
    public function edit(){

    }
}