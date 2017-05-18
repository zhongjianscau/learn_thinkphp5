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
use think\Exception;

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

    /**
     * 添加数据
     * @author Jean
     */
    public function addData()
    {
        $data = [
            'account' => 'Jean',
            'password' => 'jean123'
        ];
        try {
            $insert = Db::table('test')->insert($data);
            echo $insert;
            echo '插入' . $insert . '条数据';
            echo '最后一次插入的ID为：' . Db::table('test')->getLastInsID();
        } catch (Exception $e) {
            echo '插入数据失败，失败原因：' . $e->getMessage();
        }
        //直接添加数据后返回主键
        $data = [
            'account' => 'Jean2',
            'password' => 'password'
        ];
        try {
            $insertId = Db::table('test')->insertGetId($data);
            echo '添加的主键为：' . $insertId;
        } catch (Exception $e) {
            echo '直接添加数据失败，失败原因：' . $e->getMessage();
        }
        //使用data方法添加
        Db::table('test')
            ->data([
                'account' => 'jean3',
                'password' => 'password'
            ])
            ->insert();
    }

    /**
     * 更新数据
     * @author Jean
     */
    public function updateData()
    {
        //更新前
        $data = Db::table('test')->where('id', 1)->find();
        echo '更新前的数据：';
        dump($data);
        try {
            $update = Db::table('test')->where(['id' => 1])->update(['account' => 'ThinkPhpTest']);
            if ($update == 0) {
                throw new Exception('没有更新任何数据');
            }
            echo '更新' . $update . '条数据，更新后的数据为：';
            $data = Db::table('test')->where(['id' => 1])->find();
            dump($data);
        } catch (Exception $e) {
            echo '更新出错，错误原因为：' . $e->getMessage();
        }

        //自增
        Db::name('test')->where('id', 1)->setInc('login_time');

        //延迟更新
        //TODO:发现有问题：1.不会延迟更新，2.数次刷新更新次数会出错
        echo '正在进行延迟更新；';
        try {
            Db::name('test')->where(['id' => 1])->setInc('login_time', 1, 10);
            echo '更新完成';
        } catch (Exception $e) {
            echo '延迟更新出错，出错原因为：' . $e->getMessage();
        }

        //快捷更新
        try {
            //inc和dec不能对同一字段同时操作，否则只有最后一个操作才成功
            Db::name('test')
                ->where(['id' => 2])
                ->inc('login_time', 10)
                ->dec('login_time', 5)
                ->exp('account', 'UPPER(account)')
                ->update();
            echo '快捷更新完成';
        } catch (Exception $e) {
            echo '快捷更新出错，错误原因为：' . $e->getMessage();
        }
    }

    /**
     * 删除数据
     * @author Jean
     */
    public function deleteData()
    {
        try {
            $start = time();
            $delete = Db::name('test')->where('id', '>', 10000)->delete();
            $end = time();
            echo '删除了' . $delete . '条数据，耗时：' . ($end - $start) . '秒';
        } catch (Exception $e) {
            echo '删除失败，失败原因：' . $e->getMessage();
        }
    }
}