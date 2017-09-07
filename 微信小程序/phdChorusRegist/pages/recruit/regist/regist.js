// pages/recruit/regist/regist.js

var config = require('../../../config');
var appInstance = getApp();

Page({

  /**
   * 页面的初始数据
   */
  data: {
    registLocationTypeItems: [
      { value: '中关村', name: '中关村', checked: 'true' },
      { value: '雁栖湖', name: '雁栖湖' }
    ],

    sexTypeItems: [
      { value: 0, name: '女', checked:'true' },
      { value: 1, name: '男' }
    ],

    contactLocationTypeItems: [
      { value: '中关村', name: '中关村', checked: 'true' },
      { value: '雁栖湖', name: '雁栖湖' },
      { value: '奥运村', name: '奥运村' },
      { value: '玉泉路', name: '玉泉路' }
    ],

    vocalTypeItems: [
      { value: '只是喜欢和模仿', name: '只是喜欢和模仿', checked: 'true' },
      { value: '了解一点，平时喜欢唱歌', name: '了解一点，平时喜欢唱歌' },
      { value: '有过专业学习', name: '有过专业学习' }
    ],

    readMusicTypeItems: [
      { value: '不识谱', name: '不识谱', checked: 'true' },
      { value: '简谱', name: '简谱' },
      { value: '五线谱', name: '五线谱' }
    ],

    contactName: '',
    contactSex: 0,
    contactPhone: '',
    contactEmail: '',
    contactLocation: '中关村',
    contactCompany: '',
    contactGrade: '',
    contactVocalAbility: '只是喜欢和模仿',
    contactInstruments: '',
    contactReadMusic: '不识谱',
    registTableDate: '2017-08-06',
    registLocationType: '中关村',
  },

  bindNameChange: function (e) {
    this.setData({
      contactName: e.detail.value
    })
  },

  bindDateChange: function (e) {
    this.setData({
      registTableDate: e.detail.value
    })
  },

  bindRegistLocationChange: function (e) {
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

  bindSexChange: function (e) {
    var newSex = e.detail.value
    var tmpArray = this.data.sexTypeItems
    for (var i = 0, len = tmpArray.length; i < len; ++i) {
      tmpArray[i].checked = (tmpArray[i].value == newSex)
    }

    this.setData({
      sexTypeItems: tmpArray,
      contactSex: newSex
    })
  },

  bindPhoneChange: function (e) {
    this.setData({
      contactPhone: e.detail.value
    })
  },

  bindEmailChange: function (e) {
    this.setData({
      contactEmail: e.detail.value
    })
  },

  bindContactLocationChange: function (e) {
    var newLocation = e.detail.value
    var tmpArray = this.data.contactLocationTypeItems
    for (var i = 0, len = tmpArray.length; i < len; ++i) {
      tmpArray[i].checked = (tmpArray[i].value == newLocation)
    }

    this.setData({
      contactLocationTypeItems: tmpArray,
      contactLocation: newLocation
    })
  },

  bindCompanyChange: function (e) {
    this.setData({
      contactCompany: e.detail.value
    })
  },

  bindGradeChange: function (e) {
    this.setData({
      contactGrade: e.detail.value
    })
  },

  bindVocalChange: function (e) {
    var newVocal = e.detail.value
    var tmpArray = this.data.vocalTypeItems
    for (var i = 0, len = tmpArray.length; i < len; ++i) {
      tmpArray[i].checked = (tmpArray[i].value == newVocal)
    }

    this.setData({
      vocalTypeItems: tmpArray,
      contactVocalAbility: newVocal
    })
  },

  bindInstrumentsChange: function (e) {
    this.setData({
      contactInstruments: e.detail.value
    })
  },

  bindReadMusicChange: function (e) {
    var newReadMusic = e.detail.value
    var tmpArray = this.data.readMusicTypeItems
    for (var i = 0, len = tmpArray.length; i < len; ++i) {
      tmpArray[i].checked = (tmpArray[i].value == newReadMusic)
    }

    this.setData({
      readMusicTypeItems: tmpArray,
      contactReadMusic: newReadMusic
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
      url: config.serviceUrl.recruit_tableRegistUrl,
      method: 'POST',
      data: {
        contactName: this.data.contactName,
        contactSex: this.data.contactSex,
        contactPhone: this.data.contactPhone,
        contactEmail: this.data.contactEmail,
        contactLocation: this.data.contactLocation,
        contactCompany: this.data.contactCompany,
        contactGrade: this.data.contactGrade,
        contactVocalAbility: this.data.contactVocalAbility,
        contactInstruments: this.data.contactInstruments,
        contactReadMusic: this.data.contactReadMusic,
        registTableDate: this.data.registTableDate,
        registLocationType: this.data.registLocationType,
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log('table regist...', res.data)
        var status = res.data.status
        var warningTitle = ''
        var warningContent = ''
        if (status == 0) {
          warningTitle = '签到成功'
          warningContent = '您是' + res.data.registID + '号面试者，请耐心等待叫号，等待过程中请保持安静'
        }
        else {
          warningTitle = '签到失败'
          if (status == 1) {
            warningContent = '签到表不存在，请联系团长或声部长创建签到表'
          }
          else if (status == 3) {
            warningContent = '你已经签过到了，无需重复签到'
          }
          else if (status == 5) {
            warningContent = '请填写完整的信息'
          }
        }

        wx.hideLoading();
        wx.showModal({
          title: warningTitle,
          content: warningContent,
          confirmText: '好',
          showCancel: false
        })
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