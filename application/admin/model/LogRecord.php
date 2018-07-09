<?php
namespace application\admin\model;

use \think\Config;
use \think\Model;
use \think\Session;


/**
 * 操作日志记录
 */
class logRecord extends Admin
{
    protected $updateTime = false;
    protected $insert     = ['ip', 'user_id','browser','os'];
    protected $type       = [
        'create_time' => 'int',
    ];

    /**
     * 记录ip地址
     */
    protected function setIpAttr()
    {
        return \application\common\tools\Visitor::getIP();
    }

    /**
     * 浏览器把版本
     */
    protected function setBrowserAttr()
    {
        return \application\common\tools\Visitor::getBrowser().'-'.\application\common\tools\Visitor::getBrowserVer();
    }

    /**
     * 系统类型
     */
    protected function setOsAttr()
    {
        return \application\common\tools\Visitor::getOs();
    }

    /**
     * 用户id
     */
    protected function setUserIdAttr()
    {
        $user_id = 0;
        if (Session::has('userinfo', 'admin') !== false) {
            $user = Session::get('userinfo','admin');
            $user_id = $user['id'];
        }
        return $user_id;
    }
 
    public function record($remark)
    {
        $this->save(['remark' => $remark]);
    }


    public function UniqueIpCount()
    {   
        $data = $this->column('ip');
        $data = count( array_unique($data) );
        return $data;
    }

}
