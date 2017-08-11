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

  bindLocationChange: function (e) {
    var tmpArray = this.data.registLocationTypeItems
    for (var i = 0, len = tmpArray.length; i < len; ++i) {
      tmpArray[i].checked = (tmpArray[i].value == e.detail.value)
    }

    this.setData({
      registLocationTypeItems: tmpArray,
      registLocationType: e.detail.value
    })
  },

  bindContactPartChange: function (e) {
    console.log('regist change part to', e.detail.value)
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
        wx.hideLoading()
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

        setTimeout(function () {
          wx.navigateBack({

          })
        }, 3000)
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