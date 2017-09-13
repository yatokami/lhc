<?php
namespace Admin\Controller;
//use Think\Controller;
class UserPanelController extends BaseController {

    //个人面板显示
    public function index() {
        //获取用户信息
        $agentId = session('AgentId');
    	$T_AgentInfo = M('t_agent')
                    ->field('AgentName, Money, Nulltiy, Proportion, IsAuth, SumMoney')
                    ->where(['AgentId' => $agentId])
                    ->find();

        $this->assign('t_agentinfo', $T_AgentInfo);
        $this->assign('user_active', 'active');
        $this->assign('user_info_active', 'active');
    	$this->display('Index/AdminInfo');
    }

    //修改密码页面
    public function pwdPanel() {
        //获取用户信息
        $agentId = session('AgentId');
        $T_AgentInfo = M('t_agent')
                    ->field('AgentName')
                    ->where(['AgentId' => $agentId])
                    ->find();

        $this->assign('t_agentinfo', $T_AgentInfo);
        $this->assign('user_active', 'active');
        $this->assign('user_pwd_active', 'active');
        $this->display('Index/UpdatePwd');
    }

    //修改密码操作
    public function updatePwd() {

        $agentId = session('AgentId');
        $old_pwd = md5(I('post.old_pwd'));
        $new_pwd = md5(I('post.new_pwd'));
        //判断旧密码
        $count = M('t_agent')
                ->where(['AgentId' => $agentId, 'AgentPwd' => $old_pwd])
                ->count();
        if($count > 0) {
            $status = M('t_agent')
                    ->where(['AgentId' => $agentId])
                    ->save(['AgentPwd' => $new_pwd]);
            if($status) {
                $this->success('修改成功', '/UserPanel/pwdPanel');
            } else {
                $this->error('修改失败', '/UserPanel/pwdPanel');
            }
        } else {
            $this->error('旧密码错误请重新输入', '/UserPanel/pwdPanel');
        }
    }
}