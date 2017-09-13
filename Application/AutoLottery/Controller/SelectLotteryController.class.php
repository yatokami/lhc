<?php
namespace AutoLottery\Controller;
use Think\Controller;
class SelectLotteryController extends Controller {
    public function index(){
        $t_history=M('t_history');
        if(time()%600==0||time()%600<=64){
            $map['State']=array('in',[2,3]);
        }
        else{
            $map['State']=3;
        }
        $hlist=$t_history->where($map)->limit(1)->order('Id desc')->select();
        //dump($hlist);
        //虎--龙--马--猪--猴--羊--鼠:8--6--40--35--2--15--10
        $arr1=split(':', $hlist[0]['num']);
        $sx1=split("--", $arr1[0]);
        $hm1=split("--", $arr1[1]);
        
        $is=substr($hlist[0]['issue'],8,3);
        $stime=date('Y-m-d', $hlist[0]['time'])." 10:00:00";
        $addtime='+'.(((int)$is-18)*10).' minute';

        if(((int)$is)>18){
        $stime=date('Y-m-d', $hlist[0]['time'])." 10:00:00";
        $addtime='+'.(((int)$is-19)*10).' minute';
        }
        else{
            $stime=date('Y-m-d', $hlist[0]['time'])." 00:00:00";
            $addtime='+'.(((int)$is)*10).' minute';
        }

        $ltime=date('Y-m-d H:i:s',strtotime($addtime,strtotime($stime)));



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

        $this->tj=$tj=session('tj');
        $this->ej=$ej=session('ej');
        $this->sj=$sj=session('sj');

        $this->ltime=$ltime;

        if(((int)$is)==18){
            $time=date('Y-m-d', $hlist[0]['time'])." 10:00:00";
            $this->nexttime=$ltime2=$time;
        }
        else{
            $this->nexttime=$ltime2=date('Y-m-d H:i:s',strtotime('+10 minute',strtotime($ltime)));
        }
        $this->shoupiaotime=date('Y-m-d H:i:s',strtotime('-3 minute',strtotime($ltime2)));
        if(session('?sumbetmoney')){
            $sumbetmoney=session('sumbetmoney');
        }
        else{
            $sumbetmoney=29804635;
        }
        
        $this->sumbetmoney =$sumbetmoney;
        $this->lottertime =$lottertime;
        $this->fptime =$fptime;
        $this->hm1 =$hm1[0];
        $this->hm2 =$hm1[1];
        $this->hm3 =$hm1[2];
        $this->hm4 =$hm1[3];
        $this->hm5 =$hm1[4];
        $this->hm6 =$hm1[5];
        $this->hm7 =$hm1[6];
        $this->serverdatetime=date('m/d/y H:i', time());
        $this->assign('issue',$hlist[0]['issue']);
        $this->assign('num',$hlist[0]['num']);
        $this->assign('dx',$hlist[0]['tbigurl']==-1?'和':($hlist[0]['tbigurl']==0?'小':'大'));
        $this->assign('ds',$hlist[0]['tdanshuangurl']==-1?'和':($hlist[0]['tdanshuangurl']==0?'双':'单'));
        $this->assign('jqys',$hlist[0]['tanimaltypeurl']=='jq'?'家 禽':'野 兽');
        $this->display();

    }

    public function history(){
        $starttime=I('post.starttime','','htmlspecialchars');//开始时间
        $finishtime=I('post.finishtime','','htmlspecialchars');//结束时间
        $issue=I('post.issue','','htmlspecialchars');//期号
        $p=(int)I('get.page',0);//要跳转的页码，get方法
        $map['State'] = 3;
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

        $count      = count($t_history->where($map)->order('Id desc')->select());// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出

        $list = $t_history->where($map)->order('Id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        for($i=0;$i<count($list);$i++){
            $is=substr($list[$i]['issue'],8,3);
            $stime=date('Y-m-d', $list[$i]['time'])." 10:00:00";
            $addtime='+'.(((int)$is-18)*10).' minute';
            
            if(((int)$is)>18){
            $stime=date('Y-m-d', $list[$i]['time'])." 10:00:00";
            $addtime='+'.(((int)$is-19)*10).' minute';
            }
            else{
                $stime=date('Y-m-d', $list[$i]['time'])." 00:00:00";
                $addtime='+'.(((int)$is)*10).' minute';
            }
            $ltime=date('Y-m-d H:i:s',strtotime($addtime,strtotime($stime)));
                        $arr=split(':', $list[$i]['num']);
            $hm=split("--", $arr[1]);
            $list[$i]['hm1']=$hm[0];
            $list[$i]['hm2']=$hm[1];
            $list[$i]['hm3']=$hm[2];
            $list[$i]['hm4']=$hm[3];
            $list[$i]['hm5']=$hm[4];
            $list[$i]['hm6']=$hm[5];
            $list[$i]['hm7']=$hm[6];
            $list[$i]['lotterytime']=$ltime;
            $list[$i]['tnumurl']=$list[$i]['tnumurl']<10?'0'.$list[$i]['tnumurl']:$list[$i]['tnumurl'];
            $list[$i]['time']=$date=date('Y-m-d H:i:s', $list[$i]['time']);
            $list[$i]['tbigurl']=$list[$i]['tbigurl']==-1?'和':($list[$i]['tbigurl']==0?'小':'大');
            $list[$i]['tdanshuangurl']=$list[$i]['tdanshuangurl']==-1?'和':($list[$i]['tdanshuangurl']==0?'双':'单');
            $list[$i]['tanimaltypeurl']=$list[$i]['tanimaltypeurl']=='jq'?'家禽':'野兽';

        }
        $list[0]['starttime']=date('Y-m-d H:i:s', strtotime('-1 month'));
        $list[0]['finishtime']=date('Y-m-d H:i:s', time());
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display(); // 输出模板
    }
}