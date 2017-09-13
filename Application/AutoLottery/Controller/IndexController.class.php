<?php
namespace AutoLottery\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	redirect('/SelectLottery/index');
    }

    public function auto_six() {
    	$minute  = date("i");
    	$h = date("H");
    	$bool = true;
    	if($h >= 3 && $h < 10) {
    		$bool = false;
    		if($h == 9 && $minute >= 57) {
    			$bool = true;
    		}
    	}
    	//if(true) {
    	if(((int)$minute%10) >= 7 && $bool) {
    		$t_history = M('t_history')->order('id desc')->where(['State' => 1])->limit(1)->field('Issue, IsSuperterminal, IsConfirm, State')->find();
    		$issue = (float)$t_history["issue"];
    		//判断本期开奖是否已经开出
    		//if(true) {
    		if(!session("is_auto")) {
		    	$map["Issue"] = $issue;
		    	$map["Nullity"] = 0;
		        $t_bet = M('t_bet')->where($map)->select();

		        $result = auto($issue);

		        $t_config = M('t_config')->select();
		        $win_money = 0;
				
				//拆分中奖动物和号码
		        $nums = split(":", $result["Num"]);
		        $pmsx = array_splice(split("--", $nums[0]), 0, 7);
		        $tmsx = split("--", $nums[1])[6];
		        $profit = 0;
		        for ($i = 0; $i < count($t_bet); $i++) { 
		        	//控制奖池
		        	$profit += controlMoney($t_bet[$i]["betnumber"], $pmsx, $tmsx, $result, $t_bet[$i]);
		        }

		        $profit = -$profit;
		        $p_bool = false;
		        //玩家输赢
		        if($profit >= 0) {
		        	$p_bool = true;
		        } else {
		        	$temp = (float)$t_config[14]["configvalue"] + (float)$profit;
		        	if($temp > 0) {
		        		$p_bool = true;
		        	}
		        }
		        
		        //保证奖池够付款
		        if($p_bool) {
		        	$map2["Issue"] = $issue;
		        	//$result["IsConfirm"] = 1;
		        	//判断是否超端设置
		        	if($t_history["issuperterminal"] == 0) {
			        	M('t_history')->where($map2)->save($result);
		        	} 
		        	
		    		M('t_history')->where("Issue=".$issue)->save(['State' => 2]);
		    		$savedata["Issue"] = $issue + 1;
		    		if((int)$h == 23 && $minute >= 57) {
		  				for ($i = 1; $i <= 103; $i++) { 
    						$newNumber = substr(strval($i+1000),1,3);
		  					$adddata["Issue"] = date("Ymd",strtotime("+1 day")).$newNumber;
		  					$adddata["State"] = 0; 
		  					$count = M('t_history')->where(['Issue' => $adddata["Issue"]])->count();
		  					if($count == 0) {
								M('t_history')->add($adddata);
							}
		    				if($i == 1) {
			    				$savedata["Issue"] = $adddata["Issue"];
		    				}
		  				}

		    		}
		    		
		    		M('t_history')->where("Issue=".$savedata["Issue"])->save(['State' => 1]);

		        	session("is_auto", true);
		        	redirect('/Index/auto_six',2, '开奖成功。。。。。');
		        } else {
		        	redirect('/Index/auto_six',2, '重置开奖中。。。。。');
		        }
		    } else {
		    	redirect('/Index/auto_six',2, '已经开奖。。。。。');
		    }
    	} else {
    		session("is_auto", false); 
    		redirect('/Index/auto_six',2, '尚未到封盘时间。。。。。');
    	}     	
    }

    //自动结算
    public function auto_account() {

    	$minute  = date("i");
    	$h = date("H");
    	$second = date("s");
    	$bool = true;
    	if($h > 3 && $h < 10) {
    		$bool = false;
    	}
    	//if(true) {
        if((((int)$minute%10) < 1 && (int)$second >= 56) && $bool) {
	    	$t_history = M('t_history')->where(['State' => '2'])->find();
	    	$t_bet = M('t_bet')->where(['Issue' => $t_history["issue"], 'BetState' => 0])->select();
	    	$success_count = 0;
	    	$error_count = 0;
	    	$sum_profitorloss = 0;
	    	//循环注单
	    	foreach ($t_bet as $key => $value) {
	    		$win_money = 0;
	    		//计算该注单输赢
	    		$win_money = autoCalculation($value, $t_history, $win_money);

	    		if($win_money > 0) {
	    			$savedata["IsWin"] = 1;
	    			$savedata["Profitorloss"] = (float)$value["winmoney"];
	    			$savedata["Profit"] = (float)$value["winmoney"] - (float)$value["betmoney"];
	    			$money = (float)$value["winmoney"];
	    		} else if($win_money < 0) {
	    			$savedata["IsWin"] = 0;
	    			$savedata["Profitorloss"] = 0;
	    			$money = (float)$value["betmoney"];
	    			$savedata["Profit"] = 0;
	    		} else {
	    			$savedata["Profitorloss"] = -(float)$value["betmoney"];
	    			$savedata["IsWin"] = -1;
	    			$savedata["Profit"] =  -(float)$value["betmoney"];
	    			$money = 0;
	    		}

	    		//更新注单
				$savedata["BetState"] = 1;
				$res = M('t_bet')->where(["Id" => $value["id"]])->save($savedata);
				M('t_user')->where(["UserId" => $value["userid"]])->setInc('Money', $money);

				M('t_user')->where(["UserId" => $value["userid"]])->setInc('Total', $savedata["Profit"]);
				$AgentId = M('t_user')
						->where(["UserId" => $value["userid"]])
						->getfield('AgentId');
				
				M('t_agent')->where(["AgentId" => $AgentId])->setInc('Total', $savedata["Profit"]);
				//if(true) {
				if($res) { 
					$sum_profitorloss += -(float)$savedata["Profit"];
	                $success_count++;
	            } else {
	                $error_count++;
	            }


	    	}

            if($error_count > 0) {
            	$t_betlog["Issue"] = $t_history["issue"];
            	$issue_log = $t_history['issue'];
                $t_betlog["Log"] = "第 $issue_log 期, 失败了 $error_count 条";
            	M('t_betlog')->add($t_betlog);
            }
	    	if($success_count > 0) {
				//获取奖池并且开始计算奖池增减
	    		$t_config = M('t_config')->select();
	    		$Jackpot = $t_config[14]['configvalue'];
	    		$Scale = (float)$t_config[15]['configvalue'];
	    		$temp1 = 0;
	    		if($sum_profitorloss > 0) {
	    			$temp1 = $sum_profitorloss * (float)$Scale / 100;
	    			$sum_profitorloss = $sum_profitorloss - $temp1;
	    		}
	    		$config['ConfigValue'] =  (float)$Jackpot + (float)($sum_profitorloss);
	    		M('t_config')->where(['Id' => 15])->save($config);

	    		//抽水
	    		if($temp1 > 0) {
	    			M('t_agent')->where(['AgentId' => 1])->setInc('Money', $temp1);
	    		}


	    		$autolog["Issue"] = $t_history['issue'];
	    		$autolog["SettleCount"] = $success_count;
	    		$autolog["Jackpot"] = $Jackpot;
	    		$autolog["Profitorloss"] = $sum_profitorloss;
	    		$autolog["Jackpoted"] = $config['ConfigValue'];
	    		$autolog["Temp"] = $temp1;
	    		$autolog["Scale"] = $Scale;
	    		M('t_autolog')->add($autolog);
	    		
    		}

    		$count1 = M('t_bet')->where(['Issue' => $t_history["issue"], 'BetState' => 0])->count();


    		//if(true) {
	    	if((((int)$minute%10) == 1 && ((int)$second > 4)) || ($count == 0)) {
				M('t_history')->where(["State" => 2])->save(["State" => 3]);
				redirect('/Index/auto_account', 2, '停止结算。。。。。');
			}	
			redirect('/Index/auto_account', 2, "正在结算中。。。。。成功结算了$success_count 条，失败了 $error_count 条");
    	} else {
    		redirect('/Index/auto_account', 2, "尚未到结算时间。。。。。");
    	}
    	//dump($t_bet);
    }



}