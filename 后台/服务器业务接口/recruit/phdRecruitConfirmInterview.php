<?php
/**
 * Created by 梁志鹏 on 17-9-10 下午1:15
 * Copyright (c) 2017 PhdChorus. All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: zkey
 * Date: 2017/9/10
 * Time: 下午1:15
 */

/*
 * 接口功能
 * 确认参加面试
 *
 * 参数
 * registTableID // 签到表ID
 * contactName // 姓名
 *
 * 返回
 * status // 0-成功 | 1-失败 | 5-失败(参数错误)
 */

include_once('phdRecruitDatabaseManager.php');

// 获取参数
$_INPUT = json_decode(file_get_contents("php://input"));
$registTableID = $_INPUT->registTableID;
$registTableID = intval($registTableID);
$contactName = $_INPUT->contactName;

// 定义返回值
const return_status = 'status';
const return_params = 'params';
$result = array();

$result[return_params] = $registTableID . $contactName;

if ($registTableID < 1 || strlen($contactName) <= 0) {
    $result[return_status] = 5;
    echo json_encode($result);
    exit();
}

$dbManager = new WXRecruitDatabaseManager();

$status = $dbManager->confirmInterview($registTableID, $contactName);

if ($status == 0) {
    $result[return_status] = 0;
}
else if ($status == 1) {
    $result[return_status] = 1;
}

echo json_encode($result);

?>

