<?php
namespace Admin\Controller;
//use Think\Controller;
class BulletinController extends BaseController {

    //公告页面显示
    public function index() {

        $this->assign('bul_active', 'active');
        $this->assign('bul_add_active', 'active');
    	$this->display('Index/Bulletin');
    }

    //新增公告
    public function add_bulletin() {
    	$data["Time"] = time();
    	$data["Bulletin"] = I('post.content');

    	$count = M('t_bulletin')->add($data);

    	if($count) {
    		echo "<script>alert('发布成功');history.go(-1)</script>";
    	} else {
    		echo "<script>alert('发布失败');history.go(-1)</script>";
    	}
    	
    }

}