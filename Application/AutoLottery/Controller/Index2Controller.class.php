<?php
namespace AutoLottery\Controller;
use Think\Controller;
class Index2Controller extends Controller {


	public function auto_compltion() {
		$minute  = (int)date("i");
    	$h = (int)date("H");
    	$bool = true;

    	$check_Issue = date("Ymd");

    	//自动生成当天1-103期开奖
    	for ($i = 1; $i <= 103 ; $i++) {

    		//生成期号
    		$newNumber = substr(strval($i + 1000),1,3);
			$adddata["Issue"] = date("Ymd").$newNumber;
			$adddata["State"] = 0;

			$count = M('t_history')->where(['Issue' => $adddata["Issue"]])->count();
			//如果已经有该期号则不添加
			if($count == 0) {
				M('t_history')->add($adddata);
                echo "生成".$adddata["Issue"]."期成功<br>";
			} else {
                echo "第".$adddata["Issue"]."期已存在<br>";
            }
    	}

        $t_history = M('t_history')->where(['State' => 2])->select();

        for ($t = 0; $t < count($t_history); $t++) { 
            $t_bet = M('t_bet')->where(['Issue' => $t_history[$t]["issue"], 'BetState' => 0])->select();
            $success_count = 0;
            $error_count = 0;
            $sum_profitorloss = 0;
            //循环注单
            foreach ($t_bet as $key => $value) {
                $win_money = 0;
                //计算该注单输赢
                $win_money = autoCalculation($value, $t_history[$t], $win_money);

                if($win_money > 0) {
                    $savedata1["IsWin"] = 1;
                    $savedata1["Profitorloss"] = (float)$value["winmoney"];
                    $savedata1["Profit"] = (float)$value["winmoney"] - (float)$value["betmoney"];
                    $money = (float)$value["winmoney"];
                } else if($win_money < 0) {
                    $savedata1["IsWin"] = 0;
                    $savedata1["Profitorloss"] = 0;
                    $money = (float)$value["betmoney"];
                    $savedata1["Profit"] = 0;
                } else {
                    $savedata1["Profitorloss"] = -(float)$value["betmoney"];
                    $savedata1["IsWin"] = -1;
                    $savedata1["Profit"] =  -(float)$value["betmoney"];
                    $money = 0;
                }

                //更新注单
                $savedata1["BetState"] = 1;
                $res = M('t_bet')->where(["Id" => $value["id"]])->save($savedata1);
                M('t_user')->where(["UserId" => $value["userid"]])->setInc('Money', $money);

                M('t_user')->where(["UserId" => $value["userid"]])->setInc('Total', $savedata1["Profit"]);
                $AgentId = M('t_user')
                        ->where(["UserId" => $value["userid"]])
                        ->getfield('AgentId');
                
                M('t_agent')->where(["AgentId" => $AgentId])->setInc('Total', $savedata1["Profit"]);
                //if(true) {
                if($res) { 
                    $sum_profitorloss += -(float)$savedata1["Profit"];
                    $success_count++;
                } else {
                    $error_count++;
                }
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
            M('t_history')->where(['Issue' => $t_history[$t]["issue"]])->save(["State" => 3]);
        }

    	//判断3点以前启动补全器
    	$issue_End = intval($minute/10);
    	if($h < 3) {
    		//获取当前应该进行期号
    		$num = 6 * $h + $issue_End + 1;
    		for ($i = 1; $i <= $num; $i++) { 
				$newNumber = substr(strval($i + 1000),1,3);
    			$savedata["Issue"] = date("Ymd").$newNumber;
    			//如果到当前期的封盘期间，将当前期状态变为2，后一期的装填变为1
    			//如果到当前期正在下注时间，将当前期状态变为1
    			//当前期之前的期号状态都变为3
                $map2["Issue"] = $savedata["Issue"];
                $result = auto($savedata["Issue"]);
    			if($i == $num && ($minute%10) >= 7) {
    				$savedata["State"] = 2;
    				M('t_history')->where(['Issue' => ((float)$savedata["Issue"] + 1)])->save(['State' => 1]);
                    
                    M('t_history')->where($map2)->save($result);
    			} else if($i == $num && ($minute%10) < 7){
					$savedata["State"] = 1;
    			} else {
    				$savedata["State"] = 3;
                    M('t_history')->where($map2)->save($result);
    			}
    			$where['Issue'] = $savedata["Issue"];
    			$where['State'] = array("in", "0,1");
    			$count = M('t_history')->where($where)->count();
    			if($count > 0) {
    				M('t_history')->where(['Issue' => $savedata["Issue"]])->save(['State' => $savedata["State"]]);
    			}
    		}	
    	}
    	//3点到9点期间
    	if($h >= 3 && $h <= 9) {
    		for ($j = 1; $j <= 19; $j++) { 
    			$newNumber = substr(strval($j + 1000),1,3);
    			$savedata["Issue"] = date("Ymd").$newNumber;
                $map2["Issue"] = $savedata["Issue"];
    			//如果到9点57的封盘期间，将当前期状态变为2，后一期的装填变为1
    			//如果到当前期正在下注时间，将当前期状态变为1
    			//当前期之前的期号状态都变为3
                $result = auto($savedata["Issue"]);
    			if($j == 19 && $minute >= 57 && $h == 9) {
    				$savedata["State"] = 2;
    				M('t_history')->where(['Issue' => ((float)$savedata["Issue"] + 1)])->save(['State' => 1]);
                    M('t_history')->where($map2)->save($result);
    			} else if($j == 19) {
    				$savedata["State"] = 1;
    			} else {
    				$savedata["State"] = 3;
                    M('t_history')->where($map2)->save($result);
    			}
    			$where['Issue'] = $savedata["Issue"];
    			$where['State'] = array("in", "0,1");
    			$count = M('t_history')->where($where)->count();
    			if($count > 0) {
    				M('t_history')->where(['Issue' => $savedata["Issue"]])->save(['State' => $savedata["State"]]);
    			}
    		}
    	}

        //9点至23点启动补全功能
        if($h > 9 && $h <=23) {
            $temp = $h - 10;
            $num = 6 * $temp + $issue_End + 20;
            for ($k = 1; $k <= $num; $k++) { 
                $newNumber = substr(strval($k + 1000),1,3);
                $savedata["Issue"] = date("Ymd").$newNumber;
                $map2["Issue"] = $savedata["Issue"];
                $result = auto($savedata["Issue"]);
                if($k <= 20) {
                    $savedata["State"] = 3;
                    M('t_history')->where($map2)->save($result);
                } else {
                    if($k == $num && ($minute%10) >= 7) {
                        $savedata["State"] = 2;
                        if($num != 103) {
                            M('t_history')->where(['Issue' => ((float)$savedata["Issue"] + 1)])->save(['State' => 1]);  
                        }
                        M('t_history')->where($map2)->save($result);
                    } else if($k == $num) {
                        $savedata["State"] = 1;
                    } else {
                        $savedata["State"] = 3;
                        M('t_history')->where($map2)->save($result);
                    }
                }
                $where['Issue'] = $savedata["Issue"];
                $where['State'] = array("in", "0,1");
                $count = M('t_history')->where($where)->count();
                if($count > 0) {
                    M('t_history')->where(['Issue' => $savedata["Issue"]])->save(['State' => $savedata["State"]]);
                }
            }
        }



	}
}