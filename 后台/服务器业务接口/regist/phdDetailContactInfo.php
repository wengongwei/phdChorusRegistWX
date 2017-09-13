<?php
/**
 * Created by 梁志鹏 on 17-8-15 上午9:59
 * Copyright (c) 2017 PhdChorus. All rights reserved
 */

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/15
 * Time: 9:59
 */

/**
 * 团员详细信息
 *
 * 接口名
 * detailContactInfo.php
 *
 * 参数
 * contactID // 团员的id
 *
 * 返回值
 * status // 0-成功 | 1-失败 | 2-失败(参数错误) | 3-失败(服务器代码bug)
 * contact // 团员信息{id: 12, name: '蓝胖子', part: 'T2', location: '中关村', includeInStatics: 1}
 *
 */


include_once('phdDatabaseManager.php');

// 获取参数
$_INPUT = json_decode(file_get_contents("php://input"));
$contactID = $_INPUT->contactID;

$contactID = intval($contactID);

// 定义返回值
const return_status = 'status';
const return_params = 'params';
const return_contact = 'contact';
$result = array();

$result[return_params] = $contactID;

// 校验参数是否合法
if ($contactID < 1) {
    $result[return_status] = 2;
    echo json_encode($result);
    exit();
}

$dbManager = new WXDatabaseManager();

// 获取团员信息
$contact = $dbManager->contactInfo($contactID);
$result[return_contact] = $contact;
$result[return_status] = 0;

echo json_encode($result);

?>