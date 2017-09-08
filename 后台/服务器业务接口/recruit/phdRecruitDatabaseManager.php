<?php
/**
 * Created by 梁志鹏 on 17-9-6 上午9:25
 * Copyright (c) 2017 PhdChorus. All rights reserved.
 */

/**
 * Created by PhpStorm.
 * User: zkey
 * Date: 2017/9/6
 * Time: 上午9:25
 */

interface RecruitDatabaseManager {
    /**
     * 签到表是否已存在
     *
     * 参数
     * registTableDate // 日期 '2018-08-06'
     * registLocationType // 园区 '中关村' OR '雁栖湖'
     *
     * 返回值
     * int // -1表示registTable不存在 | 如存在则返回签到表的数据库表id值
     *
     */
    public function idOfRegistTable($registTableDate, $registLocationType) : int;

    /*
     * 接口功能
     * 插入签到表
     *
     * 参数
     * registTableDate // 日期 '2018-08-06'
     * registLocationType // 园区 '中关村' OR '雁栖湖'
     *
     * 返回值
     * int // 0-成功 | 1-失败
     *
     */
    public function insertRegistTable($registTableDate, $registLocationType) : int;

    /**
     * 判定用户是否为requestAuthority的授权用户，授权用户可进行数据库修改相关的操作
     * 声部长只能修改自己声部相关的信息，团长可以修改所有信息
     *
     * 参数
     * wxNickname // 微信昵称
     * $requestAuthority // 授权范围，S | A | T | B | ALL
     * 返回值
     * status // 0-未授权 | 1-已授权
     *
     */
    public function userAuthorizedStatus($wxNickname, $requestAuthority): int;

    /*
     * 接口功能
     * 查看面试者的签到ID
     *
     * 参数
     * theTableID // 签到表ID
     * contactName // 面试者姓名
     *
     * 返回值
     * interviewerID // -1-未签到 | 面试者签到ID
     *
     */
    public function registIdOfInterviewer ($theTableID, $contactName) : int;

    /*
     * 接口功能
     * 签到者签到
     *
     * 参数
     * tableID // 签到表ID
     * contactName // 姓名
     * contactSex // 性别 0-女 | 1-男
     * contactPhone // 手机号
     * contactEmail // 邮箱
     * contactLocation // 面试者所在园区
     * contactCompany: // 培养单位
     * contactGrade: // 年级
     * contactVocalAbility // 是否学过声乐
     * contactInstruments // 是否学过乐器
     * contactReadMusic // 是否识谱
     *
     * 返回值
     * interviewerID // -1-失败 | 签到ID
     */
    public function tableRegist($tableID, $contactName, $contactSex, $contactPhone, $contactEmail, $contactLocation, $contactCompany, $contactGrade, $contactVocalAbility, $contactInstruments, $contactReadMusic) : int;

    /**
     * 获取签到表(regist_table)
     *
     * 参数
     * theTableID // 用以参考的tableID
     * isNewer // 0-返回比tableID小的10张签到表(所有符合条件的签到表，按tableID倒序排，取前10张) | 1-返回比tableID大的所有签到表
     *
     * 返回值
     * registTableList // 签到表数组[{'id': '12', 'date': '2017-08-11', 'location': '中关村'}, ...]
     */
    public function registTableList($theTableID, $isNewer) : array;

    /*
     * 接口功能
     * 获取签到表中面试者列表
     *
     * 参数
     * registTableID // 签到表ID
     * theInterviewerID // 用以参考的面试者ID
     *
     * 返回值
     * interviewerList // 比theInterviewerID大的所有的面试者 [{'id': 12, 'name': 蓝胖}, ...]
     */
    public function interviewerListOfRegistTable($registTableID, $theInterviewerID) : array;

    /*
     * 接口功能
     * 获取面试者(regist_info)详细信息
     *
     * 参数
     * interviewerID // 面试者ID
     *
     * 返回值
     * interviewerInfo // {'id': 14, 'name': 蓝胖, 'sex': 1, 'location': 中关村, 'company': 中科院软件所, 'grade': 研三, 'phone': 13317945775, 'email': zhipengliang@qq.com, 'vocal': 了解一点, 'instruments': 钢琴+6级, 'readMusic': 简谱}
     */
    public function interviewerDetailInfo($interviewerID) : array;
}

class WXRecruitDatabaseManager implements RecruitDatabaseManager
{
    // 数据库账号
    const _dbHost = "10.66.85.131";
    const _dbUsername = "phdChorusRecruit";
    const _dbPassword = "SATB@phdChorus";
    const _dbName = "test_phdChorusRecruit";
    const _db_regist_table = "regist_table";
    const _db_regist_info = "regist_info";
    const _db_authorized_user = "authorized_user";

    private $_mysqliConnection;

    public function __construct() {
        $this->_mysqliConnection = new mysqli('p:' . self::_dbHost, self::_dbUsername, self::_dbPassword, self::_dbName);
        if ($this->_mysqliConnection->connect_errno != 0) {
            // 写log报错
            echo "Failed to connect to MySQL: (" . $this->_mysqliConnection->connect_errno . ") " . $this->_mysqliConnection->connect_error;
        }

        $this->_mysqliConnection->set_charset('utf8');
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

            if ($authority == 'ALL') {
                $status = 1;
            }
            else if (strpos($requestAuthority, $authority) === 0) {
                $status = 1;
            }
        }

        $result->free();

        return $status;
    }

    public function idOfRegistTable($registTableDate, $registLocationType) : int {
        $queryStr = "SELECT id FROM " . self::_db_regist_table . " WHERE date = '" . $registTableDate . "' AND location = '" . $registLocationType . "'";
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

    public function insertRegistTable($registTableDate, $registLocationType) : int {
        $queryStr = "INSERT INTO " . self::_db_regist_table . " (date, location) VALUES ('" . $registTableDate . "', '" . $registLocationType . "')";
        $result = $this->_mysqliConnection->query($queryStr);
        $status = 1;
        if ($result == true) {
            $status = 0;
        }

        return $status;
    }

    public function tableRegist($tableID, $contactName, $contactSex, $contactPhone, $contactEmail, $contactLocation, $contactCompany, $contactGrade, $contactVocalAbility, $contactInstruments, $contactReadMusic) : int {
        $insertStr = "INSERT INTO " . self::_db_regist_info . " (regist_table_id, name, sex, location, company, grade, phone, email, vocal, instruments, readMusic) VALUES (". $tableID .", '" . $contactName ."', " . $contactSex . ", '" . $contactLocation . "', '" . $contactCompany . "', '" . $contactGrade . "', '" . $contactPhone . "', '" . $contactEmail . "', '" . $contactVocalAbility . "', '" . $contactInstruments . "', '" . $contactReadMusic . "');";
        $result = $this->_mysqliConnection->query($insertStr);
        $interviewerID = -1;
        if ($result == true) {
            $interviewerID = $this->_mysqliConnection->insert_id;
        }

        return $interviewerID;
    }

    public function registIdOfInterviewer ($theTableID, $contactName) : int {
        $selectStr = "SELECT id FROM " . self::_db_regist_info . " WHERE regist_table_id = " . $theTableID . " AND name = '" . $contactName ."';";
        $selectResult = $this->_mysqliConnection->query($selectStr);
        $interviewerID = -1;
        while ($row = $selectResult->fetch_assoc()) {
            $interviewerID = $row['id'];
        }

        $selectResult->free();

        return $interviewerID;
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
            $tableLocation = $row['location'];
            $tableList[] = array('id'=>$tableID, 'date'=>$tableDate, 'location'=>$tableLocation);
        }

        $selectResult->free();

        return $tableList;
    }

    public function interviewerListOfRegistTable($registTableID, $theInterviewerID) : array {
        $selectStr = "SELECT id, name FROM " . self::_db_regist_info . " WHERE regist_table_id = " . $registTableID . " AND id > " . $theInterviewerID . " ORDER BY id ASC";
        $selectResult = $this->_mysqliConnection->query($selectStr);
        $interviewerList = array();
        while ($row = $selectResult->fetch_assoc()) {
            $interviewerList[] = array('id'=>$row['id'], 'name'=>$row['name']);
        }

        $selectResult->free();

        return $interviewerList;
    }

    public function interviewerDetailInfo($interviewerID) : array {
        $selectStr = "SELECT * FROM " . self::_db_regist_info . " WHERE id = " . $interviewerID . " ;";
        $selectResult = $this->_mysqliConnection->query($selectStr);
        $interviewer = array();
        while($row = $selectResult->fetch_assoc()) {
            $interviewer['id'] = $row['id'];
            $interviewer['name'] = $row['name'];
            $interviewer['sex'] = $row['sex'];
            $interviewer['location'] = $row['location'];
            $interviewer['company'] = $row['company'];
            $interviewer['grade'] = $row['grade'];
            $interviewer['phone'] = $row['phone'];
            $interviewer['email'] = $row['email'];
            $interviewer['vocal'] = $row['vocal'];
            $interviewer['instruments'] = $row['instruments'];
            $interviewer['readMusic'] = $row['readMusic'];
        }

        $selectResult->free();

        return $interviewer;
    }

    public function __destruct() {
        $this->_mysqliConnection->close();
    }

}



?>