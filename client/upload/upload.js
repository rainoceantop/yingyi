// client/upload/upload.js
const app = getApp()
Page({
  /**
   * 页面的初始数据
   */
  data: {
    tempFilePaths: '',
    informations: Array(),
    count: 0
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function(){
    //获取图片路径和图片张数
    this.setData({
      tempFilePaths: wx.getStorageSync('tempFilePaths'),
      count: wx.getStorageSync('count'),
    })
  },
  //监听文本域输入
  uploadAreaInput: function(e){
    let img_info = 'informations['+e.currentTarget.dataset.input_i+']'
    //更新对应图片的描述
    this.setData({
      [img_info]:e.detail.value
    })
  },
  //调用上传函数
  postUpload: function(){
    let i=0
    this.upload(i)
  },
  //上传函数
  upload: function(i){
    let that = this
    //上传时显示上传进度
    wx.showToast({
      title: '图片上传中'+(i+1)+'/'+that.data.count,
      icon: 'loading',
      mask: true,
    })
    //限定递归次数等于总图片数
    if(i<that.data.count){
      //上传图片
      wx.uploadFile({
        url: 'http://localhost/upload_handler.php',
        filePath: that.data.tempFilePaths[i],
        name: 'img',
        header: {
          'content-type': 'multipart/form-data'
        },
        formData: {
          'openid': app.globalData.openid,
          'info': that.data.informations[i]
        },
        success: res=>{
          //根据返回结果判断上传成功失败
          console.log(res)
        },
        fail: res=>{
          console.log(res)
        },
        //上传完成后，递归继续下一张图片上传
        complete: function(){
          i++
          that.upload(i)
        }
      })
    }else{
      //上传完成后显示成功
      wx.showToast({
        title: '上传成功',
        icon: 'success',
        duration: 2000,
        complete: function () {
          wx.navigateBack({
            delta: 1
          })
        }
      })
    }
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
  
  }
})