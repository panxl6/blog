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

}
