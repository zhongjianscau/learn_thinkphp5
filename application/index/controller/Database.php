<?php
// +----------------------------------------------------------------------
// | Get Everybody Coding [ CODE CHANGES THE WORLD ]
// +----------------------------------------------------------------------
// | Author: Jean
// +----------------------------------------------------------------------
// | Date: 2017/5/2
// +----------------------------------------------------------------------
// | Time: 23:16
// +----------------------------------------------------------------------
namespace app\index\controller;

use think\Controller;
use think\Db;

class Database extends Controller
{
    /**
     * 使用数据库配置连接数据库
     * @author Jean
     */
    public function connectByConfig()
    {
        $data = Db::name('db')->select();
        dump($data);
    }

    /**
     * 使用方式配置连接数据库
     * @author Jean
     */
    public function connectByFunction()
    {
        //数组方式
        $connect = Db::connect([
            // 数据库类型
            'type'            => 'mysql',
            // 服务器地址
            'hostname'        => '127.0.0.1',
            // 数据库名
            'database'        => 'test',
            // 用户名
            'username'        => 'root',
            // 密码
            'password'        => 'root',
            // 端口
            'hostport'        => '3306',
            // 连接dsn
            'dsn'             => '',
            // 数据库连接参数
            'params'          => [],
            // 数据库编码默认采用utf8
            'charset'         => 'utf8',
            // 数据库表前缀
            'prefix'          => '',
        ]);
        //字符串方式
//        $connect = Db::connect('mysql://root:root@127.0.0.1:3306/test#utf8');
        $data = $connect->name('test')->select();
        dump($data);
    }

    /**
     * 使用模型类定义连接数据库
     * @author Jean
     */
    public function connectByModel()
    {
        $databaseModel = new \app\index\model\Test();
        $data = $databaseModel->findAll();
        dump($data);
    }
}