<?php
/**
 * Created by 梁志鹏 on 17-9-11 上午8:17
 * Copyright (c) 2017 PhdChorus. All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: zkey
 * Date: 2017/9/11
 * Time: 上午8:17
 */

/*
 * 接口功能
 * 获取面试者列表
 *
 * 参数
 * registTableID // 签到表id
 * theWaiterID // 用以参考的interviewerID
 * interviewStatus // 面试状态位 1-已报名 | 2-已确认参加面试 | 3-已现场面试签到
 * wxNickname //
 *
 * 返回值
 * status // 0-成功 | 1-失败
 * interviewerList // 面试者列表[{'id': 14, 'name': 34号梁志鹏},...]
 */

include_once('phdRecruitDatabaseManager.php');

// 获取参数
$_INPUT = json_decode(file_get_contents("php://input"));
$registTableID = $_INPUT->registTableID;
$theWaiterID = $_INPUT->theWaiterID;
$theWaiterID = intval($theWaiterID);
$interviewStatus = $_INPUT->interviewStatus;
$interviewStatus = intval($interviewStatus);
$wxNickname = $_INPUT->wxNickname;

// 定义返回值
const return_status = 'status';
const return_params = 'params';
const return_interviewerList = 'interviewerList';
$result = array();

$result[return_params] = $registTableID . $theWaiterID . $interviewStatus;

$dbManager = new WXRecruitDatabaseManager();
// 鉴权
if($dbManager->userAuthorizedStatus($wxNickname, 'ANY') != 1) {
    $result[return_status] = '5';
    echo json_encode($result);
    exit();
}


$list = array();
if ($interviewStatus == 1 || $interviewStatus == 2) {
    $list = $dbManager->applicantList($registTableID, $interviewStatus);
}
else if ($interviewStatus == 3) {
    $list = $dbManager->interviewerList($registTableID, $theWaiterID, $interviewStatus);
}

$interviewerList = array();
for ($i = 0; $i < count($list); ++$i) {
    $interviewer = $list[$i];
    $name = $interviewer['name'];
    $waiterID = $interviewer['waiterID'];
    if ($interviewStatus == 3) {
        $name = $waiterID . '号 · ' . $name;
    }

    $interviewerList[] = array('id'=>$interviewer['id'], 'name'=>$name, 'waiterID'=>$waiterID, 'phone'=>$interviewer['phone'], 'email'=>$interviewer['email']);
}

$result[return_status] = 0;
$result[return_interviewerList] = $interviewerList;
echo json_encode($result);

?>