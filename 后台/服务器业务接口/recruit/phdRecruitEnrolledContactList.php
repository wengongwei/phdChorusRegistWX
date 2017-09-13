<?php
/**
 * Created by 梁志鹏 on 17-9-13 下午5:29
 * Copyright (c) 2017 PhdChorus. All rights reserved
 */

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/13
 * Time: 17:29
 */

/*
 * 接口功能
 * 获取已录取团员列表
 *
 * 参数
 * registTableID
 * wxNickname
 * selectedPart
 *
 * 返回值
 * status // 0-成功 | 1-失败
 * contactList // 面试者列表[{'id': 14, 'name': 梁志鹏},...]
 *
 */

include_once('phdRecruitDatabaseManager.php');

$_INPUT = json_decode(file_get_contents("php://input"));
$registTableID = $_INPUT->registTableID;
$wxNickname = $_INPUT->wxNickname;
$selectedPart = $_INPUT->selectedPart;

// 定义返回值
const return_status = 'status';
const return_params = 'params';
const return_contactList = 'contactList';
$result = array();

$result[return_params] = $registTableID . $wxNickname;

$dbManager = new WXRecruitDatabaseManager();
// 鉴权
if($dbManager->userAuthorizedStatus($wxNickname, 'ANY') != 1) {
    $result[return_status] = '5';
    echo json_encode($result);
    exit();
}

$contactList = $dbManager->enrolledContactList($registTableID, $selectedPart);

$result[return_status] = 0;
$result[return_contactList] = $contactList;
echo json_encode($result);

?>