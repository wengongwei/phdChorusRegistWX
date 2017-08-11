<?php

/**
 * 工具类函数
 */

const validTableDateType = array('Y-m-d');
const validTableType = array('大排', '小排', '周日晚', '声乐课');
const validTableLocationType = array('中关村', '雁栖湖');

function isValidTableDate($tableDate) {
    $unixTime = strtotime($tableDate);
    if (!$unixTime) {
    	//strtotime转换不对，日期格式显然不对。
        return false;
    }
    //校验日期的有效性，只要满足其中一个格式就OK
    foreach (validTableDateType as $format) {
        if (date($format, $unixTime) == $tableDate) {
            return true;
        }
    }

    return false;
}

function isValidTableType($tableType) {
	foreach (validTableType as $format) {
		if ($tableType == $format) {
			return true;
		}
	}

	return false;
}

function isValidTableLocationType($tableLocation) {
	foreach (validTableLocationType as $format) {
		if ($tableLocation == $format) {
			return true;
		}
	}

	return false;
}

?>