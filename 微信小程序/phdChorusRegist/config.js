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
    updateContactInfoUrl: (httpsHost + 'updateContactInfo.php')
  }

};

module.exports = config;