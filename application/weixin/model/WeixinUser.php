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
            $result = $this->edit($saveData);
        }else{
            $result = $this->add($saveData);
        }
        return $result;
    }
    public function add($saveData){
        $result = model('weixin_user')->save($saveData);
        return $result;
    }
    public function edit($saveData){
        $savedata = [
            'nickname' =>$saveData['nickname'],
            'country' =>$saveData['country'],
            'province' =>$saveData['province'],
            'city' =>$saveData['city'],
            'headimgurl' =>$saveData['headimgurl'],
            'updatetime' =>time(),
            'ip' =>$saveData['ip'],
        ];
        $result = model('weixin_user')->where(['openid'=>$saveData['openid']])->update($savedata);
        return $result;
    }
}