<?php
namespace application\admin\controller;

use think\Loader;
use think\Request;



class WeixinDeveloper extends Admin
{

    function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 列表
     */
    public function index()
    {
        $dataRow = model('weixin_bind')->find();
        if ($dataRow){
            $this->assign('data',$dataRow);
        }
        return view('weixin/kaifazhe');
    }

    /**
     * 异步获取列表数据
     *
     * @author chengbin
     * @return mixed
     */
    public function getList()
    {
        if(!request()->isAjax()) {
            $this->error('请求错误', 4001);
        }

        $request = request()->param();
        $data = model('User')->getList( $request );
        return $data;
    }

    /**
     * 添加
     */
    public function add()
    {
        $request = Request::instance();
        $data = [
            'id' => $_POST['id'],
            'appid' => $_POST['appid'],
            'appsecret' => $_POST['appsecret'],
            'callback' => $_POST['callback'],
            'bindtime' => time(),
            'ip' => $request->ip(),
//            'ip' => request()->ip(),
        ];
        $result = model('WeixinBind')->saveData($data);
        if($result){
            $result = ['status'=>'success','msg'=>'修改成功'];
        }else{
            $result = ['status'=>'error','msg'=>'修改失败'];
        }
        return json_encode($result);
    }

    /**
     * 编辑
     * @param  string $id 数据ID（主键）
     */
    public function edit($id = 0)
    {   
        if(intval($id) < 0){
            return info(lang('Data ID exception'), 0);
        }
        if (intval($id == 1)) {
            return info(lang('Edit without authorization'), 0);
        }
        $roleData = model('role')->getKvData();
        $this->assign('roleData', $roleData);
        $data = model('User')->get(['id'=>$id]);
        $this->assign('data',$data);
        return $this->fetch();
    }

    /**
     * 保存数据
     * @param array $data
     *
     * @author chengbin
     */
    public function saveData()
    {
        $this->mustCheckRule( 'admin/user/edit' );
        if(!request()->isAjax()) {
            return info(lang('Request type error'));
        }

        $data = input('post.');
        var_dump($data);die;
        return model('User')->saveData( $data );
    }

    /**
     * 删除
     * @param  string $id 数据ID（主键）
     */
    public function delete($id = 0){
        if(empty($id)){
            return info(lang('Data ID exception'), 0);
        }
        if (intval($id == 1)) {
            return info(lang('Delete without authorization'), 0);
        }
        return Loader::model('User')->deleteById($id);
    }

   
}