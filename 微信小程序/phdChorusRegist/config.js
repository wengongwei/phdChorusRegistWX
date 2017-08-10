// 小程序配置

// 服务器IP
var httpsHost = "https://www.liangzhipeng.cn/phdChorusRegist/";

var config = {

  serviceUrl: {

    // 登录地址，用于建立会话
    loginUrl: (httpsHost + 'wxLogin.php'),

    // SATB12
    contactInfoInSATB12Url: (httpsHost + 'wxContactInfoInSATB12.php'),

    // SATB
    contactInfoInSATBUrl: (httpsHost + 'wxContactInfoInSATB.php'),

    // 创建签到表
    createRegistTableUrl: (httpsHost +'wxCreateRegistTable.php'),

    // 签到
    tableRegistUrl: (httpsHost + 'wxTableRegist.php'),

    // 添加新团员
    addContactUrl: (httpsHost + 'wxAddContact.php'),
  
    // 导出签到Excel文件
    exportRegistExcelFileUrl: (httpsHost + 'wxExportRegistExcelFile.php'),

    // 每日出勤
    registTableOnDateUrl: (httpsHost + 'registTableOnDate.php')
  }

};

module.exports = config;