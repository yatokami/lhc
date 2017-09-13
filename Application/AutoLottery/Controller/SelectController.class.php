<?php
namespace AutoLottery\Controller;
use Think\Controller;
class SelectController extends Controller {
    public function index(){
        $this->display();
    }

    //查询最近一期的开奖记录
    public function getlasthistory(){
        $issue=I('post.issue',0);//期号
        $t_history=M('t_history');
        if(time()%600==0||time()%600<=64){
            $map['State']=array('in',[2,3]);
        }
        else{
            $map['State']=3;
        }
        if($issue!=0){
            $map['Issue'] = $issue;
        }
        $hlist=$t_history->where($map)->limit(1)->order('Id desc')->select();
        $list=null;
        //dump($hlist);
        //虎--龙--马--猪--猴--羊--鼠:8--6--40--35--2--15--10
        if(!session('?issue')){
            $gailv=mt_rand(0,100);
            session('issue',$hlist[0]['issue']);
            session('tj',$gailv>1?'-':'1');
            session('ej',$gailv>50?'1':'2');
            session('sj',mt_rand(8,20));
            if($tj!='-'){
            $sbetmoney=(int)$ej*586860+(int)$sj*58100+mt_rand(500000,1500000);
            }
            else{
                $sbetmoney=(int)$tj*5969490+(int)$ej*586860+(int)$sj*58100+mt_rand(500000,1500000);
            }
            session('sumbetmoney',$sbetmoney);
        }
        else{
            if((int)substr(session('issue'),8,3)<(int)substr($hlist[0]['issue'],8,3)){
                $gailv=mt_rand(0,100);
                session('issue',$hlist[0]['issue']);
                session('tj',$gailv>1?'-':'1');
                session('ej',$gailv>50?'1':'2');
                session('sj',mt_rand(8,20));
                if($tj!='-'){
                $sbetmoney=(int)$ej*586860+(int)$sj*58100+mt_rand(500000,1500000);
                }
                else{
                    $sbetmoney=(int)$tj*5969490+(int)$ej*586860+(int)$sj*58100+mt_rand(500000,1500000);
                }
                session('sumbetmoney',$sbetmoney);
            }
        }
        for($i=0;$i<count($hlist);$i++){
            if(session('?sumbetmoney')){
                $sumbetmoney=session('sumbetmoney');
            }
            else{
                $sumbetmoney=29804635;
            }
            $list[$i]['sumbetmoney']=$sumbetmoney;
            $is=substr($hlist[$i]['issue'],8,3);
            $stime=date('Y-m-d', time())." 10:00:00";
            $addtime='+'.(((int)$is-18)*10).' minute';
            
            if(((int)$is)>18){
            $stime=date('Y-m-d', $hlist[$i]['time'])." 10:00:00";
            $addtime='+'.(((int)$is-19)*10).' minute';
            }
            else{
                $stime=date('Y-m-d', $hlist[$i]['time'])." 00:00:00";
                $addtime='+'.(((int)$is)*10).' minute';
            }
            $ltime=date('Y-m-d H:i:s',strtotime($addtime,strtotime($stime)));
            $list[$i]['lotterytime']=$ltime;
            $list[$i]['nexttime']=$ltime2=date('Y-m-d H:i:s',strtotime('+10 minute',strtotime($ltime)));
            $list[$i]['shoupiaotime']=date('Y-m-d H:i:s',strtotime('-3 minute',strtotime($ltime2)));

            $list[$i]['issue']=$hlist[$i]['issue'];
            $list[$i]['issue1']=substr($hlist[$i]['issue'], 4,4);
            $list[$i]['issue2']=substr($hlist[$i]['issue'], 8,3);
            $arr=split(':', $hlist[$i]['num']);
            $sx=split("--", $arr[0]);
            $hm=split("--", $arr[1]);

            $list[$i]['tj']=session('tj');
            $list[$i]['ej']=session('ej');
            $list[$i]['sj']=session('sj');

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
            $list[$i]['jqys']=$hlist[$i]['tanimaltypeurl']=='jq'?'家禽':'野兽';
        }
        $this->ajaxReturn($list);
    }

    //查询开奖历史
    public function recordlottery(){
        $pagesize=20;//每页显示的行数
        $starttime=I('post.starttime','','htmlspecialchars');//开始时间
        $finishtime=I('post.finishtime','','htmlspecialchars');//结束时间
        $issue=I('post.issue','','htmlspecialchars');//期号
        $p=(int)I('get.page',0);//要跳转的页码，get方法
        if(time()%600==0||time()%600<=35){
            $map['State']=array('in',[2,3]);
        }
        else{
            $map['State']=3;
        }
        if($strtotime!=''&&$finishtime!=''){
            $map['BetTime'] = array('egt',strtotime($starttime.' 00:00:00'));
            $map['BetTime'] = array('elt',strtotime($finishtime.' 23:59:59'));
        }
        else{
            $map['BetTime'] = array('egt',strtotime('-1 month'));
            $map['BetTime'] = array('elt',time());
        }
        if($issue!=""){
            $map['Issue'] = $issue;
        }
        $t_history=M('t_history');

        $list = $t_history->where($map)->order('Id desc')->page($p,$pagesize)->select();
        for($i=0;$i<count($list);$i++){
            $list[$i]['tnumurl']=$list[$i]['tnumurl']<10?'0'.$list[$i]['tnumurl']:$list[$i]['tnumurl'];
            $list[$i]['time']=$date=date('Y-m-d H:i:s', $list[$i]['time']);
            $list[$i]['tbigurl']=$list[$i]['tbigurl']==-1?'和':($list[$i]['tbigurl']==0?'小':'大');
            $list[$i]['tdanshuangurl']=$list[$i]['tdanshuangurl']==-1?'和':($list[$i]['tdanshuangurl']==0?'双':'单');
            $list[$i]['tanimaltypeurl']=$list[$i]['tanimaltypeurl']=='jq'?'家禽':'野兽';
        }
        $list[0]['Count']=$count=count($t_history->where($map)->order('Id desc')->select());
        $list[0]['PageCount']=(string)ceil((double)$count/(double)$pagesize);
        $list[0]['starttime']=date('Y-m-d H:i:s', strtotime('-1 month'));
        $list[0]['finishtime']=date('Y-m-d H:i:s', time());
        $this->ajaxReturn($list);
    }
}