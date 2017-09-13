<?php
namespace Admin\Controller;
//use Think\Controller;

class MemberController extends BaseController {

    //会员列表
    public function index(){
        $parentId = session('AgentId');
        $T_Agent = D( 'Admin/Proxy' )->getAgentRow($parentId);

        //分页
        $T_Users = M('t_user');
        $count = $T_Users
                ->where(["AgentId" => $parentId])
                ->count();

        $page = new \Think\PageBootcss($count, 10);
        $limit = $page->firstRow.','.$page->listRows;

        //获取会员
        $T_UsersList = $T_Users
                    ->field('UserId, UserName, Money, AgentId, Nullity, SumMoney, zc, fs, Remark, Total')
                    ->where(["AgentId" => $parentId])
                    ->order("UserId desc")
                    ->limit($limit)
                    ->select();

        $map1["AgentId"] = $parentId;
        $temp1 = temp_money($map1, $parentId);

        $this->assign('t_money', $T_Agent['money']);
        $this->assign('t_summoney', $temp1);
        $this->assign('member_active', 'active');
        $this->assign('member_list_active', 'active');
        $this->assign('t_userlist', $T_UsersList);
        $this->page = $page->show();
        $this->count = $count;
        $this->display('Index/MemberList');
    }

    //新增会员
    public function add_member() {
        $parentId = session('AgentId');

        $T_Agent = D( 'Admin/Proxy' )->getAgentRow($parentId);

        $count = 0;

        //密码和用户名不能为空
        if(I('post.member_name') == "" || I('post.member_pwd') == "") {
            $this->ajaxReturn(-2);
        }

        //判断用户名是否重复
        $temp = M('t_user')->where(["UserName" => I('post.member_name')])->count();
        if($temp > 0) {
            $this->ajaxReturn(-3);
        }

        if((float)I('post.member_money') < 0) {
            $this->ajaxReturn(-1);
        }


        $map1["AgentId"] = $parentId;
        $temp1 = temp_money($map1, $parentId);
        //新增金额不能大于余额
        if((float)$temp1 >= (float)I('post.member_money')) {
            $data["UserName"] = I('post.member_name');
            $data["UserPassword"] = md5(I('post.member_pwd'));
            $data["Money"] = I('post.member_money');
            $data["SumMoney"] = I('post.member_money');
            $data["AgentId"] = $parentId;
            $data["Remark"] = I('post.member_remark');
            $data["zc"] = ((int)I('post.member_zc') > 100 ? 100 : (int)I('post.member_zc'));
            $data["fs"] = ((int)I('post.member_fs') > 100 ? 100 : (int)I('post.member_fs'));

            $count = M('t_user')->add($data);

            if($count > 0) {
                $T_Agent= M('t_agent')
                        ->where(["AgentId" => $parentId])
                        ->setDec('Money', (float)$data["Money"]);

                D('Admin/OperateLog')->addlog($count, $data["UserName"], $parentId, "新增会员", 0, $data["SumMoney"]);
            }
        } else {
            $count = -1;
        }

        $this->ajaxReturn($count);
    }

    //修改会员
    public function edit_member() {
        $parentId = session('AgentId');
        $map["UserName"] = I('post.member_name');

        //密码不为空,为空则不修改原密码
        if(I('post.member_pwd') != null) {
            $data["UserPassword"] = md5(I('post.member_pwd'));
        }

        //占成不为空且大于0
        if(I('post.member_zc') != null && (float)I('post.member_zc') > 0) {
            $data["zc"] = I('post.member_zc');
        }

        //返水不为空且大于0
        if(I('post.member_fs') != null && (float)I('post.member_fs') > 0) {
            $data["fs"] = I('post.member_fs');
        }

        $money = 0;
        $bool = false;
        //会员总额不为空并且是数字
        if(I('post.member_summoney') != null && is_numeric((float)I('post.member_summoney')) && (float)I('post.member_summoney') >= 0) {
            $money = (float)I('post.member_summoney');
            $bool = true;
        }

        //会员备注
        if(I('post.member_remark') != null) {
            $data["Remark"] = I('post.member_remark');
        }

        $map["AgentId"] = $parentId;
        if($data != null) {
            $state += (int)M('t_user')
                ->where(['AgentId' => $parentId, 'UserName' => $map["UserName"]])
                ->save($data);
        }


        if($bool) {
            //获取用户额度

            $map1["AgentId"] = $parentId;
            $map1["UserName"] = array('NEQ', $map["UserName"]);
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
            if($temp1 >= $money) {
                $state += (int)M('t_user')
                        ->where(["UserName" => $map["UserName"], "AgentId" => $parentId])
                        ->save(["SumMoney" => $money]);
            } else {
                $this->ajaxReturn(-1);
            }

            //与修改金额进行对比
        }

        if($state) {
            D('Admin/OperateLog')->addlog(I('post.t_id'), $map["UserName"], $parentId, "修改会员", 0, $money);
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }

    //充额会员
    public function recharge_member() {
        $parentId = session('AgentId');

        $T_Agent = D( 'Admin/Proxy' )->getAgentRow($parentId);
        $userId = I('post.t_id');
        $userName = I('post.member_name');
        $money = (float)I('post.member_addmoney');
        $count = 0;

        if((float)I('post.member_addmoney') < 0) {
            $this->ajaxReturn(-1);
        }

        if((float)$T_Agent['money'] >= $money) {
            M('t_user')
                ->where(['UserId' => $userId])
                ->setInc('Money', $money);
            // M('t_user')
            //     ->where(['UserId' => $userId])
            //     ->setInc('SumMoney', $money);
            M('t_agent')
                ->where(['AgentId' => $parentId])
                ->setDec('Money', $money);
            D('Admin/OperateLog')->addlog($userId, $userName, $parentId, "充值会员", 0, $money);
            $count = 1;
        } else {
            $count = -1;
        }

        $this->ajaxReturn($count);
    }

    //结算会员
    public function settle_member() {
        $parentId = session('AgentId');

        $userId = I('post.t_id');
        $userName = I('post.member_name');

        $T_Users = D( 'Admin/User' )->getAgentRow($userId, $parentId);


        $money = (float)$T_Users["total"];
        M('t_user')
            ->where(['UserId' => $userId])
            ->save(['Total' => 0]);
        $Model = new \Think\Model(); // 实例化一个model对象 没有对应任何数据表
        $Model->execute("update t_user set Money=t_user.SumMoney where UserId=$userId");

        $log["UserId"] = $userId;
        $log["Type"] = 0;
        $log["Total"] = $money;
        $log["Time"] = time();
        $log["ParentId"] = $parentId;
        $log["UserName"] = $userName;
        M('t_settlelog')->add($log);

        D('Admin/OperateLog')->addlog($userId, $userName, $parentId, "结算会员", 0, $money);
        $this->ajaxReturn(1);
    }

    //提现会员
    public function takemoney_member() {
        $parentId = session('AgentId');
        $userId = I('post.t_id');
        $userName = I('post.member_name');
        $money = (float)I('post.member_takemoney');

        if($money < 0) {
            $this->ajaxReturn(-1);
        }

        $T_Users = D( 'Admin/User' )->getAgentRow($userId, $parentId);
        if((float)$T_Users["total"] <= 0) {
            $this->ajaxReturn(-1);
        }
       
        if(($money <= (float)$T_Users["total"]) && ($money <= (float)$T_Users['money'])) {
            M('t_user')
                ->where(['UserId' => $userId])
                ->setDec('Total', $money);
            M('t_user')
                ->where(['UserId' => $userId])
                ->setDec('Money', $money);

            $log["UserId"] = $userId;
            $log["Type"] = 0;
            $log["TakeMoney"] = $money;
            $log["Time"] = time();
            $log["ParentId"] = $parentId;
            $log["UserName"] = $userName;
            M('t_takemoneylog')->add($log);

            D('Admin/OperateLog')->addlog($userId, $userName, $parentId, "提现会员", 1, $money);
            $this->ajaxReturn(1);
        }
        $this->ajaxReturn(-1);
    }

    //操作日志
    public function operate_log() {
        $name = I('get.search_id');
        $type = I('get.operate_type');
        $parentId = session('AgentId');
        if($type == "1") {
            $type = "新增会员";
        }
        if($type == "2") {
            $type = "充值会员";
        }
        if($type == "3") {
            $type == "降额会员";
        }
        if($type == "4") {
            $type = "结算会员";
        }
        if($type == "5") {
            $type == "提现会员";
        }
        if($type === "0") {
            $type = null;
        }
        $data = D('Admin/OperateLog')->pagerow($type, $name, 0, $parentId);

        $this->page = $data["page"]->show();
        $this->count = $data["count"];

        $this->assign('page_log', $data["pagelog"]);
        $this->assign('member_active', 'active');
        $this->assign('member_log_active', 'active');
        $this->display('Index/OperateMemberLog');
    }

    //降额会员
    public function reduce_member() {
        $parentId = session('AgentId');
        $userId = I('post.t_id');
        $userName = I('post.member_name');
        $T_Users = D( 'Admin/User' )->getAgentRow($userId, $parentId);
        $money = (float)I('post.member_reducemoney');

        if((float)I('post.member_reducemoney') < 0) {
            $this->ajaxReturn(-1);
        }
        
        if((float)$T_Users['money'] >= $money) {
            M('t_user')
                ->where(['UserId' => $userId])
                ->setDec('Money', $money);
            // M('t_user')
            //     ->where(['UserId' => $userId])
            //     ->setDec('SumMoney', $money);
            M('t_agent')
                ->where(['AgentId' => $parentId])
                ->setInc('Money', $money);
            D('Admin/OperateLog')->addlog($userId, $userName, $parentId, "降额会员", 0, $money);
            $count = 1;
        } else {
            $count = -1;
        }
        $this->ajaxReturn($count);
    }

    //删除会员
    public function delete_member() {
        $agentId = I('post.t_id');
        $map["AgentPwd"] = md5(I('post.member_pwd'));
        $map["AgentId"] = session('AgentId');

        $count = D( 'Admin/Proxy' )->where($map)->count();

        if($count) {
            M('t_bet')->where(["UserId" => $agentId])->delete();
            M('t_takemoneylog')->where(["UserId" => $agentId, "Type" => 0])->delete();
            M('t_settlelog')->where(["UserId" => $agentId, "Type" => 0])->delete();
            M('t_log')->where(["UserId" => $agentId])->delete();
            M('t_user')->where(["UserId" => $agentId])->delete();
            M('t_operatelog')->where(["OperateUserId" => $agentId, "OperateAgentId" => $map["AgentId"]])->delete();
            $this->ajaxReturn($count);
        } else {
            $this->ajaxReturn(-7);
        }
    }

    //切换用户状态
    public function switch_member() {
        $userId = I('post.t_id');
        $data["Nullity"] = I('post.state');
        $parentId = session('AgentId');

        $status = M('t_user')
                ->where(['UserId' => $userId, 'AgentId' => $parentId])
                ->save($data);

        if($status) {
            $this->ajaxReturn(-6);
        } else {
            $this->ajaxReturn(-4);
        }
    }
}