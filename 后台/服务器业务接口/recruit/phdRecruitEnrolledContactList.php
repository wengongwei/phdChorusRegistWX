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
 * 获取某声部已录取团员列表
 *
 * 参数
 * listType // 1-按签到表查询 | 2-按日期范围查询
 * registTableID // 签到表id，仅listType=1时有效
 * fromDate
 * toDate // 起止日期
 * selectedPart // 查看该声部录取信息
 * wxNickname // 微信昵称，用以鉴权
 *
 * 返回值
 * status // 0-成功 | 1-失败
 * contactList // 面试者列表[{'id': 14, 'name': 梁志鹏},...]
 *
 */

include_once('phdRecruitDatabaseManager.php');

$_INPUT = json_decode(file_get_contents("php://input"));
$listType = $_INPUT->listType;
$registTableID = $_INPUT->registTableID;
$fromDate = $_INPUT->fromDate;
$toDate = $_INPUT->toDate;
$wxNickname = $_INPUT->wxNickname;
$selectedPart = $_INPUT->selectedPart;

// 定义返回值
const return_status = 'status';
const return_params = 'params';
const return_contactList = 'contactList';
$result = array();

$result[return_params] = $listType . '&' .$fromDate . $toDate . $registTableID . $wxNickname;

$dbManager = new WXRecruitDatabaseManager();
// 鉴权
if($dbManager->userAuthorizedStatus($wxNickname, 'ANY') != 1) {
    $result[return_status] = '5';
    echo json_encode($result);
    exit();
}

$contactList = null;
if ($listType == 1) {
    $contactList = $dbManager->enrolledContactList($registTableID, $selectedPart);
}
else if ($listType == 2) {
    $contactList = $dbManager->enrolledContactListWithinDate($fromDate, $toDate, $selectedPart);
}

$result[return_status] = 0;
$result[return_contactList] = $contactList;
echo json_encode($result);

?>