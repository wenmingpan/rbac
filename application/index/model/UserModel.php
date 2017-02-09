<?php

namespace app\index\model;

use think\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $resultSetType = 'collection'; // 数据集返回类型
    
}
