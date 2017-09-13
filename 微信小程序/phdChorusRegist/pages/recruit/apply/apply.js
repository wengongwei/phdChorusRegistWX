// pages/recruit/apply/apply.js

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
      { value: 0, name: '女', checked: 'true' },
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

    pianistTypeItems: [
      { value: 0, name: '否', checked: 'true' },
      { value: 1, name: '是' }
    ],

    interviewTimeTypeItems: [],

    contactName: '',
    contactSex: 0,
    contactNation: '',
    contactPhone: '',
    contactEmail: '',
    contactStudentId: '',
    contactLocation: '中关村',
    contactCompany: '',
    contactGrade: '',
    contactVocalAbility: '只是喜欢和模仿',
    contactInstruments: '',
    contactReadMusic: '不识谱',
    contactPianist: 0,
    contactInterest: '',
    contactSkill: '',
    contactExperience: '',
    contactExpect: '',
    interviewTime: -1,
  },

  onLoad: function (options) {
    this.loadValidRegistTable();
  },

  loadValidRegistTable: function () {
    wx.showLoading({
      title: '正在加载报名报...',
    })

    var that = this
    wx.request({
      url: config.serviceUrl.recruit_validRegistTableForApply,
      data: {

      },
      method: 'POST',
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log('valid regist table for apply:', res.data)
        if (res.data.status == 0) {
          that.setData({
            interviewTimeTypeItems: res.data.tableList
          })
        }
        wx.hideLoading()
      },
      fail: function (res) {
        wx.showModal({
          title: '无法连接服务器',
          content: '请检查网络连接',
          confirmText: '好',
          showCancel: false
        })
      }
    })
  },

  bindNameChange: function (e) {
    this.setData({
      contactName: e.detail.value
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

  bindNationChange: function (e) {
    this.setData({
      contactNation: e.detail.value
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

  bindStudentIdChange: function (e) {
    this.setData({
      contactStudentId: e.detail.value
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

  bindPianistChange: function (e) {
    var newPianist = e.detail.value
    var tmpArray = this.data.pianistTypeItems
    for (var i = 0, len = tmpArray.length; i < len; ++i) {
      tmpArray[i].checked = (tmpArray[i].value == newPianist)
    }

    this.setData({
      pianistTypeItems: tmpArray,
      contactPianist: newPianist
    })
  },

  bindInterestChange: function (e) {
    this.setData({
      contactInterest: e.detail.value
    })
  },

  bindExperienceChange: function (e) {
    this.setData({
      contactExperience: e.detail.value
    })
  },

  bindExpectChange: function (e) {
    this.setData({
      contactExpect: e.detail.value
    })
  },

  bindSkillChange: function (e) {
    this.setData({
      contactSkill: e.detail.value
    })
  },

  bindInterviewTimeChange: function (e) {
    var newTime = e.detail.value
    var tmpArray = this.data.interviewTimeTypeItems
    for (var i = 0, len = tmpArray.length; i < len; ++i) {
      tmpArray[i].checked = (tmpArray[i].value == newTime)
    }

    this.setData({
      interviewTimeTypeItems: tmpArray,
      interviewTime: newTime
    })
  },

  apply: function () {
    console.log('regist', this.data.registTableDate, this.data.registTableType, this.data.registLocationType, this.data.selectedContactID)

    // 显示正在报名
    wx.showLoading({
      title: '报名中...',
      mask: true
    })

    // 服务器
    wx.request({
      url: config.serviceUrl.recruit_apply,
      method: 'POST',
      data: {
        contactName: this.data.contactName,
        contactSex: this.data.contactSex,
        contactNation: this.data.contactNation,
        contactPhone: this.data.contactPhone,
        contactEmail: this.data.contactEmail,
        contactStudentId: this.data.contactStudentId,
        contactLocation: this.data.contactLocation,
        contactCompany: this.data.contactCompany,
        contactGrade: this.data.contactGrade,
        contactVocalAbility: this.data.contactVocalAbility,
        contactInstruments: this.data.contactInstruments,
        contactReadMusic: this.data.contactReadMusic,
        contactPianist: this.data.contactPianist,
        contactInterest: this.data.contactInterest,
        contactSkill: this.data.contactSkill,
        contactExperience: this.data.contactExperience,
        contactExpect: this.data.contactExpect,
        registTableID: this.data.interviewTime
      },
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        console.log('table regist...', res.data)
        var status = res.data.status
        if (status == 0) {
          wx.showModal({
            title: '报名成功',
            content: '请耐心等待面试通知',
            confirmText: '好',
            showCancel: false
          })
        }
        else {
          var title = '报名失败'
          var content = ''
          if (status == 5) {
            content = '请完整填写信息'
          }
          else if (status == 2) {
            content = '您已报名，请勿重复报名'
          }
          
          wx.showModal({
            title: title,
            content: content,
            confirmText: '好',
            showCancel: false
          })
        }
      },
      fail: function (res) {
        wx.showModal({
          title: '无法连接服务器',
          content: '请检查网络连接',
          confirmText: '好',
          showCancel: false
        })
        console.log("tableRegist fail:", res.data)
      },
      complete: function () {
        wx.hideLoading()
      }
    })
  }
})