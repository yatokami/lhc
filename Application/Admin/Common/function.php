<?php 



function temp_money($map1, $parentId) {
    //代理下所有会员额度
    $other_summoney = M('t_user')
                    ->where($map1)
                    ->sum('SumMoney');
    //代理的额度
    $agent_summoney = M('t_agent')
                    ->where(["AgentId" => $parentId])
                    ->sum('SumMoney');

    //比较总代理剩下的额度是否足够分配
    $temp1 = (float)$agent_summoney - (float)$other_summoney;
    return $temp1;
}

function is_admin_login() {
	return session("admin_name");
}

function is_auth() {
    if(session("Auth") == "0") {
        return true;
    } else {
        return false;
    }
}

function is_yunyin() {
    if(session("AgentId") == "2") {
        return false;
    } else {
        return true;
    }
}

function getarray_random1($arrs) {
	$arr[0] = [1,13,25,37,49];
    $arr[1] = [2,14,26,38];
    $arr[2] = [3,15,27,39];
    $arr[3] = [4,16,28,40];
    $arr[4] = [5,17,29,41];
    $arr[5] = [6,18,30,42];
    $arr[6] = [7,19,31,43];
    $arr[7] = [8,20,32,44];
    $arr[8] = [9,21,33,45];
    $arr[9] = [10,22,34,46];
    $arr[10] = [11,23,35,47];
    $arr[11] = [12,24,36,48];

    //随机在12个数组中的7个数组任意获取一个数
	for ($i = 0; $i < count($arrs); $i++) { 
		$key = array_rand($arr[$arrs[$i]], 1);
		$arrs[$i] = $arr[$arrs[$i]][$key];
	}

	return $arrs;
}



//生成超端
function super_auto($Issue, $Animal, $result, $tmhm) {
        $result = getarray_random1($result);

        $adddata["Issue"] = $Issue;
        $new_issue = (float)$adddata["Issue"];

        $adddata["Time"] = time();
        //中文生肖
        $cnNum = "";
        //数字生肖
        $strNum = "";

    	//循环遍历生肖代号，
        for($i = 0; $i < count($result); $i++) {
        	$key = $result[$i]%12;
        	$strNum .= ((int)$result[$i] >= 10?$result[$i]:"0".$result[$i])."--";
        	switch ($key) {
        		case 0:
        			$cnNum .= $Animal[11]."--";
        			break;
        		case 1:
        			$cnNum .= $Animal[0]."--";
        			break;
        		case 2:
        			$cnNum .= $Animal[1]."--";
        			break;
        		case 3:
        			$cnNum .= $Animal[2]."--";
        			break;
        		case 4:
        			$cnNum .= $Animal[3]."--";
        			break;
        		case 5:
        			$cnNum .= $Animal[4]."--";
        			break;
        		case 6:
        			$cnNum .= $Animal[5]."--";
        			break;
        		case 7:
        			$cnNum .= $Animal[6]."--";
        			break;
        		case 8:
        			$cnNum .= $Animal[7]."--";
        			break;
        		case 9:
        			$cnNum .= $Animal[8]."--";
        			break;
        		case 10:
        			$cnNum .= $Animal[9]."--";
        			break;
        		case 11:
        			$cnNum .= $Animal[10]."--";
        			break;
        		default:
        			# code...
        			break;
        	}
        }

        if((int)$tmhm < 10) {
            $tmhm = "0".$tmhm;
        }
        //去除末尾多余字符--
        $cnNum = $cnNum.$Animal[($tmhm%12==0?12:$tmhm%12)-1];
        $strNum = $strNum.$tmhm;

        $adddata["Num"] = $cnNum.":".$strNum;
        $adddata["TNumUrl"] = $tmhm;
        $adddata["TAnimalUrl"] = $Animal[($tmhm%12==0?12:$tmhm%12)-1];
        $adddata["TColor"] = getColor($adddata["TNumUrl"]);
        $adddata["TAnimalTypeUrl"] = getAnimaType($adddata["TAnimalUrl"]);

        //判断是否大小和单双
        if($adddata["TNumUrl"] >= 25 && $adddata["TNumUrl"]  <= 48) {
        	$adddata["TBigUrl"] = 1;
        }
        if($adddata["TNumUrl"]  <=24 && $adddata["TNumUrl"]  >= 1) {
        	$adddata["TBigUrl"] = 0;
        }
        if($adddata["TNumUrl"] %2 == 0) {
        	$adddata["TDanshuangUrl"] = 0;
        } else {
        	$adddata["TDanshuangUrl"] = 1;
        }

        if($adddata["TNumUrl"]  == 49) {
        	$adddata["TBigUrl"] = -1;
        	$adddata["TDanshuangUrl"] = -1;
        }
        
        $adddata["IsSuperterminal"] = 1;
        return $adddata;
}

//转换下注类型
function conversion_type($t_bet) {
    if($t_bet["bettype"] == "TMBS") {
        $t_bet["bettype"] = "特码波色";
        if($t_bet["betnumber"] == "red") $t_bet["betnumber"] = "红";
        if($t_bet["betnumber"] == "blue") $t_bet["betnumber"] = "蓝";
        if($t_bet["betnumber"] == "green") $t_bet["betnumber"] = "绿";
    }
    if($t_bet["bettype"] == "TMDX") {
        $t_bet["bettype"] = "特码大小";
        if($t_bet["betnumber"] == "1") $t_bet["betnumber"] = "大";
        if($t_bet["betnumber"] == "0") $t_bet["betnumber"] = "小";
    }
    if($t_bet["bettype"] == "TMDS") {
        $t_bet["bettype"] = "特码单双";
        if($t_bet["betnumber"] == "1") $t_bet["betnumber"] = "单";
        if($t_bet["betnumber"] == "0") $t_bet["betnumber"] = "双";
    }
    if($t_bet["bettype"] == "PMSX") {
        $t_bet["bettype"] = "平码生肖";
    }
    if($t_bet["bettype"] == "JQYS") {
        $t_bet["bettype"] = "家禽野兽";
        if($t_bet["betnumber"] == "jq") $t_bet["betnumber"] = "家禽";
        if($t_bet["betnumber"] == "ys") $t_bet["betnumber"] = "野兽";
    }
    if($t_bet["bettype"] == "TMHM") {
        $t_bet["bettype"] = "特码号码";
    }

    return $t_bet;
}


 ?>
