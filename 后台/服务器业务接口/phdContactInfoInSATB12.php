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
 * 无
 *
 * 返回值
 * status // 0-成功 | 1-失败
 * contactInfo // SATB12八声部团员信息{'T2' : [{contactID: '15', contactName:'蓝胖子', contactPart:'T2', contactLocation:'中关村'}, ...], ...}
 *
 */

include_once('phdDatabaseManager.php');

$return_status = 'status';
$return_contactInfo = 'contactInfo';
$result = array();

$dbManager = new WXDatabaseManager();

$result[$return_status] = '0';
$result[$return_contactInfo] = $dbManager->contactInfoInSATB12();

echo json_encode($result);


?>