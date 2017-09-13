<?php
namespace Home\Controller;
use Think\Controller;
class SelectController extends BaseController{
    public function index(){
        $this->display(); // 输出模板
    }

    //查询下注状况
    //查询投注记录
    public function bethistory(){
        $pagesize=20;//每页显示的行数
        $start1=I('post.starttime','','htmlspecialchars');//开始时间
        $finish1=I('post.finishtime','','htmlspecialchars');//结束时间
        $state=I('post.state',0);//状态：0，未结算，1，已结算
        $issue=I('post.issue',0);//期号
        $p=(int)I('get.page',0);//要跳转的页码，get方法
        $t_bet=M('t_bet');
        $starttime=$start1;
        $finishtime=$finish1;
        if($starttime!=''&&$finishtime!=''){
            $start=strtotime($starttime.' 03:00:00');
            $finish=strtotime($finishtime.' 03:00:00'."+1 day");
            $map['BetTime'] = array('between',array($start,$finish));
        }
        else{
            $map['BetTime'] = array('between',array(strtotime('-1 month'),time()));
        }
        if($issue!=0){
            $map['Issue']=$issue;
        }
        if($state==0){
            $map['BetState']=$state;
        }
        $map['UserId']=session('user_id');
        // 进行分页数据查询
        $list = $t_bet->where($map)->order('Id desc')->page($p,$pagesize)->select();
        if(count($list)>0){
            $list[0]['Count']=$count=count($t_bet->where($map)->order('Id desc')->select());
            $list[0]['PageCount']=(string)ceil((double)$count/(double)$pagesize);
            $list[0]['starttime']=$starttime!=''?$starttime:date('Y-m-d H:i:s', strtotime('-1 month'));
            $list[0]['finishtime']=$finishtime!=''?$finishtime:date('Y-m-d H:i:s', time());
        }
        for($i=0;$i<count($list);$i++){
            if($list[$i]['betstate']==0){
                $list[$i]['profitorloss']="未结算";
            }
            $list[0]['sumbetmoney']+=$list[$i]['betmoney'];
            $list[$i]['bettime']=$date=date('Y-m-d H:i:s', $list[$i]['bettime']);
            switch ($list[$i]['bettype']) {
                case 'PMSX':
                $list[$i]['bettype']='平码生肖';
                break;
                case 'TMHM':
                $list[$i]['bettype']='特码号码';
                break;
                case 'TMBS':
                $list[$i]['bettype']='特码波色';
                break;
                case 'TMDX':
                $list[$i]['bettype']='特码大小';
                $list[$i]['betnumber']=$list[$i]['betnumber']==1?"大":"小";
                break;
                case 'TMDS':
                $list[$i]['bettype']='特码单双';
                $list[$i]['betnumber']=$list[$i]['betnumber']==1?"单":"双";
                break;
                case 'JQYS':
                $list[$i]['bettype']='家禽野兽';
                $list[$i]['betnumber']=$list[$i]['betnumber']=='jq'?"家禽":"野兽";
                break;
            }
            if($list[$i]['betnumber']=='blue')
            {
                $list[$i]['betnumber']='蓝';
            }
            else if($list[$i]['betnumber']=='red')
            {
                $list[$i]['betnumber']='红';
            }
            else if($list[$i]['betnumber']=='green')
            {
                $list[$i]['betnumber']='绿';
            }
        }
        $this->ajaxReturn($list);
    }

    //查询账单报表
    public function recordfinance(){
        $pagesize=20;//每页显示的行数
        $type=I('post.type','','htmlspecialchars');//类型,期号不传参，类型：type，日期：date
        $start1=I('post.starttime','','htmlspecialchars');//开始时间
        $finish1=I('post.finishtime','','htmlspecialchars');//结束时间
        $p=(int)I('get.page',0);//要跳转的页码，get方法
        $starttime=$start1;
        $finishtime=$finish1;
        if($starttime!=''&&$finishtime!=''){
            $start=strtotime($starttime.' 03:00:00');
            $finish=strtotime($finishtime.' 03:00:00'."+1 day");
            $map['BetTime'] = array('between',array($start,$finish));
        }
        else{
            $map['BetTime'] = array('between',array(strtotime('-1 week'),time()));
        }

        $t_bet=M('t_bet');
        $map['BetState']=1;
        $map['UserId']=session('user_id');
        if($type==""){
            $list=$t_bet->field('Issue,count(Issue) as count,sum(BetMoney) as summoney,sum(Profit) as profitorloss')->where($map)->group('Issue')->order('Id desc')->page($p,$pagesize)->select();
            $ylist=$t_bet->field('Issue,count(Issue) as count,sum(BetMoney) as summoney,sum(Profit) as profitorloss')->where($map)->group('Issue')->order('Id desc')->select();
            
            $list=getzj($list,$ylist);//添加总计记录位于common/function.php
            if(count($list)>0){
                $list[0]['Count']=$count=count($t_bet->field('Issue,count(Issue) as count,sum(BetMoney) as summoney,sum(Profit) as profitorloss')->where($map)->group('Issue')->order('Id desc')->select());
                $list[0]['PageCount']=(string)ceil((double)$count/(double)$pagesize);
                $list[0]['starttime']=$starttime!=''?$starttime:date('Y-m-d 03:00:00', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));
                $list[0]['finishtime']=$finishtime!=''?$finishtime:date('Y-m-d H:i:s', time());
            }
            $this->ajaxReturn($list);
        }
        else if($type=='type'){
            $list=$t_bet->field('BetType,count(BetType) as count,sum(BetMoney) as summoney,sum(Profit) as profitorloss')->where($map)->group('BetType')->order('Id desc')->page($p,$pagesize)->select();
            $ylist=$t_bet->field('BetType,count(BetType) as count,sum(BetMoney) as summoney,sum(Profit) as profitorloss')->where($map)->group('BetType')->order('Id desc')->select();
            for($i=0;$i<count($list);$i++){
                switch ($list[$i]['bettype']) {
                case 'PMSX':
                $list[$i]['issue']='平码生肖';
                break;
                case 'TMHM':
                $list[$i]['issue']='特码号码';
                break;
                case 'TMBS':
                $list[$i]['issue']='特码波色';
                break;
                case 'TMDX':
                $list[$i]['issue']='特码大小';
                break;
                case 'TMDS':
                $list[$i]['issue']='特码单双';
                break;
                case 'JQYS':
                $list[$i]['issue']='家禽野兽';
                break;
            }
                //投注类型:1,PMSX:平码生肖2,TMHM:特码号码3，TMBS：特码波色4，TMDX：特码大小5，TMDS：特码单双6，JQYS：家禽野兽
                $list[$i]['Issue']=$thetype=='PMSX'?'平码生肖':$thetype=='TMHM'?'特码号码':$thetype=='TMBS'?'特码波色':$thetype=='TMDX'?'特码大小':$thetype=='TMDS'?'特码单双':$thetype=='JQYS'?'家禽野兽':'';
            }
            $list=getzj($list,$ylist);//添加总计记录common/function.php
            if(count($list)>0){
                $list[0]['Count']=$count=count($t_bet->field('BetType,count(BetType) as count,sum(BetMoney) as summoney,sum(Profit) as profitorloss')->where($map)->group('BetType')->order('Id desc')->select());
                $list[0]['PageCount']=(string)ceil((double)$count/(double)$pagesize);
                $list[0]['starttime']=$starttime!=''?$starttime:date('Y-m-d 03:00:00', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));
                $list[0]['finishtime']=$finishtime!=''?$finishtime:date('Y-m-d H:i:s', time());
            }
            $this->ajaxReturn($list);
        }
        elseif ($type=='date') {
            $list=$t_bet->field("DATE_FORMAT(FROM_UNIXTIME(BetTime),'%y-%m%-%d') as date,count(BetTime) as count,sum(BetMoney) as summoney,sum(Profit) as profitorloss")->where($map)->group('date')->order('Id desc')->page($p,$pagesize)->select();
            $ylist=$t_bet->field("DATE_FORMAT(FROM_UNIXTIME(BetTime),'%y-%m%-%d') as date,count(BetTime) as count,sum(BetMoney) as summoney,sum(Profit) as profitorloss")->where($map)->group('date')->order('Id desc')->select();
            for($i=0;$i<count($list);$i++){
                $list[$i]['issue']=$list[$i]['date'];
            }
            $list=getzj($list,$ylist);//添加总计记录common/function.php
            if(count($list)>0){
                $list[0]['Count']=$count=count($t_bet->field("DATE_FORMAT(FROM_UNIXTIME(BetTime),'%y-%m%-%d') as date,count(BetTime) as count,sum(BetMoney) as summoney,sum(Profit) as profitorloss")->where($map)->group('date')->order('Id desc')->select());
                $list[0]['PageCount']=(string)ceil((double)$count/(double)$pagesize);
                $list[0]['starttime']=$starttime!=''?$starttime:date('Y-m-d 03:00:00', (time() - ((date('w') == 0 ? 7 : date('w')) - 1) * 24 * 3600));
                $list[0]['finishtime']=$finishtime!=''?$finishtime:date('Y-m-d H:i:s', time());
            }
            $this->ajaxReturn($list);
        }
    }
    

    //查询开奖历史
    public function recordlottery(){
        $pagesize=20;//每页显示的行数
        $start1=I('post.starttime','','htmlspecialchars');//开始时间
        $finish1=I('post.finishtime','','htmlspecialchars');//结束时间
        $starttime=$start1;
        $finishtime=$finish1;
        $issue=I('post.issue','','htmlspecialchars');//期号
        $p=(int)I('get.page',0);//要跳转的页码，get方法
        if(time()%600==0||time()%600<=35){
            $map['State']=array('in',[2,3]);
        }
        else{
            $map['State']=3;
        }
        if($starttime!=''&&$finishtime!=''){
            $start=strtotime($starttime.' 03:00:00');
            $finish=strtotime($finishtime.' 03:00:00'."+1 day");
            $map['Time'] = array('between',array($start,$finish));
        }
        else{
            $map['Time'] = array('between',array(strtotime('-1 month'),time()));
        }
        if($issue!=""){
            $map['Issue'] = $issue;
        }
        $t_history=M('t_history');

        $list = $t_history->where($map)->order('Id desc')->page($p,$pagesize)->select();
        for($i=0;$i<count($list);$i++){
            $list[$i]['tnumurl']=$list[$i]['tnumurl']<10?'0'.$list[$i]['tnumurl']:$list[$i]['tnumurl'];
            $list[$i]['time']=$date=date('Y-m-d H:i:s', $list[$i]['time']);
            $list[$i]['tbigurl']=$list[$i]['tbigurl']==0?'小':'大';
            $list[$i]['tdanshuangurl']=$list[$i]['tdanshuangurl']==0?'双':'单';
            $list[$i]['tanimaltypeurl']=$list[$i]['tanimaltypeurl']=='jq'?'家禽':'野兽';
        }
        if(count($list)>0){
            $list[0]['Count']=$count=count($t_history->where($map)->order('Id desc')->select());
            $list[0]['PageCount']=(string)ceil((double)$count/(double)$pagesize);
            $list[0]['starttime']=$starttime!=''?$starttime:date('Y-m-d H:i:s', strtotime('-1 month'));
            $list[0]['finishtime']=$finishtime!=''?$finishtime:date('Y-m-d H:i:s', time());
        }
        $this->ajaxReturn($list);
    }

    //查询最近一期的开奖记录
    public function getlasthistory(){
        $t_history=M('t_history');
        if(time()%600==0||time()%600<=35){
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
            $list[$i]['dx']=$hlist[$i]['tbigurl']==0?'小':'大';
            $list[$i]['ds']=$hlist[$i]['tdanshuangurl']==0?'双':'单';
            $list[$i]['jqys']=$hlist[$i]['tanimaltypeurl']=='jq'?'家禽':'野兽';
        }
        $this->ajaxReturn($list);
    }
    
    //查询最新配置
    public function getconfig(){
        $list = M('t_config')->order('id asc')->limit(14)->select();//赔率
        $this->ajaxReturn($list);
    }

    //查询当前时间和用户金钱
    public function getservertime(){
        $time = time();
        $data["server_time"] = date('Y-m-d H:i:s', time());

        $hour = (int)date("H");
        $minute = (int)date("i");
        $second = (int)date("s");
        if($second%10 == 0) {
            $map['UserId'] = session('user_id');
            $data["user_money"] = M('t_user')->where($map)->getfield('Money');
        }

        $data["issue"] = M('t_history')->where(['State' => 1])->getfield('Issue');
        $this->ajaxReturn($data);
    }
}