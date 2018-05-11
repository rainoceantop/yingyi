App({
  globalData:{
    //存放用户信息res
    openid: "admin",
  },
  /**
   * 当小程序初始化完成时，会触发 onLaunch（全局只触发一次）
   */
  onLaunch: function () {
    //检查会话是否过期
    wx.checkSession({
      //未过期
      success: function(){
      },
      //已过期
      fail: function () {
        //重新登陆
        wx.login({
          success: res=>{
            //将openid更新
            console.log(res)
          }
        })
      },
    })
  },

  /**
   * 当小程序启动，或从后台进入前台显示，会触发 onShow
   */
  onShow: function (options) {
    
  },

  /**
   * 当小程序从前台进入后台，会触发 onHide
   */
  onHide: function () {
    
  },

  /**
   * 当小程序发生脚本错误，或者 api 调用失败时，会触发 onError 并带上错误信息
   */
  onError: function (msg) {
    
  }
})
