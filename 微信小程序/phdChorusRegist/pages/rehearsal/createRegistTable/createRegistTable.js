// createRegistTable.js

var config = require('../../../config');
var appInstance = getApp();

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

  bindDateChange: function (e) {
    this.setData({
      registTableDate: e.detail.value
    })
  },

  bindTypeChange: function (e) {
    var newType = e.detail.value
    var tmpArray = this.data.registTableTypeItems
    for (var i = 0, len = tmpArray.length; i < len; ++i) {
      tmpArray[i].checked = (tmpArray[i].value == newType)
    }

    this.setData({
      registTableTypeItems: tmpArray,
      registTableType: newType
    })
  },

  bindLocationChange: function (e) {
    var newLocation = e.detail.value;
    var tmpArray = this.data.registLocationTypeItems
    for (var i = 0, len = tmpArray.length; i < len; ++i) {
      tmpArray[i].checked = (tmpArray[i].value == newLocation)
    }

    this.setData({
      registLocationTypeItems: tmpArray,
      registLocationType: newLocation
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
        registLocationType: this.data.registLocationType,
        wxNickname: appInstance.globalData.userInfo.nickName
      },
      method: 'POST',
      header: {
        'content-type': 'application/json'
      },
      success: function(res) {
        console.log('createRegistTable', res.data)
        
        if (res.data.status == 0) {
          wx.showToast({
            title: '创建成功',
            mask: true,
            icon: 'success',
            duration: 2500
          })
        }
        else {
          var warningContent = ''
          if (res.data.status == 1) {
            warningContent = '该签到表已存在'
          }
          else if (res.data.status == 5) {
            warningContent = '您无权进行此项操作，请联系声部长'
          }

          wx.hideLoading()
          wx.showModal({
            title: '创建失败',
            content: warningContent,
            confirmText: '好',
            showCancel: false
          })
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

        console.log("createRegistTable fail:", res)
      }
    })
  }
})