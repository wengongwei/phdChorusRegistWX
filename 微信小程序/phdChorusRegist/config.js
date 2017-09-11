// 小程序配置

// 服务器IP
var httpsHost = "https://www.liangzhipeng.cn/phdChorusRegist/";

var config = {

  serviceUrl: {

    // 登录地址，用于建立会话
    //loginUrl: (httpsHost + 'phdLogin.php'),
    httpsHost ,
    // SATB12 for regist
    phdContactInfoForRegistInSATB12Url: (httpsHost + 'phdContactInfoForRegistInSATB12.php'),

    // SATB
    contactInfoInSATB12Url: (httpsHost + 'phdContactInfoInSATB12.php'),

    // 创建签到表
    createRegistTableUrl: (httpsHost +'phdCreateRegistTable.php'),
    //createRegistTableUrl: (httpsHost + 'test.php'),

    // 签到
    tableRegistUrl: (httpsHost + 'phdTableRegist.php'),

    // 添加新团员
    addContactUrl: (httpsHost + 'phdAddContact.php'),
  
    // 导出签到Excel文件
    exportRegistExcelFileUrl: (httpsHost + 'phdExportRegistExcelFile.php'),

    // 签到表详细信息
    registInfoOfRegistTableUrl: (httpsHost + 'phdRegistInfoOfRegistTable.php'),

    // 签到表列表
    registTableListUrl: (httpsHost + 'phdRegistTableList.php'),
  
    // 团员详细信息
    detailContactInfoUrl: (httpsHost + 'phdDetailContactInfo.php'),
  
    // 修改团员信息
    updateContactInfoUrl: (httpsHost + 'phdUpdateContactInfo.php'),

    // 删除团员
    deleteContactInfoUrl: (httpsHost + 'phdDeleteContact.php'),

    // 招新-创建签到表
    recruit_createRegistTableUrl: (httpsHost + 'recruit/phdRecruitCreateRegistTable.php'),

    // 招新-查看签到表列表
    recruit_registTableListUrl: (httpsHost + 'recruit/phdRecruitRegistTableList.php'),

    // 招新-查看签到表详细信息
    recruit_registTableInfoUrl: (httpsHost + 'recruit/phdRecruitRegistTableInfo.php'),

    // 招新-报名-获取签到表
    recruit_validRegistTableForApply: (httpsHost + 'recruit/phdValidRegistTableForApply.php'),

    // 招新-现场面试签到-获取签到表
    recruit_validRegistTableForRegist: (httpsHost + 'recruit/phdValidRegistTableForRegist.php'),

    // 招新-报名
    recruit_apply: (httpsHost + 'recruit/phdRecruitApply.php'),

    // 招新-确认参加面试
    recruit_confirmInterview: (httpsHost + 'recruit/phdRecruitConfirmInterview.php'),

    // 招新-现场面试签到
    recruit_interviewRegist: (httpsHost + 'recruit/phdRecruitInterviewRegist.php'),

    // 招新-签到表-面试者列表
    recruit_interviewerList: (httpsHost + 'recruit/phdRecruitInterviewerList.php'),

    // 招新-查看签到详细信息
    recruit_interviewerInfoUrl: (httpsHost + 'recruit/phdRecruitInterviewerInfo.php'),

    recruit_setRegistTableStatusUrl: (httpsHost + 'recruit/phdRecruitSetRegistTableStatus.php')
  }

};

module.exports = config;