<?php
namespace Home\Controller;
use Think\Controller;
class UserBetController extends BaseController {
    public function index(){
        $this->display();
    }

    //投注接口返回：状态值|提示信息 例如：0|投注失败 -1：错误信息 1：成功
    public function bet(){

        $Issue = I('post.issue','','htmlspecialchars');//期号
        $BetTime = time();
    	$BetType = I('post.bettype','','htmlspecialchars');//类型
        $BetNumber = split(',',I('post.betnumber','','htmlspecialchars'));//号码
        $BetMoney = (int)I('post.betmoney','','htmlspecialchars');//金额
        $peilv = I('post.peilv','','htmlspecialchars');//赔率
        $tze = (int)I('post.tze','','htmlspecialchars');//投注额
        $t_user = M('t_user');
        $map['UserId'] = session('user_id');

        $BetBeginMoney = $t_user->where($map)->getField('Money');
        $config_map['ConfigName'] = $BetType;//投注类型:1,PMSX:平码生肖2,TMHM:特码号码3，TMBS：特码波色4，TMDX：特码大小5，TMDS：特码单双6，JQYS：家禽野兽
        $BetOdds = M('t_config')->where($config_map)->getField('ConfigValue');
        $BetState = 0;//1:已结算0:未结算

        $config_map['ConfigName'] = 'M_'.$BetType;
        $BetMinMoney = (int)M('t_config')->where($config_map)->getField('ConfigValue');//最小投注额度

        if($BetMinMoney==$tze){
        	if($BetOdds==$peilv){
        		//判断金额是否大于或等于最小投注额度
		        if($BetMoney >= $BetMinMoney) {
		        	//判断余额是否大于或等于下注额度
		        	if($BetMoney <= $BetBeginMoney) {
		        		//判断期号是否能下注，判断该期号状态1：可下注，2：已封盘，3，已结算
		        		$history_map['Issue'] = $Issue;
		        		$history_map['State'] = 1;

		        		$count = (int)M('t_history')->where($history_map)->count();
		        		$num = 0;//成功条数
		        		if($count == 1){
		        			for($i = 0; $i < count($BetNumber); $i++){
		        				$adddata['Issue']=$Issue;
		        				$adddata['UserId']=session('user_id');
		        				$adddata['UserName']=session('user_name');
		        				$adddata['BetTime']=$BetTime;
		        				$adddata['BetType']=$BetType;
		        				$number='';
		                        $data = array('鼠','牛','虎','兔','龙','蛇','马','羊','猴','鸡','狗','猪');
		        				switch ($BetType) {
		        					case 'PMSX':
		    							$number=$data[(int)$BetNumber[$i]-1];
		    							break;
		    						case 'TMHM':
		    							$number=(int)$BetNumber[$i];
		    							break;
		    						case 'TMBS':
		    							$number=$BetNumber[$i];
		    							break;
		    						case 'TMDX':
		    							$number=$BetNumber[$i]=='big'?'1':'0';
		    							break;
		    						case 'TMDS':
		    							$number=$BetNumber[$i]=='odd'?'1':'0';
		    							break;
		    						case 'JQYS':
		    							$number=$BetNumber[$i]=='jq'?'jq':'ys';
		    							break;

		        					default:
		        						$number='null';
		        						break;
		        				}
		        				// if($number==''||$number=='0'||$number==0){
		        				// 	continue;
		        				// }
		        				$adddata['BetNumber']=$number;
		        				$adddata['BetMoney']=$BetMoney;
		        				$adddata['BetBeginMoney']=$BetBeginMoney;
		        				$adddata['WinMoney']=$BetMoney*(double)$BetOdds;
		        				$adddata['BetOdds']=$BetOdds;
		        				$adddata['BetState']=$BetState;
		        				$n=M('t_bet')->add($adddata);
		        				if ($n > 0) {
		        					$num++;
		        				}
		        			}
		        			if ($num > 0) {
		        				$savedata['Money']=$BetBeginMoney-$BetMoney*$num;
		                		$result = $t_user->where($map)->save($savedata);

		                		$user_money = $t_user->where($map)->getfield('Money');
		        				echo "1|下注成功！共成功".$num."条|$user_money";//下注成功
		        			}
		        			else{
		        				echo "0|下注失败！";//下注成功
		        			}
		        		}
		        		else{
		        			echo "-1|抱歉，该期号已经封盘，无法下注";//期号已封盘
		        		}
		        		
		        	}
		        	else{
		        		echo "-1|抱歉，下注金额不能大于您的余额".$BetBeginMoney;//下注金额大于自身余额
		        	}
		        }
		        else{
		        	echo "-1|抱歉，下注金额不能小于".$BetMinMoney;//下注金额小于最小投注额度
		        }
        	}
        	else{
        		echo "-2|抱歉，与服务器赔率不同";//与服务器赔率不同
        	}
        }
        else{
        	echo "-3|抱歉，与服务器最小投注额不同";//与服务器最小投注额不同
        }
        

    }
}