<?php

namespace app\index\command;

use think\Db;
use think\facade\Env;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class Server extends Command
{
    private $serv;

    protected function configure()
    {
        $this->setName('server')->setDescription('description');
    }

    protected function execute(Input $input, Output $output)
    {
        $output->writeln('启动Swoole服务:');

        $this->serv = new \swoole_server('0.0.0.0', 9501);
        $this->serv->set(array(
            'worker_num' => 2,
            'daemonize' => false,
        ));

        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('Receive', array($this, 'onReceive'));

        $this->serv->start();
    }

    public function onStart($serv)
    {
        echo "Start\n";
    }

    public function onReceive($serv, $fd, $from_id, $data)
    {
        echo "查询条件:".json_encode($data)."\n";

        $fileName = 'temp'.time().'.csv';
        $path = Env::get('root_path').'public/static/'.$fileName;
        $file = fopen($path, 'w+');
        if ($file === false)
            exit('无法创建临时文件');

        $serv->send($fd, $fileName);

        $pageSize = 500;
        $pageNum = 0;

        while (true) {
            $ret = Db::table('t_user')
                    ->field('id,nickname,mobile,email') // 避免select *
                    ->limit($pageNum*$pageSize, $pageSize) // 分页
                    ->select();

            if (empty($ret) || $pageNum>50)
                break;
            $pageNum++;

            // 写入文件
            $i = 0;
            $buffer = '';
            foreach ($ret as $key => $value) {
                $buffer .= implode(',', $value)."\n";
                if ($i%100 == 0){
                    fwrite($file, $buffer);
                }
            }
        }

        fclose($file);

        echo "查询完毕\n";
        $serv->close($fd);
    }
}
