<?php
namespace Admin\Model;
use Think\Model;
/**
 * 注单服务类
 */
class BetModel extends BaseModel{
	protected $trueTableName = 't_bet'; //数据库名.表名(包含了前缀) 

	//分页查看
	public function pagerow($limit, $agentId, $map) {
		$T_BetList = M('t_bet')
                ->field('Issue, t_user.UserId, t_user.UserName, BetTime, BetType, BetNumber, BetMoney, Profitorloss, BetOdds, t_agent.AgentName, Profit')
                ->join('LEFT JOIN t_user ON t_user.UserId = t_bet.UserId')
                ->join('INNER JOIN t_agent ON t_user.AgentId=t_agent.AgentId')
                ->where(["t_user.AgentId" => $agentId])
                ->where($map)
                ->order('BetTime desc')
                ->limit($limit)
                ->select();

                return $T_BetList;
	}

        //获取总投注额，总输赢，抽水金额
        public function betinfo($map) {
                $t_betinfo = M('t_bet')
                        ->field('sum(BetMoney) as Sum_Bet_Money, sum(Profit) as SumProfit, sum(BetMoney*fs/100) as sfs')
                        ->join('LEFT JOIN t_user ON t_user.UserId = t_bet.UserId')
                        ->join('INNER JOIN t_agent ON t_user.AgentId=t_agent.AgentId')
                        ->where($map)
                        ->group('t_agent.AgentName')
                        ->find();
                return $t_betinfo;
        }
}