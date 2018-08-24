<?php
namespace app\index\controller;

use think\Db;
use think\facade\Env;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Index
{
    public function index()
    {
        return 'hello world';
    }

    // 版本1
    public function exportUserV1()
    {
        // Model层：数据查询
    	$ret = Db::table('t_user')
                ->limit(20000)
                ->select();

        // Logic层:处理业务逻辑
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', '用户ID');
        $sheet->setCellValue('B1', '用户昵称');
        $sheet->setCellValue('C1', '手机号');
        $sheet->setCellValue('D1', '邮箱');

        $i = 2;
        foreach ($ret as $key => $value) {
            $sheet->setCellValue('A'.$i, $value['id']);
            $sheet->setCellValue('B'.$i, $value['nickname']);
            $sheet->setCellValue('C'.$i, $value['mobile']);
            $sheet->setCellValue('D'.$i, $value['email']);

            $i++;
        }

        // Controller层：跟用户直接交互
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="file.xlsx"');
        $writer->save("php://output");
    }

    // 版本2
    public function exportUserV2()
    {
        $path = Env::get('root_path').'public/static/temp'.time().'.csv';
        $file = fopen($path, 'w+');
        if ($file === false)
            exit('无法创建临时文件');

        $pageSize = 500;
        $pageNum = 0;

        while (true) {
            $ret = Db::table('t_user')
                    ->field('id,nickname,mobile,email') // 避免select *
                    ->limit($pageNum*$pageSize, $pageSize) // 分页
                    ->select();

            if (empty($ret) || $pageNum>5)
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
        ob_end_clean();
        header("Pragma: public");
        header("Expires: 0");
        header('Content-type: text/plain');
        header('Content-Length: '.filesize($path));
        header('Content-Disposition:attachment;filename="导出用户.csv"');
        readfile($path);
        unlink($path);
    }
}
