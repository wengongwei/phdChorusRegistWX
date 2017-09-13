<?php

/*
 * 用于签到的姓名列表
 *
 * 接口名
 * phdContactInfoForRegistInSATB12
 *
 * 参数
 * wxNickname
 *
 * 返回值
 * status // 0-成功 | 1-失败
 * contactList // SATB12八声部团员名字列表 {'T2' : ['蓝胖子', '袁敦胜', ...], ...}
 *
 */

include_once('phdDatabaseManager.php');

$_INPUT = json_decode(file_get_contents("php://input"));
$wxNickname = $_INPUT->wxNickname;

$return_status = 'status';
$return_contactInfo = 'contactList';
$result = array();

$dbManager = new WXDatabaseManager();

// 判定用户是否有进行此操作的权限
if($dbManager->userAuthorizedStatus($wxNickname, 'ANY') != 1) {
    $result[$return_status] = '5';
    echo json_encode($result);
    exit();
}


$result[$return_status] = '0';
$result[$return_contactInfo] = $dbManager->contactInfoForRegistInSATB12();

echo json_encode($result);

?>