<?php
/**
 * Created by 梁志鹏 on 17-9-6 下午4:58
 * Copyright (c) 2017 PhdChorus. All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: zkey
 * Date: 2017/9/6
 * Time: 下午4:58
 */

/*
 * 接口功能
 * 获取面试者详细信息
 *
 * 参数
 * interviewerID // 面试者id
 *
 * 返回值
 * status // 0-成功 | 1-失败 | 2-失败(参数错误) | 3-失败(服务器代码bug)
 * contact // {id : 15, name : 梁志鹏, sex : 男, location : 中关村, company : 中科院软件所, grade : 研三, phone : 13317945775, email : zhipengliang@qq.com, vocal : 了解一点, instruments : 钢琴6级, readMusic : 简谱}
 */

include_once('phdRecruitDatabaseManager.php');

// 获取参数
$_INPUT = json_decode(file_get_contents("php://input"));
$interviewerID = $_INPUT->interviewerID;
$interviewerID = intval($interviewerID);

// 定义返回值
const return_status = 'status';
const return_params = 'params';
const return_contact = 'contact';
$result = array();

$result[return_params] = $interviewerID;
$result[return_status] = 0;

$dbManager = new WXRecruitDatabaseManager();

$interviewer = $dbManager->interviewerDetailInfo($interviewerID);
// 性别转换
if ($interviewer['sex'] == 1) {
    $interviewer['sex'] = '男';
}
else if ($interviewer['sex'] == 0) {
    $interviewer['sex'] = '女';
}

$result[return_contact] = $interviewer;

echo json_encode($result);

?>