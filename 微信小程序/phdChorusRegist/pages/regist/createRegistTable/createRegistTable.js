// createRegistTable.js

var config = require('../../../config');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    registTableTypeItems: [
      {value:'大排', name:'大排', checked:'true'},
      {value:'小排', name:'小排'},
      {value:'周日晚', name:'周日晚'},
      {value:'声乐课', name:'声乐课'}
    ],

    registLocationTypeItems: [
      {value:'中关村', name:'中关村', checked:'true'},
      {value:'雁栖湖', name:'雁栖湖'}
    ],

    registTableDate: '2017-08-06',
    registTableType: '大排',
    registLocationType: '中关村'
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    wx.showNavigationBarLoading()
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
    wx.hideNavigationBarLoading()
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

  bindDateChange: function (e) {
    this.setData({
      registTableDate: e.detail.value
    })
  },

  bindTypeChange: function (e) {
    var tmpArray = this.data.registTableTypeItems
    for (var i = 0, len = tmpArray.length; i < len; ++i) {
      tmpArray[i].checked = (tmpArray[i].checked == e.detail.value)
    }

    this.setData({
      registTableTypeItems: tmpArray,
      registTableType: e.detail.value
    })
  },

  bindLocationChange: function (e) {
    var tmpArray = this.data.registLocationTypeItems
    for (var i = 0, len = tmpArray.length; i < len; ++i) {
      tmpArray[i].checked = (tmpArray[i].checked == e.detail.value)
    }

    this.setData({
      registLocationTypeItems: tmpArray,
      registLocationType: e.detail.value
    })
  },

  createRegistTable: function () {
    console.log('create ', this.data.registTableDate, this.data.registTableType, this.data.registLocationType)

    // 显示正在创建
    wx.showLoading({
      title: '创建中',
    })

    // 服务器
    wx.request({
      url: config.serviceUrl.createRegistTableUrl,
      data: {
        registTableDate: this.data.registTableDate,
        registTableType: this.data.registTableType,
        registLocationType: this.data.registLocationType
      },
      method: 'POST',
      header: {
        'content-type': 'application/json'
      },
      success: function(res) {
        console.log('createRegistTable', res.data)
        var title = ''
        if (res.data.status == 0) {
          title = '创建成功'
        }
        else if (res.data.status == 1) {
          title = '签到表已存在，无需重复创建'
        }

        wx.showToast({
          title: title,
          mask: true,
          icon: 'success',
          duration: 2500
        })

        setTimeout(function () {
          wx.navigateBack({

          })
        }, 3000)
      },
      fail: function (res) {
        wx.hideLoading()

        wx.showModal({
          title: '无法连接服务器',
          content: '请检查网络连接',
          confirmText: '好',
          showCancel: false
        })

        console.log("createRegistTable fail:", res)
      }
    })
  }
})