

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
     * 按SATB获取团员详细信息
     *
     * 参数
     * 无
     *
     * 返回值
     * contactInfo // SATB四声部团员列表{'S' : [{contactID:'5', contactName:'蓝胖子', contactPart:'T2', contactLocation:'中关村'}, ...], ...}
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
        $status = -1;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $status = $row['id'];
            }
        }

        return $status;
    }

    public function insertRegistTable($registTableDate, $registTableType, $registLocationType) : int {
        $queryStr = "INSERT INTO " . self::_db_regist_table . " (date, type, location) VALUES('" . $registTableDate . "', '" . $registTableType . "', '" . $registLocationType . "')";
        $result = $this->_mysqliConnection->query($queryStr);
        $status = 1;
        if ($result == true) {
            $status = 0;
        }

        return $status;
	}

    public function idOfContact($contactName, $contactPart, $contactLocation) : int {
	    $queryStr = "SELECT id FROM " . self::_db_contact . " WHERE name = '" . $contactName . "' AND part = '" . $contactPart . "' AND location = '" . $contactLocation . "'";
        $result = $this->_mysqliConnection->query($queryStr);
        $status = -1;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $status = $row['id'];
            }
        }

        return $status;
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
        }

        return $contactInfo;
    }

    public function contactInfoForRegistInSATB12() : array {
        $contactInfo = array();

        foreach (self::_partArrayInSATB12 as $part) {
            $queryStr = "SELECT name from " . self::_db_contact . " WHERE part = '" . $part . "'";
            $result = $this->_mysqliConnection->query($queryStr);
            $partInfo = array();
            while ($row = $result->fetch_assoc()) {
                $partInfo[] = $row[self::_db_contact_name];
            }

            $contactInfo[$part] = $partInfo;
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

        if ($registID > 0 && $attend) {
            return 1;
        }
        else {
            $queryStr = '';
            if ($registID > 0) {
                // update 签到表中 attend的值
                $queryStr = "UPDATE " . self::_db_regist_info . " SET attend = 'true' WHERE regist_table_id = '" . $registTableID . "' AND contact_id = '" . $contactID . "'";
            } else {
                // insert 新条目
                $queryStr = "INSERT INTO " . self::_db_regist_info . " (regist_table_id, contact_id, attend) " . " VALUES ('" . $registTableID . "', '" . $contactID . "', 'true')";
            }

            $queryResult = $this->_mysqliConnection->query($queryStr);
            if ($queryResult) {
                return 0;
            }

            return 2;
        }

        return -1;
	}


	public function __destruct() {
	    $this->_mysqliConnection->close();
    }

/*
    public function __get($property) {
        if (isset($this->__data[$property])) {
            return $this->__data[$property];
        } else {
            return false;
        }
    }

    public function __set($property, $value) {
        if (isset($this->__data[$property])) {
            return $this->__data[$property] = $value;
        } else {
            return false;
        }
    }
*/

}


?>