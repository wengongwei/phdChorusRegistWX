// detailContactInfo.js

var config = require('../../../config');
var appInstance = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    contactID: -1,
    contactName: '',
    contactDescription: '',
    contact: {id: -1, part: 'T2', name: '蓝胖子', location: '中关村', includeInStatics: 0}
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      contactID: options.contactID
    })

    this.loadContactInfo(options.contactID)
  },

  loadContactInfo: function (contactID) {
    console.log('load info of contactID:', contactID);
    wx.showNavigationBarLoading()
    var that = this
    wx.request({
      url: config.serviceUrl.detailContactInfoUrl,
      method: 'POST',
      data: {
        contactID: contactID
      },
      header: {
        'content-type': 'application/json'
      },
      success: function(res) {
        console.log("contact detail info:", res.data);
        if (res.data.status == 0) {
          var contact = res.data.contact
          var staticsInfo = '未纳入统计范围'
          if (contact.includeInStatics == 1) {
            staticsInfo = '已纳入统计范围'
          }
          var contactDescription = contact.part + ' · ' + contact.location + ' · ' + staticsInfo
          that.setData({
            contact: contact,
            contactName: contact.name,
            contactDescription: contactDescription
          })
        }
      },
      fail: function(err) {
        wx.showModal({
          title: '无法连接服务器',
          content: '请检查网络连接',
          confirmText: '好',
          showCancel: false
        })
      },
      complete: function () {
        wx.hideNavigationBarLoading()
      }
    })
  },

  alterInfo: function (e) {
    var contactID = this.data.contactID
    var url = '../alterContactInfo/alterContactInfo?contactID=' + contactID
    wx.redirectTo({
      url: url
    })
  },

  deleteContact: function (e) {
    var that = this
    wx.showModal({
      title: '确认删除？',
      content: '删除该团员后，与该团员相关的所有签到信息也都会被删除，无法恢复',
      confirmText: '删除',
      success: function (res) {
        if (res.confirm) {
          that.confirmDeleteContact()
        }
      }
    })
  },

  confirmDeleteContact: function () {
    wx.showLoading({
      title: '正在删除...'
    })

    var that = this
    wx.request({
      url: config.serviceUrl.deleteContactInfoUrl,
      method: 'POST',
      data: {
        contactID: this.data.contactID,
        wxNickname: appInstance.globalData.userInfo.nickName
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log('deleteContact:', res.data)
        if (res.data.status == 0) {
          wx.showToast({
            title: '已删除',
            mask: true,
            icon: 'success',
            duration: 2500
          })
        }
        else {
          var warningContent = '';
          if (res.data.status == 2) {
            warningContent = '系统错误'
          }
          else if (res.data.status == 5) {
            warningContent = '您无权进行此操作，请联系该声部部长'
          }
          wx.hideLoading()
          wx.showModal({
            title: '删除失败',
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
      }
    })

  }
})