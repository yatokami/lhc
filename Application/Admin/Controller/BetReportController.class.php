<?php
namespace Admin\Controller;
//use Think\Controller;
class BetReportController extends BaseController {
    public function index(){

        $stime = date("Y-m-d H:i:s", strtotime("-1 week"));
        $etime = date("Y-m-d H:i:s");
        if(I('get.stime')) {
            $stime = I('get.stime');
            $etime = I('get.etime');
        }

        $map["BetTime"] = array(array('EGT', strtotime($stime)), array('ELT', strtotime("$etime")));

        $t_bet = M('t_bet');
        if(session('AgentId') == 1 || session('AgentId') == 2) {
            if(I('get.agentname')) {
                $map["t_agent.AgentName"] = I('agentname');
            }
            //总代理查看投注
            $count = $t_bet
                    ->join('LEFT JOIN t_user ON t_user.UserId = t_bet.UserId')
                    ->join('INNER JOIN t_agent ON t_user.AgentId=t_agent.AgentId')
                    ->where($map)
                    ->count();

            $page = new \Think\TimePageBootcss($count, 10);
            $limit = $page->firstRow.','.$page->listRows;

            $T_BetList = $t_bet
                    ->order('BetTime desc')
                    ->join('LEFT JOIN t_user ON t_user.UserId = t_bet.UserId')
                    ->join('INNER JOIN t_agent ON t_user.AgentId=t_agent.AgentId')
                    ->where($map)
                    ->limit($limit)
                    ->select();

            if(I('agentname')) {
                $T_BetInfo = D('Admin/Bet')->betinfo($map);
            }

        } else {
            if(I('get.agentname')) {
                $map["t_user.UserName"] = I('agentname');
            }

                //子代理查看投注
                $agentId = session('AgentId');

                $count = $t_bet
                        ->join('LEFT JOIN t_user ON t_user.UserId = t_bet.UserId')
                        ->where(["t_user.AgentId" => $agentId])
                        ->where($map)
                        ->count();

                $page = new \Think\TimePageBootcss($count, 10);
                $limit = $page->firstRow.','.$page->listRows;

                $T_BetList = D('Admin/Bet')->pagerow($limit, $agentId, $map);
                $map["t_user.AgentId"] = $agentId;
                $T_BetInfo = D('Admin/Bet')->betinfo($map);

            
            
        }

        for ($i = 0; $i < count($T_BetList); $i++) { 
            $T_BetList[$i] = conversion_type($T_BetList[$i]);
        }
        $this->assign('t_betinfo', $T_BetInfo);
        $this->assign('proxy_name', I('agentname'));
        $this->assign('stime', $stime);
        $this->assign('etime', $etime);
        $this->assign('t_betlist', $T_BetList);
        $this->page = $page->show();
        $this->count = $count;
        $this->assign('bet_report_active', 'active');
        $this->assign('report_active', 'active');
        $this->display('Index/BetReport');
    }
}