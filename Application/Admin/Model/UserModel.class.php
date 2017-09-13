<?php
namespace Admin\Model;
use Think\Model;
/**
 * 会员服务类
 */
class UserModel extends BaseModel{
	protected $trueTableName = 't_user'; //数据库名.表名(包含了前缀) 

	//获取可用余额
	public function getAgentRow($userId, $parentId) {

		$map["UserId"] = $userId;
		if($parentId) {
			$map["AgentId"] = $parentId;
		}

		$T_User = M('t_user')
				->field('Money, SumMoney, AgentId, Total')
				->where($map)
				->find();

		return $T_User;
	}

}