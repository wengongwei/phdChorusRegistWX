var config = require('../../../../config');
var appInstance = getApp();


Page({

  /**
   * 页面的初始数据
   */
  data: {
    tableID: -1,
    tableDate: '',
    tableLocation: '',
    tableStatusDescription: ''
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var tableID = options.registTableID
    console.log('table info:', tableID)
    this.setData({
      tableID: tableID
    })

    this.loadTableInfo(tableID)
  },

  loadTableInfo: function (registTableID) {
    wx.showNavigationBarLoading();
    var that = this
    wx.request({
      url: config.serviceUrl.recruit_registTableInfoUrl,
      data: {
        registTableID: registTableID,
        wxNickname: appInstance.globalData.userInfo.nickName
      },
      method: 'POST',
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log('regist table:', res.data)
        if (res.data.status == 0) {
          var table = res.data.registTable
          that.setData({
            tableDate: table.date,
            tableLocation: table.location,
            tableStatusDescription: table.statusDescription
          })
          wx.hideNavigationBarLoading();
        }
        else {
          if (res.data.status == 5) {
            wx.showModal({
              title: '您无权查看数据',
              content: '仅有招新小组能查看签到数据',
              confirmText: '好',
              showCancel: false
            })
          }
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

  useForApply: function (e) {
    this.setRegistTableStatus(1)
  },

  useForInterview: function (e) {
    this.setRegistTableStatus(2)
  },

  forbiddenUse: function (e) {
    this.setRegistTableStatus(0)
  },

  setRegistTableStatus: function (status) {
    wx.showLoading({
      title: '正在设置...'
    })
    var that = this
    wx.request({
      url: config.serviceUrl.recruit_setRegistTableStatusUrl,
      data: {
        registTableID: this.data.tableID,
        status: status,
        wxNickname: appInstance.globalData.userInfo.nickName
      },
      method: 'POST',
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log('set regist table:', res.data)
        if (res.data.status == 0) {
          wx.showToast({
            title: '设置成功',
            mask: true,
            icon: 'success',
            duration: 2500
          })
        }
        else {
          if (res.data.status == 5) {
            wx.hideLoading()
            wx.showModal({
              title: '您无权查看数据',
              content: '仅有招新小组能查看签到数据',
              confirmText: '好',
              showCancel: false
            })
          }
        }
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