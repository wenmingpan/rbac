<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Db;
use app\index\model\UserModel;


/**
 * Description of User
 *
 * @author Administrator
 */
//class User extends Controller
class User extends Base
{
    public function index()
    {
        $users = UserModel::all();
        $users = $users->toArray();
        $this->assign('users',$users);
        
//        return view('index');
        // 模板输出
        return $this->fetch('index');
    }
    
    
    public function add()
    {
        // 添加入库
        if (Request::instance()->isPost()) {
            $params = Request::instance()->param();

            $name = $params['name'];
            $email = $params['email'];
            $status = $params['status'];
            $role_ids = empty($params['role_id']) ? array(): $params['role_id']; // 角色id
            $data = ['name' => $name, 
                    'email' => $email, 
                    'status' => $status,
                    'created_time' => time(), 
                    'updated_time' => time(),
                ];
            $res = Db::table('user')->insert($data);
            $userid = Db::table('user')->getLastInsID();
            if($res) {     
                $this->_setUserRole($userid, $role_ids);
                $this->success('新增成功', 'User/index');
            } else{
                $this->error('新增失败');
            }
        }
        
        // 角色
        $role = Db::table('role')->select();
        $this->assign('role',$role);
        return view('add');
    }
    
    
    public function edit()
    {
        // 进入列表
        if (Request::instance()->isGet()) {
            $params = Request::instance()->param();
            
            $user = Db::table('user')->where('id',$params['id'])->find();
            // 角色
            $role = Db::table('role')->select();
            
            // 通过userid 查询role_id
            $role_ids = Db::table('user_role')->where('uid', $user['id'])->select();
            $user_roleid = array_column($role_ids, 'role_id');
            $this->assign('user',$user);
            $this->assign('role',$role);
            $this->assign('role_id',$user_roleid);
            
            return $this->fetch('edit');
        }
        
        // 进行添加
        if (Request::instance()->isPost()) {
            $params = Request::instance()->param();
            // 修改用户数据

            $userid = $params['id']; // 用户id
            $name = $params['name'];
            $email = $params['email'];
            $status = $params['status'];
            $role_ids = empty($params['role_id']) ? array(): $params['role_id']; // 角色id
             
            // 修改用户信息
            $data = ['name' => $name, 
                    'email' => $email, 
                    'status' => $status,
                    'updated_time' => time(),
                ];
            // 更新用户
            $updat_user = Db::table('user')
                    ->where('id', $userid)
                    ->update($data);
            // 设置用户角色关系
            $this->_setUserRole($userid, $role_ids);
            if($updat_user) {
                $this->success('修改成功', 'User/index');
            } else{
                $this->error('修改失败');
            }
        }
    }
    
    /**
     * 设置用户与角色之间的关联关系
     * 
     * @param type $userid
     * @param type $role_ids
     */
    private function _setUserRole($userid, $role_ids=array())
    {
        // 通过userid 查询role_id
        $res = Db::table('user_role')->where('uid', $userid)->select();
        $user_roleid = array_column($res, 'role_id');
        $result = array_diff($user_roleid, $role_ids); // 求差集,

        // 修改用户权限信息
        if ($result) {  // 有值说明删除
            foreach ($result as $key => $value) {
                Db::table('user_role')->where(['uid' => $userid, 'role_id' => $value])->delete();
            }
        } else { // 无值进行添加
            foreach ($role_ids as $key => $value) {
                if (!in_array($value, $user_roleid)) {
                    $data1 = ['uid' => $userid, 'role_id' => $value, 'created_time'=> time()];
                    Db::table('user_role')->insert($data1);
                }
            }
        }
    }
}
