/*
** 创建签到表
** 接口名
** wxCreateRegistTable.php
**
** 参数
** registTableDate // 日期 '2018-08-06'
** registTableType // 类型 '大排' OR '小排' OR '周日晚' OR '声乐课'
** registLocationType // 园区 '中关村' OR '雁栖湖'
**
** 返回值
** status // 0-成功；1-失败（签到表已存在）
*/

<?php

$result = array('status' => '0');
echo json_encode(result);

?>