<?php
namespace Admin\Controller;
//use Think\Controller;
class RedirectController extends Controller {

    public function index() {
        $this->display("/Public/404");
    }
}