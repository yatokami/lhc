<?php
namespace Admin\Controller;
//use Think\Controller;
class IndexController extends BaseController {
    public function index(){

    	$agentId = session('AgentId');
    	//获取登陆日志
    	$t_agentLog = M('t_agentlog')
    				->where(['AgentId' => $agentId])
                    ->order('Id desc')
                    ->limit(5)
    				->select();

        if(is_auth()) {
            $agentId = 1;
        }

    	//获取操作日志
    	$t_operateLog = M('t_operatelog')
    					->where(['OperateAgentId' => $agentId])
                        ->order('Id desc')
    					->limit(5)
    					->select();

        //获取公告
        $t_bulletin = M('t_bulletin')
                    ->order('Id desc')
                    ->limit(5)
                    ->select();

        if(is_auth()) {
            $info = M('t_agent')
                ->field('count(AgentId) as acount, sum(SumMoney) as SumMoney, sum(Money) as Money, sum(Total) as Total')
                ->where(['AgentParent' => $agentId])
                ->group('AgentParent')
                ->find();
        } else {
            $info = M('t_user')
                ->field('count(UserId) as acount, sum(SumMoney) as SumMoney, sum(Money) as Money, sum(Total) as Total')
                ->where(['AgentId' => $agentId])
                ->group('AgentId')
                ->find();
        }


        $this->assign('t_bulletin', $t_bulletin);
        $this->assign('info', $info);
    	$this->assign('t_agentlog', $t_agentLog);
    	$this->assign('t_operatelog', $t_operateLog);
    	$this->display();
    }
}