// pages/recruit/recruitIndex/recruitIndex.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
  
  },

  regist: function (e) {
    wx.navigateTo({
      url: '../regist/regist',
    })
  },

  createRegistTable: function(e) {
    wx.navigateTo({
      url: '../createRegistTable/createRegistTable'
    })
  },

  staticByTable: function (e) {
    wx.navigateTo({
      url: '../registTableList/registTableList'
    })
  }
})