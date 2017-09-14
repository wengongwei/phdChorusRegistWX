// exportExcelFile.js

var config = require('../../../../config');
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

  query: function (e) {
    wx.navigateTo({
      url: '../enrolledContactList/enrolledContactList?type=2&fromDate=' + this.data.fromDate + '&toDate=' + this.data.toDate
    })
  }

})