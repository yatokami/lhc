<?php
namespace Admin\Controller;
//use Think\Controller;
class ReturnMoneyReportController extends BaseAuthController {



    //会员统计报表
    public function rmreport() {
        $stime = date("Y-m-d H:i:s", strtotime("-1 week"));
        $etime = date("Y-m-d H:i:s");
        if(I('get.stime')) {
            $stime = I('get.stime');
            $etime = I('get.etime');
        }

        $stime1 = strtotime($stime);
        $etime1 = strtotime($etime);
        $map["t_bet.BetTime"] = array(array('EGT', $stime1), array('ELT', $etime1));

        if(I('get.agentname')) {
            $map["t_user.UserName"] = I('agentname');
        }
        $where1 = "";
        $map["t_bet.BetState"] = 1;

        $map["t_user.AgentId"] = session('AgentId');
        $agentid = session('AgentId');

        $t_bet = M('t_bet');
        $count = $t_bet
                ->field('count(*)')
                ->join('LEFT JOIN t_user ON t_user.UserId = t_bet.UserId')
                ->where($map)
                ->group('t_user.UserId')
                ->select();
        $count = count($count);

        $page = new \Think\TimePageBootcss($count, 10);
        $limit = $page->firstRow.','.$page->listRows;

        $t_betlist = $t_bet
                    ->field('t_user.UserName, t_user.SumMoney, t_user.Money, -sum(t_bet.Profit) as sprofit, sum(BetMoney) as sbetmoney, t_user.fs, FORMAT((sum(BetMoney)*fs/100),2) as fsmoney, FORMAT((-sum(t_bet.Profit)-(sum(BetMoney)*fs/100)), 2) as income, t_user.zc, FORMAT(((-sum(t_bet.Profit)-(sum(BetMoney)*fs/100))*zc/100),2) as zcmoney, FORMAT(IFNULL(t_operatelog1.omoney, 0),2) as omoney')
                    ->join('LEFT JOIN t_user ON t_user.UserId = t_bet.UserId')
                    ->join("LEFT JOIN (select sum(OperateMoney) as omoney, OperateUserId from t_operatelog where t_operatelog.OperateType = 0 and t_operatelog.OperateType = '充值会员' and (Time >= '$stime1' and Time <= '$etime1')  GROUP BY OperateUserName) as t_operatelog1 on t_operatelog1.OperateUserId = t_user.UserId")
                    ->where($map)
                    ->limit($limit)
                    ->group('t_user.UserId')
                    ->select();

        $Model = new \Think\Model(); // 实例化一个model对象 没有对应任何数据表
        $st_betlist = $Model->query("select -sum(sprofit) as ssprofit, sum(sbetmoney) as ssbetmoney, FORMAT(sum(fsmoney),2) as sfsmoney, FORMAT(sum(income),2) as sincome, FORMAT(sum(zcmoney),2) as szcmoney from (SELECT t_user.UserName,t_user.SumMoney,t_user.Money,sum(t_bet.Profit) as sprofit,sum(BetMoney) as sbetmoney, t_user.fs, (sum(BetMoney)*fs/100) as fsmoney,(-sum(t_bet.Profit)-(sum(BetMoney)*fs/100)) as income,t_user.zc,((-sum(t_bet.Profit)-(sum(BetMoney)*fs/100))*zc/100) as zcmoney,IFNULL(t_operatelog1.omoney,0) as omoney FROM `t_bet` LEFT JOIN t_user ON t_user.UserId = t_bet.UserId LEFT JOIN (select sum(OperateMoney) as omoney, OperateUserId from t_operatelog where t_operatelog.OperateType = 0 and t_operatelog.OperateType = '充值会员' and (Time >= '$stime1' and Time <= '$etime1') GROUP BY OperateUserName) as t_operatelog1 on t_operatelog1.OperateUserId = t_user.UserId WHERE ( t_bet.BetTime >= '$stime1' AND t_bet.BetTime <= '$etime1' ) AND t_bet.BetState = 1 AND t_user.AgentId = $agentid GROUP BY t_user.UserId) A");

        $this->assign('st_betlist', $st_betlist[0]);
        $this->assign('stime', $stime);
        $this->assign('etime', $etime);
        $this->assign('user_name', I('agentname'));
        $this->assign('t_betlist', $t_betlist);
        $this->page = $page->show();
        $this->count = $count;
        $this->assign('returnmoney_report_active', 'active');
        $this->assign('report_active', 'active');
        $this->display('Index/ReturnMoneyReport');
    }

    //代理统计报表
    public function srmreport() {
        $stime = date("Y-m-d H:i:s", strtotime("-1 week"));
        $etime = date("Y-m-d H:i:s");
        if(I('get.stime')) {
            $stime = I('get.stime');
            $etime = I('get.etime');
        }

        $stime1 = strtotime($stime);
        $etime1 = strtotime($etime);
        $map["t_bet.BetTime"] = array(array('EGT', $stime1), array('ELT', $etime1));

        if(I('get.agentname')) {
            $map["t_agent.AgentName"] = I('agentname');
        }

        $map["t_bet.BetState"] = 1;
        $t_bet = M('t_bet');
        $count = $t_bet
                ->field('count(*)')
                ->join('LEFT JOIN t_user ON t_user.UserId = t_bet.UserId')
                ->join('LEFT JOIN t_agent ON t_agent.AgentId = t_user.AgentId')
                ->where($map)
                ->group('t_user.AgentId')
                ->select();
        $count = count($count);

        $page = new \Think\TimePageBootcss($count, 10);
        $limit = $page->firstRow.','.$page->listRows;


        $Model = new \Think\Model(); // 实例化一个model对象 没有对应任何数据表
        $t_betlist = $Model->query("select A.AgentName,A.SumMoney,A.Money,FORMAT(-sum(sprofit),2) as ssprofit,FORMAT(sum(sbetmoney),2) as ssbetmoney, FORMAT(sum(fsmoney),2) as sfsmoney, FORMAT(sum(income),2) as sincome, 
FORMAT(sum(zcmoney),2) 
as szcmoney, FORMAT(sum(omoney),2) as somoney from (SELECT t_agent.AgentName,t_agent.SumMoney,t_agent.Money,sum(t_bet.Profit) as sprofit,sum(BetMoney) as sbetmoney,
t_user.fs,(sum(BetMoney)*fs/100) as fsmoney,(-sum(t_bet.Profit)-(sum(BetMoney)*fs/100)) as income,
t_user.zc,((-sum(t_bet.Profit)-(sum(BetMoney)*fs/100))*zc/100) as zcmoney,IFNULL(t_operatelog1.omoney,0) as omoney FROM `t_bet` 
LEFT JOIN t_user ON t_user.UserId = t_bet.UserId left JOIN t_agent ON t_user.AgentId = t_agent.AgentId
LEFT JOIN (select sum(OperateMoney) as omoney, OperateUserId from t_operatelog  
where t_operatelog.OperateType = 0 and t_operatelog.OperateType = '充值会员' and (Time >= '$stime1' and Time <= '$etime1') 
GROUP BY OperateUserName) as t_operatelog1 
on t_operatelog1.OperateUserId = t_user.UserId 
WHERE ( t_bet.BetTime >= '$stime1' AND t_bet.BetTime <= '$etime1' ) 
AND t_bet.BetState = 1  
GROUP BY t_user.UserId) A
GROUP BY A.AgentName
limit $limit");


         $st_betlist = $Model->query("select FORMAT(-sum(ssprofit),2) as sssprofit,FORMAT(sum(ssbetmoney),2) as sssbetmoney, FORMAT(sum(sfsmoney),2) as ssfsmoney, FORMAT(sum(sincome),2) as ssincome, 
FORMAT(sum(szcmoney),2) 
as sszcmoney from (select A.AgentName,A.SumMoney,A.Money,sum(sprofit) as ssprofit,sum(sbetmoney) as ssbetmoney, sum(fsmoney) as sfsmoney, sum(income) as sincome, 
sum(zcmoney)
as szcmoney, FORMAT(sum(omoney),2) as somoney from (SELECT t_agent.AgentName,t_agent.SumMoney,t_agent.Money,sum(t_bet.Profit) as sprofit,sum(BetMoney) as sbetmoney,
t_user.fs,(sum(BetMoney)*fs/100) as fsmoney,(-sum(t_bet.Profit)-(sum(BetMoney)*fs/100)) as income,
t_user.zc,((-sum(t_bet.Profit)-(sum(BetMoney)*fs/100))*zc/100) as zcmoney,IFNULL(t_operatelog1.omoney,0) as omoney FROM `t_bet` 
LEFT JOIN t_user ON t_user.UserId = t_bet.UserId left JOIN t_agent ON t_user.AgentId = t_agent.AgentId
LEFT JOIN (select sum(OperateMoney) as omoney, OperateUserId from t_operatelog  
where t_operatelog.OperateType = 0 and t_operatelog.OperateType = '充值会员' and (Time >= '$stime1' and Time <= '$etime1') 
GROUP BY OperateUserName) as t_operatelog1 
on t_operatelog1.OperateUserId = t_user.UserId 
WHERE ( t_bet.BetTime >= '$stime1' AND t_bet.BetTime <= '$etime1' ) 
AND t_bet.BetState = 1  
GROUP BY t_user.UserId) A
GROUP BY A.AgentName
) B");

        $this->assign('st_betlist', $st_betlist[0]);
        $this->assign('stime', $stime);
        $this->assign('etime', $etime);
        $this->assign('user_name', I('agentname'));
        $this->assign('t_betlist', $t_betlist);
        $this->page = $page->show();
        $this->count = $count;
        $this->assign('returnmoney_report_active', 'active');
        $this->assign('report_active', 'active');
        $this->display('Index/SReturnMoneyReport');
    }
}