// regist.js

var config = require('../../../config');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    registTableTypeArray: ['大排', '小排', '周日晚', '声乐课'],
    registLocationTypeArray: ['中关村', '雁栖湖'],

    registTableTypeItems: [
      { value: '大排', name: '大排', checked: 'true' },
      { value: '小排', name: '小排' },
      { value: '周日晚', name: '周日晚' },
      { value: '声乐课', name: '声乐课' }
    ],

    registLocationTypeItems: [
      { value: '中关村', name: '中关村', checked: 'true' },
      { value: '雁栖湖', name: '雁栖湖' }
    ],

    // 服务器返回的团员信息
    contactInfo: {S1 : ['s1', 's2', 's3'], A1 : ['a1', 'a2', 'a3']},

    // 选择声部
    contactPartItems: [
      {value: 'S1', name: 'S1'}, 
      {value: 'S2', name: 'S2'},
      {value: 'A1', name: 'A1'}, 
      {value: 'A2', name: 'A2'},
      {value: 'T1', name: 'T1'},
      {value: 'T2', name: 'T2', checked: 'true'},
      {value: 'B1', name: 'B1'},
      {value: 'B2', name: 'B2'}
    ],

    // 当前声部的团员
    contactPartInfoArray: [],

    registTableDate: '2017-08-06',
    registTableType: '大排',
    registLocationType: '中关村',
    selectedContactPart: 'T2',
    selectedContactName: '蓝胖子',
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    wx.showNavigationBarLoading()
    
    wx.request({
      url: config.serviceUrl.contactInfoInSATB12Url,
      method: 'POST',
      header: {
        'content-type': 'application/json'
      },
      success: function (contactInfo) {
        this.setData({
          contactInfo: contactInfo
        })
      },
      fail: function (errMessage) {
        wx.showToast({
          title: '无法连接服务器，请检查网络设置',
          duration: 2500
        })
      },
      complete: function () {
        wx.hideNavigationBarLoading();
      }
    })
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

  bindDateChange: function (e) {
    this.setData({
      registTableDate: e.detail.value
    })
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

  bindContactPartChange: function (e) {
    console.log('regist change part to', e.detail.value)
    var newPart = e.detail.value
    var newPartInfo = this.data.contactInfo[newPart]
    this.setData({
      selectedContactPart: newPart,
      contactPartInfoArray: newPartInfo
    })
  },

  bindContactNameChange: function (e) {
    this.setData({
      selectedContactName: this.data.contactPartInfoArray[e.detail.value]
    })
  },

  regist: function () {
    console.log('regist', this.data.registTableDate, this.data.registTableType, this.data.registLocationType, this.data.selectedContactPart, this.data.selectedContactName)

    // 显示正在创建
    wx.showLoading({
      title: '签到中...',
      mask: true
    })

    // 服务器
    wx.request({
      url: config.serviceUrl.tableRegistUrl,
      method: 'POST',
      data: {
        registTableDate: this.data.registTableDate,
        registTableType: this.data.registTableType,
        registLocationType: this.data.registLocationType,
        selectedContactPart: this.data.selectedContactPart,
        selectedContactName: this.data.selectedContactName
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log('createRegistTable', res.data)
        var status = res.data.status
        if (status == 0) {
          wx.showToast({
            title: '签到成功',
            mask: true,
            icon: 'success',
            duration: 2500
          })
        }
        else {
          warningContent = ''
          if (status == 1) {
            warningContent = '签到表不存在，请联系团长或声部长创建签到表'
          }
          else if (status == 2) {
            warningContent = '该团员不存在，请联系声部长添加团员'
          }
          else if (status == 3) {
            warningContent = '你已签过到了，无需重复签到'
          }

          wx.hideLoading();
          wx.showModal({
            title: '签到失败',
            content: warningContect,
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

        console.log("tableRegist fail:", res.data)
      }
    })
  }
})