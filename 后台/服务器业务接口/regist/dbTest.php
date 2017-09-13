<?php

include_once ('php_excel_classes_1.8.1/PHPExcel.php');
include_once ('php_excel_classes_1.8.1/PHPExcel/Writer/Excel2007.php');


// 创建Excel文件
$excelFile = new PHPExcel();

// 设置文件属性
$excelFile->getProperties()->setCreator("博士合唱团签到小程序");
$excelFile->getProperties()->setTitle("博士合唱团" . $fromDate . "至" . $toDate . "签到表");
$excelFile->getProperties()->setSubject("SATB四声部");

$excelWriter = PHPExcel_IOFactory::createWriter($excelFile, 'Excel2007');
$fileName = "博士合唱团" . $fromDate . "至" . $toDate . "签到表" . ".xlsx";
$filePath = __DIR__ . '/excel_file/dbTest.xlsx';

if (is_writable('excel_file/test.txt')) {
    echo 'can write';
}

$excelWriter->save('excel_file/dbTest.xlsx');

echo shell_exec('whoami');

?>