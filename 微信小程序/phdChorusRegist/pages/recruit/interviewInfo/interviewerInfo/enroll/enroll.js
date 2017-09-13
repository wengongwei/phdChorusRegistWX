var config = require('../../../../../config');
var appInstance = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    interviewerID: 0,
    name: '',
    part: 'T2',

    // 选择声部
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
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      interviewerID: options.interviewerID,
      name: options.name
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
      contactPartItems: tmpArray,
      part: newPart,
    })
  },

  enroll: function (e) {
    console.log('enroll to part:', this.data.part)

    wx.showLoading({
      title: '正在录取',
    })

    var that = this
    wx.request({
      url: config.serviceUrl.recruit_enrollContactUrl,
      method: 'POST',
      data: {
        interviewerID: this.data.interviewerID,
        wxNickname: appInstance.globalData.userInfo.nickName,
        part: this.data.part
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log("enroll contact:", res.data);
        if (res.data.status == 0) {
          wx.showToast({
            title: '录取成功',
          })
        }
        else {
          if (res.data.status == 5) {
            wx.showModal({
              title: '无操作权限',
              content: '声部长不可跨声部录取团员',
              confirmText: '好',
              showCancel: false
            })
          }

          wx.hideLoading()
        }
      },
      fail: function (err) {
        wx.showModal({
          title: '无法连接服务器',
          content: '请检查网络连接',
          confirmText: '好',
          showCancel: false
        })

        wx.hideLoading()
      }
    })
  }
})