// contactIndex.js

var config = require('../../../config');

Page({

  /**
   * 页面的初始数据
   */
  data: {
    list: [
      {
        id: 'S',
        name: '女高音',
        open: false,
        pages: ['王冕', '朱思虹']
      },
      {
        id: 'A',
        name: '女中音',
        open: false,
        pages: ['温雅婷', '肖寒']
      },
      {
        id: 'T',
        name: '男高音',
        open: false,
        pages: ['梁志鹏', '江文宇']
      },
      {
        id: 'B',
        name: '男低音',
        open: false,
        pages: ['李洪宇', '文俊']
      }
    ]
  },

  /**
   * 生命周期函数--监听页面加载
   * 从options中可以获取上个页面穿过来的参数（options.title）
   */
  onLoad: function (options) {
    wx.showNavigationBarLoading();

    wx.request({
      url: config.serviceUrl.contactInfoInSATBUrl,
      method: 'POST',
      header: {
        'content-type': 'application/json'
      },
      success: function (contactInfo) {
        this.setData({
          list: contactInfo
        })
      },
      fail: function (errMessage) {
        wx.showModal({
          title: '无法连接服务器',
          content: '请检查网络连接',
          confirmText: '好'
        })
      }
    })
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
    wx.hideNavigationBarLoading();
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

  kindToggle: function (e) {
    var id = e.currentTarget.id
    var list = this.data.list
    for (var i = 0, len = list.length; i < len; ++i) {
      if (list[i].id == id) {
        list[i].open = !list[i].open
      }
      else {
        list[i].open = false;
      }
    }

    this.setData({
      list: list
    })
  },

  addContact: function (e) {
    wx.navigateTo({
      url: '../addContact/addContact'
    })
  }
})