<?php
/**
 * Created by 梁志鹏 on 17-9-2 上午7:25
 * Copyright (c) 2017 PhdChorus. All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: zkey
 * Date: 2017/9/2
 * Time: 上午7:25
 */

/*
 * 修改（更新）团员信息
 *
 * 接口名
 * phdUpdateContactInfo.php
 *
 * 参数
 * contactID // 团员id
 * contactName // 姓名
 * contactPart // 声部 S1 S2 A1 A2 T1 T2 B1 B2
 * contactLocation // 园区
 * contactIncludeInStatics //是否纳入统计数据 1-纳入 | 0-不纳入
 * wxNickname // 小程序使用者的nickname，用以进行鉴权操作
 *
 * 返回值
 * status // 0-成功 | 2-失败（更新信息失败） | 3-参数错误 | 4-服务器代码bug | 5-无操作权限
 *
 */


include_once('phdUtils.php');
include_once('phdDatabaseManager.php');

// 获取参数
$_INPUT = json_decode(file_get_contents("php://input"));
$contactID = $_INPUT->contactID;
$contactID = intval($contactID);
$contactName = $_INPUT->contactName;
$contactPart = $_INPUT->contactPart;
$contactLocation = $_INPUT->contactLocation;
$contactIncludeInStatics = $_INPUT->contactIncludeInStatics;
$contactIncludeInStatics = intval($contactIncludeInStatics);
$wxNickname = $_INPUT->wxNickname;

// 定义返回值
const return_status = 'status';
const return_params = 'params';
$result = array();

// 测试是否收到了正确的参数
$result[return_params] = $contactID . $contactName . $contactPart . $contactLocation . $contactIncludeInStatics . $wxNickname;

// 数据库操作
$dbManager = new WXDatabaseManager();


// 判定用户是否有进行此操作的权限
if($dbManager->userAuthorizedStatus($wxNickname, $contactPart) != 1) {
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

// 更新团员信息
// 添加contact
$insertSuccess = $dbManager->updateContactInfo($contactID, $contactName, $contactPart, $contactLocation, $contactIncludeInStatics);
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