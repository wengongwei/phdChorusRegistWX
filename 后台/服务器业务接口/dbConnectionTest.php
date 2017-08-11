<?php

const _dbHost = "10.66.85.131";
const _dbUsername = "phdChorusRegist";
const _dbPassword = "SATB@phdChorus";
const _dbName = "phdChorusRegist";
const _db_regist_table = "regist_table";
const _db_contact = "contact";
const _db_regist_info = "regist_info";

$registTableDate = '2017-08-11';
$registTableType = '大排';
$registLocationType = '中关村';


$sqlCon = new mysqli('p:' . _dbHost, _dbUsername, _dbPassword, _dbName);
echo 'connecting...</br>';
echo 'connection error:' . $sqlCon->connect_errno . '</br>';


$selectStr = "SELECT * FROM " . _db_regist_table . " WHERE date = '" . $registTableDate . "' AND type = '". $registTableType. "' AND location = '" . $registLocationType . "'";
echo 'querying...</br>';
echo $selectStr . '</br>';
$selectResult = $sqlCon->query($selectStr);
echo 'number of effected rows:' .  $selectResult->num_rows . '</br>';

$insertStr = "INSERT INTO " . _db_regist_table . "(date, type, location) VALUES('" . $registTableDate . "', '" . $registTableType . "', '" . $registLocationType . "')";
echo $insertStr . '</br>';
$insertResult = $sqlCon->query($insertStr);
if ($insertResult == true) {
    echo 'successfully insert </br>';
}
else {
    echo 'fail to insert, error code:' . $sqlCon->error;
}

?>
