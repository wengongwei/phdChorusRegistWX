<?php

/*
 * 添加新团员
 *
 * 接口名
 * phdAddContact.php
 *
 * 参数
 * contactName // 姓名
 * contactPart // 声部 S1 S2 A1 A2 T1 T2 B1 B2
 * contactLocation // 园区
 * wxNickname // 小程序使用者的nickname，用以进行鉴权操作
 *
 * 返回值
 * status // 0-成功 | 1-失败（团员已存在）| 2-失败（添加团员失败） | 3-参数错误 | 4-服务器代码bug | 5-无操作权限
 *
 */

include_once('phdUtils.php');
include_once('phdDatabaseManager.php');

// 获取参数
$_INPUT = json_decode(file_get_contents("php://input"));
$contactName = $_INPUT->contactName;
$contactPart = $_INPUT->contactPart;
$contactLocation = $_INPUT->contactLocation;
$wxNickname = $_INPUT->wxNickname;

// 定义返回值
const return_status = 'status';
const return_params = 'params';
$result = array();

// 测试是否收到了正确的参数
$result[return_params] = $contactName . $contactPart . $contactLocation . $wxNickname;

// 数据库操作
$dbManager = new WXDatabaseManager();


// 判定用户是否有进行此操作的权限
if($dbManager->userAuthorizedStatus($wxNickname) != 1) {
    $result[return_status] = '5';
    echo json_encode($result);
    exit();
}

// 校验参数格式是否正确
if (!(isValidPartType($contactPart) && isValidLocationType($contactLocation))) {
    $result[return_status] = '3';
    echo json_encode($result);
    exit();
}



// 是否已存在相同的contact
$contactExist = $dbManager->idOfContact($contactName, $contactPart, $contactLocation);
if ($contactExist != -1) {
    $result[return_status] = '1';
    echo json_encode($result);
    exit();
}

// 创建签到表
$insertSuccess = $dbManager->insertContact($contactName, $contactPart, $contactLocation);
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