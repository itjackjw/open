var app = getApp();
Page({

  data: {
    wuliu: ['已接收', '抵达长沙', '抵达湖南'],
    remind: '加载中'
  },

  onLoad: function (options) {
    console.log(options)
    var that = this;

    wx.setNavigationBarColor({
      frontColor: app.d.frontColor,
      backgroundColor: app.d.bgcolor, 
      animation: {
        duration: 400,
        timingFunc: 'easeIn'
      }
    });

    var orderId = options.orderId;
    var details = options.details ? options.details : '';
    var type = options.type ? options.type : '';
    var courier_num = options.courier_num;
    var express_id = options.express_id;
    wx.request({
      url: app.d.laikeUrl + '&action=order&m=logistics',
      method: 'post',
      data: {
        id: orderId,
        details: details,
        type: type,
        courier_num: courier_num,
        express_id: express_id
      },
      header: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      success: function (res) {
        var status = res.data.status;
        if (status == 1) {
          if (res.data.res_1.message == 'ok') {
            that.setData({
              wuliu: res.data.res_1.data,
              res: res.data
            });
          } else {
            that.setData({
              wuliu: res.data.res_1.data,
              res: res.data
            });
          }
          console.log(res.data.res_1.data)

        } else {
          wx.showToast({
            title: res.data.err,
            duration: 2000
          });
        }
      },
      fail: function () {
        wx.showToast({
          title: '网络异常！',
          duration: 2000
        });
      }
    });
  },

  //页面加载完成函数
  onReady: function () {
    var that = this;
    setTimeout(function () {
      that.setData({
        remind: ''
      });
    }, 1000);
  },
  copyText: function (t) {
    var a = t.currentTarget.dataset.text;
    wx.setClipboardData({
      data: a,
      success: function () {
        wx.showToast({
          title: "已复制"
        })
      }
    })
  },

  onShow: function () {

  },


  onHide: function () {

  },


  onUnload: function () {

  },


  onPullDownRefresh: function () {

  },


  onReachBottom: function () {

  },


})