<?php
/**
 * Created by 梁志鹏 on 17-8-13 下午8:22
 * Copyright (c) 2017 PhdChorus. All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: zkey
 * Date: 2017/8/13
 * Time: 下午8:22
 */

/*
 * 获取某张签到表中的出勤信息
 *
 * 接口名
 * registInfoOfRegistTable
 *
 * 参数
 * registTableID // 选择的签到表的id
 * contactLocationType  // 需要统计的园区 0-都统计 | 1-只统计中关村 | 2-只统计雁栖湖
 *
 * 返回值
 * status // 0-成功 | 1-失败 | 2-失败(参数错误) | 3-失败(系统代码bug)
 * registInfo // 出勤名单{'S1': {'attend': '刘晓雯、王冕', 'absent': ['小白姐、李赛楠', ...]}, ....}
 */

include_once('phdUtils.php');
include_once('phdDatabaseManager.php');

// 获取参数
$_INPUT = json_decode(file_get_contents("php://input"));
$registTableID = $_INPUT->registTableID;
$contactLocationType = $_INPUT->contactLocationType;

$registTableID = intval($registTableID);
$contactLocationType = intval($contactLocationType);

// 定义返回值
const return_status = 'status';
const return_params = 'params';
const return_registInfo = 'registInfo';
$result = array();

// 查询表单
$dbManager = new WXDatabaseManager();
$registInfoFromDB = $dbManager->registInfoOfRegistTable($registTableID, $contactLocationType);
$registInfo = array();

foreach ($registInfoFromDB as $partInfo) {
    $attendNameStr = nameStringFromNameList($partInfo['attend']);
    $absentNameStr = nameStringFromNameList($partInfo['absent']);
    $part = partDescription($partInfo['part']) . '  (' . count($partInfo['attend']) . ' / ' . count($partInfo['absent']) . ')';
    $registInfo[] = array('part'=>$part, 'attend'=>$attendNameStr, 'absent'=>$absentNameStr);
}

$result[return_status] = 0;
$result[return_registInfo] = $registInfo;

echo json_encode($result);

?>