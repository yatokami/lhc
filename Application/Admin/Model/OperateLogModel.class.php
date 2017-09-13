<?php
namespace Admin\Model;
use Think\Model;
/**
 * 操作日志服务类
 */
class OperateLogModel extends BaseModel{
	protected $trueTableName = 't_operatelog'; //数据库名.表名(包含了前缀) 

	//添加日志
	public function addLog($agentId, $name, $parentId, $otype, $type, $money) {
		$data_log["OperateUserId"] = $agentId;
		$data_log["OperateUserName"] = $name;
		$data_log["OperateAgentId"] = $parentId;
		$data_log["Time"] = time();
		$data_log["OperateMoney"] = $money;
		$data_log["OperateType"] = $otype;
		$data_log["Type"] = $type;
		$count = M('t_operatelog')->add($data_log);
		
		return $count;
	}

	public function pagerow($otype, $name, $type , $parentId) {
		$Logs = M('t_operatelog');
		$map = [];
		if($otype != null) {
			$map["OperateType"] = $otype;
			
		}
		if($name != null) {
			$map["OperateUserName"] = $name;
		}

		$map["OperateAgentId"] = $parentId;
		$map["Type"] = $type;
        $count = $Logs
                ->where($map)
                ->count();
        $page = new \Think\PageBootcss($count, 10);
        $limit = $page->firstRow.','.$page->listRows;

        $PageLog = $Logs
        		->where($map)
        		->order("Id desc")
        		->limit($limit)
                ->select();

        $data["page"] = $page;
        $data["count"] = $count;
        $data["pagelog"] = $PageLog;
        return $data;
	}
}