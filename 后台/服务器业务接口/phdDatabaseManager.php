

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
	 * int // 0-不存在 | 1-存在
	 *
	 */
	public function isRegistTableExist($registTableDate, $registTableType, $registLocationType) : int;


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
	 * 签到
	 *
	 * 参数
	 * registTableDate //
	 * registTableType //
	 * registLocationType //
	 * selectedContactPart //
	 * selectedContactName //
	 *
	 * 返回值
	 * int // 0-成功 1-签到表不存在 2-失败(已签到)
	 *
	 */
	//public function tableRegist($registTableDate, $registTableType, $registLocationType, $selectedContactPart, $selectedContactName);
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

	private $_mysqliConnection;

	public function __construct() {
	    $this->_mysqliConnection = new mysqli('p:' . self::_dbHost, self::_dbUsername, self::_dbPassword, self::_dbName);
        if ($this->_mysqliConnection->connect_errno != 0) {
            // 写log报错
            echo "Failed to connect to MySQL: (" . $this->_mysqliConnection->connect_errno . ") " . $this->_mysqliConnection->connect_error;
        }

        $this->_mysqliConnection->set_charset('utf8');
    }

    public function isRegistTableExist($registTableDate, $registTableType, $registLocationType) : int {
	    $queryStr = "SELECT id FROM " . self::_db_regist_table . " WHERE date = '" . $registTableDate . "' AND type = '". $registTableType. "' AND location = '" . $registLocationType . "'";
        $result = $this->_mysqliConnection->query($queryStr);
        $status = 0;
        if ($result->num_rows > 0) {
            $status = 1;
        }

        return $status;
    }

    public function insertRegistTable($registTableDate, $registTableType, $registLocationType) : int {
        $queryStr = "INSERT INTO " . self::_db_regist_table . "(date, type, location) VALUES('" . $registTableDate . "', '" . $registTableType . "', '" . $registLocationType . "')";
        $result = $this->_mysqliConnection->query($queryStr);
        $status = 1;
        if ($result == true) {
            $status = 0;
        }

        return $status;
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