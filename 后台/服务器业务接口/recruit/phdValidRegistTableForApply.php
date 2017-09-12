<?php
/**
 * Created by 梁志鹏 on 17-9-9 下午12:34
 * Copyright (c) 2017 PhdChorus. All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: zkey
 * Date: 2017/9/9
 * Time: 下午12:34
 */

/*
 * 接口
 * 可用于报名和面试通知的签到表
 *
 * 参数
 * 无
 *
 * 返回值
 * status 0-成功 | 1-失败
 * tableList // [{'id': 15, 'date': 2017-03-23, 'location': 中关村}, ...]
 */

include_once('phdRecruitDatabaseManager.php');
include_once('../phdUtils.php');

const return_status = 'status';
const return_params = 'params';
const return_tableList = 'tableList';

$result = array();

$dbManager = new WXRecruitDatabaseManager();

$list = $dbManager->validRegistTableOfType(1);
$tableList = array();
for ($i = 0; $i < count($list); $i++) {
    $table = $list[$i];
    $date = $table['date'];
    $weekday = weekdayFromDate($date);
    $tableName = $date . $weekday . $table['location'];
    $tableID = $table['id'];
    $tableList[] = array('value'=>$tableID, 'name'=>$tableName);
}

$result[return_status] = 0;
$result[return_tableList] = $tableList;

echo json_encode($result);

?>