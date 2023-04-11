<?php
declare (strict_types = 1);

namespace app\admin\controller;

use app\Request;
use think\facade\Db;
use think\facade\Filesystem;
class TestController
{
    public function test (Request $request) {
        echo 111;exit;
        $res = Db::table('test')->select();

        return ajaxReturn('aaa','',$res);
        
    }

    public function upload(Request $request){
       
        $fileimg = $request->file();
        return ajaxReturn('','',$request->param());

    }
}