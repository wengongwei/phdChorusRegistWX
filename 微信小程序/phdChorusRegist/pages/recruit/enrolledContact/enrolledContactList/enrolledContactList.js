// pages/recruit/registTableInfo/registTableInfo.js

var config = require('../../../../config');
var appInstance = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    registTableID: -1,
    fromDate: '2017-08-06',
    toDate: '2018-08-06',
    selectedPart: 'S1',
    contactList: [],
    listType: 1, // type = 1-按签到表查询 | 2-按起止日期查询
    selectedLocationType: 0,

    locationItems: [
      { value: 0, name: '全部' },
      { value: 1, name: '仅雁栖湖' },
      { value: 2, name: '仅中关村' }
    ],

    partItems: [
      { value: 'S1', name: 'S1' },
      { value: 'S2', name: 'S2' },
      { value: 'A1', name: 'A1' },
      { value: 'A2', name: 'A2' },
      { value: 'T1', name: 'T1' },
      { value: 'T2', name: 'T2' },
      { value: 'B1', name: 'B1' },
      { value: 'B2', name: 'B2' }
    ],
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    // type = 1 按签到表查询
    // type = 2 按起止日期查询
    var listType = options.type
    if (listType == 1) {
      this.setData({
        listType: listType,
        registTableID: options.registTableID
      })
    }
    else if (listType == 2) {
      this.setData({
        listType: listType,
        fromDate: options.fromDate,
        toDate: options.toDate
      })
    }

    this.loadcontactList();
  },

  loadcontactList: function () {
    wx.showNavigationBarLoading()
    var that = this
    wx.request({
      url: config.serviceUrl.recruit_enrolledContactListUrl,
      data: {
        listType: this.data.listType,
        registTableID: this.data.registTableID,
        fromDate: this.data.fromDate,
        toDate: this.data.toDate,
        selectedPart: this.data.selectedPart,
        selectedLocationType: this.data.selectedLocationType,
        wxNickname: appInstance.globalData.userInfo.nickName
      },
      method: 'POST',
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log('recruit - enrolled contact list:', res.data)
        if (res.data.status != 0) {
          return
        }

        var tmpArray = that.data.partItems
        var selectedPart = that.data.selectedPart
        var newContactList = res.data.contactList
        for (var i = 0, len = tmpArray.length; i < len; ++i) {
          if (selectedPart == tmpArray[i].value) {
            tmpArray[i].name = tmpArray[i].value + '·' + newContactList.length
            break
          }
        }

        that.setData({
          contactList: newContactList,
          partItems: tmpArray
        })
      },
      fail: function (res) {
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

  // 点击tab切换
  swichPart: function (e) {
    var newPart = e.target.dataset.current
    if (this.data.selectedPart == newPart) {
      return
    }
    else {
      this.setData({
        selectedPart: newPart,
        contactList: []
      })
    }

    this.loadcontactList()
  },

  switchLocation: function (e) {
    var newLocation = e.target.dataset.current
    if (this.data.selectedLocationType == newLocation) {
      return
    }
    else {
      this.setData({
        selectedLocationType: newLocation,
        contactList: []
      })
    }

    this.loadcontactList()
  },

  queryRegistTable: function (e) {
    var interviewerID = e.currentTarget.id
    wx.navigateTo({
      url: 'interviewerInfo/interviewerInfo?interviewerID=' + interviewerID,
    })
  },

  copyPhone: function (e) {
    var contactList = this.data.contactList
    var phone = ''
    for (var i = 0; i < contactList.length; ++i) {
      var contact = contactList[i]
      phone = phone + contact.phone + ';'
    }

    wx.setClipboardData({
      data: phone,
      success: function () {
        wx.showToast({
          title: '复制成功',
          icon: 'success',
          duration: 2500
        })
      }
    })
  },

  copyEmail: function (e) {
    var contactList = this.data.contactList
    var email = ''
    for (var i = 0; i < contactList.length; ++i) {
      var contact = contactList[i]
      email = email + contact.email + ';'
    }

    wx.setClipboardData({
      data: email,
      success: function () {
        wx.showToast({
          title: '复制成功',
          icon: 'success',
          duration: 2500
        })
      }
    })
  }
})