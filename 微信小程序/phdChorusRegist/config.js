// 小程序配置

// 服务器IP
var httpsHostRegist = "https://www.liangzhipeng.cn/test_phdChorusRegist/";

var httpsHostRecruit = "https://www.liangzhipeng.cn/test_phdChorusRecruit/";

var config = {

  serviceUrl: {

    // 签到
    httpsHostRegist ,

    //招新
    httpsHostRecruit,

    // SATB12 for regist
    phdContactInfoForRegistInSATB12Url: (httpsHostRegist + 'phdContactInfoForRegistInSATB12.php'),

    // SATB
    contactInfoInSATB12Url: (httpsHostRegist + 'phdContactInfoInSATB12.php'),

    // 创建签到表
    createRegistTableUrl: (httpsHostRegist +'phdCreateRegistTable.php'),
    //createRegistTableUrl: (httpsHost + 'test.php'),

    // 签到
    tableRegistUrl: (httpsHostRegist + 'phdTableRegist.php'),

    // 添加新团员
    addContactUrl: (httpsHostRegist + 'phdAddContact.php'),
  
    // 导出签到Excel文件
    exportRegistExcelFileUrl: (httpsHostRegist + 'phdExportRegistExcelFile.php'),

    // 签到表详细信息
    registInfoOfRegistTableUrl: (httpsHostRegist + 'phdRegistInfoOfRegistTable.php'),

    // 签到表列表
    registTableListUrl: (httpsHostRegist + 'phdRegistTableList.php'),
  
    // 团员详细信息
    detailContactInfoUrl: (httpsHostRegist + 'phdDetailContactInfo.php'),
  
    // 修改团员信息
    updateContactInfoUrl: (httpsHostRegist + 'phdUpdateContactInfo.php'),

    // 删除团员
    deleteContactInfoUrl: (httpsHostRegist + 'phdDeleteContact.php'),

    // 分配权限
    allocAuthorityUrl: (httpsHostRegist + 'phdAllocAuthority.php'),

    // 招新-创建签到表
    recruit_createRegistTableUrl: (httpsHostRecruit + 'phdRecruitCreateRegistTable.php'),

    // 招新-查看签到表列表
    recruit_registTableListUrl: (httpsHostRecruit + 'phdRecruitRegistTableList.php'),

    // 招新-查看签到表详细信息
    recruit_registTableInfoUrl: (httpsHostRecruit + 'phdRecruitRegistTableInfo.php'),

    // 招新-报名-获取签到表
    recruit_validRegistTableForApply: (httpsHostRecruit + 'phdValidRegistTableForApply.php'),

    // 招新-现场面试签到-获取签到表
    recruit_validRegistTableForRegist: (httpsHostRecruit + 'phdValidRegistTableForRegist.php'),

    // 招新-报名
    recruit_apply: (httpsHostRecruit + 'phdRecruitApply.php'),

    // 招新-确认参加面试
    recruit_confirmInterview: (httpsHostRecruit + 'phdRecruitConfirmInterview.php'),

    // 招新-现场面试签到
    recruit_interviewRegist: (httpsHostRecruit + 'phdRecruitInterviewRegist.php'),

    // 招新-签到表-面试者列表
    recruit_interviewerList: (httpsHostRecruit + 'phdRecruitInterviewerList.php'),

    // 招新-查看签到详细信息
    recruit_interviewerInfoUrl: (httpsHostRecruit + 'phdRecruitInterviewerInfo.php'),

    // 招新-设置签到表状态
    recruit_setRegistTableStatusUrl: (httpsHostRecruit + 'phdRecruitSetRegistTableStatus.php'),

    recruit_enrollContactUrl: (httpsHostRecruit + 'phdRecruitEnrollContact.php'),

    recruit_enrolledContactListUrl: (httpsHostRecruit + 'phdRecruitEnrolledContactList.php')
  }

};

module.exports = config;