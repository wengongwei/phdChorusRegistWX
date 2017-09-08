<?php
/**
 * Created by 梁志鹏 on 17-9-6 下午12:13
 * Copyright (c) 2017 PhdChorus. All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: zkey
 * Date: 2017/9/6
 * Time: 下午12:13
 */

/*
 * 接口功能
 * 面试者签到
 *
 * 参数
 * contactName // 姓名
 * contactSex // 性别 0-女 | 1-男
 * contactPhone // 手机号
 * contactEmail // 邮箱
 * contactLocation // 面试者所在园区
 * contactCompany: // 培养单位
 * contactGrade: // 年级
 * contactVocalAbility // 是否学过声乐
 * contactInstruments // 是否学过乐器
 * contactReadMusic // 是否识谱
 * registTableDate // 签到日期
 * registLocationType // 签到地点
 *
 * 返回值
 * status // 0-成功 | 1-失败(签到表不存在) | 3-失败(已签到，无需重复签到) | 4-失败(系统代码bug) | 5-失败(参数错误)
 * registID // 签到的序号数（第几个签到的，是第几号面试者）
 *
 */

include_once('phdRecruitDatabaseManager.php');

// 获取参数
$_INPUT = json_decode(file_get_contents("php://input"));
$contactName = $_INPUT->contactName;
$contactSex = $_INPUT->contactSex;
$contactSex = intval($contactSex);
$contactPhone = $_INPUT->contactPhone;
$contactEmail = $_INPUT->contactEmail;
$contactLocation = $_INPUT->contactLocation;
$contactCompany = $_INPUT->contactCompany;
$contactGrade = $_INPUT->contactGrade;
$contactVocalAbility = $_INPUT->contactVocalAbility;
$contactInstruments = $_INPUT->contactInstruments;
$contactReadMusic = $_INPUT->contactReadMusic;
$registTableDate = $_INPUT->registTableDate;
$registLocationType = $_INPUT->registLocationType;

// 定义返回值
const return_status = 'status';
const return_params = 'params';
const return_registID = 'registID';
$result = array();

$result[return_params] = $contactName . $contactSex . $contactPhone . $contactEmail . $contactLocation . $contactCompany . $contactGrade . $contactVocalAbility . $contactInstruments . $contactReadMusic . $registTableDate . $registLocationType;

// 验证参数是否正确
if (strlen($contactName) <= 0 || strlen($contactPhone) <= 0 || strlen($contactEmail) <= 0 || strlen($contactCompany) <= 0 || strlen($contactGrade) <= 0 || strlen($contactInstruments) <= 0) {
    $result[return_status] = 5;
    echo json_encode($result);
    exit();
}

$dbManager = new WXRecruitDatabaseManager();

// 查询是否有该签到表
$tableID = $dbManager->idOfRegistTable($registTableDate, $registLocationType);

if ($tableID == -1) {
    $result[return_status] = 1;
    echo json_encode($result);
    exit();
}

// 查看是否已签到
$interviewerID = $dbManager->registIdOfInterviewer($tableID, $contactName);
if ($interviewerID != -1) {
    $result[return_status] = 3;
    $result[return_registID] = $interviewerID;
    echo json_encode($result);
    exit();
}

// 签到
$interviewerID = $dbManager->tableRegist($tableID, $contactName, $contactSex, $contactPhone, $contactEmail, $contactLocation, $contactCompany, $contactGrade, $contactVocalAbility, $contactInstruments, $contactReadMusic);
if ($interviewerID == -1) {
    $result[return_status] = 4;
    echo json_encode($result);
    exit();
}

$result[return_status] = 0;
$result[return_registID] = $interviewerID;
echo json_encode($result);

?>