<?php

/**
 * 创建签到表
 *
 * 接口名
 * wxCreateRegistTable.php
 *
 * 参数
 * registTableDate // 日期 '2018-08-06'
 * registTableType // 类型 '大排' OR '小排' OR '周日晚' OR '声乐课'
 * registLocationType // 园区 '中关村' OR '雁栖湖'
 * contactPartType // 声部 S1 | S2 | A1 | A2 | T1 | T2 | B1 | B2
 * wxNickname // 小程序使用者的nickname，用以进行鉴权操作
 *
 * 返回值
 * status // 0-成功 | 1-失败（签到表已存在）| 2-失败（创建签到表失败） | 3-参数错误 | 4-服务器代码bug | 5-无操作权限
 *
 */

include_once('phdUtils.php');
include_once('phdDatabaseManager.php');

// 获取参数
$_INPUT = json_decode(file_get_contents("php://input"));
$registTableDate = $_INPUT->registTableDate;
$registTableType = $_INPUT->registTableType;
$registLocationType = $_INPUT->registLocationType;
$wxNickname = $_INPUT->wxNickname;

// 定义返回值
const return_status = 'status';
const return_params = 'params';
$result = array();

// 测试是否收到了正确的参数
$result[return_params] = $registTableDate . $registTableType . $registLocationType . $wxNickname;

$dbManager = new WXDatabaseManager();

// 判定用户是否有进行此操作的权限
if($dbManager->userAuthorizedStatus($wxNickname, 'ALL') != 1) {
    $result[return_status] = '5';
    echo json_encode($result);
    exit();
}

// 校验参数格式是否正确
if (!(isValidTableDate($registTableDate) && isValidTableType($registTableType) && isValidLocationType($registLocationType))) {
    $result[return_status] = '3';
    echo json_encode($result);
    exit();
}



// 是否已存在相同的签到表
$tableID = $dbManager->idOfRegistTable($registTableDate, $registTableType, $registLocationType);
if ($tableID != -1) {
    $result[return_status] = '1';
    echo json_encode($result);
    exit();
}

// 创建签到表
$insertSuccess = $dbManager->insertRegistTable($registTableDate, $registTableType, $registLocationType);
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