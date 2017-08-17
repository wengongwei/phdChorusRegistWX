// detailContactInfo.js

var config = require('../../../config');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    contactID: -1,
    contact: {id: -1, part: 'T2', name: '蓝胖子', location: '中关村'}
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      contactID: options.contactID
    })

    this.loadContactInfo(options.contactID)
  },

  loadContactInfo: function (contactID) {
    console.log('load info of contactID:', contactID);
    wx.showNavigationBarLoading()
    var that = this
    wx.request({
      url: config.serviceUrl.detailContactInfoUrl,
      method: 'POST',
      data: {
        contactID: contactID
      },
      header: {
        'content-type': 'application/json'
      },
      success: function(res) {
        console.log("contact detail info:", res.data);
        if (res.data.status == 0) {
          that.setData({
            contact: res.data.contact
          })
        }
      },
      fail: function(err) {
        wx.showModal({
          title: '无法连接服务器',
          content: '请检查网络连接',
          confirmText: '好',
          showCancel: false
        })
      },
      complete: function () {
        wx.hideNavigationBarLoading()
      }
    })
  },

  alterInfo: function (e) {
    var contactID = this.data.contactID
    wx.redirectTo({
      url: '../alterContactInfo/alterContactInfo?contactID={{contactID}}'
    })
  }
})