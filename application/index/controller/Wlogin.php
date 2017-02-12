<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Session;

/**
 * Description of Wlogin
 *
 * @author matt
 */
class Wlogin extends Controller{
    //put your code here
    // http://test.rbac.com/index/wlogin/login/userid/1
    public function login()
    {
        $params = Request::instance()->param();
            // 修改用户数据
        $userid = $params['userid'];
        
        // 查询用户
        $user = Db::table('user')->where(['id' => $userid, 'status'=>1])->find();
        
        if ($user) {
            Session::set('useid', $user['id']);
            Session::set('username', $user['name']);
            
            $this->success('登录成功', '/');
        }
          
        
    }
}
