<?php
// 应用公共文件

function ajaxReturn ($code, $status, $data) {
    return json([
        'code' => $code,
        'status'  => $status,
        'data' => $data
    ]);
}