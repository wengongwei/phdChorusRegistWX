// pages/recruit/enrolledContact/enrolledContactIndex.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
  
  },

  queryByRegistTable: function () {
    wx.navigateTo({
      url: 'registTableList/registTableList'
    })
  },

  queryByDate: function () {
    wx.navigateTo({
      url: 'selectDate/selectDate'
    })
  }
})