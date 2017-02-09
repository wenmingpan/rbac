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
class Role extends Controller
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
                $this->success('新增成功', 'Role/index');
            } else{
                $this->error('新增失败');
            }
        }
        
        
        return view('add');
    }
}
