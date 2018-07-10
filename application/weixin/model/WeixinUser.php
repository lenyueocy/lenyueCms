<?php
namespace application\weixin\model;

use think\Model;

class WeixinUser extends Model
{
    public function saveUserinfo($data){
        echo "<pre>";
        print_r($data);
        exit;
    }
    public function add(){

    }
}