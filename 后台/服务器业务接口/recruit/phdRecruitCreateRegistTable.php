<?php
/**
 * Created by 梁志鹏 on 17-9-6 上午9:20
 * Copyright (c) 2017 PhdChorus. All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: zkey
 * Date: 2017/9/6
 * Time: 上午9:20
 */

/*
 * 接口功能
 * 招新-创建签到表
 *
 * 参数
 * registTableDate // 日期 '2018-08-06'
 * registLocationType // 园区 '中关村' OR '雁栖湖'
 * wxNickname // 小程序使用者的nickname，用以进行鉴权操作
 *
 * 返回值
 * status // 0-成功 | 1-失败（签到表已存在）| 2-失败（创建签到表失败） | 3-参数错误 | 4-服务器代码bug | 5-无操作权限
 *
 */

include_once('phdRecruitDatabaseManager.php');

// 获取参数
$_INPUT = json_decode(file_get_contents("php://input"));
$registTableDate = $_INPUT->registTableDate;
$registLocationType = $_INPUT->registLocationType;
$wxNickname = $_INPUT->wxNickname;

// 定义返回值
const return_status = 'status';
const return_params = 'params';
$result = array();

$result[return_params] = $registTableDate . $registLocationType . $wxNickname;

$dbManager = new WXRecruitDatabaseManager();

// 判定用户是否有进行此操作的权限
if($dbManager->userAuthorizedStatus($wxNickname, 'ALL') != 1) {
    $result[return_status] = '5';
    echo json_encode($result);
    exit();
}

// 是否已存在相同的签到表
$tableID = $dbManager->idOfRegistTable($registTableDate, $registLocationType);
if ($tableID != -1) {
    $result[return_status] = '1';
    echo json_encode($result);
    exit();
}

// 创建签到表
$insertSuccess = $dbManager->insertRegistTable($registTableDate, $registLocationType);
if ($insertSuccess == 0) {
    $result[return_status] = '0';
    echo json_encode($result);
    exit();
}
else if ($insertSuccess == 1) {
    $result[return_status] = '2';
    echo json_encode($result);
    exit();
}

$result[return_status] = '4';
echo json_encode($result);

?>