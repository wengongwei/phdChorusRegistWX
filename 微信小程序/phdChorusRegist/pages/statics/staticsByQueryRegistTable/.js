// staticsByDay.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
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

    registTableDate: '2017-08-06',
    registTableType: '大排',
    registLocationType: '中关村'
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

  bindDateChange: function (e) {
    this.setData({
      registTableDate: e.detail.value
    })
  },

  bindTypeChange: function (e) {
    var newType = e.detail.value
    var tmpArray = this.data.registTableTypeItems
    for (var i = 0, len = tmpArray.length; i < len; ++i) {
      tmpArray[i].checked = (tmpArray[i].value == newType)
    }

    this.setData({
      registTableTypeItems: tmpArray,
      registTableType: newType
    })
  },

  bindLocationChange: function (e) {
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

  queryRegistTable: function (e) {
    wx.request({
      url: 'registTableOnDateUrl',
      fail: function (e) {
        
      }
    })
  }
})