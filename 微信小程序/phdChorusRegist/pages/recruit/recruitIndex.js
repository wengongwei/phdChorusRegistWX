// pages/recruit/recruitIndex/recruitIndex.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
  
  },

  apply: function (e) {
    wx.navigateTo({
      url: 'apply/apply'
    })
  },

  confirmInterview: function (e) {
    wx.navigateTo({
      url: 'confirmInterview/confirmInterview',
    })
  },

  interviewRegist: function (e) {
    wx.navigateTo({
      url: 'interviewRegist/interviewRegist',
    })
  },

  setRegistTable: function(e) {
    wx.navigateTo({
      url: 'setRegistTable/registTableList'
    })
  },

  interviewInfo: function (e) {
    wx.navigateTo({
      url: 'interviewInfo/registTableList'
    })
  },

  enrolledContact: function (e) {
    wx.navigateTo({
      url: 'enrolledContact/enrolledContactIndex',
    })
  }
})