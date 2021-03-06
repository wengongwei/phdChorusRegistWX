

<?php

include_once ('phdUtils.php');

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
     * 团员信息
     *
     * 参数
     * contactID // 团员id
     *
     * 返回值
     * contact // 团员信息{id: 12, name: '蓝胖子', part: 'T2', location: '中关村'}
     *
     */
    public function contactInfo($contactID) : array ;

    /**
     * 添加团员
     *
     * 参数
     * contactName // 姓名
     * contactPart // 声部
     * contactLocation // 所在园区
     * contactIncludeInStatics // 是否纳入统计数据 1-纳入 | 0-不纳入
     *
     * 返回值
     * int // 0-成功 | 1-失败
     *
     */
    public function insertContact($contactName, $contactPart, $contactLocation, $contactIncludeInStatics) : int;

    /**
     * 修改（更新）团员信息
     *
     * 参数同insertContact接口
     *
     * 返回值
     * int // 0-成功 | 1-失败
     *
     */
    public function updateContactInfo($contactID, $contactName, $contactPart, $contactLocation, $contactIncludeInStatics) : int;

    /**
     * 删除团员
     *
     * 参数
     * contactID
     *
     * 返回值
     * int // 0-成功 | 1-失败
     *
     */
    public function deleteContact($contactID) : int;

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


    /**
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

    /**
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

    /**
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

    /**
     * 指定起止日期内制定类型的签到表
     *
     * 参数
     * type // 签到表类型
     *
     * 返回值
     * 签到表数组 // 按date-location排升序 [{id: 10, type: 大排, date: 2017-08-06, location:中关村}, ...]
     *
     */
    public function registTableOfType($type, $fromDate, $toDate) : array;

    /**
     * SATB12八声部按照location的分声部分园区团员名单(共16个数组)
     *
     * 参数
     * 无
     *
     * 返回值
     * 分声部分园区团员名单数组(按contact_id升序排) contactSections [{part: T2, location: 中关村, contactList:[{id: 3, name: 蓝胖, locaton: 中关村, part: T2}, ...]}, ...]
     */
    public function contactSectionsByPartAndLocation() : array;

    /**
     * 指定签到表的出勤描述
     *
     * 参数
     * registTableID // 签到表id
     * contactSections // 按照这个数组中的contact顺序，依次描述contact是否签到(0 | 1)
     *
     * 返回值
     * 签到的0 | 1描述
     *
     */
    public function attendDescriptionForRegistTable($registTableID, $contactSections) : array;

    /**
     * 判定用户是否为requestAuthority的授权用户，授权用户可进行数据库修改相关的操作
     * 声部长只能修改自己声部相关的信息，团长可以修改所有信息
     *
     * 参数
     * wxNickname // 微信昵称
     * $requestAuthority // 授权范围，S | A | T | B | ALL | ANY，ANY指的是对所有授权用户开放
     * 返回值
     * status // 0-未授权 | 1-已授权
     *
     */
    public function userAuthorizedStatus($wxNickname, $requestAuthority): int;

    /*
     * 接口功能
     * 给TA分配权限
     *
     * 参数
     * contactName // TA的姓名
     * contactWxID // TA的微信ID
     * contactWXNickname // TA的微信昵称
     * allocAuthority // 分配给TA的权限
     * wxNickname // 我的微信昵称
     *
     * 返回值
     * status // 1-成功 | 0-失败 | 2-失败(没有权限)
     */
    public function allocAuthority($contactName, $contactWxID, $contactWXNickname, $allocAuthority, $wxNickname) : int;
}

class WXDatabaseManager implements DatabaseManager {
 	
 	// 数据库账号
	const _dbHost = "10.66.85.131";
	const _dbUsername = "phdChorusRegist";
	const _dbPassword = "SATB@phdChorus";
	const _dbName = "phdChorusRegist";
	const _db_regist_table = "regist_table";
	const _db_contact = "contact";
	const _db_regist_info = "regist_info";
    const _db_authorized_user = "authorized_user";

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

    public function contactInfo($contactID) : array {
	    $selectStr = "SELECT * FROM " . self::_db_contact . " WHERE id = " . $contactID;
	    $selectResult = $this->_mysqliConnection->query($selectStr);
	    $contact = array();
	    while ($row = $selectResult->fetch_assoc()) {
	        $contact['id'] = $row['id'];
	        $contact['name'] = $row['name'];
	        $contact['part'] = $row['part'];
	        $contact['location'] = $row['location'];
	        $contact['includeInStatics'] = $row['include_in_statics'];
        }

        return $contact;
    }

    public function insertContact($contactName, $contactPart, $contactLocation, $contactIncludeInStatics) : int {
	    $queryStr = "INSERT INTO " . self::_db_contact . " (name, part, location, include_in_statics) VALUES ('" . $contactName . "', '" . $contactPart . "', '" . $contactLocation . "', " . $contactIncludeInStatics . ")";
        $result = $this->_mysqliConnection->query($queryStr);
        $status = 1;
        if ($result == true) {
            $status = 0;
        }

        return $status;
    }

    public function updateContactInfo($contactID, $contactName, $contactPart, $contactLocation, $contactIncludeInStatics) : int {
	    $queryStr = "UPDATE " . self::_db_contact . " SET name = '" . $contactName . "', part = '" . $contactPart . "', location = '" . $contactLocation . "', include_in_statics = " . $contactIncludeInStatics . " WHERE id = " . $contactID . "";
        $result = $this->_mysqliConnection->query($queryStr);
        $status = 1;
        if ($result == true) {
            $status = 0;
        }

        return $status;
	}

    public function deleteContact($contactID) : int {
	    $queryStr = "DELETE FROM " . self::_db_contact . " WHERE id = " . $contactID;
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
                $queryStr .= "SELECT contact.name FROM contact INNER JOIN regist_info ON contact.id = regist_info.contact_id WHERE regist_info.regist_table_id = " . $registTableID . " AND regist_info.attend = " . $attend . " AND contact.part = '" . $part . "' AND contact.location like '" . $contactLocation . "' AND contact.include_in_statics = 1; ";
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

    public function registTableOfType($type, $fromDate, $toDate): array {
	    $selectStr = "SELECT * FROM " . self::_db_regist_table . " WHERE type = '" . $type . "' AND date BETWEEN '" . $fromDate ."' AND '" . $toDate . "' ORDER BY date, location DESC";
	    $selectResult = $this->_mysqliConnection->query($selectStr);
	    $result = array();
	    while ($row = $selectResult->fetch_assoc()) {
	        $result[] = array('id'=>$row['id'], 'type'=>$row['type'], 'location'=>$row['location'], 'date'=>$row['date']);
        }

        $selectResult->free();

        return $result;
    }

    public function contactSectionsByPartAndLocation() : array {

	    $contactSections = array();
	    foreach (validPartType as $part) {
	        foreach (validLocationType as $location) {
                $contactSections[] = array('part'=>$part, 'location'=>$location);
            }
        }

        $queryStr = '';
        foreach ($contactSections as $section) {
            $location = $section['location'];
            $part = $section['part'];
            $queryStr .= "SELECT * FROM " . self::_db_contact . " WHERE part = '" . $part . "' AND location = '" . $location . "' ORDER BY id ASC;";
        }

        $sectionIndex = 0;
        if ($this->_mysqliConnection->multi_query($queryStr)) {
            do {
                $contactList = array();
                if ($result = $this->_mysqliConnection->store_result()) {
                    while ($row = $result->fetch_assoc()) {
                        $contactList[] = array('id'=>$row['id'], 'name'=>$row['name'], 'part'=>$row['part'], 'location'=>$row['location']);
                    }

                    $result->free();
                }

                $section = $contactSections[$sectionIndex];
                $section['contactList'] = $contactList;
                $contactSections[$sectionIndex] = $section;
                ++$sectionIndex;
            }while($this->_mysqliConnection->next_result());
        }

        return $contactSections;
    }

    public function attendDescriptionForRegistTable($registTableID, $contactSections) : array {
        $contactSectionsWithAttendList = $contactSections;
	    // 为每个section构造一条query语句
        $queryStr = '';
        foreach ($contactSectionsWithAttendList as $section) {
            $location = $section['location'];
            $part = $section['part'];
            $queryStr .= "SELECT contact.id FROM contact INNER JOIN regist_info ON contact.id = regist_info.contact_id WHERE regist_info.regist_table_id = " . $registTableID . " AND regist_info.attend = 1 AND contact.part = '" . $part . "' AND contact.location = '" . $location . "' ORDER BY contact.id ASC;";
        }

        // 获取每个section的出勤人员名单(regist_info.attend=1)
        $sectionIndex = 0;
        if ($this->_mysqliConnection->multi_query($queryStr)) {
            do {
                $attendList = array();
                if ($result = $this->_mysqliConnection->store_result()) {
                    while ($row = $result->fetch_assoc()) {
                        $attendList[] = $row['id'];
                    }

                    $result->free();
                }

                $section = $contactSectionsWithAttendList[$sectionIndex];
                $section['attendList'] = $attendList;
                $contactSectionsWithAttendList[$sectionIndex] = $section;
                ++$sectionIndex;
            }while($this->_mysqliConnection->next_result());
        }

        // 比对section中的attendList和contactList，得出attendDescription
        $attendDescription = array();
        foreach ($contactSectionsWithAttendList as $section) {
            $contactList = $section['contactList'];
            $attendList = $section['attendList'];

            $i = $j = 0;
            for ($i = 0; $i < count($contactList), $j < count($attendList); $i++) {
                $contact = $contactList[$i];
                $contactID = $contact['id'];
                $attendID = $attendList[$j];
                if ($contactID == $attendID) {
                    $attendDescription[] = 1;
                    ++$j;
                }
                else if ($contactID < $attendID) {
                    $attendDescription[] = 0;
                }
                else {
                    // 程序不应该运行到此处
                    $attendDescription[] = 0;
                }
            }

            // 除开attendList，剩下的人都是absent
            for ( ; $i < count($contactList); $i++) {
                $attendDescription[] = 0;
            }
        }

        return $attendDescription;
    }

    public function userAuthorizedStatus($wxNickname, $requestAuthority): int {
        $queryStr = "SELECT authority FROM " . self::_db_authorized_user . " WHERE wx_nickname = '" . $wxNickname ."'";
        $result = $this->_mysqliConnection->query($queryStr);
        $status = 0;
        if ($result->num_rows > 0) {
            $authority = '';
            while ($row = $result->fetch_assoc()) {
                $authority = $row['authority'];
            }

            if ($requestAuthority == 'ANY') {
                $status = 1;
            }
            else if ($requestAuthority == 'READ') {
                $status = 1;
            }
            else if ($authority == 'ALL') {
                $status = 1;
            }
            else if (strpos($requestAuthority, $authority) === 0) {
                $status = 1;
            }
        }

        $result->free();

        return $status;
    }

    public function allocAuthority($contactName, $contactWxID, $contactWXNickname, $allocAuthority, $wxNickname) : int {
	    if ($this->userAuthorizedStatus($wxNickname, $allocAuthority) != 1) {
            return 2;
        }

        // 看是否已经给此人分配权限
        $selectStr = "SELECT authority FROM " . self::_db_authorized_user . " WHERE wx_id = '" . $contactWxID ."';";
        $selectResult = $this->_mysqliConnection->query($selectStr);
        if ($selectResult->num_rows > 0) {
            // 如果更新了权限，则update，否则直接返回
            $row = $selectResult->fetch_assoc();
            $authority = $row['authority'];
            if ($authority == $allocAuthority) {
                return 1;
            }

            $updateStr = "UPDATE " . self::_db_authorized_user . " SET name = '" . $contactName . "', wx_nickname = '" . $contactWXNickname . "', authority = '" . $allocAuthority . "' WHERE wx_id = '" . $contactWxID ."';";
            $updateResult = $this->_mysqliConnection->query($updateStr);
            if ($updateResult == true && $this->_mysqliConnection->affected_rows > 0) {
                return 1;
            }

            return 0;
        }

        // 如未分配权限，则分配权限
        $insertStr = "INSERT INTO " . self::_db_authorized_user . " (name, wx_id, wx_nickname, authority) VALUES ('" . $contactName . "', '" . $contactWxID . "', '" . $contactWXNickname . "', '" . $allocAuthority . "');";
        $insertResult = $this->_mysqliConnection->query($insertStr);
        if ($insertResult == true) {
            return 1;
        }

        return 0;
    }

    public function __destruct() {
	    $this->_mysqliConnection->close();
    }


}


?>