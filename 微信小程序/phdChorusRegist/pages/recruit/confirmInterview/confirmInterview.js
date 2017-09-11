// pages/recruit/confirmInterview/confirmInterview.js

var config = require('../../../config');
var appInstance = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    interviewTimeTypeItems: [],

    contactName: '',
    interviewTime: 0
  },

  onLoad: function (options) {
    this.loadValidRegistTable();
  },

  loadValidRegistTable: function () {
    wx.showLoading({
      title: '正在加载报名报...',
    })

    var that = this
    wx.request({
      url: config.serviceUrl.recruit_validRegistTableForApply,
      data: {

      },
      method: 'POST',
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log('valid regist table for apply:', res.data)
        if (res.data.status == 0) {
          that.setData({
            interviewTimeTypeItems: res.data.tableList
          })
        }
        wx.hideLoading()
      },
      fail: function (res) {
        wx.showModal({
          title: '无法连接服务器',
          content: '请检查网络连接',
          confirmText: '好',
          showCancel: false
        })
      }
    })
  },

  bindNameChange: function (e) {
    this.setData({
      contactName: e.detail.value
    })
  },

  bindInterviewTimeChange: function (e) {
    var newTime = e.detail.value
    var tmpArray = this.data.interviewTimeTypeItems
    for (var i = 0, len = tmpArray.length; i < len; ++i) {
      tmpArray[i].checked = (tmpArray[i].value == newTime)
    }

    this.setData({
      interviewTimeTypeItems: tmpArray,
      interviewTime: newTime
    })
  },

  confirmInterview: function (e) {
    wx.showLoading({
      title: '确认中...'
    })

    // 服务器
    wx.request({
      url: config.serviceUrl.recruit_confirmInterview,
      method: 'POST',
      data: {
        registTableID: this.data.interviewTime,
        contactName: this.data.contactName
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log('confirm interview...', res.data)
        var status = res.data.status
        if (status == 0) {
          wx.showModal({
            title: '成功确认',
            content: '请按通知的时间，准时到达面试地点',
            confirmText: '好',
            showCancel: false
          })
        }
        else if (status == 1) {
          wx.showModal({
            title: '确认失败',
            content: '如已确认，请勿重复确认。否则请检查您选择的面试时间、姓名是否与之前报名表中所填写的相同',
            confirmText: '好',
            showCancel: false
          })
        }
        wx.hideLoading()
      },
      fail: function (res) {
        wx.hideLoading()
        wx.showModal({
          title: '无法连接服务器',
          content: '请检查网络连接',
          confirmText: '好',
          showCancel: false
        })
      }
    })
  }
})