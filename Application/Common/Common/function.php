<?php 
//数组随机
function getarray_random($arrs) {
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
		$key = array_rand($arr[$arrs[$i]-1], 1);
		$arrs[$i] = $arr[$arrs[$i]-1][$key];
	}

	return $arrs;
}

//获取颜色
function getColor($index) {
	$arr_red = [1,2,7,8,12,13,18,19,23,24,29,30,34,35,40,45,46];
	$arr_blue = [3,4,9,10,14,15,20,25,26,31,36,37,41,42,47,48];
	$arr_green = [5,6,11,16,17,21,22,27,28,32,33,38,39,43,44,49];
	$isred = in_array($index, $arr_red);
	if($isred) {
		return "red";
	}
	$isblue = in_array($index, $arr_blue);
	if($isblue) {
		return "blue";
	}
	$isgreen = in_array($index, $arr_green);
	if($isgreen) {
		return "green";
	}
}

//获取动物类型
function getAnimaType($index) {
	$arr_jq = ['鸡','羊','马','牛','猪','狗'];
	$arr_ys = ['猴','兔','蛇','龙','虎','鼠'];
	$isjq = in_array($index, $arr_jq);
	if($isjq) {
		return "jq";
	}
	$isys = in_array($index, $arr_ys);
	if($isys) {
		return "ys";
	}
}


 ?>