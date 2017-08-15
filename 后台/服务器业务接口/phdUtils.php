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

function weekdayFromDate($date) {
    $weekdayInt = date('w', strtotime($date));
    switch ($weekdayInt) {
        case 0:
            return '周日';
        case 1:
            return '周一';
        case 2:
            return '周二';
        case 3:
            return '周三';
        case 4:
            return '周四';
        case 5:
            return '周五';
        case 6:
            return '周六';
    }

    return '周X';
}

function nameStringFromNameList($nameList) {
    $nameStr = "";
    foreach ($nameList as $name) {
        $nameStr .= ($name . '、');
    }

    return $nameStr;
}

function partDescription($part) {
    $primaryPart = substr($part, 0, 1);
    $partName = '';
    if ($primaryPart == 'S') {
        $partName = '女高音';
    }
    else if ($primaryPart == 'A') {
        $partName = 女中音;
    }
    else if ($primaryPart == 'T') {
        $partName = '男高音';
    }
    else if ($primaryPart == 'B') {
        $partName = '男低音';
    }

    return $partName . $part;
}

function descriptionForRegistTable($table) {
    $weekday = weekdayFromDate($table['date']);
    $tableDescription = $table['date'] . $weekday . $table['location'] . $table['type'];
    return $tableDescription;
}


/**
 * excel文件 - 列值转列标
 */
function IntToExcelChar($index, $start = 65) {
    $str = '';
    if (floor($index / 26) > 0) {
        $str .= IntToExcelChar(floor($index / 26)-1);
    }
    return $str . chr($index % 26 + $start);
}

?>