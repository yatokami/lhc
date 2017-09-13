<?php 
function isUserlogin(){
	return session('user_name');
	}

	    //获取总计
function getzj($list,$ylist){
	$cou=0;
	$gold=0;
	$pfl=0;
	for($i=0;$i<count($ylist);$i++){
   		$cou+=(int)$ylist[$i]['count'];
   		$gold+=(int)$ylist[$i]['summoney'];
   		$pfl+=(double)$ylist[$i]['profitorloss'];
   	}
   	$lastindex=count($list);
   	$list[$lastindex]['Issue']='总计';
   	$list[$lastindex]['count']=$cou;
   	$list[$lastindex]['summoney']=$gold;
   	$list[$lastindex]['profitorloss']=$pfl;

   	return $list;
}
 ?>
