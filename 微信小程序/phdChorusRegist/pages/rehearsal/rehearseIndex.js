// pages/rehearsal/rehearsalIndex.js
Page({

  createRegistTable: function () {
    wx.navigateTo({
      url: 'createRegistTable/createRegistTable'
    })
  },

  regist: function () {
    wx.navigateTo({
      url: 'regist/regist'
    })
  },

  contactInfo: function () {
    wx.navigateTo({
      url: 'contact/contactIndex'
    })
  },

  dailyStatics: function () {
    wx.navigateTo({
      url: 'dailyStatics/staticsBySelectRegistTable'
    })
  },

  exportExcelFile: function () {
    wx.navigateTo({
      url: 'exportExcelFile/exportExcelFile'
    })
  },

  allocAuthority:function () {
    wx.navigateTo({
      url: 'authority/authority',
    })

  }
  
})