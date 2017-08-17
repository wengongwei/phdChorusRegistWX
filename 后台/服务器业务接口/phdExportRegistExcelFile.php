<?php
/**
 * Created by 梁志鹏 on 17-8-16 上午8:33
 * Copyright (c) 2017 PhdChorus. All rights reserved
 */

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/16
 * Time: 8:33
 */

/*
 * 整合指定日期范围内的签到表，生成Excel文件
 *
 * 接口名
 * phdExportRegistExcelFile.php
 *
 * 参数
 * fromDate // 起始日期
 * toDate // 截止日期
 *
 * 返回值
 * status // 0-成功 | 1-失败 | 2-失败(参数错误) | 4-失败(服务器代码bug)
 * filePath // 文件下载相对路径
 * fileName // 文件名
 *
 */

include_once('phdUtils.php');
include_once('phdRegistInfoByExcelFileInSATB.php');

// 获取参数
$_INPUT = json_decode(file_get_contents("php://input"));
$fromDate = $_INPUT->fromDate;
$toDate = $_INPUT->toDate;

// 定义返回值
const return_status = 'status';
const return_filePath = 'filePath';
const return_fileName = 'fileName';
const return_params = 'params';
$result = array();

$result[return_params] = $fromDate . $toDate;


// 校验参数是否合法
if (!(isValidTableDate($fromDate) && isValidTableDate($toDate))) {
    $result[return_status] = '2';
    echo json_encode($result);
    exit();
}

// 写Excel文件
$excelWriter = new RegistInfoExcelFileWrite();
$filePath = $excelWriter->excelFileDownloadPathOfRegistInfoInSATB($fromDate, $toDate);
$fileName = "博士合唱团" . $fromDate . "至" . $toDate . "签到表" . ".xlsx";

// 返回值
$result[return_status] = 0;
$result[return_filePath] = $filePath;
$result[return_fileName] = $fileName;

echo json_encode($result);


?>