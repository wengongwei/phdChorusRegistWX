<?php
/**
 * Created by PhpStorm.
 * User: zkey
 * Date: 2017/8/12
 * Time: 上午9:45
 */

/**
 * 团员列表(SATB12)中显示团员详细信息
 *
 * 接口名
 * phdContactInfoInSATB.php
 *
 * 参数
 * wxNickname
 *
 * 返回值
 * status // 0-成功 | 1-失败 | 5-无权限
 * contactInfo // SATB12八声部团员信息{'T2' : [{contactID: '15', contactName:'蓝胖子', contactPart:'T2', contactLocation:'中关村'}, ...], ...}
 *
 */

include_once('phdDatabaseManager.php');


$_INPUT = json_decode(file_get_contents("php://input"));
$wxNickname = $_INPUT->wxNickname;

$return_status = 'status';
$return_contactInfo = 'contactInfo';
$result = array();

$dbManager = new WXDatabaseManager();

// 判定用户是否有进行此操作的权限
if($dbManager->userAuthorizedStatus($wxNickname, 'ANY') != 1) {
    $result[$return_status] = '5';
    echo json_encode($result);
    exit();
}

$result[$return_status] = '0';
$result[$return_contactInfo] = $dbManager->contactInfoInSATB12();

echo json_encode($result);


?>