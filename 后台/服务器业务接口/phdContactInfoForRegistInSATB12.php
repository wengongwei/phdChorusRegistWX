<?php

/*
 * 用于签到的姓名列表
 *
 * 接口名
 * phdContactInfoForRegistInSATB12
 *
 * 参数
 * 无
 *
 * 返回值
 * status // 0-成功 | 1-失败
 * contactList // SATB12八声部团员名字列表 {'T2' : ['蓝胖子', '袁敦胜', ...], ...}
 *
 */

include_once('phdDatabaseManager.php');

$return_status = 'status';
$return_contactInfo = 'contactList';
$result = array();

$dbManager = new WXDatabaseManager();

$result[$return_status] = '0';
$result[$return_contactInfo] = $dbManager->contactInfoForRegistInSATB12();

echo json_encode($result);

?>