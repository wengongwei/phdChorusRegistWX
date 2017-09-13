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
     * registTableStatus // 签到表状态 0-禁用 | 1-用于报名和确认面试 | 2-用于现场面试签到
     *
     * 返回值
     * int // 0-成功 | 1-失败
     *
     */
    public function insertRegistTable($registTableDate, $registLocationType, $registTableStatus) : int;

    /*
     * 接口功能
     * 签到表详细信息
     *
     * 参数
     * registTableID // 签到表ID
     *
     * 返回值
     * registTable // {id: 15, date: 2017-02-23, location: 中关村, status:0}
     *
     */
    public function registTableInfo($registTableID) : array;


    /*
     * 接口功能
     * 获取相应类型的签到表
     *
     * 参数
     * registTableType // 签到表状态 0-禁用 | 1-用于报名和确认面试 | 2-用于现场面试签到
     */
    public function validRegistTableOfType($registTableType) : array;

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
     * 设置签到表status
     *
     * 参数
     * registTableID // 签到表id
     * status // 将签到表设置为该status
     *
     * 返回值
     * status // 0-成功 | 1-失败
     */
    public function setRegistTableStatus($registTableID, $status) : int;

    /*
     * 接口功能
     * 查看签到表状态
     *
     * 参数
     * registTableID // 签到表id
     *
     * 返回值
     * status // 签到表状态
     */
    public function statusOfRegistTable($registTableID) : int;

    /*
     * 接口功能
     * 是否已报名，防止重复报名
     *
     * 参数
     * registTableID // 签到表id
     * contactName // 姓名
     *
     * 返回值
     * status // 0-没有重复 | 1-已报名
     */
    public function duplicateApply($registTableID, $contactName) : int;

    /*
     * 接口功能
     * 在报名表中添加一条信息
     *
     * 参数
     * registTableID // 签到表ID
     * contactID // 面试者信息ID
     *
     * 返回值
     * status // 0-成功 | 1-失败
     */
    public function applyToJoin($registTableID, $contactID) : int;

    /*
     * 接口功能
     * 添加一条面试者信息
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
     * 该条记录的id
     *
     */
    public function addContactInfo($contactName, $contactSex, $contactNation, $contactPhone, $contactEmail, $contactStudentId, $contactLocation, $contactCompany, $contactGrade, $contactVocalAbility, $contactInstruments ,$contactReadMusic ,$contactPianist, $contactInterest, $contactSkill, $contactExperience, $contactExpect) : int;

    /*
     * 接口功能
     * 确认参加面试
     *
     * 参数
     * registTableID // 签到表ID
     * contactName // 姓名
     *
     * 返回
     * status // 0-成功 | 1-失败
     */
    public function confirmInterview($registTableID, $contactName) : int;

    /*
     * 接口功能
     * 面试现场签到
     *
     * 参数
     * registTableID // 签到表ID
     * contactName // 姓名
     *
     * 返回
     * waiterID // -1-失败 | 其他-现场面试的ID     *
     */
    public function interviewRegist($registTableID, $contactName) : int;

    /*
     * 接口功能
     * 获取签到表中已现场签到面试者列表
     *
     * 参数
     * registTableID // 签到表ID
     * waiterID // 用以参考的面试者ID
     * interviewStatus // 面试状态位 1-已报名 | 2-已确认参加面试 | 3-已现场面试签到
     *
     * 返回值
     * interviewerList // 比theInterviewerID大的所有的面试者 [{'id': 12, 'name': 蓝胖, 'waiterID': 2, 'phone': 13317945775, 'email': mingjiameng@sina.com}, ...]
     */
    public function interviewerList($registTableID, $waiterID, $interviewStatus) : array;

    /*
     * 接口功能
     * 获取签到表中已报名或已确认参加面试的面试者列表
     *
     * 参数
     * registTableID // 签到表ID
     * waiterID // 用以参考的面试者ID
     * interviewStatus // 面试状态位 1-已报名 | 2-已确认参加面试 | 3-已现场面试签到
     *
     * 返回值
     * interviewerList // 比theInterviewerID大的所有的面试者 [{'id': 12, 'name': 蓝胖, 'waiterID': 2, 'phone': 13317945775, 'email': mingjiameng@sina.com}, ...]
     */
    public function applicantList($registTableID, $interviewStatus) : array;

    /*
     * 接口功能
     * 录取团员
     *
     * 参数
     * interviewerID // interview_info的id
     * part // 录取到声部
     *
     * 返回值
     * status // 0-成功 | 1-失败
     */
    public function enrollInterviewer($interviewerID, $part) : int;

    public function enrolledContactList($registTableID, $part) : array;
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
    const _db_interview_info = "interview_info";
    const _db_contact_info = "contact_info";
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

            if ($requestAuthority == 'ANY') {
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

    public function insertRegistTable($registTableDate, $registLocationType, $registTableStatus) : int {
        $queryStr = "INSERT INTO " . self::_db_regist_table . " (date, location, status) VALUES ('" . $registTableDate . "', '" . $registLocationType . "', " . $registTableStatus . ");";
        $result = $this->_mysqliConnection->query($queryStr);
        $status = 1;
        if ($result == true) {
            $status = 0;
        }

        return $status;
    }

    public function validRegistTableOfType($registTableType) : array {
        $selectStr = $selectStr = "SELECT * FROM " . self::_db_regist_table . " WHERE status >= " . $registTableType . ";";
        $selectResult = $this->_mysqliConnection->query($selectStr);
        $tableList = array();
        while ($row = $selectResult->fetch_assoc()) {
            $table = array();
            $table['id'] = $row['id'];
            $table['date'] = $row['date'];
            $table['location'] = $row['location'];
            $table['status'] = $row['status'];
            $tableList[] = $table;
        }

        return $tableList;
    }

    public function registTableInfo($registTableID) : array {
        $selectStr = "SELECT * FROM " . self::_db_regist_table . " WHERE id = " . $registTableID . ";";
        $selectResult = $this->_mysqliConnection->query($selectStr);
        $table = array();
        while ($row = $selectResult->fetch_assoc()) {
            $table['id'] = $row['id'];
            $table['date'] = $row['date'];
            $table['location'] = $row['location'];
            $table['status'] = $row['status'];
        }

        $selectResult->free();

        return $table;
    }

    public function duplicateApply($registTableID, $contactName) : int {
        $selectStr = "SELECT interview_info.id FROM " . self::_db_interview_info . " INNER JOIN contact_info ON contact_info.id = interview_info.contact_info_id WHERE interview_info.regist_table_id = " . $registTableID . " AND contact_info.name = '" . $contactName . "';";
        $selectResult = $this->_mysqliConnection->query($selectStr);
        $status = 0;
        if ($selectResult->num_rows > 0) {
            $status = 1;
        }

        $selectResult->free();
        return $status;
    }

    public function addContactInfo($contactName, $contactSex, $contactNation, $contactPhone, $contactEmail, $contactStudentId, $contactLocation, $contactCompany, $contactGrade, $contactVocalAbility, $contactInstruments ,$contactReadMusic ,$contactPianist, $contactInterest, $contactSkill, $contactExperience, $contactExpect) : int {
        $insertStr = "INSERT INTO " . self::_db_contact_info . " (name, sex, nation, studentId, location, company, grade, phone, email, vocal, instruments, readMusic, pianist, interest, skill, experience, expect) VALUES ('" . $contactName ."', " . $contactSex . ", '" . $contactNation . "', '" . $contactStudentId . "', '" . $contactLocation . "', '" . $contactCompany . "', '" . $contactGrade . "', '" . $contactPhone . "', '" . $contactEmail . "', '" . $contactVocalAbility . "', '" . $contactInstruments . "', '" . $contactReadMusic . "', " . $contactPianist . ", '" . $contactInterest . "', '" . $contactSkill . "', '" . $contactExperience . "', '" . $contactExpect . "');";
        $result = $this->_mysqliConnection->query($insertStr);
        $contactID = -1;
        if ($result == true) {
            $contactID = $this->_mysqliConnection->insert_id;
        }

        return $contactID;
    }

    public function applyToJoin($registTableID, $contactID) : int {
        $insertStr = "INSERT INTO " . self::_db_interview_info . "(contact_info_id, regist_table_id, status) VALUE (" . $contactID . ", " . $registTableID. ", 1);";
        $result = $this->_mysqliConnection->query($insertStr);
        $status = 1;
        if ($result == true) {
            $status = 0;
        }

        return $status;
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

    public function statusOfRegistTable($registTableID) : int {
        $selectStr = "SELECT status FROM" . self::_db_regist_table . " WHERE id = " . $registTableID . ";";
        $selectResult = $this->_mysqliConnection->query($selectStr);
        $status = 0;
        while ($row = $selectResult->fetch_assoc()) {
            $status = $row['status'];
        }

        return $status;
    }

    public function setRegistTableStatus($registTableID, $status) : int {
        $updateStr = "UPDATE " . self::_db_regist_table . " SET status = " . $status . " WHERE id = " . $registTableID . ";";
        $updateResult = $this->_mysqliConnection->query($updateStr);
        $status = 1;
        if ($updateResult == true) {
            $status = 0;
        }

        return $status;
    }

    public function confirmInterview($registTableID, $contactName) : int {
        $updateStr = "UPDATE " . self::_db_interview_info . " INNER JOIN contact_info ON contact_info.id = interview_info.contact_info_id SET interview_info.status = 2 WHERE interview_info.regist_table_id = " . $registTableID . " AND contact_info.name = '" . $contactName ."';";
        $updateResult = $this->_mysqliConnection->query($updateStr);
        $status = 1;
        if ($updateResult == true && $this->_mysqliConnection->affected_rows > 0) {
            $status = 0;
        }

        return $status;
    }

    public function interviewRegist($registTableID, $contactName) : int {
        // 查询该签到表已经有多少人签到了
        $countStr = "SELECT COUNT(*) FROM " . self::_db_interview_info . " WHERE regist_table_id = " . $registTableID . " AND status = 3;";
        $countResult = $this->_mysqliConnection->query($countStr);
        $countArr = $countResult->fetch_array();
        $waiterID = $countArr[0] + 1;

        $updateStr = "UPDATE " . self::_db_interview_info . " INNER JOIN contact_info ON contact_info.id = interview_info.contact_info_id SET interview_info.status = 3, interview_info.waiterID = " . $waiterID . " WHERE interview_info.regist_table_id = " . $registTableID . " AND contact_info.name = '" . $contactName ."';";
        $updateResult = $this->_mysqliConnection->query($updateStr);
        if ($updateResult == true && $this->_mysqliConnection->affected_rows > 0) {
            return $waiterID;
        }

        return -1;
    }

    public function applicantList($registTableID, $interviewStatus) : array {
        $selectStr = "SELECT interview_info.id, interview_info.waiterID, contact_info.name as contactName, contact_info.phone as contactPhone, contact_info.email as contactEmail FROM " . self::_db_interview_info . " INNER JOIN contact_info ON contact_info.id = interview_info.contact_info_id WHERE interview_info.regist_table_id = " . $registTableID . " AND interview_info.status >= " . $interviewStatus . " ORDER BY interview_info.id ASC;";
        $selectResult = $this->_mysqliConnection->query($selectStr);
        $applicantList = array();
        while ($row = $selectResult->fetch_assoc()) {
            $applicantList[] = array('id'=>$row['id'], 'name'=>$row['contactName'], 'waiterID'=>$row['waiterID'], 'phone'=>$row['contactPhone'], 'email'=>$row['contactEmail']);
        }

        $selectResult->free();

        return $applicantList;
    }

    public function interviewerList($registTableID, $theWaiterID, $interviewStatus) : array {
        $selectStr = "SELECT interview_info.id, interview_info.waiterID, contact_info.name as contactName, contact_info.phone as contactPhone, contact_info.email as contactEmail FROM " . self::_db_interview_info . " INNER JOIN contact_info ON contact_info.id = interview_info.contact_info_id WHERE interview_info.regist_table_id = " . $registTableID . " AND interview_info.status >= " . $interviewStatus . " AND interview_info.waiterID > " . $theWaiterID . " ORDER BY interview_info.waiterID ASC;";
        $selectResult = $this->_mysqliConnection->query($selectStr);
        $interviewerList = array();
        while ($row = $selectResult->fetch_assoc()) {
            $interviewerList[] = array('id'=>$row['id'], 'name'=>$row['contactName'], 'waiterID'=>$row['waiterID'], 'phone'=>$row['contactPhone'], 'email'=>$row['contactEmail']);
        }

        $selectResult->free();

        return $interviewerList;
    }

    public function enrolledContactList($registTableID, $part) : array {
        $selectStr = "SELECT interview_info.id, contact_info.name as contactName, contact_info.phone as contactPhone, contact_info.email as contactEmail FROM " . self::_db_interview_info . " INNER JOIN contact_info ON contact_info.id = interview_info.contact_info_id WHERE interview_info.regist_table_id = " . $registTableID . " AND interview_info.pass = 1 AND interview_info.part = '" . $part . "' ORDER BY interview_info.id ASC;";
        $selectResult = $this->_mysqliConnection->query($selectStr);
        $interviewerList = array();
        while ($row = $selectResult->fetch_assoc()) {
            $interviewerList[] = array('id'=>$row['id'], 'name'=>$row['contactName'], 'phone'=>$row['contactPhone'], 'email'=>$row['contactEmail']);
        }

        $selectResult->free();

        return $interviewerList;
    }

    public function interviewerDetailInfo($interviewerID) : array {
        // 找到面试者的contact_info_id
        $selectStr = "SELECT contact_info_id FROM " . self::_db_interview_info . " WHERE id = " . $interviewerID . " ;";
        $selectResult = $this->_mysqliConnection->query($selectStr);
        $contactID = -1;
        while ($row = $selectResult->fetch_assoc()) {
            $contactID = $row['contact_info_id'];
        }
        $selectResult->free();

        // 查询信息
        $selectStr = "SELECT * FROM " . self::_db_contact_info . " WHERE id = " . $contactID . " ;";
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

    public function enrollInterviewer($interviewerID, $part) : int {
        $updateStr = "UPDATE " . self::_db_interview_info . " SET pass = 1, part = '" . $part . "' WHERE id = " . $interviewerID .";";
        $updateResult = $this->_mysqliConnection->query($updateStr);
        $status = 1;
        if ($updateResult == true && $this->_mysqliConnection->affected_rows > 0) {
            $status = 0;
        }

        return $status;
    }

    public function __destruct() {
        $this->_mysqliConnection->close();
    }
}



?>