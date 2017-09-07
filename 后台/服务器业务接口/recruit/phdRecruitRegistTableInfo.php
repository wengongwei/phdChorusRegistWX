<?php
/**
 * Created by 梁志鹏 on 17-9-6 下午3:42
 * Copyright (c) 2017 PhdChorus. All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: zkey
 * Date: 2017/9/6
 * Time: 下午3:42
 */

/*
 * 接口功能
 * 获取面试者列表
 *
 * 参数
 * registTableID // 签到表ID
 * theInterviewerID // 用以参考的interviewerID
 *
 * 返回值
 * status //
 * interviewerList // 面试者数组(比theInterviewerID大的所有的面试者)
 * // [{name : 15号-梁志鹏, id : 15}, ...]
 * // name-姓名 id-面试编号
 *
 */

include_once('phdRecruitDatabaseManager.php');

// 获取参数
$_INPUT = json_decode(file_get_contents("php://input"));
$registTableID = $_INPUT->registTableID;
$theInterviewerID = $_INPUT->theInterviewerID;

// 定义返回值
const return_status = 'status';
const return_params = 'params';
const return_interviewerList = 'interviewerList';
$result = array();

$result[return_params] = $registTableID . '-' . $theInterviewerID;

// 查询表单
$dbManager = new WXRecruitDatabaseManager();
$list = $dbManager->interviewerListOfRegistTable($registTableID, $theInterviewerID);
$interviewerList = array();

for ($i = 0; $i < count($list); $i++) {
    $interviewer = $list[$i];
    $id = $interviewer['id'];
    $name = $id . '号 · ' . $interviewer['name'];
    $interviewerList[] = array('id'=>$id, 'name'=>$name);
}

$result[return_status] = 0;
$result[return_interviewerList] = $interviewerList;
echo json_encode($result);

?>