

<?php

/**
 * 数据库管理单例
 */

interface DatabaseManager {
	
	/**
	 * 签到表是否已存在
	 *
	 * 参数
 	 * registTableDate // 日期 '2018-08-06'
 	 * registTableType // 类型 '大排' OR '小排' OR '周日晚' OR '声乐课'
	 * registLocationType // 园区 '中关村' OR '雁栖湖'
	 *
	 * 返回值
	 * int // -1表示registTable不存在 | 如存在则返回签到表的数据库表id值
	 *
	 */
	public function idOfRegistTable($registTableDate, $registTableType, $registLocationType) : int;


	/**
	 * 插入签到表
	 *
	 * 参数（同isRegistTableExist）
	 *
	 * 返回值
	 *
	 * int // 0-成功 | 1-失败
	 *
	 */
	public function insertRegistTable($registTableDate, $registTableType, $registLocationType) : int;


    /**
     * 团员是否已存在
     *
     * 参数
     * contactName // 姓名
     * contactPart // 声部
     * contactLocation // 所在园区
     *
     * 返回值
     * int // -1表示contact不存在 | 如存在则返回contact的数据库表id值
     *
     */
    public function idOfContact($contactName, $contactPart, $contactLocation) : int;

    /**
     * 添加团员
     *
     * 参数
     * contactName // 姓名
     * contactPart // 声部
     * contactLocation // 所在园区
     *
     * 返回值
     * int // 0-成功 | 1-失败
     *
     */
    public function insertContact($contactName, $contactPart, $contactLocation) : int;


    /**
     * 按SATB12获取团员详细信息
     *
     * 参数
     * 无
     *
     * 返回值
     * contactInfo // SATB12八声部团员列表{'T2' : [{contactID:'5', contactName:'蓝胖子'}, ...], ...}
     *
     */
    public function contactInfoInSATB12() : array;


    /**
     * 按SATB12获取团员详细信息
     *
     * 参数
     * 无
     *
     * 返回值
     * contactInfo // SATB12八声部团员列表{'T2' : [{contactID:'5', contactName:'蓝胖子'}, ...], ...}
     *
     */
    public function contactInfoForRegistInSATB12() : array;


    /*
     * 签到
     *
     * 参数
     * registTableID // 签到表id
     * contactID // 团员id
     *
     * 返回值
     * status // 0-成功 | 1-失败(已签到，无需重复签到) | 2-失败(数据库代码逻辑错误)
     *
     */
    public function tableRegist($registTableID, $contactID) : int;

    /*
     * 获取签到表(regist_table)
     *
     * 参数
     * theTableID // 用以参考的tableID
     * isNewer // 0-返回比tableID小的10张签到表(所有符合条件的签到表，按tableID倒序排，取前10张) | 1-返回比tableID大的所有签到表
     *
     * 返回值
     * registTableList // 签到表数组[{'id': '12', 'date': '2017-08-11', 'type': '小排', 'location': '中关村'}, ...]
     */
    public function registTableList($theTableID, $isNewer) : array;

    /*
     * 获取签到表中的出勤名单
     *
     * 参数
     * registTableID // 选择的签到表的id
     * contactLocationType  // 需要统计的园区 0-都统计 | 1-只统计中关村 | 2-只统计雁栖湖
     *
     * 返回值
     * registInfo // 出勤名单{'S1': {'attend': ['刘晓雯', ...], 'absent': ['小白姐', ...]}, ....}
     *
     */
    public function registInfoOfRegistTable($registTableID, $contactLocationType) : array;
}

class WXDatabaseManager implements DatabaseManager {
 	
 	// 数据库账号
	const _dbHost = "10.66.85.131";
	const _dbUsername = "phdChorusRegist";
	const _dbPassword = "SATB@phdChorus";
	const _dbName = "test_phdChorusRegist";
	const _db_regist_table = "regist_table";
	const _db_contact = "contact";
	const _db_regist_info = "regist_info";

    const _partArrayInSATB12 = array('S1', 'S2', 'A1', 'A2', 'T1', 'T2', 'B1', 'B2');
    const _partArrayInABST12 = array('A1', 'A2', 'B1', 'B2', 'S1', 'S2', 'T1', 'T2');

    const _db_contact_id = 'id';
    const _db_contact_part = 'part';
    const _db_contact_name = 'name';
    const _db_contact_location = 'location';

	private $_mysqliConnection;

	public function __construct() {
	    $this->_mysqliConnection = new mysqli('p:' . self::_dbHost, self::_dbUsername, self::_dbPassword, self::_dbName);
        if ($this->_mysqliConnection->connect_errno != 0) {
            // 写log报错
            echo "Failed to connect to MySQL: (" . $this->_mysqliConnection->connect_errno . ") " . $this->_mysqliConnection->connect_error;
        }

        $this->_mysqliConnection->set_charset('utf8');
    }

    public function idOfRegistTable($registTableDate, $registTableType, $registLocationType) : int {
	    $queryStr = "SELECT id FROM " . self::_db_regist_table . " WHERE date = '" . $registTableDate . "' AND type = '". $registTableType. "' AND location = '" . $registLocationType . "'";
        $result = $this->_mysqliConnection->query($queryStr);
        $tableID = -1;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tableID = $row['id'];
            }
        }

        $result->free();

        return intval($tableID);
    }

    public function insertRegistTable($registTableDate, $registTableType, $registLocationType) : int {
        $queryStr = "INSERT INTO " . self::_db_regist_table . " (date, type, location) VALUES ('" . $registTableDate . "', '" . $registTableType . "', '" . $registLocationType . "')";
        $result = $this->_mysqliConnection->query($queryStr);
        $status = 1;
        if ($result == true) {
            $status = 0;
        }

        // 创建签到表后，全部人都默认没有签到，向regist_info表中写信息
        if ($status == 0) {
            $tableID = $this->idOfRegistTable($registTableDate, $registTableType, $registLocationType);
            $selectStr = "SELECT id FROM " . self::_db_contact . " WHERE include_in_statics = 1";
            $selectResult = $this->_mysqliConnection->query($selectStr);
            $insertStr = "";

            if ($selectResult->num_rows > 0) {
                while ($row = $selectResult->fetch_assoc()) {
                    $insertStr .=  "INSERT INTO " . self::_db_regist_info . " (regist_table_id, contact_id, attend) " . " VALUES ('" . $tableID . "', '" . $row['id'] . "', 0);";
                }

                $insertResult = $this->_mysqliConnection->multi_query($insertStr);
                if ($insertResult == false) {
                    $status = 1;
                }
            }

            $selectResult->free();
        }

        return $status;
	}

    public function idOfContact($contactName, $contactPart, $contactLocation) : int {
	    $queryStr = "SELECT id FROM " . self::_db_contact . " WHERE name = '" . $contactName . "' AND part = '" . $contactPart . "' AND location = '" . $contactLocation . "'";
        $result = $this->_mysqliConnection->query($queryStr);
        $contactID = -1;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $contactID = $row['id'];
            }
        }

        $result->free();

        return intval($contactID);
	}

    public function insertContact($contactName, $contactPart, $contactLocation) : int {
	    $queryStr = "INSERT INTO " . self::_db_contact . " (name, part, location) VALUES ('" . $contactName . "', '" . $contactPart . "', '" . $contactLocation . "')";
        $result = $this->_mysqliConnection->query($queryStr);
        $status = 1;
        if ($result == true) {
            $status = 0;
        }

        return $status;
    }


    public function contactInfoInSATB12() : array {
        $contactInfo = array();

        /* 以下代码多次查询，花费时间长，果断放弃
        foreach (self::_partArrayInSATB12 as $part) {
            $queryStr = "SELECT * from " . self::_db_contact . " WHERE part = '" . $part . "'";
            $result = $this->_mysqliConnection->query($queryStr);
            $partInfo = array();
            while ($row = $result->fetch_assoc()) {
                $contactID = $row[self::_db_contact_id];
                $contactName = $row[self::_db_contact_name];
                $contactPart = $row[self::_db_contact_part];
                $contactLocation = $row[self::_db_contact_location];
                $partInfo[] = array(self::_db_contact_id=>$contactID, self::_db_contact_name=>$contactName, self::_db_contact_part=>$contactPart, self::_db_contact_location=>$contactLocation);
            }

            $contactInfo[$part] = $partInfo;
            $result->free();
        }
        */

        // 批量查询，高性能，果断选择
        $queryStr = "";
        foreach (self::_partArrayInSATB12 as $part) {
            $queryStr .= "SELECT * from " . self::_db_contact . " WHERE part = '" . $part . "' ;";
        }

        $partIndex = 0;
        if ($this->_mysqliConnection->multi_query($queryStr)) {
            do {
                if ($result = $this->_mysqliConnection->store_result()) {
                    $partInfo = array();
                    while ($row = $result->fetch_assoc()) {
                        $contactID = $row[self::_db_contact_id];
                        $contactName = $row[self::_db_contact_name];
                        $contactPart = $row[self::_db_contact_part];
                        $contactLocation = $row[self::_db_contact_location];
                        $partInfo[] = array(self::_db_contact_id=>$contactID, self::_db_contact_name=>$contactName, self::_db_contact_part=>$contactPart, self::_db_contact_location=>$contactLocation);
                    }

                    $part = self::_partArrayInSATB12[$partIndex];
                    ++$partIndex;
                    $contactInfo[$part] = $partInfo;

                    $result->free();
                }
            }while($this->_mysqliConnection->next_result());
        }

        return $contactInfo;
    }

    public function contactInfoForRegistInSATB12() : array {
        $contactInfo = array();

        foreach (self::_partArrayInSATB12 as $part) {
            $queryStr = "SELECT id, name from " . self::_db_contact . " WHERE part = '" . $part . "'";
            $result = $this->_mysqliConnection->query($queryStr);
            $partInfo = array();
            while ($row = $result->fetch_assoc()) {
                $contactID = $row[self::_db_contact_id];
                $contactName = $row[self::_db_contact_name];
                $partInfo[] = array(self::_db_contact_id=>$contactID, self::_db_contact_name=>$contactName);
            }

            $contactInfo[$part] = $partInfo;
            $result->free();
        }

        return $contactInfo;
    }

    public function tableRegist($registTableID, $contactID) : int {
        // 是否已经签过到了
        $selectStr = "SELECT id, attend FROM " . self::_db_regist_info . " WHERE regist_table_id = '" . $registTableID . "' AND contact_id = '" . $contactID . "'";
        $selectResult = $this->_mysqliConnection->query($selectStr);
        $registID = -1;
        $attend = false;
        if ($selectResult->num_rows > 0) {
            while ($row = $selectResult->fetch_assoc()) {
                $registID = $row['id'];
                $attend = $row['attend'];
            }
        }

        $registID = intval($registID);
        $attend = intval($attend);
        $selectResult->free();

        if ($registID > 0 && $attend == 1) {
            return 1;
        }
        else {
            $queryStr = '';
            if ($registID > 0) {
                // update 签到表中 attend的值
                $queryStr = "UPDATE " . self::_db_regist_info . " SET attend = 1 WHERE regist_table_id = '" . $registTableID . "' AND contact_id = '" . $contactID . "'";
            } else {
                // insert 新条目
                $queryStr = "INSERT INTO " . self::_db_regist_info . " (regist_table_id, contact_id, attend) " . " VALUES ('" . $registTableID . "', '" . $contactID . "', 1)";
            }

            $queryResult = $this->_mysqliConnection->query($queryStr);
            if ($queryResult == true) {
                return 0;
            }

            return 2;
        }

        return 2;
	}

    public function registTableList($theTableID, $isNewer) : array {
	    $selectStr = '';
	    if ($isNewer == 0) {
	        $selectStr = "SELECT * FROM " . self::_db_regist_table . " WHERE id < " . $theTableID . " ORDER BY id DESC LIMIT 10";
        }
        else if ($isNewer == 1) {
	        $selectStr = "SELECT * FROM " . self::_db_regist_table . " WHERE id > " . $theTableID . " ORDER BY id DESC";
        }

        $selectResult = $this->_mysqliConnection->query($selectStr);
	    $tableList = array();
        while ($row = $selectResult->fetch_assoc()) {
            $tableID = $row['id'];
            $tableDate = $row['date'];
            $tableType = $row['type'];
            $tableLocation = $row['location'];
            $tableList[] = array('id'=>$tableID, 'date'=>$tableDate, 'type'=>$tableType, 'location'=>$tableLocation);
        }

        $selectResult->free();

        return $tableList;
    }

    public function registInfoOfRegistTable($registTableID, $contactLocationType) : array {
	    $contactLocation  = '%';
	    if ($contactLocationType == 1) {
	        $contactLocation = '中关村';
        }
        else if ($contactLocationType == 2) {
	        $contactLocation = '雁栖湖';
        }

        $queryStr = "";
	    $attendStatus = array(0, 1);
	    foreach (self::_partArrayInSATB12 as $part) {
            foreach ($attendStatus as $attend) {
                $queryStr .= "SELECT contact.name FROM contact INNER JOIN regist_info ON contact.id = regist_info.contact_id WHERE regist_info.regist_table_id = " . $registTableID . " AND regist_info.attend = " . $attend . " AND contact.part = '" . $part . "' AND contact.location like '" . $contactLocation . "' ; ";
            }
        }

	    $partIndex = 0;
	    $registInfo = array();

	    if ($this->_mysqliConnection->multi_query($queryStr)) {
	        do {

                $result_absent = $this->_mysqliConnection->store_result();
                $result_attend = null;
                if ($this->_mysqliConnection->next_result()) {
                    $result_attend = $this->_mysqliConnection->store_result();
                }

                if ($result_absent && $result_attend) {
                    $partAbsent = array();
                    while ($row = $result_absent->fetch_assoc()) {
                        $partAbsent[] = $row['name'];
                    }

                    $partAttend = array();
                    while ($row = $result_attend->fetch_assoc()) {
                        $partAttend[] = $row['name'];
                    }

                    $part = self::_partArrayInSATB12[$partIndex];
                    ++$partIndex;
                    $partInfo = array('part'=>$part, 'absent'=>$partAbsent, 'attend'=>$partAttend);
                    $registInfo[] = $partInfo;
                }

                if ($result_absent) {
                    $result_absent->free();
                }

                if ($result_attend) {
                    $result_attend->free();
                }


	            /* 这段代码仅用于测试
	            if ($result = $this->_mysqliConnection->store_result()) {
	                $partInfo = array();
	                while ($row = $result->fetch_assoc()) {
	                    $partInfo[] = $row['name'];
                    }

                    $part = self::_partArrayInSATB12[$partIndex % 8];
                    ++$partIndex;
                    $registInfo[$part] = $partInfo;
                }
	            */
            }while($this->_mysqliConnection->next_result());
        }

        return $registInfo;
	}

	public function __destruct() {
	    $this->_mysqliConnection->close();
    }


}


?>