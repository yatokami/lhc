<?php
namespace Admin\Controller;
//use Think\Controller;
class TakeReportController extends BaseAuthController {

    //代理提现记录
    public function proxy(){
        if(is_auth()) {
            $stime = date("Y-m-d H:i:s", strtotime("-1 week"));
            $etime = date("Y-m-d H:i:s");
            if(I('get.stime')) {
                $stime = I('get.stime');
                $etime = I('get.etime');
            }

            $map["t_takemoneylog.Time"] = array(array('EGT', strtotime($stime)), array('ELT', strtotime("$etime")));
            if(I('get.agentname')) {
                $map["t_agent.AgentName"] = I('agentname');
            }
            $map["t_takemoneylog.Type"] = 1;
            $t_takemoneylog = M('t_takemoneylog');
            if(session('AgentId') == 1 || session('AgentId') == 2) {
                //总代理查看日志
                $count = $t_takemoneylog
                        ->join('LEFT JOIN t_agent ON t_agent.AgentId = t_takemoneylog.UserId')
                        ->where($map)
                        ->count();

                $page = new \Think\TimePageBootcss($count, 10);
                $limit = $page->firstRow.','.$page->listRows;

                $t_takemoneylog = $t_takemoneylog
                        ->join('LEFT JOIN t_agent ON t_agent.AgentId = t_takemoneylog.UserId')
                        ->field('t_takemoneylog.UserId, t_agent.AgentName, t_agent.SumMoney, t_agent.Money, t_takemoneylog.TakeMoney, t_takemoneylog.time')
                        ->order('t_takemoneylog.Time desc')
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
            $this->assign('t_takemoneylog', $t_takemoneylog);
            $this->page = $page->show();
            $this->count = $count;
            $this->assign('takemoney_report_active', 'active');
            $this->assign('report_active', 'active');
            $this->display('Index/TakeProxyReport');
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

        $map["t_takemoneylog.Time"] = array(array('EGT', strtotime($stime)), array('ELT', strtotime("$etime")));
        if(I('get.agentname')) {
            $map["t_takemoneylog.UserName"] = I('agentname');
        }

        $map["t_takemoneylog.Type"] = 0;
        $map["t_user.AgentId"] = session('AgentId');
        $t_takemoneylog = M('t_takemoneylog');
        //查看会员提现

        $count = $t_takemoneylog
                    ->join('LEFT JOIN t_user ON t_user.UserId = t_takemoneylog.UserId')
                    ->where($map)
                    ->count();

        $page = new \Think\TimePageBootcss($count, 10);
        $limit = $page->firstRow.','.$page->listRows;

        $t_takemoneylog = $t_takemoneylog
                ->join('LEFT JOIN t_user ON t_user.UserId = t_takemoneylog.UserId')
                ->field('t_takemoneylog.UserId, t_user.UserName, t_user.SumMoney, t_user.Money, t_takemoneylog.TakeMoney, t_takemoneylog.time')
                ->order('t_takemoneylog.Time desc')
                ->where($map)
                ->limit($limit)
                ->select();
        
        $this->assign('stime', $stime);
        $this->assign('etime', $etime);
        $this->assign('user_name', I('agentname'));
        $this->assign('t_takemoneylog', $t_takemoneylog);
        $this->page = $page->show();
        $this->count = $count;
        $this->assign('takemoney_report_active', 'active');
        $this->assign('report_active', 'active');
        $this->display('Index/TakeMemberReport');
    }
}