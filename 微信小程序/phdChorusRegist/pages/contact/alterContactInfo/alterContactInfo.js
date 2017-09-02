// alterContactInfo.js

var config = require('../../../config');
var appInstance = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    contactPartItems: [
      { value: 'S1', name: 'S1' },
      { value: 'S2', name: 'S2' },
      { value: 'A1', name: 'A1' },
      { value: 'A2', name: 'A2' },
      { value: 'T1', name: 'T1' },
      { value: 'T2', name: 'T2', checked: 'true' },
      { value: 'B1', name: 'B1' },
      { value: 'B2', name: 'B2' }
    ],

    registLocationTypeItems: [
      { value: '中关村', name: '中关村', checked: 'true' },
      { value: '雁栖湖', name: '雁栖湖' }
    ],

    includeInStaticsTypeItems: [
      { value: '1', name: '是', checked: 'true' },
      { value: '0', name: '否' }
    ],

    contactID: -1,
    registLocationType: '中关村',
    selectedContactPart: 'T2',
    selectedContactName: '',
    includeInStatics: 1,
    contact: { id: -1, part: 'T2', name: '蓝胖子', location: '中关村', includeInStatics: 0}
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
    console.log('alter-load info of contactID:', contactID);
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
      success: function (res) {
        console.log("contact detail info:", res.data);
        if (res.data.status == 0) {
          var contact = res.data.contact
          that.setData({
            selectedContactPart: contact.part,
            registLocationType: contact.location,
            selectedContactName: contact.name,
            includeInStatics: contact.includeInStatics
          })

          that.displayUserInfo()
        }
      },
      fail: function (err) {
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

  // 根据加载出来的团员信息设置选项
  displayUserInfo: function () {
    // 姓名
    // 通过绑定input的value，自动设定input中的文字

    // 园区
    var newLocation = this.data.registLocationType
    var tmpArray = this.data.registLocationTypeItems
    for (var i = 0, len = tmpArray.length; i < len; ++i) {
      tmpArray[i].checked = (tmpArray[i].value == newLocation)
    }

    this.setData({
      registLocationTypeItems: tmpArray,
    })

    // 声部
    var newPart = this.data.selectedContactPart
    var tmpArray = this.data.contactPartItems
    for (var i = 0, len = tmpArray.length; i < len; ++i) {
      tmpArray[i].checked = (tmpArray[i].value == newPart)
    }
    this.setData({
      contactPartItems: tmpArray
    })

    // 是否纳入统计范围
    var newInclude = this.data.includeInStatics
    var tmpArray = this.data.includeInStaticsTypeItems
    for (var i = 0, len = tmpArray.length; i < len; ++i) {
      tmpArray[i].checked = (tmpArray[i].value == newInclude)
    }

    this.setData({
      includeInStaticsTypeItems: tmpArray
    })

  },

  bindLocationChange: function (e) {
    console.log('regist change location to ', e.detail.value)
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

  bindContactPartChange: function (e) {
    console.log('regist change part to ', e.detail.value)
    var newPart = e.detail.value
    var tmpArray = this.data.contactPartItems
    for (var i = 0, len = tmpArray.length; i < len; ++i) {
      tmpArray[i].checked = (tmpArray[i].value == newPart)
    }
    this.setData({
      selectedContactPart: newPart,
      contactPartItems: tmpArray
    })
  },

  bindNameChange: function (e) {
    this.setData({
      selectedContactName: e.detail.value
    })
  },

  bindIncludeInStaticsChange: function (e) {
    console.log('include in statics change:', e.detail.value)
    var newInclude = e.detail.value
    var tmpArray = this.data.includeInStaticsTypeItems
    for (var i = 0, len = tmpArray.length; i < len; ++i) {
      tmpArray[i].checked = (tmpArray[i].value == newInclude)
    }

    this.setData({
      includeInStatics: newInclude,
      includeInStaticsTypeItems: tmpArray
    })
  },

  updateContactInfo: function (e) {
    if (this.data.selectedContactName.length <= 0) {
      wx.showModal({
        title: '请输入团员姓名',
        confirmText: '好',
        showCancel: false
      })

      return
    }

    wx.showLoading({
      title: '正在更新...',
    })
    
    var that = this
    wx.request({
      url: config.serviceUrl.updateContactInfoUrl,
      method: 'POST',
      data: {
        contactID: this.data.contactID,
        contactLocation: this.data.registLocationType,
        contactPart: this.data.selectedContactPart,
        contactName: this.data.selectedContactName,
        contactIncludeInStatics: this.data.includeInStatics,
        wxNickname: appInstance.globalData.userInfo.nickName
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log('addContactUrl:', res.data)
        if (res.data.status == 0) {
          wx.showToast({
            title: '更新成功',
            mask: true,
            icon: 'success',
            duration: 2500
          })
        }
        else {
          var warningContent = '';
          if (res.data.status == 2) {
            warningContent = '更新失败(系统错误)'
          }
          else if (res.data.status == 5) {
            warningContent = '您无权进行此操作，请联系声部长'
          }
          wx.hideLoading()
          wx.showModal({
            title: '更新失败',
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