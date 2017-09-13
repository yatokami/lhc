<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller {
    function _initialize() {
        if(!isUserlogin()) {
            //重定向到指定的URL地址
            redirect('/Login/weblogin');
        } 
        else{
        	$t_user = M('t_user');
        	$map['UserId'] = session('user_id');
        	$map['IsLogin'] = 1;

            $count = $t_user->where($map)->count();
            if($count == 1) {

                $t_online = M('t_online');
                if(session('?Lid')){
                    $t_map['Lid']=session('Lid');
                    $t_map['UserId']=session('user_id');
                    $c=$t_online->where($t_map)->count();
                    if($c<=0){
                        //异地
                        //dump($t_map);
                        session(null);
                        $this->ajaxReturn(1);
                        //echo "<script>location.href='/Login/weblogin?state=other'</script>";
                    }
                }
            }

        	// $count = $t_user->where($map)->count();
        	// if($count == 1) {

        	// 	$t_log = M('t_log');
        	// 	$lastloginip = $t_log->where($map)->order('Id desc')->limit(1)->getField('LoginIp');

        	// 	if($lastloginip != $_SERVER["REMOTE_ADDR"]) {
         //            echo "<script>location.href='/Login/weblogin?state=other'</script>";
        	//         //$this->error('异地登陆，你被强制退出','/Home/Login/weblogin',1);
        	// 	}

        	// }
        }
    }
}