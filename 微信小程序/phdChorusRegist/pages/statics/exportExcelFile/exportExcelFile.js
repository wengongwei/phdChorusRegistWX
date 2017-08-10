// exportExcelFile.js

var config = require('../../../config');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    partItems: [
      { value: 'S', name: 'S' },
      { value: 'A', name: 'A' },
      { value: 'T', name: 'T', checked: 'true' },
      { value: 'B', name: 'B' }
    ],

    fromDate: '2016-08-06',
    toDate: '2016-08-06',
    part: 'T'
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
  
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
  
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {
  
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
  
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
  
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
  
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
  
  },

  bindFromDateChange: function (e) {
    this.setData({
      fromDate: e.detail.value
    })
  },

  bindToDateChange: function (e) {
    this.setData({
      toDate: e.detail.value
    })
  },

  bindPartChange: function (e) {
    this.setData({
      part: e.detail.value
    })
  },

  exportExcelFile: function (e) {
    console.log('export excel file from ', this.data.fromDate, ' to ', this.data.toDate, ' for part ', this.data.part)

    wx.showLoading({
      title: '正在导出...'
    })

    wx.request({
      url: config.serviceUrl.exportRegistExcelFileUrl,
      method: 'POST',
      data: {
        fromDate: this.data.fromDate,
        toDate: this.data.toDate,
        part: this.data.part
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log('exportRegistExcelFileUrl:', res.data)
        var fileName = res.data.fileName;
        var filePath = res.data.filePath;
        if (res.data.status == 0) {
          wx.redirectTo({
            url: 'downloadExcelFile/downloadExcelFile?filePath=' + filePath + '&fileName=' + fileName
          })
        }
        else if (res.data.status == 1) {
          wx.hideLoading()
          wx.showModal({
            title: '服务器错误',
            content: '请联系签到小程序管理员',
            confirmText: '好'
          })
        }
      },
      fail: function (res) {
        wx.hideLoading()
        wx.showModal({
          title: '无法连接服务器',
          content: '请检查网络连接',
          confirmText: '好'
        })

        console.log("createRegistTable fail:", res.data)
      }
    })
  }

})