<?php
namespace Admin\Controller;
use Think\Controller;
class AuthController extends Controller {
    public function login(){
        $this->display();
    }
    public function getLogin() {
        $data['AgentName'] = I('AdminName');
        $data['AgentPwd'] = md5(I('AdminPwd'));
        $T_Agent = M('t_agent');

        $count = $T_Agent->where($data)->count();

        if($count == 1) {
            session('admin_name', $data['AgentName']);

            //记录登陆
            $datalog['AgentId'] = $T_Agent->where($data)->getField('AgentId');
            $auth = $T_Agent->where($data)->getField('AgentParent');
            $datalog['AgentName'] = $data['AgentName'];
            $datalog['LoginTime'] = time();
            $datalog['LoginIp'] = $_SERVER["REMOTE_ADDR"];
            M('t_agentlog')->add($datalog);
            session('AgentId', $datalog['AgentId']);
            session('Auth', $auth);
            $this->success('登录成功等待进入主页','/Index/index');
        } else {
            $this->error('登录失败，用户名或密码错误');
        }
    }

    public function logout() {
        session(null);
        $this->success('退出成功等待重新登录','/Auth/login');
    }
} 