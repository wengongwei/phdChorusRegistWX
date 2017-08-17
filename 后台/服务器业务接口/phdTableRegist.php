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
 * registContactID // 团员ID
 * wxNickname // 小程序使用者的nickname，用以进行鉴权操作
 *
 * 返回值
 * status // 0-成功 | 1-失败(签到表不存在) | 2-失败(联系人不存在) | 3-失败(已签到，无需重复签到) | 4-失败(系统代码bug) | 5-失败(参数错误) | 6-无操作权限
 *
 */

include_once('phdUtils.php');
include_once('phdDatabaseManager.php');

// 获取参数
$_INPUT = json_decode(file_get_contents("php://input"));
$registTableDate = $_INPUT->registTableDate;
$registTableType = $_INPUT->registTableType;
$registLocationType = $_INPUT->registLocationType;
$registContactID = $_INPUT->registContactID;
$registContactID = intval($registContactID);
$wxNickname = $_INPUT->wxNickname;

// 定义返回值
const return_status = 'status';
const return_params = 'params';
$result = array();

$result[return_params] = $registTableDate . $registTableType . $registLocationType . $registContactID . $wxNickname;

$dbManager = new WXDatabaseManager();

// 判定用户是否有进行此操作的权限
if($dbManager->userAuthorizedStatus($wxNickname) != 1) {
    $result[return_status] = '6';
    echo json_encode($result);
    exit();
}

// 校验参数格式是否正确
if (!(isValidTableDate($registTableDate) && isValidTableType($registTableType) && isValidLocationType($registLocationType))) {
    $result[return_status] = '5';
    echo json_encode($result);
    exit();
}

// 联系人是否合法
if ($registContactID < 1) {
    $result[return_status] = '2';
    echo json_encode($result);
    exit();
}


// 检查签到表是否存在
$registTableID = $dbManager->idOfRegistTable($registTableDate, $registTableType, $registLocationType);
if ($registTableID == -1) {
    $result[return_status] = '1';
    echo json_encode($result);
    exit();
}

// 签到
$registStatus = $dbManager->tableRegist($registTableID, $registContactID);
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