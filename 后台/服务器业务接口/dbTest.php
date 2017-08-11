<?php

$registTableDate = '2017-08-11';
$registTableType = '大排';
$registLocationType = '中关村';

$dbManager = new WXDatabaseManager();

// 是否已存在相同的签到表
$tableExist = $dbManager->isRegistTableExist($registTableDate, $registTableType, $registLocationType);
if ($tableExist == 1) {
    $result[return_status] = '1';
}

echo json_encode($result);

?>