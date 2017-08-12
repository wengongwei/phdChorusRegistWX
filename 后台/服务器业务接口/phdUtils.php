<?php

/**
 * 工具类函数
 */

const validTableDateType = array('Y-m-d');
const validTableType = array('大排', '小排', '周日晚', '声乐课');
const validLocationType = array('中关村', '雁栖湖');
const validPartType = array('S1', 'S2', 'A1', 'A2', 'T1', 'T2', 'B1', 'B2');
const partArrayInSATB = array('S', 'A', 'T', 'B');

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

function isValidLocationType($tableLocation) {
	foreach (validLocationType as $format) {
		if ($tableLocation == $format) {
			return true;
		}
	}

	return false;
}

function isValidPartType($partType) {
    foreach (validPartType as $format) {
        if ($partType == $format) {
            return true;
        }
    }

    return false;
}

?>