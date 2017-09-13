<?php
namespace Admin\Model;
use Think\Model;
/**
 * 代理服务类
 */
class ProxyModel extends BaseModel{
	protected $trueTableName = 't_agent'; //数据库名.表名(包含了前缀) 

	//获取只需字段
	public function getAgentRow($agentId, $parentId) {

		$map["AgentId"] = $agentId;
		if($parentId) {
			$map["AgentParent"] = $parentId;
		}

		$T_Agent = M('t_agent')
				->field('Money, SumMoney, Total')
				->where($map)
				->find();

		return $T_Agent;
	}

}