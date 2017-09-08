<?php
/**
 * Created by 梁志鹏 on 17-9-6 下午3:11
 * Copyright (c) 2017 PhdChorus. All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: zkey
 * Date: 2017/9/6
 * Time: 下午3:11
 */

/*
 * 获取签到表列表(获取指定的10张签到表)
 *
 * 参数
 * theTableID // 用以参考的tableID
 * isNewer // 0-返回比tableID小的10张签到表(所有符合条件的签到表，按tableID倒序排，取前10张) | 1-返回比tableID大的所有签到表
 *
 * 返回值
 * status // 0-成功 | 1-失败 | 2-失败(参数错误) | 3-失败(系统代码bug)
 * registTableList // 签到表数组[{'name' : '20170815周三中关村大排', 'id' : '15'}, ...]
 * wxNickname // 小程序使用者的nickname，用以进行鉴权操作
 */

include_once('phdRecruitDatabaseManager.php');

// 获取参数
$_INPUT = json_decode(file_get_contents("php://input"));
$theTableID = $_INPUT->theTableID;
$isNewer = $_INPUT->isNewer;
$isNewer = intval($isNewer);
$wxNickname = $_INPUT->wxNickname;

// 定义返回值
const return_status = 'status';
const return_params = 'params';
const return_registTableList = 'registTableList';
$result = array();

$result[return_params] = $theTableID . ' ' . $isNewer;

// 校验参数是否合法
if ($theTableID < 1 || ($isNewer != 0 && $isNewer != 1)) {
    $result[return_status] = '2';
    echo  json_encode($result);
    exit();
}

$dbManager = new WXRecruitDatabaseManager();

// 判定用户是否有进行此操作的权限
if($dbManager->userAuthorizedStatus($wxNickname, 'ALL') != 1) {
    $result[return_status] = '5';
    echo json_encode($result);
    exit();
}

// 查询表单
$tableList = $dbManager->registTableList($theTableID, $isNewer);
$registTableList = array();

// 拼接签到表信息
for ($i = 0; $i < count($tableList); $i++) {
    $table = $tableList[$i];
    $tableName = $table['date'] . $table['location'];
    $tableID = $table['id'];
    $registTableList[] = array('id'=>$tableID, 'name'=>$tableName);
}

$result[return_status] = 0;
$result[return_registTableList] = $registTableList;

// 返回值
echo  json_encode($result);

?>