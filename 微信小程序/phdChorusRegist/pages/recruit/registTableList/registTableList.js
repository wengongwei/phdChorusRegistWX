// pages/recruit/registTableList/registTableList.js

var config = require('../../../config');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    lastRegistTableID: 99999999, // 大整数
    registTableList: []
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.loadRegistTableList();
  },

  loadRegistTableList: function () {
    wx.showNavigationBarLoading();
    var lastTableID = this.data.lastRegistTableID
    console.log('lastRegistTableID:', lastTableID)
    var that = this
    wx.request({
      url: config.serviceUrl.recruit_registTableListUrl,
      data: {
        theTableID: lastTableID,
        isNewer: '0'
      },
      method: 'POST',
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log('regist table:', res.data)
        if (res.data.status == 0) {
          var newTableList = res.data.registTableList
          newTableList.sort(function (obj1, obj2) {
            return obj2.id - obj1.id
          })

          console.log('sorted table list:', newTableList);

          var tableList = that.data.registTableList
          for (var i = 0; i < newTableList.length; i++) {
            tableList.push(newTableList[i]);
          }

          var lastTable = tableList[tableList.length - 1]
          console.log('new last table id:', lastTable.id)
          that.setData({
            registTableList: tableList,
            lastRegistTableID: lastTable.id
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
    this.loadRegistTableList()
  },

  queryRegistTable: function (e) {
    var registTableID = e.currentTarget.id
    wx.navigateTo({
      url: '../registTableInfo/registTableInfo?registTableID=' + registTableID,
    })
  }
})