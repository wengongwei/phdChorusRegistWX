// addContact.js

var config = require('../../../config');

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

    registLocationType: '中关村',
    selectedContactPart: 'T2',
    selectedContactName: '蓝胖子'
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

  addContact: function (e) {
    console.log("add contact ", this.data.selectedContactName, this.data.selectedContactPart, this.data.registLocationType)
  
    // 显示正在创建
    wx.showLoading({
      title: '正在添加...',
      mask: true
    })

    // 服务器
    wx.request({
      url: config.serviceUrl.addContactUrl,
      method: 'POST',
      data: {
        contactLocation: this.data.registLocationType,
        contactPart: this.data.selectedContactPart,
        contactName: this.data.selectedContactName
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log('addContactUrl:', res.data)
        var title = ''
        if (res.data.status == 0) {
          title = '添加成功'
        }
        else if (res.data.status == 1) {
          title = '该团员已存在'
        }

        wx.showToast({
          title: title,
          mask: true,
          icon: 'success',
          duration: 2500
        })
      },
      fail: function (res) {
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