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
      url: config.serviceUrl.recruit_validRegistTableForRegist,
      data: {

      },
      method: 'POST',
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log('valid regist table for apply:', res.data)
        var status = res.data.status
        if (status == 0) {
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

  interviewRegist: function (e) {
    wx.showLoading({
      title: '确认中...',
      mask: true
    })

    // 服务器
    wx.request({
      url: config.serviceUrl.recruit_interviewRegist,
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
            title: '签到成功',
            content: '您是' + res.data.interviewID + '号面试者，请耐心等待叫号，等候过程中请保持安静',
            confirmText: '好',
            showCancel: false
          })
        }
        else {
          var content = ''
          if (status == 1) {
            content = '如已签到，请勿重复签到。否则请检查您选择的面试时间、姓名是否与之前确认面试时所填写的相同'
          }
          else if (status == 5) {
            content = '请完整填写信息'
          }
          wx.showModal({
            title: '签到失败',
            content: content,
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