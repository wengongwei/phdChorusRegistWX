// contactIndex.js

var config = require('../../../config');
var appInstance = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    contactInfoList: [],

    partList: [
      { id: 'S1', name: '女高音S1' },
      { id: 'S2', name: '女高音S2' },
      { id: 'A1', name: '女中音A1' },
      { id: 'A2', name: '女中音A2' },
      { id: 'T1', name: '男高音T1' },
      { id: 'T2', name: '男高音T2' },
      { id: 'B1', name: '男低音B1' },
      { id: 'B2', name: '男低音B2' }
    ],

  },

  /**
   * 生命周期函数--监听页面加载
   * 从options中可以获取上个页面穿过来的参数（options.title）
   */
  onLoad: function (options) {
    this.loadContactInfo()
  },

  onPullDownRefresh: function () {
    this.loadContactInfo()
  },

  kindToggle: function (e) {
    var id = e.currentTarget.id
    console.log('target id:', id)
    var list = this.data.contactInfoList
    for (var i = 0, len = list.length; i < len; ++i) {
      if (list[i].id == id) {
        list[i].open = !list[i].open
      }
      else {
        list[i].open = false;
      }
    }

    this.setData({
      contactInfoList: list
    })
  },

  loadContactInfo: function () {
    console.log('contact info loading')
    wx.showNavigationBarLoading()

    var that = this

    wx.request({
      url: config.serviceUrl.contactInfoInSATB12Url,
      method: 'POST',
      data: {
        wxNickname: appInstance.globalData.userInfo.nickName
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log('contact info:', res.data)
        var status = res.data.status
        if (status == 0) {
          var contactList = new Array()
          var contactDic = res.data.contactInfo
          var partList = that.data.partList
          for (var i = 0; i < partList.length; ++i) {
            var partTag = partList[i]
            contactList.push({ id: partTag.id, name: partTag.name, open: false, contactList: contactDic[partTag.id]})
          }

          that.setData({
            contactInfoList: contactList
          })
        }
        else {
          if (res.data.status == 5) {
            wx.showModal({
              title: '没有权限',
              content: '仅团委会可查看数据',
              confirmText: '好',
              showCancel: false
            })
          }
        }
      },
      fail: function (errMessage) {
        wx.showModal({
          title: '无法连接服务器',
          content: '请检查网络连接',
          confirmText: '好',
          showCancel: false
        })
      },
      complete: function () {
        wx.hideNavigationBarLoading()
        wx.stopPullDownRefresh()
      }
    })
  },

  addContact: function (e) {
    wx.navigateTo({
      url: '../addContact/addContact'
    })
  }
})