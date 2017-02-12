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

/**
 * Description of Access
 *
 * @author Administrator
 */
class Access extends Controller{
    //put your code here
    
    
    public function index()
    {
        $list = Db::table('access')->select();
        $this->assign('list',$list);

        return $this->fetch('index');
    }
    
    public function add()
    {
        // 添加入库
        if (Request::instance()->isPost()) {
            $params = Request::instance()->param();
            $title = $params['title'];
            $status = $params['status'];
            $urls = $params['urls'];
            $urls = explode("\r\n", $urls);
            
            $res = Db::table('access')->where('title',$title)->find();
            if ($res) {
                $this->error('已存在，不要重复添加');
            }
            $data = ['title' => $title,
                    'status' => $status,
                    'urls' => json_encode($urls),
                    'created_time' => time(),
                    'updated_time' => time(),
                ];
            $res = Db::table('access')->insert($data);
            if($res) {
                $this->success('新增成功', 'access/index');
            } else{
                $this->error('新增失败');
            }
        }
        
        return view('add');
    }
    
    public function edit()
    {
        // 进入列表
        if (Request::instance()->isGet()) {
            $params = Request::instance()->param();
            
            $access = Db::table('access')->where('id',$params['id'])->find();
            
            $this->assign('access',$access);
            
            return $this->fetch('edit');
        }
        
        // 进行添加
        if (Request::instance()->isPost()) {
            $params = Request::instance()->param();
            // 修改用户数据
            $id     = $params['id'];
            $title  = $params['title'];
            $status = $params['status'];
            $urls   = $params['urls'];
            $urls   = explode("\r\n", $urls);
            
            // 修改用户信息
            $data = ['title' => $title,
                    'status' => $status,
                    'urls' => json_encode($urls),
                    'updated_time' => time(),
                ];
            
            // 更新用户
            $updated = Db::table('access')
                    ->where('id', $id)
                    ->update($data);
            if($updated) {
                $this->success('修改成功', 'access/index');
            } else{
                $this->error('修改失败');
            }
        }
    }
}
