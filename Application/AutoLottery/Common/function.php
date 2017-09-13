<?php 

//生成7个随机数
function getrandom($max) {
    for ($i = 0; $i < 7; $i++) { 
        $numbers[$i] = rand(1,12);
    }

    shuffle($numbers); 
    //array_slice 取该数组中的某一段 
    $num = 7; 
    $result = array_slice($numbers,0,$num); 

    return $result;
}


//生成开奖
function auto($Issue) {
        $result = getrandom(12);
        while(true) { 
            $result1 = getarray_random($result);
            $result2 = $result1;
            if (count($result1) == count(array_unique($result2))) {   
                break;
            } 
        }
        $result = $result1;
        $adddata["Issue"] = $Issue;
        $new_issue = (float)$adddata["Issue"];

        $adddata["Time"] = time();
        //中文生肖
        $cnNum = "";
        //数字生肖
        $strNum = "";
        $data = array('鼠','牛','虎','兔','龙','蛇','马','羊','猴','鸡','狗','猪');
        
        //获取生肖
        $index = (date('Y')-1900)%12;

        //进行数组拆分翻转
        $data2 = array_splice($data, $index);
        $data = array_reverse($data);
        $data3 = array_splice($data2, 1);
        $data3 = array_reverse($data3);
        $Animal = array_merge($data2,$data);
        $Animal = array_merge($Animal,$data3);

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

        //去除末尾多余字符--
        $cnNum = substr($cnNum,0,strlen($cnNum)-2);
        $strNum = substr($strNum,0,strlen($strNum)-2);

        $adddata["Num"] = $cnNum.":".$strNum;
        $adddata["TNumUrl"] = end($result);
        $adddata["TAnimalUrl"] = $Animal[(end($result)%12==0?12:end($result)%12)-1];
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
        
        return $adddata;
    }

//控水
function controlMoney($betnumber, $pmsx, $tmsx, $result, $t_bet) {
    //判断平码生肖
    if($t_bet["bettype"] == "PMSX") {
        if(in_array($betnumber, $pmsx) !== false) {
            $win_money = (float)$t_bet["winmoney"] - (float)$t_bet["betmoney"];
        } else {
            $lose_money = (float)$t_bet["betmoney"];
        }
    }

    //判断特码生肖
    if($t_bet["bettype"] == "TMHM") {
        if($betnumber === $tmsx) {
            $win_money = (float)$t_bet["winmoney"] - (float)$t_bet["betmoney"];
        } else {
            $lose_money = (float)$t_bet["betmoney"];
        }
    }

    if($t_bet["bettype"] == "TMBS") {
        if($betnumber === $result["TColor"]) {
            $win_money = (float)$t_bet["winmoney"] - (float)$t_bet["betmoney"];
        } else {
            $lose_money = (float)$t_bet["betmoney"];
        }
    }

    if($t_bet["bettype"] == "TMDX") {
        if((int)$betnumber == $result["TBigUrl"]) {
            $win_money = (float)$t_bet["winmoney"] - (float)$t_bet["betmoney"];
        } else {
            $lose_money = (float)$t_bet["betmoney"];
        }

        if($result["TBigUrl"] == -1) {
            return 0;
        }
    }
    if($t_bet["bettype"] == "TMDS") {
        if((int)$betnumber == $result["TDanshuangUrl"]) {
            $win_money = (float)$t_bet["winmoney"] - (float)$t_bet["betmoney"];
        } else {
            $lose_money = (float)$t_bet["betmoney"];
        }

        if($result["TDanshuangUrl"] == -1) {
            return 0;
        }
    }

    if($t_bet["bettype"] == "JQYS") {
        if($betnumber === $result["TAnimalTypeUrl"]) {
            $win_money = (float)$t_bet["winmoney"] - (float)$t_bet["betmoney"];
        } else {
            $lose_money = (float)$t_bet["betmoney"];
        }
    }

    return $win_money-$lose_money;
}

//结算
function autoCalculation($value, $t_history, $win_money) {

    $nums = split(":", $t_history["num"]);
    $pmsx = array_splice(split("--", $nums[0]), 0, 7);
    if($value["bettype"] == "PMSX") {
        if(in_array($value["betnumber"], $pmsx) !== false) {
            $win_money = (float)$value["winmoney"];
        }
    }

    //判断特码生肖
    if($value["bettype"] == "TMHM") {
        if((int)$value["betnumber"] == (int)$t_history["tnumurl"]) {
            $win_money = (float)$value["winmoney"];
        }
    }

    if($value["bettype"] == "TMBS") {
        if($value["betnumber"] == $t_history["tcolor"]) {
            $win_money = (float)$value["winmoney"];
        }
    }

    if($value["bettype"] == "TMDX") {

        if($value["betnumber"] == $t_history["tbigurl"]) {
            $win_money = (float)$value["winmoney"];
        }

        if($t_history["tnumurl"] == "49") {
            $win_money = -1;
        }
    }

    if($value["bettype"] == "TMDS") {
        if($value["betnumber"] == $t_history["tdanshuangurl"]) {
            $win_money = (float)$value["winmoney"];
        }

        if($t_history["tnumurl"] == "49") {
            $win_money = -1;
        }
    }

    if($value["bettype"] == "JQYS") {
        if($value["betnumber"] == $t_history["tanimaltypeurl"]) {
            $win_money = (float)$value["winmoney"];
        }
    }

    return $win_money;
}

 ?>