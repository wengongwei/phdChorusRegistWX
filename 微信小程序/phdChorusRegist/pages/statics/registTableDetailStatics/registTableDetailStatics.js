
var config = require('../../../config');

Page({

  data: {
    currentTab: 0,
    registTableID: 0,
    attendInfo: []

  },

  onLoad: function(options) {
    this.data.registTableID = options.registTableID
    this.loadRegistInfo()
  },

  loadRegistInfo: function() {
    var locationType = this.data.currentTab
    var tableID = this.data.registTableID
    var that = this
    wx.request({
      url: config.serviceUrl.registInfoOfRegistTableUrl,
      method: 'POST',
      data: {
        registTableID: tableID,
        contactLocationType: locationType
      },
      success: function (res) {
        console.log('regist table detail info:', res.data);
        if (res.data.status == 0) {
          that.setData({
            attendInfo: res.data.registInfo
          })
        }
      },
      fail: function (err) {

      }
    })
  },

  // 点击tab切换
  swichNav: function (e) {

    var that = this;

    if (this.data.currentTab === e.target.dataset.current) {
      return;
    } else {
      this.setData({
        currentTab: e.target.dataset.current
      })
    }

    this.loadRegistInfo()
  }
})  