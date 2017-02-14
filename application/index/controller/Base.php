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
            // 取出用户角色权限urls
            $access = Session::get('access');
            if(!$access) {
                $access = $this->_getUserAccess($userid);
            }
            // User/edit
            $action = strtolower(CONTROLLER_NAME.'/'.ACTION_NAME);
            if (!in_array($action, $access)) {
                $this->error('没有权限');
            }

       }
    }
    
    /**
     * 获取用户权限列表
     * @param type $userid
     */
    private function _getUserAccess($userid)
    {
        // 获取用户角色的权限id
        $res = Db::query("select u.uid,u.role_id,r.access_id from user_role as u left join role_access as r on u.role_id=r.role_id where u.uid={$userid}");
        $user_access_id = array_column($res, 'access_id');
        $access_ids = implode(',', $user_access_id);
        // 权限ID 查询权限urls
        $urls = Db::table('access')->where('id','in',$access_ids)->column('urls');
        $access = array();
        foreach ($urls as $value) {
            $arr = json_decode($value);
            $access = array_merge($access, $arr);
        }
        Session::set('access',$access);
        return $access;
    }
        
}
