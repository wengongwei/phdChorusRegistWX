// 小程序配置

// 服务器IP
var httpsHost = "https://www.liangzhipeng.cn/phdChorusRegist/";

var config = {

  serviceUrl: {

    // 登录地址，用于建立会话
    loginUrl: (httpsHost + 'phdLogin.php'),

    // SATB12
    contactInfoInSATB12Url: (httpsHost + 'phdContactInfoInSATB12.php'),

    // SATB
    contactInfoInSATBUrl: (httpsHost + 'phdContactInfoInSATB.php'),

    // 创建签到表
    createRegistTableUrl: (httpsHost +'phdCreateRegistTable.php'),
    //createRegistTableUrl: (httpsHost + 'test.php'),

    // 签到
    tableRegistUrl: (httpsHost + 'phdTableRegist.php'),

    // 添加新团员
    addContactUrl: (httpsHost + 'phdAddContact.php'),
  
    // 导出签到Excel文件
    exportRegistExcelFileUrl: (httpsHost + 'phdExportRegistExcelFile.php'),

    // 每日出勤
    registTableOnDateUrl: (httpsHost + 'phdRegistTableOnDate.php')
  }

};

module.exports = config;