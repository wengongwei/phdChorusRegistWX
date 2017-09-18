<?php
/**
 * Created by 梁志鹏 on 17-9-18 下午7:25
 * Copyright (c) 2017 PhdChorus. All rights reserved
 */

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/18
 * Time: 19:25
 */

/*
 * 接口功能
 * 给TA分配权限
 *
 * 参数
 * contactName // TA的姓名
 * contactWxID // TA的微信ID
 * contactWXNickname // TA的微信昵称
 * allocAuthority // 分配给TA的权限
 * wxNickname // 我的微信昵称
 *
 * 返回值
 * status // 0-成功 | 1-失败 | 2-失败(没有权限) | 5-失败(参数错误)
 */

include_once('phdDatabaseManager.php');

// 获取参数
$_INPUT = json_decode(file_get_contents("php://input"));
$contactName = $_INPUT->contactName;
$contactWxID = $_INPUT->contactWxID;
$contactWXNickname = $_INPUT->contactWXNickname;
$allocAuthority = $_INPUT->allocAuthority;
$wxNickname = $_INPUT->wxNickname;

// 定义返回值
const return_status = 'status';
const return_params = 'params';
$result = array();

$result[return_params] = $contactName . $contactWxID . $contactWXNickname . $allocAuthority . $wxNickname;

if (strlen($contactName) <= 0 || strlen($contactWxID) <= 0 || strlen($contactWXNickname) <= 0) {
    $result[return_status] = 5;
    echo json_encode($result);
    exit();
}

// 数据库操作
$dbManager = new WXDatabaseManager();

$status = $dbManager->allocAuthority($contactName, $contactWxID, $contactWXNickname, $allocAuthority,$wxNickname);
if ($status == 1) {
    $result[return_status] = 0;
}
else if ($status == 2) {
    $result[return_status] = 2;
}
else if ($status == 0) {
    $result[return_status] = 1;
}

echo json_encode($result);
exit();

?>