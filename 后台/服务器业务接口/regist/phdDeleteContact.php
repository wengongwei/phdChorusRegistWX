<?php
/**
 * Created by 梁志鹏 on 17-9-2 下午9:46
 * Copyright (c) 2017 PhdChorus. All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: zkey
 * Date: 2017/9/2
 * Time: 下午9:46
 */

/**
 * 删除团员
 *
 * 参数
 * contactID
 *
 * 返回值
 * int // 0-成功 | 1-失败(团员不存在) | 2-系统错误 | 5-无权限
 *
 */

include_once('phdDatabaseManager.php');

// 获取参数
$_INPUT = json_decode(file_get_contents("php://input"));
$contactID = $_INPUT->contactID;
$contactID = intval($contactID);
$wxNickname = $_INPUT->wxNickname;

// 定义返回值
const return_status = 'status';
const return_params = 'params';
$result = array();

// 测试是否收到了正确的参数
$result[return_params] = $contactID;

// 数据库操作
$dbManager = new WXDatabaseManager();

// 找到对应的团员
$contact = $dbManager->contactInfo($contactID);

// 判定用户是否有进行此操作的权限
if($dbManager->userAuthorizedStatus($wxNickname, $contact['part']) != 1) {
    $result[return_status] = '5';
    echo json_encode($result);
    exit();
}

$deleteSuccess = $dbManager->deleteContact($contactID);
if ($deleteSuccess == 0) {
    $result[return_status] = '0';
    echo json_encode($result);
    exit();
}
else if ($deleteSuccess == 1) {
    $result[return_status] = '1';
    echo json_encode($result);
    exit();
}

$result[return_status] = '2';
echo json_encode($result);

?>