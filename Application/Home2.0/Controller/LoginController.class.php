<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index(){
        $UserName = I('post.username');
        $PassWord = md5(I('post.password'));
        $getuname=I('get.username','','htmlspecialchars');
        $getpwd=I('get.password','','htmlspecialchars');
        // $getuname='test0102';
        // $getpwd=md5('123');
        if($getuname!=''){
            $UserName=$getuname;
        }
        if($getpwd!=''){
            $PassWord=$getpwd;
        }

        if($UserName !="" && $PassWord != ""){
            $data['UserName'] = $UserName;
            $data['UserPassword'] = $PassWord;
            $data['Nullity'] = '0';
            $User = M('t_user');
            $count = $User
                    -> where($data)
                    -> count();
            if($count == 1) {
                $data['IsLogin'] = '1';
                $User = M('t_user');
                $countlogin = $User
                    -> where($data)
                    -> count();
                if($countlogin>0){
                    echo '1|0';
                }
                else{
                    echo '1|1';
                }
            }
            else{
                echo 0;
            }
        }
        else{
            echo 0;
        }
    }

    public function weblogin(){
        $this->display('weblogin');
    }

    public function userexit(){
        session(null);
        //重定向到指定的URL地址
        redirect('/Login/weblogin');
    }

    public function login(){
        $UserName = I('post.username');
        $PassWord = md5(I('post.password'));
        $getuname=I('get.username','','htmlspecialchars');
        $getpwd=I('get.password','','htmlspecialchars');
        if($getuname!=''){
            $UserName=$getuname;
        }
        if($getpwd!=''){
            $PassWord=$getpwd;
        }

        $data['UserName'] = $UserName;
        $data['UserPassword'] = $PassWord;
        $data['Nullity'] = 0;
        $User = M('t_user');
        $count = $User
                ->where($data)
                ->count();
        if($count == 1) {
            session('user_name', $data['UserName']);
            $log = M('t_log');
            $map['UserName'] = $data['UserName'];
            $adddata['UserId'] = $User -> where($map) -> getField('UserId');
            session('user_id', $adddata['UserId']);
            $adddata['UserName'] = $data['UserName'];
            $adddata['LoginTime'] = time();
            $adddata['LoginIp'] = $reIP = $_SERVER["REMOTE_ADDR"];
            $count = $log -> add($adddata);
            if($count)
            {
                //删除该id在线记录并添加一条
                $d['Lid']=time().rand(1000,9999);
                session('Lid',$d['Lid']);
                $d['UserId']=session('user_id');
                $d['UserName']=session('user_name');
                $d['Time']=time();
                $m['UserId']=session('user_id');
                $t_online=M('t_online');
                $t_online->where($m)->delete();
                $r=$t_online->add($d);

                $savedata['IsLogin']=1;
                $result = $User->where($map)->save($savedata);
                if($result !== false){
                //重定向到指定的URL地址
                    redirect('/Index/Index');
                }
                else{
                    $this->error('更新状态失败','/Login/weblogin',3);
                }
            }
            else{
                $this->error('写入日志失败！','/Login/weblogin',3);
            }
        }else{
            $this->error('登陆失败！','/Login/weblogin',3);
        }
    }

    public function exitlogin(){
        $t_user=M('t_user');
        $UserName = I('post.username','','htmlspecialchars');
        $getuname=I('get.username','','htmlspecialchars');
        if($getuname!=''){
            $UserName=$getuname;
        }
        $map['UserName']=$UserName;
        $savedata['IsLogin']=0;
        $result = $t_user->where($map)->save($savedata);
        session(null);
        echo $result;
    }

    public function alertpwd(){
        $getuname=I('get.username','','htmlspecialchars');
        $getpwd=I('get.password','','htmlspecialchars');
        $getnewpwd=I('get.newpassword','','htmlspecialchars');
        $map['UserName']=$getuname;
        $map['UserPassword']=$getpwd;
        $User = M('t_user');
        $count = $User
                ->where($map)
                ->count();
        if($count == 1) {
            $savedata['UserPassword']=$getnewpwd;
            $savedata['IsLogin']=1;
            $result = $User->where($map)->setField($savedata);
            echo 1;
        }
        else{
            echo 0;
        }
    }
}
?>