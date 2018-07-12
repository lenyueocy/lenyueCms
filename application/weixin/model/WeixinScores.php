<?php
namespace application\weixin\model;

use think\Model;

class WeixinScores extends Model
{
    public function getRow($filter){
        $data = $this->db('WeixinScores')->where($filter)->find();
        return $data;
    }
    public function saveData($saveData){
        $data = model('WeixinScores')->where(['openid'=>$saveData['openid']])->find();
        if (!empty($data)){
            if($saveData['scores'] > $data['scores']) {
                $result = $this->edit($saveData);
            }else{
                return true;
            }
        }else{
            $result = $this->add($saveData);
        }
        return $result;
    }
    public function add($saveData){
        $result = model('WeixinScores')->save($saveData);
        return $result;
    }
    public function edit($saveData){
        $savedata = [
            'scores' =>$saveData['scores'],
            'updatetime' =>time(),
            'ip' =>$saveData['ip'],
        ];
        $result = model('WeixinScores')->where(['openid'=>$saveData['openid']])->update($savedata);
        return $result;
    }
    public function saveHongbao($saveData)
    {
        $data=[
            'money'=>$saveData['money'],
        ];
        $result = model('WeixinScores')->where(['openid'=>$saveData['openid']])->update($data);
        return $result;
    }
}