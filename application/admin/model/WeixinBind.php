<?php
namespace application\admin\model;

use think\Config;
use think\Db;
use think\Loader;
use think\Model;
use traits\model\SoftDelete;

class WeixinBind extends Admin
{
	use SoftDelete;
    protected $deleteTime = 'delete_time';

	public function getList( $request )
	{
		$request = $this->fmtRequest( $request );
		$data = $this->order('create_time desc')->where( $request['map'] )->limit($request['offset'], $request['limit'])->select();
		return $this->_fmtData( $data );
	}

	public function saveData($data)
	{
		if( isset( $data['id']) && !empty($data['id'])) {
            $result = model('weixin_bind')->allowField(true)->update($data,['id' => $data['id']]);
		} else {
            $result = model('weixin_bind')->add($data);
		}
		return $result;
	}


	public function deleteById($id)
	{
		$result = User::destroy($id);
		if ($result > 0) {
            return info('删除成功', 1);
        }else{
            return info('删除失败', 0);
        }
	}

	//格式化数据
	private function _fmtData( $data )
	{
		if(empty($data) && is_array($data)) {
			return $data;
		}

		foreach ($data as $key => $value) {
			$data[$key]['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
			$data[$key]['status'] = $value['status'] == 1 ? lang('Start') : lang('Off');
		}

		return $data;
	}

}
