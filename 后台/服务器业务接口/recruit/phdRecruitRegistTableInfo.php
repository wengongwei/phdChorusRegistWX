<?php
/**
 * Created by 梁志鹏 on 17-9-9 上午11:15
 * Copyright (c) 2017 PhdChorus. All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: zkey
 * Date: 2017/9/9
 * Time: 上午11:15
 */

/*
 * 接口功能
 * 签到表详细信息
 *
 * 参数
 * registTableID // 签到表ID
 * wxNickname // 微信昵称
 *
 * 返回值
 * registTable // 签到表信息 {id: 14, date: 2017-08-15, location: 中关村, statusDescription:可用于面试签到}
 *
 */


include_once('phdRecruitDatabaseManager.php');

// 获取参数
$_INPUT = json_decode(file_get_contents("php://input"));
$registTableID = $_INPUT->registTableID;
$wxNickname = $_INPUT->wxNickname;

// 定义返回值
const return_status = 'status';
const return_params = 'params';
const return_registTable = 'registTable';

$result = array();

$result[return_params] = $registTableID . $wxNickname;

$dbManager = new WXRecruitDatabaseManager();

// 鉴权
if($dbManager->userAuthorizedStatus($wxNickname, 'ALL') != 1) {
    $result[return_status] = '5';
    echo json_encode($result);
    exit();
}

// 查询签到表信息
$table = $dbManager->registTableInfo($registTableID);
$tableStatus = $table['status'];
$statusDescription = '';
if ($tableStatus == 0) {
    $statusDescription = '已禁用';
}
else if ($tableStatus == 1) {
    $statusDescription = '可用于报名和面试通知';
}
else if ($tableStatus == 2) {
    $statusDescription = '可用于现场面试签到';
}


$tableInfo = array('id'=>$table['id'], 'date'=>$table['date'], 'location'=>$table['location'], 'statusDescription'=>$statusDescription);

$result[return_status] = 0;
$result[return_registTable] = $tableInfo;

echo json_encode($result);


?>