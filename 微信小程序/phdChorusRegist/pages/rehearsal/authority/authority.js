// pages/rehearsal/authority/authority.js

var config = require('../../../config');
var appInstance = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    contactName: '',
    contactWxID: '',
    contactWXNickname: '',
    allocAuthority: '',

    authorityTypeItems: [
      { value: 'ALL', name: '所有权限' },
      { value: 'S', name: '女高音声部数据写入权限' },
      { value: 'A', name: '女中音声部数据写入权限' },
      { value: 'T', name: '男高音声部数据写入权限' },
      { value: 'B', name: '男低音声部数据写入权限' },
      { value: 'READ', name: '数据读取权限'}
    ]
  },

  bindNameChange: function (e) {
    this.setData({
      contactName: e.detail.value
    })
  },

  bindWXidChange: function (e) {
    this.setData({
      contactWxID: e.detail.value
    })
  },

  bindWXnicknameChange: function (e) {
    this.setData({
      contactWXNickname: e.detail.value
    })
  },

  bindAuthorityChange: function (e) {
    var newAuthority = e.detail.value
    var tmpArray = this.data.authorityTypeItems
    for (var i = 0, len = tmpArray.length; i < len; ++i) {
      tmpArray[i].checked = (tmpArray[i].value == newAuthority)
    }
    this.setData({
      allocAuthority: newAuthority,
      authorityTypeItems: tmpArray
    })
  },

  allocAuthority: function (e) {
    // 显示正在创建
    wx.showLoading({
      title: '正在授权...',
      mask: true
    })

    // 服务器
    wx.request({
      url: config.serviceUrl.allocAuthorityUrl,
      method: 'POST',
      data: {
        contactName: this.data.contactName,
        contactWxID: this.data.contactWxID,
        contactWXNickname: this.data.contactWXNickname,
        allocAuthority: this.data.allocAuthority,
        wxNickname: appInstance.globalData.userInfo.nickName
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log('alloc authority:', res.data)
        if (res.data.status == 0) {
          wx.showToast({
            title: '授权成功',
            mask: true,
            icon: 'success',
            duration: 2500
          })
        }
        else {
          var warningContent = '';
          if (res.data.status == 2) {
            warningContent = '您没有没有足够高的权限'
          }
          
          wx.hideLoading()
          wx.showModal({
            title: '授权失败',
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

        console.log("createRegistTable fail:", res.data)
      }
    })
  }
  
})