<?php
namespace Admin\Controller;
//use Think\Controller;

class LotteryController extends BaseController {

    //开奖配置
    public function index(){

        $t_config = M('t_config')
                ->where(['Id' => ['ELT', 12]])
                ->select();

        $map["Id"] = array('ELT', 16);
        $map["Id"] = array('EGT', 15);
        $jackpot_scale = M('t_config')
                ->where($map)
                ->select();
        
        $this->assign('jackpot_scale', $jackpot_scale);
        $this->assign('t_config', $t_config);
        $this->assign('lottery_active', 'active');
        $this->assign('lottery_config_active', 'active');
    	$this->display('Index/Lottery');
    }

    //修改开奖配置
    public function update_config() {

        for ($i = 1; $i < 13; $i++) { 
           $data["ConfigValue"] = I("t_config-".$i);
           $state += (int)M('t_config')->where(["Id" => $i])->save($data);
        }

        if($state) {
            echo "<script>alert('更新成功');history.go(-1)</script>";
        } else {
            echo "<script>alert('更新失败');history.go(-1)</script>";
        }
       
    }

    //修改奖池
    public function update_Jackpot() {
        for ($i = 15; $i < 17; $i++) { 
            $data["ConfigValue"] = I("t_config-".$i);
            if((float)$data["ConfigValue"] >= 0) {
                $state += (int)M('t_config')->where(["Id" => $i])->save($data);
            }
        }

        if($state) {
            echo "<script>alert('更新成功');history.go(-1)</script>";
        } else {
            echo "<script>alert('更新失败');history.go(-1)</script>";
        }
    }
}