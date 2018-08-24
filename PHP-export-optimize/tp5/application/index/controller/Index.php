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
}
