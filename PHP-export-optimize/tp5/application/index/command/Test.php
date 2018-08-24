<?php

namespace app\index\command;

use think\Db;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class Test extends Command
{
    protected function configure()
    {
        $this->setName('test')->setDescription('description');
    }

    protected function execute(Input $input, Output $output)
    {
        $max = 200000;

        // TODO:改用多进程
        for ($i=0; $i<$max; $i++) {
            var_dump($i);
            $ret = Db::table('t_user')->insert($this->getData(), true);
            var_dump($ret);
        }
        
        $output->writeln('TestCommand:');
    }

    private function getData()
    {
        $truename = array(
            '张三', '李四', '王五', '赵六'
        );

        $nickname = array(
            'abc', 'apple', 'banana', 'tomato', 'potato'
        );

    	return array(
            'headurl' => 'https://avatars1.githubusercontent.com/u/7712043?s=460&v=4',
            'password' => hash('md5', time()),
    		'nickname' => $nickname[array_rand($nickname)],
            'truename' => $truename[array_rand($truename)],
            'province' => '测试省份',
            'city'  => '测试城市',
            'address' => '测试具体的地址,某某村，某某街道',
            'nation' => '汉族',
            'age' => rand(1, 100),
            'desc' => '这里是描述，这里是描述，这里是描述，这里是描述，这里是描述，',
            'reg_src' => rand(1, 10),
            'reg_at' => time(),
            'sex' => rand(1, 2),
            'birthday' => '1990-01-01',
            'last_login_ip' => long2ip(rand(0, "4294967295")),
            'last_login_time' => strtotime(time()),
            'email' => rand(0, "4294967295").'@qq.com',
            'mobile' => '13450389657',
            'level' => rand(1, 10),
            'id_card' => '441481199401045096',
            'bank_card' => '62378697860354500'
    	);
    }
}
