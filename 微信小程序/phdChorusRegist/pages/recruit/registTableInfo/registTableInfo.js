// pages/recruit/registTableInfo/registTableInfo.js

var config = require('../../../config');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    registTableID: -1,
    lastInterviewerID: -1,
    interviewerList: []
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      registTableID: options.registTableID
    })
    this.loadInterviewerList();
  },

  loadInterviewerList: function () {
    console.log('request inteviewer list with tableID&theInterviewerID', this.data.registTableID, this.data.lastInterviewerID)
    wx.showNavigationBarLoading();
    var that = this
    wx.request({
      url: config.serviceUrl.recruit_registTableInfoUrl,
      data: {
        registTableID: this.data.registTableID,
        theInterviewerID: this.data.lastInterviewerID
      },
      method: 'POST',
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log('recruit - interviewer list:', res.data)
        if (res.data.status == 0) {
          var newInterviewerList = res.data.interviewerList

          // 按id升序排序
          newInterviewerList.sort(function (obj1, obj2) {
            return obj1.id - obj2.id
          })

          var interviewerList = that.data.interviewerList
          for (var i = 0; i < newInterviewerList.length; i++) {
            interviewerList.push(newInterviewerList[i]);
          }

          var lastInterviewer = interviewerList[interviewerList.length - 1]
          that.setData({
            interviewerList: interviewerList,
            lastInterviewerID: lastInterviewer.id
          })

          wx.hideNavigationBarLoading();
        }
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

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
    this.loadInterviewerList()
  },

  queryRegistTable: function (e) {
    var interviewerID = e.currentTarget.id
    wx.navigateTo({
      url: '../interviewerInfo/interviewerInfo?interviewerID=' + interviewerID,
    })
  }
})