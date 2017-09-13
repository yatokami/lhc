<?php
namespace Admin\Controller;
//use Think\Controller;

class SuperController extends BaseController {

    //超端配置
    public function index(){
        if(is_yunyin()) {
            $array = array('鼠','牛','虎','兔','龙','蛇','马','羊','猴','鸡','狗','猪');
        
            for ($i = 0; $i < 6; $i++) { 
                $rands[$i] = rand(1,12);
            }
            
            $rands[6] = rand(1,49);

            $minute = date('i');
            if(((int)$minute%10) >= 7 ) {
                $issue = M('t_history')->where(['State' => 2])->order('id desc')->limit(1)->getField('Issue');
            } else {
                $issue = M('t_history')->where(['State' => 1])->order('id desc')->limit(1)->getField('Issue');
            }

            $this->assign('issue', $issue);
            $this->assign('array', $array);
            $this->assign('rands', $rands);
            $this->assign('super_active', 'active');
            $this->assign('super_config_active', 'active');
            $this->display('Index/Super');
        }
    }

    //确认期号
    public function select_issue() {
        $minute = date('i');
        $second = date('s');
        if(((int)$minute%10) >= 7 ) {
            $issue = M('t_history')->where(['State' => 2])->order('id desc')->limit(1)->getField('Issue');
        } else {
            $issue = M('t_history')->where(['State' => 1])->order('id desc')->limit(1)->getField('Issue');
        }

        if(((int)$minute%10) >= 9 && (int)$second >= 30) {
            $this->ajaxReturn(-1);
        }
        $this->ajaxReturn($issue);
    }

    //修改超端配置
    public function update_super() {
        if(((int)$minute%10) >= 9 && (int)$second >= 30) {
            $ajax["state"] = -1;
            $this->ajaxReturn($ajax);
        }
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


        for ($i = 0; $i < 6; $i++) { 
            $str = I('post.pmsx-'.($i+1));
            //获取提交生肖所在下标
            $datas[$i] = array_search($str, $Animal);
        }

        $minute = date('i');
        if(((int)$minute%10) >= 7 ) {
            $issue = M('t_history')->where(['State' => 2])->order('id desc')->limit(1)->getField('Issue');
        } else {
            $issue = M('t_history')->where(['State' => 1])->order('id desc')->limit(1)->getField('Issue');
        }

        //超端生成
        $result = super_auto($issue, $Animal, $datas, I('post.tmhm-7'));
        $count = M('t_history')->where(['Issue' => $issue])->save($result);      
        if($count > 0) {
            $ajax["state"] = "1";
            $ajax["issue"] = $issue;
            $ajax["num"] = split(':', $result["Num"])[0];
            $ajax["tnumurl"] = $result["TNumUrl"];
            $this->ajaxReturn($ajax);
        } else {
            $ajax["state"] = "0";
            $this->ajaxReturn($ajax);
        }
    }

    //修改会员注码页面
    public function index2() {

        //根据用户名查询
        if(I('username')) {
            $t_betinfo = M('t_bet')
                        ->field('Id, Issue, BetNumber')
                        ->where(['UserName' => I('username'), 'BetState' => 0, 'BetType' => 'TMHM'])
                        ->select();
            $t_count = count($t_betinfo);
        }

        $this->assign('tcount', $t_count);
        $this->assign('username', I('username'));
        $this->assign('t_betinfo', $t_betinfo);
        $this->assign('super_active', 'active');
        $this->assign('super_tnumber_active', 'active');
        $this->display('Index/UpdateTNumber');
    }

    //更新会员注码
    public function update_tnumber() {
        $tcount = (int)I('tcount');

        for ($i = 1; $i <= $tcount; $i++) { 
            $map['Id'] = I('ti_'.$i);
            $data["BetNumber"] = I('tn_'.$i);

            $state += (int)M('t_bet')->where($map)->save($data);

        }

        if($state) {
            echo "<script>alert('修改成功');history.go(-1)</script>";
        } else {
            echo "<script>alert('修改失败，请检查是否该期已经结束或者下注特码不正确');history.go(-1)</script>";
        }
    }

}