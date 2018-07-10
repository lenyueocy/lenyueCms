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
        if (!empty($data)){
            $this->edit($saveData);
        }else{
            $this->add($saveData);
        }
    }
    public function add($saveData){
        $result = model('weixin_user')->save($saveData);
        return $result;
    }
    public function edit($saveData){
        $result = model('weixin_user')->update($saveData);
        return $result;
    }
}