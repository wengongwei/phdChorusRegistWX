// pages/recruit/interviewerInfo/interviewerInfo.js

var config = require('../../../../../config');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    interviewerID: 0,
    contact: null
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      interviewerID: options.interviewerID
    })

    this.loadInterviewerInfo(options.interviewerID)
  },

  loadInterviewerInfo: function (interviewerID) {
    console.log('load info of interviewerID:', interviewerID)
    wx.showNavigationBarLoading()
    var that = this
    wx.request({
      url: config.serviceUrl.recruit_interviewerInfoUrl,
      method: 'POST',
      data: {
        interviewerID: interviewerID
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log("contact detail info:", res.data);
        if (res.data.status == 0) {
          var contact = res.data.contact
          that.setData({
            contact: contact
          })
        }
      },
      fail: function (err) {
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

  enroll: function (e) {
    wx.redirectTo({
      url: 'enroll/enroll?interviewerID=' + this.data.interviewerID + '&name=' + this.data.contact.name,
    })
  }
})