<?php
/**
 * Created by 梁志鹏 on 17-8-15 下午3:22
 * Copyright (c) 2017 PhdChorus. All rights reserved
 */

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/15
 * Time: 15:22
 */

include_once ('php_excel_classes_1.8.1/PHPExcel.php');
include_once ('php_excel_classes_1.8.1/PHPExcel/Writer/Excel2007.php');
include_once ('phdDatabaseManager.php');
include_once ('phdUtils.php');


class RegistInfoExcelFileWrite {

    private $_dbManager;

    public function __construct() {
        $this->_dbManager = new WXDatabaseManager();
    }

    /**
     * 整合制定日期范围内的签到表
     *
     * 参数
     * fromDate // 开始日期
     * toDate // 截至日期
     *
     * 返回值
     * filePath // Excel文件下载路径
     */
    public function excelFileDownloadPathOfRegistInfoInSATB($fromDate, $toDate) : string {

        // 找到给定日期范围内的签到表
        $registTableSections = $this->registTableSections($fromDate, $toDate);
        $contactSections = $this->contactSections();

        // 创建Excel文件
        $excelFile = new PHPExcel();

        // 设置文件属性
        $excelFile->getProperties()->setCreator("博士合唱团签到小程序");
        $excelFile->getProperties()->setTitle("博士合唱团" . $fromDate . "至" . $toDate . "签到表");
        $excelFile->getProperties()->setSubject("SATB四声部");

        // 为$registTableSections中每种type创建一个excel工作簿(WorkSheet)
        // 即为 大排 | 小排 | 周日晚 | 声乐课 各创建一个WorkSheet

        // 文字颜色
        $yanqiTextStyle = new PHPExcel_RichText();

        for ($i = 0; $i < count($registTableSections); $i++) {
            $tableSection = $registTableSections[$i];
            $theWorkSheet = new PHPExcel_Worksheet($excelFile, $tableSection['type']);
            $excelFile->addSheet($theWorkSheet, $i);

            $startRow = $currentRow = 1;
            $startColumn = $currentColumn = 1;

            // 每张WorkSheet中
            // 第1列是'声部'，第2列是'编号'，第3列是'姓名'，第4列是'园区'
            $theWorkSheet->setCellValueByColumnAndRow($currentColumn, $currentRow, '声部');
            $theWorkSheet->setCellValueByColumnAndRow($currentColumn + 1, $currentRow, '编号');
            $theWorkSheet->setCellValueByColumnAndRow($currentColumn + 2, $currentRow, '姓名');
            $theWorkSheet->setCellValueByColumnAndRow($currentColumn + 3, $currentRow, '园区');
            ++$currentRow;

            // 写入第1、2、3、4列的信息
            for ($j = 0; $j < count($contactSections); $j++) {
                $partInfo = $contactSections[$j];
                $contactList = $partInfo['contactList'];
                $part = $partInfo['part'];
                $location = $partInfo['location'];
                for ($k = 0; $k < count($contactList); $k++) {
                    $contact = $contactList[$k];
                    $theWorkSheet->setCellValueByColumnAndRow($currentColumn, $currentRow, $part);
                    $theWorkSheet->setCellValueByColumnAndRow($currentColumn + 1, $currentRow, $currentRow - 2);
                    $theWorkSheet->setCellValueByColumnAndRow($currentColumn + 2, $currentRow, $contact['name']);
                    $theWorkSheet->setCellValueByColumnAndRow($currentColumn + 3, $currentRow, $location);
                    ++$currentRow;
                }
            }


            // 每张签到表创建一列
            $currentColumn = $startColumn + 4;
            $tableList = $tableSection['tableList'];
            for ($j = 0; $j < count($tableList); $j++) {
                $currentRow = $startRow;
                $registTable = $tableList[$j];

                // 在当前行写下签到表名称
                $theWorkSheet->setCellValueByColumnAndRow($currentColumn, $currentRow, $registTable['name']);

                // 移到下一行
                ++$currentRow;

                // 获取签到信息
                $registInfo = $this->attendList($registTable['id'], $contactSections);

                // 在当前列写当前签到表的签到信息
                for ($k = 0; $k < count($registInfo); $k++) {
                    $theWorkSheet->setCellValueByColumnAndRow($currentColumn, $currentRow, $registInfo[$k]);

                    // 移到下一行
                    ++$currentRow;
                }

                // 移到下一列
                ++$currentColumn;
            }


            // 统计工作

            // 每列求和运算(每张签到表有多少人出勤)的起止行
            $sumRowFrom = $startRow + 1;
            $sumRowTo = $currentRow - 1;

            // 每行求和运算(该团员出勤了多少次)的起止列
            $sumColumnFrom = $startColumn + 4;

            // 当前WorkSheet没有签到表，则列没有移动
            if ($currentColumn == $startColumn) {
                $sumColumnTo = $currentColumn;
            }
            else {
                $sumColumnTo = $currentColumn - 1;
            }


            // 每列求和运算(每张签到表有多少人出勤)
            $sumAtRow = $sumRowTo + 2;
            for ($j = $sumColumnFrom; $j <= $sumColumnTo; $j++) {
                $columnFlag = IntToExcelChar($j);
                $sumSentence = '=SUM(' . $columnFlag . $sumRowFrom . ':' . $columnFlag . $sumRowTo . ')';
                $theWorkSheet->setCellValueByColumnAndRow($j, $sumAtRow, $sumSentence);
            }

            // 每行求和运算(该团员出勤了多少次)
            $sumAtColumn = $sumColumnTo + 2;
            $sumColumnFromFlag = IntToExcelChar($sumColumnFrom);
            $sumColumnToFlag = IntToExcelChar($sumColumnTo);
            for ($j = $sumRowFrom; $j <= $sumRowTo; $j++) {
                $sumSentence = '=SUM(' . $sumColumnFromFlag . $j . ':' . $sumColumnToFlag . $j . ')';
                $theWorkSheet->setCellValueByColumnAndRow($sumAtColumn, $j, $sumSentence);
            }


        }

        // 保存文件
        $excelWriter = PHPExcel_IOFactory::createWriter($excelFile, 'Excel2007');
        $fileName = "博士合唱团" . $fromDate . "至" . $toDate . "签到表" . ".xlsx";
        $filePath = __DIR__ . '/excel_file/' . $fileName;
        $excelWriter->save($filePath);
        return '/excel_file/' . $fileName;
    }

    /**
     * 指定时间范围内的签到表，按照签到表种类(type)分组，组内按照'date-location'排序
     *
     * 参数
     * fromDate 开始日期
     * toDate 截止日期
     *
     * 返回值
     * 签到表数组 [{type: 大排, tableList: [{id: 5, name:2017-08-12周日雁栖湖大排}, ...]}, ...]
     *
     */
    public function registTableSections($fromDate, $toDate) : array {
        $result = array();
        foreach (validTableType as $type) {
            $tableArray = $this->_dbManager->registTableOfType($type, $fromDate, $toDate);
            $tableList = array();
            foreach ($tableArray as $table) {
                $tableList[] = array('id'=>$table['id'], 'name'=>descriptionForRegistTable($table));
            }

            $result[] = array('type'=>$type, 'tableList'=>$tableList);
        }

        return $result;
    }

    /**
     * SATB12八声部按照location的分声部分园区团员名单(共16个数组)
     *
     * 参数
     * 无
     *
     * 返回值
     * 分声部分园区团员名单数组 [{part: T2, location: 中关村, contactList:[{id: 3, name: 蓝胖, locaton: 中关村, part: T2}, ...]}, ...]
     */
    public function contactSections() : array {
        return $this->_dbManager->contactSectionsByPartAndLocation();
    }

    /**
     * 指定签到表的出勤描述
     *
     * 参数
     * registTableID // 签到表id
     *
     * 返回值
     * 签到表出勤描述 [0, 1, 1, 0 ,1, 0, ...]
     *
     */
    public function attendList($registTableID, $contactSections) : array {
        return $this->_dbManager->attendDescriptionForRegistTable($registTableID, $contactSections);
    }

}


?>