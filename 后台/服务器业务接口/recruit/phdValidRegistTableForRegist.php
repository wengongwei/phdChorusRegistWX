<?php
/**
 * Created by 梁志鹏 on 17-9-11 上午10:28
 * Copyright (c) 2017 PhdChorus. All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: zkey
 * Date: 2017/9/11
 * Time: 上午10:28
 */

/*
 * 接口功能
 * 可用于面试现场签到的签到表
 *
 * 参数
 * 无
 *
 * 返回值
 * status 0-成功 | 1-失败
 * tableList // [{'id': 15, 'date': 2017-03-23, 'location': 中关村}, ...]
 */

include_once('phdRecruitDatabaseManager.php');

const return_status = 'status';
const return_params = 'params';
const return_tableList = 'tableList';

$result = array();

$dbManager = new WXRecruitDatabaseManager();

$list = $dbManager->validRegistTableOfType(2);
$tableList = array();
for ($i = 0; $i < count($list); ++$i) {
    $tmpTable = $list[$i];
    $table = array();
    $table['value'] = $tmpTable['id'];
    $table['name'] = $tmpTable['date'] . $tmpTable['location'];
    $tableList[] = $table;
}

$result[return_status] = 0;
$result[return_tableList] = $tableList;

echo json_encode($result);

?>