<?php
/**
 * Created by 梁志鹏 on 17-9-13 下午3:51
 * Copyright (c) 2017 PhdChorus. All rights reserved
 */

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/13
 * Time: 15:51
 */

/*
 * 接口功能
 * 录取团员
 *
 * 参数
 * interviewerID // interview_info的id
 * wxNickname // 小程序使用者的nickname，用以进行鉴权操作
 * part // 录取到声部
 *
 * 返回值
 * status // 0-成功 | 1-失败 | 5-无权限
 */

include_once('phdRecruitDatabaseManager.php');

$_INPUT = json_decode(file_get_contents("php://input"));
$interviewerID = $_INPUT->interviewerID;
$interviewerID = intval($interviewerID);
$wxNickname = $_INPUT->wxNickname;
$part = $_INPUT->part;

// 定义返回值
const return_status = 'status';
const return_params = 'params';
$result = array();

$result[return_params] = $interviewerID . $wxNickname . $part;


$dbManager = new WXRecruitDatabaseManager();

// 鉴权，声部长只能将人录取到自己声部，团长拥有所有权限
if($dbManager->userAuthorizedStatus($wxNickname, $part) != 1) {
    $result[return_status] = '5';
    echo json_encode($result);
    exit();
}

// 录取
$status = $dbManager->enrollInterviewer($interviewerID, $part);
if ($status == 0) {
    $result[return_status] = 0;
}
else if ($status == 1) {
    $result[return_status] = 1;
}

echo json_encode($result);

?>