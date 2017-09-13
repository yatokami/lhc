<?php
namespace Admin\Controller;
//use Think\Controller;
class SettleReportController extends BaseAuthController {

    //代理结算记录
    public function proxy(){
        if(is_auth()) {
            $stime = date("Y-m-d H:i:s", strtotime("-1 week"));
            $etime = date("Y-m-d H:i:s");
            if(I('get.stime')) {
                $stime = I('get.stime');
                $etime = I('get.etime');
            }

            $map["t_settlelog.Time"] = array(array('EGT', strtotime($stime)), array('ELT', strtotime("$etime")));
            if(I('get.agentname')) {
                $map["t_agent.AgentName"] = I('agentname');
            }
            $map["t_settlelog.Type"] = 1;
            $t_settlelog = M('t_settlelog');
            if(session('AgentId') == 1 || session('AgentId') == 2) {
                //总代理查看投注
                $count = $t_settlelog
                        ->join('LEFT JOIN t_agent ON t_agent.AgentId = t_settlelog.UserId')
                        ->where($map)
                        ->count();

                $page = new \Think\TimePageBootcss($count, 10);
                $limit = $page->firstRow.','.$page->listRows;

                $T_Settlelog = $t_settlelog
                        ->join('LEFT JOIN t_agent ON t_agent.AgentId = t_settlelog.UserId')
                        ->field('t_settlelog.UserId, t_agent.AgentName, t_agent.SumMoney, t_agent.Money, t_settlelog.Total, t_settlelog.time')
                        ->order('t_settlelog.Time desc')
                        ->where($map)
                        ->limit($limit)
                        ->select();
            }

            // } else {
            //     //子代理查看投注
            //     $agentId = session('AgentId');
            //     $count = $t_settlog
            //             ->join('LEFT JOIN t_agent ON t_agent.AgentId = t_settlog.UserId')
            //             ->where(['t_agent.AgentParent' => $agentId])
            //             ->where($map)
            //             ->count();

            //     $page = new \Think\PageBootcss($count, 10);
            //     $limit = $page->firstRow.','.$page->listRows;
            //     $T_BetList = D('Admin/Bet')->pagerow($limit, $agentId, $map);
            // }



            $this->assign('stime', $stime);
            $this->assign('etime', $etime);
            $this->assign('proxy_name', $map["t_agent.AgentName"]);
            $this->assign('t_settlelog', $T_Settlelog);
            $this->page = $page->show();
            $this->count = $count;
            $this->assign('settlelog_report_active', 'active');
            $this->assign('report_active', 'active');
            $this->display('Index/SettleProxyReport');
        } else {
            $this->error('非法操作返回主页中','/Index/index');
        }   
       
    }


    //会员结算记录
    public function member_report() {
        $stime = date("Y-m-d H:i:s", strtotime("-1 week"));
        $etime = date("Y-m-d H:i:s");
        if(I('get.stime')) {
            $stime = I('get.stime');
            $etime = I('get.etime');
        }

        $map["t_settlelog.Time"] = array(array('EGT', strtotime($stime)), array('ELT', strtotime("$etime")));
        if(I('get.agentname')) {
            $map["t_settlelog.UserName"] = I('agentname');
        }
        $map["t_settlelog.Type"] = 0;
        $map["t_user.AgentId"] = session('AgentId');
        $t_settlelog = M('t_settlelog');
        //查看会员结算

        $count = $t_settlelog
                    ->join('LEFT JOIN t_user ON t_user.AgentId = t_settlelog.ParentId')
                    ->where($map)
                    ->count();

        $page = new \Think\TimePageBootcss($count, 10);
        $limit = $page->firstRow.','.$page->listRows;

        $T_Settlelog = $t_settlelog
                ->join('LEFT JOIN t_user ON t_user.UserId = t_settlelog.UserId')
                ->field('t_settlelog.UserId, t_user.UserName, t_user.SumMoney, t_user.Money, t_settlelog.Total, t_settlelog.time')
                ->order('t_settlelog.Time desc')
                ->where($map)
                ->limit($limit)
                ->select();
        

        $this->assign('stime', $stime);
        $this->assign('etime', $etime);
        $this->assign('user_name', I('agentname'));
        $this->assign('t_settlelog', $T_Settlelog);
        $this->page = $page->show();
        $this->count = $count;
        $this->assign('settlelog_report_active', 'active');
        $this->assign('report_active', 'active');
        $this->display('Index/SettleMemberReport');
    }
}