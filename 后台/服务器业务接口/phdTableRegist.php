<?php

/*
 * 签到
 *
 * 接口名
 * phdTableRegist.php
 *
 * 参数
 * registTableDate // 签到表日期
 * registTableType // 签到表类型
 * registLocationType // 签到地点
 * selectedContactPart // 签到人姓名
 * selectedContactName //  签到人所在声部
 *
 * 返回值
 * status // 0-成功 | 1-失败(签到表不存在) | 2-失败(联系人不存在) | 3-失败(已签到，无需重复签到) | 4-失败(系统代码bug) | 5-失败(参数错误)
 *
 */

include_once('phdUtils.php');
include_once('phdDatabaseManager.php');

// 获取参数
$_INPUT = json_decode(file_get_contents("php://input"));
$registTableDate = $_INPUT->registTableDate;
$registTableType = $_INPUT->registTableType;
$registLocationType = $_INPUT->registLocationType;
$selectedContactPart = $_INPUT->selectedContactPart;
$selectedContactName = $_INPUT->selectedContactName;

// 定义返回值
const return_status = 'status';
const return_params = 'params';
$result = array();

$result[return_params] = $registTableDate . $registTableType . $registLocationType . $selectedContactPart . $selectedContactName;

// 校验参数格式是否正确
if (!(isValidTableDate($registTableDate) && isValidTableType($registTableType) && isValidLocationType($registLocationType) && isValidPartType($selectedContactPart))) {
    $result[return_status] = '5';
    echo json_encode($result);
    exit();
}

$dbManager = new WXDatabaseManager();

// 检查签到表是否存在
$registTableID = $dbManager->idOfRegistTable($registTableDate, $registTableType, $registLocationType);
if ($registTableID == -1) {
    $result = [return_status] = '1';
    echo json_encode($result);
    exit();
}

// 联系人是否存在
$contactID = $dbManager->idOfContact($selectedContactName, $selectedContactPart);
if ($contactID == -1) {
    $result = [return_status] = '2';
    echo json_encode($result);
    exit();
}

// 签到
$registStatus = $dbManager->tableRegist($registTableID, $contactID);
if ($registStatus == 0) {
    $result[return_status] = '0';
}
else if ($registStatus == 1) {
    $result[return_status] = '3';
}
else {
    $result[return_status] = '4';
}

echo json_encode($result);

?>