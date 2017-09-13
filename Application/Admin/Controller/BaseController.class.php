<?php
namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller {
    function _initialize() {
        if(!is_admin_login()) {
            redirect('/Auth/login');
        } else {
        	$this->assign('uname', session("admin_name"));
        }
    }

    function _empty(){
        header("Location:/Admin/Public/404.html");
    }
}