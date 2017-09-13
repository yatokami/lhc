<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends BaseController{
    public function index(){
        $t_user=M('t_user');
        $map['UserId']=session('user_id');
        $money=$t_user->where($map)->getField('Money');
        $t_history=M('t_history');
        $minute  = date("i");
        if(((int)$minute%10) <= 2){
            $map['State']=array('in',[2,3]);
        }
        else{
            $map['State']=3;
        }
        $hlist=$t_history->where($map)->limit(7)->order('Id desc')->select();
        $list=null;
        //虎--龙--马--猪--猴--羊--鼠:8--6--40--35--2--15--10
        for($i=0;$i<count($hlist);$i++){
            $list[$i]['issue']=$hlist[$i]['issue'];
            $list[$i]['issue1']=substr($hlist[$i]['issue'], 4,4);
            $list[$i]['issue2']=substr($hlist[$i]['issue'], 8,3);
            $arr=split(':', $hlist[$i]['num']);
            $sx=split("--", $arr[0]);
            $hm=split("--", $arr[1]);
            $list[$i]['sx1']=$sx[0];
            $list[$i]['sx2']=$sx[1];
            $list[$i]['sx3']=$sx[2];
            $list[$i]['sx4']=$sx[3];
            $list[$i]['sx5']=$sx[4];
            $list[$i]['sx6']=$sx[5];
            $list[$i]['sx7']=$sx[6];
            $list[$i]['hm1']=$hm[0];
            $list[$i]['hm2']=$hm[1];
            $list[$i]['hm3']=$hm[2];
            $list[$i]['hm4']=$hm[3];
            $list[$i]['hm5']=$hm[4];
            $list[$i]['hm6']=$hm[5];
            $list[$i]['hm7']=$hm[6];
            $list[$i]['dx']=$hlist[$i]['tbigurl']==-1?'和':($hlist[$i]['tbigurl']==0?'小':'大');
            $list[$i]['ds']=$hlist[$i]['tdanshuangurl']==-1?'和':($hlist[$i]['tdanshuangurl']==0?'双':'单');
            $list[$i]['jqys']=$hlist[$i]['tanimaltypeurl']=='jq'?'家 禽':'野 兽';
        }

        $config_map['ConfigName'] = 'LotterTime';
        $lottertime = (int)M('t_config')->where($config_map)->getField('ConfigValue');//开奖时间
        $config_map['ConfigName'] = 'EntertainedTime';
        $fptime = (int)M('t_config')->where($config_map)->getField('ConfigValue');//封盘时间

        $config_map['ConfigName'] = 'PMSX';
        $this->PMSX=$PMSX = M('t_config')->where($config_map)->getField('ConfigValue');//赔率
        $config_map['ConfigName'] = 'TMHM';
        $this->TMHM=$TMHM = M('t_config')->where($config_map)->getField('ConfigValue');//赔率
        $config_map['ConfigName'] = 'TMBS';
        $this->TMBS=$TMBS = M('t_config')->where($config_map)->getField('ConfigValue');//赔率
        $config_map['ConfigName'] = 'TMDX';
        $this->TMDX=$TMDX = M('t_config')->where($config_map)->getField('ConfigValue');//赔率
        $config_map['ConfigName'] = 'TMDS';
        $this->TMDS=$TMDS = M('t_config')->where($config_map)->getField('ConfigValue');//赔率
        $config_map['ConfigName'] = 'JQYS';
        $this->JQYS=$JQYS = M('t_config')->where($config_map)->getField('ConfigValue');//赔率

        $config_map['ConfigName'] = 'M_PMSX';
        $this->M_PMSX=$M_PMSX = M('t_config')->where($config_map)->getField('ConfigValue');//最小投注额
        $config_map['ConfigName'] = 'M_TMHM';
        $this->M_TMHM=$M_TMHM = M('t_config')->where($config_map)->getField('ConfigValue');//最小投注额
        $config_map['ConfigName'] = 'M_TMBS';
        $this->M_TMBS=$M_TMBS = M('t_config')->where($config_map)->getField('ConfigValue');//最小投注额
        $config_map['ConfigName'] = 'M_TMDX';
        $this->M_TMDX=$M_TMDX = M('t_config')->where($config_map)->getField('ConfigValue');//最小投注额
        $config_map['ConfigName'] = 'M_TMDS';
        $this->M_TMDS=$M_TMDS = M('t_config')->where($config_map)->getField('ConfigValue');//最小投注额
        $config_map['ConfigName'] = 'M_JQYS';
        $this->M_JQYS=$M_JQYS = M('t_config')->where($config_map)->getField('ConfigValue');//最小投注额

        $Message = M('t_bulletin')->limit(1)->order('Id desc')->getField('Bulletin');//最新公告
        $this->Message = $Message;
        $arr1=split(':', $hlist[0]['num']);
        $sx1=split("--", $arr1[0]);
        $hm1=split("--", $arr1[1]);
        $this->lottertime =$lottertime;
        $this->fptime =$fptime;
        $this->sx1 =$sx1[0];
        $this->sx2 =$sx1[1];
        $this->sx3 =$sx1[2];
        $this->sx4 =$sx1[3];
        $this->sx5 =$sx1[4];
        $this->sx6 =$sx1[5];
        $this->sx7 =$sx1[6];
        $this->hm1 =$hm1[0];
        $this->hm2 =$hm1[1];
        $this->hm3 =$hm1[2];
        $this->hm4 =$hm1[3];
        $this->hm5 =$hm1[4];
        $this->hm6 =$hm1[5];
        $this->hm7 =$hm1[6];

        //dump($list);
        $this->assign('username',session('user_name'));
        $this->assign('money',$money);
        $this->assign('issue',$hlist[0]['issue']);
        $this->assign('num',$hlist[0]['num']);
        $this->assign('dx',$hlist[0]['tbigurl']==-1?'和':($hlist[0]['tbigurl']==0?'小':'大'));
        $this->assign('ds',$hlist[0]['tdanshuangurl']==-1?'和':($hlist[0]['tdanshuangurl']==0?'双':'单'));
        $this->assign('jqys',$hlist[0]['tanimaltypeurl']=='jq'?'家 禽':'野 兽');
        $this->assign('list',$list);
        $this->display();
    }
}