<?php
/**
 * Created by 梁志鹏 on 17-9-11 上午9:30
 * Copyright (c) 2017 PhdChorus. All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: zkey
 * Date: 2017/9/11
 * Time: 上午9:30
 */

/*
 * 接口功能
 * 设置签到表status
 *
 * 参数
 * registTableID // 签到表id
 * status // 将签到表设置为该status
 *
 * 返回值
 * status // 0-成功 | 1-失败
 */

include_once('phdRecruitDatabaseManager.php');

// 获取参数
$_INPUT = json_decode(file_get_contents("php://input"));
$registTableID = $_INPUT->registTableID;
$status = $_INPUT->status;

// 定义返回值
const return_status = 'status';
const return_params = 'params';
$result = array();

$result[return_params] = $registTableID . $status;

$dbManager = new WXRecruitDatabaseManager();
$status = $dbManager->setRegistTableStatus($registTableID, $status);
if ($status == 0) {
    $result[return_status] = 0;
}
else if ($status == 1) {
    $result[return_status] = 1;
}

echo json_encode($result);

?>