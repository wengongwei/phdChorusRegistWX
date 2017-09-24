// regist.js

var config = require('../../../config');
var appInstance = getApp();

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
    contactInfo: {},

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

    // 当前声部的团员信息
    selectedPartInfoArray: [],

    // 当前声部团员姓名列表
    selectedPartContactNameList: [],
    
    

    registTableDate: '2017-08-06',
    registTableType: '大排',
    registLocationType: '中关村',
    selectedContactPart: 'T2',
    selectedContactName: '蓝胖子',
    selectedContactID: '-1'
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.loadContactInfo();
  },

  loadContactInfo: function () {
    wx.showNavigationBarLoading()
    var that = this
    wx.request({
      url: config.serviceUrl.phdContactInfoForRegistInSATB12Url,
      method: 'POST',
      data: {
        wxNickname: appInstance.globalData.userInfo.nickName
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log('regist wx.request:', res.data)
        var status = res.data.status
        if (status == 0) {
          var contactList = res.data.contactList
          var partInfo = contactList[that.data.selectedContactPart];
          var partContactNameList = that.nameListFromPartInfo(partInfo);
          console.log(partContactNameList);
          that.setData({
            contactInfo: res.data.contactList,
            selectedPartInfoArray: partInfo,
            selectedPartContactNameList: partContactNameList
          })
        }
        else {
          if (status == 5) {
            wx.showModal({
              title: '没有权限',
              content: '仅团委会可查看数据',
              confirmText: '好',
              showCancel: false
            })
          }
        }
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

  nameListFromPartInfo: function (partInfo) {
    var nameList = new Array();
    for (var i = 0; i < partInfo.length; i++) {
      var contact = partInfo[i];
      nameList[i] = contact.name;
    }

    return nameList;
  },

  contactIdFromName: function (name) {
    var partInfo = this.data.selectedPartInfoArray;
    for (var i = 0; i < partInfo.length; i++) {
      if (name == partInfo[i].name) {
        return partInfo[i].id
      }
    }

    return -1;
  },

  bindDateChange: function (e) {
    this.setData({
      registTableDate: e.detail.value
    })
  },

  bindTypeChange: function (e) {
    var newType = e.detail.value;
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
    var newLocation = e.detail.value
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
    console.log('regist change part to', e.detail.value)
    var newPart = e.detail.value
    var newPartInfo = this.data.contactInfo[newPart]
    var partContactNameList = this.nameListFromPartInfo(newPartInfo);

    var tmpArray = this.data.contactPartItems
    for (var i = 0, len = tmpArray.length; i < len; ++i) {
      tmpArray[i].checked = (tmpArray[i].value == newPart)
    }

    this.setData({
      contactPartItems: tmpArray,
      selectedContactPart: newPart,
      selectedPartInfoArray: newPartInfo,
      selectedPartContactNameList: partContactNameList
    })
  },

  bindContactNameChange: function (e) {
    var contactName = this.data.selectedPartContactNameList[e.detail.value];
    var contactID = this.contactIdFromName(contactName)
    console.log('contactID change to:', contactID)
    this.setData({
      selectedContactName: contactName,
      selectedContactID: contactID
    })
  },

  regist: function () {
    console.log('regist', this.data.registTableDate, this.data.registTableType, this.data.registLocationType, this.data.selectedContactID)

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
        registContactID: this.data.selectedContactID,
        wxNickname: appInstance.globalData.userInfo.nickName
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log('table regist...', res.data)
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
          var warningContent = ''
          if (status == 1) {
            warningContent = '签到表不存在，请联系团长创建签到表'
          }
          else if (status == 2) {
            warningContent = '该团员不存在，请联系声部长添加团员'
          }
          else if (status == 3) {
            warningContent = '你已经签过到了，无需重复签到'
          }
          else if (status == 6) {
            warningContent = '您无权进行此项操作，请联系声部长'
          }

          wx.hideLoading();
          wx.showModal({
            title: '签到失败',
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

        console.log("tableRegist fail:", res.data)
      }
    })
  }
})