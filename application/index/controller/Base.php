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
 * Description of Base
 *
 * @author matt
 */
define('CONTROLLER_NAME',Request::instance()->controller());
define('MODULE_NAME',Request::instance()->module());
define('ACTION_NAME',Request::instance()->action());

class Base extends Controller{
    //put your code here
    public function _initialize()
    {
       // 判断是否登录
       $userid = Session::get('useid');
       $is_admin = Session::get('is_admin');
       if($is_admin) {
           return true;
       }
       if ($userid) {
            // 取出用户角色
            $role_ids = Db::table('user_role')->where(['uid'=>$userid])->column('role_id');
            $role_ids = implode(',', $role_ids);
            // 取出角色对应的权限
            $access_ids = Db::table('role_access')->where('role_id','in',$role_ids)->column('access_id');
            $access_ids = implode(',', $access_ids);
            // 权限ID 查询权限urls
            $urls = Db::table('access')->where('id','in',$access_ids)->column('urls');
//            p($urls);
            $access = array();
            foreach ($urls as $value) {
                $arr = json_decode($value);
                $access = array_merge($access, $arr);
            }
            // User/edit
            $action = strtolower(CONTROLLER_NAME.'/'.ACTION_NAME);
            
            if (!in_array($action, $access)) {
                $this->error('没有权限');
            }

           
       }
    }
}
