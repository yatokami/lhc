<?php
namespace Admin\Controller;
//use Think\Controller;
class ProxyController extends BaseAuthController {
	//代理列表显示
	public function index() {
		//$parentId = session('AgentId');
		$parentId = 1;

		$T_Agent = D( 'Admin/Proxy' )->getAgentRow($parentId);
		$map["t_agent.AgentId"] = array('EGT', 3);
		//分页
		$T_Agents = M('t_agent');
        $count = $T_Agents
        		->where($map)
                //->where(["AgentParent" => $parentId])
                ->count();
        $page = new \Think\PageBootcss($count, 10);
        $limit = $page->firstRow.','.$page->listRows;
		//获取子代理
		$T_AgentList = $T_Agents
					->join("LEFT JOIN t_user on t_agent.AgentId = t_user.AgentId")
					->field('t_agent.AgentId, AgentName, AgentParent, t_agent.Money, Nulltiy, Proportion, t_agent.SumMoney, t_agent.Remark, IFNULL(sum(t_user.SumMoney), 0) as u_summoney,IFNULL(sum(t_user.Money),0) as u_money, t_agent.Total')
					// ->where(["AgentParent" => $parentId])
					->group('AgentId')
					->where($map)
					->order("AgentId Desc")
					->limit($limit)
					->select();

		$this->assign('t_money', $T_Agent['money']);
		$this->assign('proxy_active', 'active');
		$this->assign('proxy_list_active', 'active');
		$this->assign('t_agentlist', $T_AgentList);
		$this->page = $page->show();
        $this->count = $count;
		$this->display('Index/ProxyList');
	}

	//新增代理
	public function add_proxy() {
		//$parentId = session('AgentId');
		$parentId = 1;

		$T_Agent = D( 'Admin/Proxy' )->getAgentRow($parentId);

		//判断用户名是否重复
        $temp = M('t_agent')->where(["AgentName" => I('post.proxy_name')])->count();
        if($temp > 0) {
            $this->ajaxReturn(-3);
        }

		$count = 0;
		//额度不能为负
		if((float)I('post.proxy_money') < 0) {
            $this->ajaxReturn(-1);
        }

        //额度不能超过余额
		if((float)$T_Agent['money'] > (float)I('post.proxy_money')) {
			$data["AgentParent"] = $parentId;
			$data["AgentName"] = I('post.proxy_name');
			$data["AgentPwd"] = md5(I('post.proxy_pwd'));
			$data["SumMoney"] = I('post.proxy_money');
			$data["Money"] = $data["SumMoney"];
			$data["Remark"] = I('post.proxy_remark');
			$data["Proportion"] = 3;

			$count = M('t_agent')->add($data);
			if($count > 0) {
				// $T_Agent= M('t_agent')
				// 		->where(["AgentId" => $parentId])
				// 		->setDec('Money', (float)$data["Money"]);
				D('Admin/OperateLog')->addlog($count, $data["AgentName"], $parentId, "新增代理", 1, $data["SumMoney"]);
			}
		} else {
			$count = -1;
		}
		$this->ajaxReturn($count);
	}

	//修改代理
	public function edit_proxy() {
		//$parentId = session('AgentId');
		$parentId = 1;
		$data["AgentName"] = I('post.proxy_name');
		//密码不能为空,为空则不修改原密码
		if(I('post.proxy_pwd') != null) {
			$data["AgentPwd"] = md5(I('post.proxy_pwd'));
		}

		$money = 0;
		$bool = false;
		//代理总额不为空并且是数字
		if(I('post.proxy_summoney') != null && is_numeric((float)I('post.proxy_summoney')) && (float)I('post.proxy_summoney') > 0) {
			$money = (float)I('post.proxy_summoney');
			$bool = true;
		}

		//代理备注
		if(I('post.proxy_remark') != null) {
			$data["Remark"] = I('post.proxy_remark');	
		}
		
		$map["AgentId"] = I('post.t_id');
		$map["AgentName"] = $data["AgentName"];
		if($data != null) {
			$state = M('t_agent')
					->where(['AgentParent' => $parentId, 'AgentName' => $data["AgentName"]])
					->save($data);
		}

		if($bool) {
			$user_summoney = M('t_user')->where(["AgentId" => $map["AgentId"]])->sum('SumMoney');

			if($money >= $user_summoney) {
				$state = M('t_agent')->where(["AgentId" => $map["AgentId"]])->save(["SumMoney" => $money]);
			} else {
				$this->ajaxReturn(-5);
			}
		}

		

		if($state) {
			D('Admin/OperateLog')->addlog($map["AgentId"], $data["AgentName"], $parentId, "修改代理", 1, $money);
			$this->ajaxReturn(1);
		} else {
			$this->ajaxReturn(0);
		}
	}

	//充值代理
	public function recharge_proxy() {

		//$parentId = session('AgentId');
		$parentId = 1;
		$T_Agent = D( 'Admin/Proxy' )->getAgentRow($parentId);
		$agentId = I('post.t_id');
		$agentName = I('post.proxy_name');
		$money = (float)I('post.proxy_addmoney');
		$count = 0;

		//添加金额不能小于0
		if((float)I('post.proxy_addmoney') < 0) {
            $this->ajaxReturn(-1);
        }

		if((float)$T_Agent['money'] >= $money) {
			M('t_agent')
				->where(['AgentId' => $agentId, 'AgentParent' => $parentId])
				->setInc('Money', $money);
			// M('t_agent')
			// 	->where(['AgentId' => $agentId])
			// 	->setInc('SumMoney', $money);
			// M('t_agent')
			// 	->where(['AgentId' => $parentId])
			// 	->setDec('Money', $money);
			D('Admin/OperateLog')->addlog($agentId, $agentName, $parentId, "充值代理", 1, $money);
			$count = 1;
		} else {
			$count = -1;
		}

		$this->ajaxReturn($count);
	}

	//降额代理
	public function reduce_proxy() {
		//$parentId = session('AgentId');
		$parentId = 1;
		$agentId = I('post.t_id');
		$agentName = I('post.proxy_name');
		$T_Agent = D( 'Admin/Proxy' )->getAgentRow($agentId, $parentId);
		$money = (float)I('post.proxy_reducemoney');


		if((float)I('post.proxy_reducemoney') < 0) {
            $this->ajaxReturn(-1);
        }
        
		if((float)$T_Agent['money'] >= $money) {
			M('t_agent')
				->where(['AgentId' => $agentId])
				->setDec('Money', $money);
			// M('t_agent')
			// 	->where(['AgentId' => $agentId])
			// 	->setDec('SumMoney', $money);
			// M('t_agent')
			// 	->where(['AgentId' => $parentId])
			// 	->setInc('Money', $money);
			D('Admin/OperateLog')->addlog($agentId, $agentName, $parentId, "降额代理", 1, $money);
			$count = 1;
		} else {
			$count = -1;
		}
		$this->ajaxReturn($count);
	}

	//操作日志
	public function operate_log() {
		$name = I('get.search_id');
		$type = I('get.operate_type');
		//$parentId = session('AgentId');
		$parentId = 1;
		if($type == "1") {
			$type = "新增代理";
		}
		if($type == "2") {
			$type = "充值代理";
		}
		if($type == "3") {
			$type = "降额代理";
		}
		if($type == "4") {
			$type = "结算代理";
		}
		if($type == "5") {
			$type = "提现代理";
		}
		if($type === "0") {
			$type = null;
		}
		$data = D('Admin/OperateLog')->pagerow($type, $name, 1, $parentId);

		$this->page = $data["page"]->show();
        $this->count = $data["count"];

		$this->assign('page_log', $data["pagelog"]);
		$this->assign('proxy_active', 'active');
		$this->assign('proxy_log_active', 'active');
		$this->display('Index/OperateLog');
	}

	//结算代理
	public function settle_proxy() {
		//$parentId = session('AgentId');
		$parentId = 1;
		$agentId = I('post.t_id');
		$agentName = I('post.proxy_name');

		$T_Agent = D( 'Admin/Proxy' )->getAgentRow($agentId, $parentId);


		$money = (float)$T_Agent["total"];
		M('t_agent')
			->where(['AgentId' => $agentId])
			->save(['Total' => 0]);
		$Model = new \Think\Model(); // 实例化一个model对象 没有对应任何数据表
		$Model->execute("update t_agent set Money=t_agent.SumMoney where AgentId=".$agentId);

		$log["UserId"] = $agentId;
		$log["Type"] = 1;
		$log["Total"] = $money;
		$log["Time"] = time();
		$log["ParentId"] = $parentId;
		$log["UserName"] = $agentName;
		M('t_settlelog')->add($log);

		D('Admin/OperateLog')->addlog($agentId, $agentName, $parentId, "结算代理", 1, $money);
		$this->ajaxReturn(1);
		
	}

	//提现代理
	public function takemoney_proxy() {
		//$parentId = session('AgentId');
		$parentId = 1;
		$agentId = I('post.t_id');
		$agentName = I('post.proxy_name');
		$money = (float)I('post.proxy_takemoney');

		if($money < 0) {
			$this->ajaxReturn(-1);
		}


		$T_Agent = D( 'Admin/Proxy' )->getAgentRow($agentId, $parentId);

		if((float)$T_Agent["total"] <= 0) {
			$this->ajaxReturn(-1);
		}

		if(($money <= (float)$T_Agent["total"]) && ($money <= (float)$T_Agent['money'])) {
			M('t_agent')
				->where(['AgentId' => $agentId])
				->setDec('Total', $money);
			M('t_agent')
				->where(['AgentId' => $agentId])
				->setDec('Money', $money);

			$log["UserId"] = $agentId;
			$log["Type"] = 1;
			$log["TakeMoney"] = $money;
			$log["Time"] = time();
			$log["ParentId"] = $parentId;
			$log["UserName"] = $agentName;
			M('t_takemoneylog')->add($log);

			D('Admin/OperateLog')->addlog($agentId, $agentName, $parentId, "提现代理", 1, $money);
			$this->ajaxReturn(1);
		}
		$this->ajaxReturn(-1);

	}

	//删除代理
	public function delete_proxy() {
		$agentId = I('post.t_id');
		$map["AgentPwd"] = md5(I('post.proxy_pwd'));
		$map["AgentId"] = session('AgentId');

		$count = D( 'Admin/Proxy' )->where($map)->count();

		if($count) {

			D( 'Admin/Proxy' )->where(['AgentId' => $agentId])->delete();
			M('t_agentlog')->where(['AgentId' => $agentId])->delete();
			M('t_takemoneylog')->where(['UserId' => $agentId, 'Type' => 1])->delete();
			M('t_settlelog')->where(['UserId' => $agentId, 'Type' => 1])->delete();
			
			$userlist = M('t_user')->where(['AgentId' => $agentId])->select();

			for ($i = 0; $i < count($userlist); $i++) { 
				$map["UserId"] = $userlist[$i]["userid"];
				M('t_bet')->where($map)->delete();
				M('t_log')->where($map)->delete();
			}

			M('t_takemoneylog')->where(['ParentId' => $agentId])->select();
			M('t_settlelog')->where(['ParentId' => $agentId])->select();
			M('t_user')->where(['AgentId' => $agentId])->delete();

			$this->ajaxReturn($count);
		} else {
			$this->ajaxReturn(-7);
		}

	}
	//切换代理状态
	public function switch_proxy() {
		$agentId = I('post.t_id');
		$data["Nulltiy"] = I('post.state');
		//$parentId = session('AgentId');
		$parentId = 1;

		$status = M('t_agent')
				->where(['AgentId' => $agentId, 'AgentParent' => $parentId])
				->save($data);
		if($status) {
			$this->ajaxReturn(-6);
		} else {
			$this->ajaxReturn(-4);
		}	
	}

}