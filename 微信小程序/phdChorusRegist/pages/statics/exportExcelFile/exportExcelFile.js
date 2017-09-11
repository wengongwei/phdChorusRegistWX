// exportExcelFile.js

var config = require('../../../config');
var appInstance = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    fromDate: '2017-08-06',
    toDate: '2017-08-06',
  },

  bindFromDateChange: function (e) {
    this.setData({
      fromDate: e.detail.value
    })
  },

  bindToDateChange: function (e) {
    this.setData({
      toDate: e.detail.value
    })
  },

  bindPartChange: function (e) {
    this.setData({
      part: e.detail.value
    })
  },

  exportExcelFile: function (e) {
    console.log('export excel file from ', this.data.fromDate, ' to ', this.data.toDate)

    wx.showLoading({
      title: '正在导出...'
    })

    wx.request({
      url: config.serviceUrl.exportRegistExcelFileUrl,
      method: 'POST',
      data: {
        fromDate: this.data.fromDate,
        toDate: this.data.toDate,
        wxNickname: appInstance.globalData.userInfo.nickName
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log('exportRegistExcelFileUrl:', res.data)
        var status = res.data.status
        if (status == 0) {
          var fileName = res.data.fileName
          var filePath = res.data.filePath
          filePath = config.serviceUrl.httpsHost + filePath
          wx.redirectTo({
            url: 'downloadExcelFile/downloadExcelFile?filePath=' + filePath + '&fileName=' + fileName
          })
        }
        else {
          var title = ''
          var content = ''
          if (status == 1) {
            title = '服务器错误'
            content = '请联系签到小程序管理员'
          }
          else if (status == 5) {
            title = '没有权限'
            content = '仅团委会可查看数据'
          }

          wx.showModal({
            title: title,
            content: content,
            confirmText: '好',
            showCancel: false
          })
        }
      },
      fail: function (res) {
        wx.showModal({
          title: '无法连接服务器',
          content: '请检查网络连接',
          confirmText: '好'
        })

        console.log("createRegistTable fail:", res.data)
      },
      complete: function () {
        wx.hideLoading()
      }
    })
  }

})