<?php
/**
 * Created by 梁志鹏 on 17-9-12 下午12:46
 * Copyright (c) 2017 PhdChorus. All rights reserved
 */

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/12
 * Time: 12:46
 */

include_once('phdRecruitDatabaseManager.php');

$tableIDArr = array(2, 3, 4);

const _dbHost = "10.66.85.131";
const _dbUsername = "phdChorusRecruit";
const _dbPassword = "SATB@phdChorus";
const _dbName = "phdChorusRecruit";

$mysqli = new mysqli('p:' . _dbHost, _dbUsername, _dbPassword, _dbName);

$dbManager = new WXRecruitDatabaseManager();

foreach ($tableIDArr as $tableID) {
    $selectStr = "SELECT * FROM regist_info WHERE regist_table_id = " . $tableID;
    $selectResult = $mysqli->query($selectStr);
    while ($row = $selectResult->fetch_assoc()) {
        $contactID = $dbManager->addContactInfo($row['name'], $row['sex'], '无记录', $row['phone'], $row['email'], '无记录', $row['location'], $row['company'], $row['grade'], $row['vocal'], $row['instruments'], $row['readMusic'], 0, '无', '无', '无', '无');
        $dbManager->applyToJoin($tableID, $contactID);
        $dbManager->confirmInterview($tableID, $row['name']);
        $dbManager->interviewRegist($tableID, $row['name']);
    }

    $selectResult->free();
}