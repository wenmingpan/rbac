<?php

namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;

/**
 * Description of Role
 *
 * @author Administrator
 */
//class Role extends Controller
class Role extends Base
{
    public function index()
    {
        $list = Db::table('role')->select();

        $this->assign('list',$list);
        
        // 模板输出
        return $this->fetch('index');
    }
    
    
    public function add()
    {
        // 添加入库
        if (Request::instance()->isPost()) {
            $params = Request::instance()->param();
            $name = $params['name'];
            $status = $params['status'];
            $access_id = empty($params['access_id']) ? array(): $params['access_id']; // 角色id
            $res = Db::table('role')->where('name',$name)->find();
            if ($res) {
                $this->error('已存在，不要重复添加');
            }
            $data = ['name' => $name,
                    'status' => $status,
                    'created_time' => time(),
                    'updated_time' => time(),
                ];
            $res = Db::table('role')->insert($data);
            if($res) {
                // role_access 关系
//                $this->_setRoleAccess($role_id, $access_id);
                $this->success('新增成功', 'Role/index');
            } else{
                $this->error('新增失败');
            }
        }
        
//        $access = Db::table('access')->select();
//        $this->assign('list',$access);
        return view('add');
    }
    
    public function edit()
    {
        // 进入列表
        if (Request::instance()->isGet()) {
            $params = Request::instance()->param();
            $role = Db::table('role')->where('id',$params['id'])->find();
            // 权限
//            $access = Db::table('access')->select();
            // role_id 查询access_id
            $access_user = Db::table('role_access')->where('role_id', $params['id'])->select();
            $user_access_id = array_column($access_user, 'access_id');
            
            $this->assign('role',$role); // 角色名称
//            $this->assign('access',$access); // 所有权限
            $this->assign('user_access_id',$user_access_id); // 用户权限ID
            
            return $this->fetch('edit');
        }
        
        // 进行添加
        if (Request::instance()->isPost()) {
            $params = Request::instance()->param();
            // 修改用户数据

            $id = $params['id']; // 用户id
            $name = $params['name'];
            $status = $params['status'];
            $access_id = empty($params['access_id']) ? array(): $params['access_id']; // 角色id
            
            // 修改用户信息
            $data = ['name' => $name, 
                    'status' => $status,
                    'updated_time' => time(),
                ];
            // 更新用户
            $updat_user = Db::table('role')
                    ->where('id', $id)
                    ->update($data);
            // 设置用户角色关系
//            $this->_setRoleAccess($id, $access_id);
            if($updat_user) {
                $this->success('修改成功', 'role/index');
            } else{
                $this->error('修改失败');
            }
        }
    }
    
    /**
     *  配置权限
     */
    public function addaccess()
    {
        if (Request::instance()->isGet()) {
            $params = Request::instance()->param();
//            p($params);
            $this->assign('role_id',$params['id']); // 角色名称

            // 所有权限
            $list = Db::table('access')->field(['id','title','pid','status'])->select();
            $list = node_merge($list);
            // 用户权限
            $access_user = Db::table('role_access')->where('role_id', $params['id'])->select();
            $user_access_id = array_column($access_user, 'access_id');
            $this->assign('user_access_id',$user_access_id); // 用户权限ID
//            p($user_access_id);exit;
            $this->assign('list',$list);
            return $this->fetch('addaccess');
        }
        
        if (Request::instance()->isPost()) {
            $params = Request::instance()->param();
            $role_id = $params['role_id'];
            $access_id = empty($params['access_id']) ? array(): $params['access_id']; // 角色id
            foreach($access_id as $k=>$v) {
                if ($v == 'on') {
                    unset($access_id[$k]);
                }
            }
            
            // 设置用户角色关系
            $res = $this->_setRoleAccess($role_id, $access_id);
            if($res) {
                $this->success('修改成功', 'role/index');
            } else{
                $this->error('修改失败');
            }
        }
    }
    
    /**
     * 角色与权限的关系
     * @param type $role_id
     * @param type $access_id
     */
    private function _setRoleAccess($role_id, $access_id=array())
    {
        // 通过userid 查询role_id
        $res = Db::table('role_access')->where('role_id', $role_id)->select();
        $role_access_id = array_column($res, 'access_id');
        $result = array_diff($role_access_id, $access_id); // 求差集,
//        p($access_id);
//        p($role_access_id);
//        p($result);exit;
        
        // 修改用户权限信息
        if ($result) {  // 有值说明删除
            foreach ($result as $key => $value) {
                Db::table('role_access')->where(['role_id' => $role_id, 'access_id' => $value])->delete();
            }
        } else { // 无值进行添加
            foreach ($access_id as $key => $value) {
                if (!in_array($value, $role_access_id)) {
                    $data = ['role_id' => $role_id, 'access_id' => $value, 'created_time'=> time()];
                    Db::table('role_access')->insert($data);
                }
            }
        }
        return true;
    }
}
