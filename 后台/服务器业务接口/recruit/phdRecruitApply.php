<?php
/**
 * Created by 梁志鹏 on 17-9-9 上午12:33
 * Copyright (c) 2017 PhdChorus. All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: zkey
 * Date: 2017/9/9
 * Time: 上午12:33
 */

/*
 * 接口功能
 * 报名参加合唱团
 *
 * 参数
 * contactName // 姓名
 * contactSex // 性别 0-女 | 1-男
 * contactNation // 民族
 * contactPhone // 手机号
 * contactEmail // 邮箱
 * contactStudentId // 学号
 * contactLocation // 面试者所在园区
 * contactCompany: // 培养单位
 * contactGrade: // 年级
 * contactVocalAbility // 是否学过声乐
 * contactInstruments // 是否学过乐器
 * contactReadMusic // 是否识谱
 * contactPianist // 是否有意愿担任钢伴 0-无 | 1-有
 * contactInterest // 兴趣爱好
 * contactSkill // 技能
 * contactExperience // 艺术团体经历
 * contactExpect // 加入合唱团的期望
 * registTableID // 选择的参加面试的签到表ID
 *
 * 返回值
 * status // 0-成功 | 1-失败(数据库插入失败) | 4-失败(系统代码bug) | 5-失败(参数错误)
 *
 */

include_once('phdRecruitDatabaseManager.php');

// 获取参数
$_INPUT = json_decode(file_get_contents("php://input"));
$contactName = $_INPUT->contactName;
$contactSex = $_INPUT->contactSex;
$contactSex = intval($contactSex);
$contactNation = $_INPUT->contactNation;
$contactPhone = $_INPUT->contactPhone;
$contactEmail = $_INPUT->contactEmail;
$contactStudentId = $_INPUT->contactStudentId;
$contactLocation = $_INPUT->contactLocation;
$contactCompany = $_INPUT->contactCompany;
$contactGrade = $_INPUT->contactGrade;
$contactVocalAbility = $_INPUT->contactVocalAbility;
$contactInstruments = $_INPUT->contactInstruments;
$contactReadMusic = $_INPUT->contactReadMusic;
$contactPianist = $_INPUT->contactPianist;
$contactPianist = intval($contactPianist);
$contactInterest = $_INPUT->contactInterest;
$contactSkill = $_INPUT->contactSkill;
$contactExperience = $_INPUT->contactExperience;
$contactExpect = $_INPUT->contactExpect;
$registTableID = $_INPUT->registTableID;
$registTableID = intval($registTableID);

const return_status = 'status';
const return_params = 'params';
$result = array();

// 验证参数是否正确
if ($registTableID < 1 || strlen($contactName) <= 0 || strlen($contactNation) <= 0 || strlen($contactPhone) <= 0 || strlen($contactEmail) <= 0 || strlen($contactStudentId) <= 0 || strlen($contactCompany) <= 0 || strlen($contactGrade) <= 0 || strlen($contactInstruments) <= 0) {
    $result[return_status] = 5;
    echo json_encode($result);
    exit();
}

$dbManager = new WXRecruitDatabaseManager();

// 存储报名者个人信息
$contactID = $dbManager->addContactInfo($contactName, $contactSex, $contactNation, $contactPhone, $contactEmail, $contactStudentId, $contactLocation, $contactCompany, $contactGrade, $contactVocalAbility, $contactInstruments ,$contactReadMusic ,$contactPianist, $contactInterest, $contactSkill, $contactExperience, $contactExpect);

// 报名
$status = $dbManager->applyToJoin($registTableID, $contactID);
if ($status == 1) {
    $result[return_status] = 1;
}
else if ($status == 0) {
    $result[return_status] = 0;
}

echo json_encode($result);