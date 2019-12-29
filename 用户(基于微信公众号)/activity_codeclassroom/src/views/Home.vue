<template>
	<div id="app" v-cloak style="font-size: 0.4rem;">
		<div class="content" style="position: relative;" v-for="(item,index) in list" :key="index">
			<img :src="item.img" class="b_img" alt="">
			<div style="position: absolute;" :style="{top:(item.top1)/40+'rem',left:(item.left1)/50+'rem',}">
				<span :style="{fontFamily:item.ziti1,fontSize:item.zihao1+'rem',letterSpacing:item.jianju1/100+'rem',color:item.color1,fontWeight:item.yangshi1,textDecoration:item.underline1}">{{item.text1}}</span>
			</div>

			<div style="position: absolute;" :style="{top:(item.top2)/40+'rem',left:(item.left2)/50+'rem',}">
				<span :style="{fontFamily:item.ziti2,fontSize:item.zihao2+'rem',letterSpacing:item.jianju2/100+'rem',color:item.color2,fontWeight:item.yangshi2,textDecoration:item.underline2}">{{item.text2}}</span>
			</div>

			<div style="position: absolute;" :style="{top:(item.top3)/40+'rem',left:(item.left3)/50+'rem',}">
				<span :style="{fontFamily:item.ziti3,fontSize:item.zihao3+'rem',letterSpacing:item.jianju3/100+'rem',color:item.color3,fontWeight:item.yangshi3,textDecoration:item.underline3}">{{item.text3}}</span>
			</div>
		</div>
		<div class="guize" @click="get_gz"></div>
		<div class="msg" v-if="centre_list">
			<div class="line"><span>中心名称：</span>{{centre_list.centre}}</div>
			<div class="line"><span>中心地址：</span>{{centre_list.site}}</div>
			<div class="line" v-if="centre_list.hd_site"><span>活动地址：</span>{{centre_list.hd_site}}</div>
			<div class="line"><span>中心电话：</span>{{centre_list.c_phone}}</div>
		</div>
		<!--红包领取规则暂时注释-->
		<div class="image-text" v-if="red_type===6789">
			<div style="float: left;font-weight: bold;">
				<div style="font-size: 0.45rem;">温馨提示:</div>
				<div style="font-size: 0.4rem;">1.分享该活动到朋友/群</div>
				<div style="font-size: 0.4rem;">2.完善报名信息</div>
				<div style="font-size: 0.4rem;">即可领现金红包</div>
			</div>
			<img src="../assets/img/tu6.png" style="float: right;margin-right: 1rem;">
			<div style="clear: both;"></div>
		</div>
		<!--红包领取规则暂时注释结束-->
		<div style="height: 1.6rem;"></div>

		<img src="../assets/img/tu1.png" @click="img3_show = true" class="center_img">
		<div class="footer">
			<div style="padding-left:3%;" @click="register">
				<img src="../assets/img/baoming.png" class="imgs">
				<span>我要报名</span>
			</div>
			<div style="text-align: right;padding-right:3%;">
				<span><a  :href="linkhref">了解更多</a></span>
				<img src="../assets/img/gengduo.png" class="imgs">
			</div>
		</div>

		<!-- 我要报名弹框 -->
		<van-dialog v-model="isApply" :show-confirm-button="false" show-cancel-button>
			<div class="two_btn">
				<button @click="meitemer()">我是会员</button>
				<button @click="meitemer(1)">立即购买</button>
			</div>
		</van-dialog>
		<!-- 活动规则弹框 -->
		<van-dialog v-model="isguize" :show-confirm-button="false">
			<div class="gz_top">
				<img src="../assets/img/hdgz_dian.png" />
				<img src="../assets/img/hdgz.png" />
				<img src="../assets/img/hdgz_dian.png" />
			</div>
			<div class="gz_con">
				<van-cell-group>
					<van-field v-model="gz_name" type="textarea" rows="1" :autosize="{minHeight:150,maxHeight:350}" />
				</van-cell-group>
			</div>
			<div class="gz_foot">
				<img @click="quxiao()" src="../assets/img/hdgz_close.png" />
			</div>
		</van-dialog>
		<!-- 我是会员弹框 -->
		<div class="vip_box" v-if="isVip">
			<div class="order_div">
				<img src="../assets/img/tu2.png" class="right_img">
				<div class="title">报名</div>
				<div class="line">
					<img src="../assets/img/ren.png">
					<input type="text" v-model="order_name" value="" placeholder="请输入姓名" />
				</div>
				<div class="line">
					<img src="../assets/img/tel.png">
					<input type="tel" v-model="order_tel" @change="isTel" maxlength="11" placeholder="请输入手机号" />
				</div>
				<!--<div class="line">-->
					<!--<img src="../assets/img/yan.png">-->
					<!--<input type="text" v-model="order_code" maxlength="6" style="width: 58%;" placeholder="请输入验证码" />-->
					<!--<span @click="get_code" :class="code_txt == '获取验证码' ? 'code' : 'noClick'">{{code_txt}}</span>-->
				<!--</div>-->
				<div class="btn">
					<button @click="refer">提交</button>
				</div>
			</div>
			<div style="text-align: center;overflow: hidden;">
				<img src="../assets/img/close1.png" @click="isVip = false" style="height: 3.8rem;margin-top: -1.8rem;">
			</div>
		</div>

		<!-- 报名成功 -->
		<div class="vip_box" v-if="isSuccess">
			<div class="order_div" style="margin-top: 40%;">
				<img src="../assets/img/tu3.png" class="right_img" style="top: 21%;right: 1rem;">
				<div class="title" style="padding: 1.4rem 0 0.6rem 0;font-size: 0.4rem;">{{hd_name}}活动</div>
				<div class="apply_txt">报名成功</div>
				<div class="btn" @click="isSuccess = false">
					<button>确定</button>
				</div>
			</div>
		</div>

		<!-- 免费课报名成功 -->
		<div class="vip_box" v-if="isSuccess2">
			<div class="order_div" style="margin-top: 40%;">
				<img src="../assets/img/tu3.png" class="right_img" style="top: 21%;right: 1rem;">
				<div class="title" style="padding-top: 1.4rem;">{{hd_name}}活动</div>
				<div class="title" style="font-size: 0.45rem;color: #ff8f0c;padding: 0.2rem 0 0.6rem;">消费码：{{pay_code}}</div>
				<div class="apply_txt" style="margin-bottom: 0.9rem;">报名成功</div>
				<div class="btn" @click="isSuccess2 = false">
					<button>确定</button>
				</div>
			</div>
		</div>

		<!-- 立即购买弹框 -->
		<div class="vip_box" v-if="isBuy">
			<div class="order_div">
				<div style="text-align: center;padding-top: 0.5rem;">
					<img src="../assets/img/tu4.png" alt="" style="width: 2rem;">
				</div>
				<div class="title">抱歉！</div>
				<div class="title" style="padding-top: 0.3rem;">目前课时不足，请重新购买。</div>
				<div class="btn" style="margin-top: 0.55rem;">
					<button @click="buy">立即购买</button>
				</div>
			</div>
			<div style="text-align: center;overflow: hidden;">
				<img src="../assets/img/close1.png" @click="isBuy = false" style="height: 3.8rem;margin-top: -1.8rem;">
			</div>
		</div>

		<div class="three_box">
			<van-popup v-model="img3_show">
				<!-- <div style="position: absolute;top: 10%;left: 27%;">
					<img :src="share_img" alt="" style="width: 4.8rem;">
					<div style="color: white;width: 4.8rem;font-size: 0.3rem;">长按图片保存到手机，再发朋友圈见证你的影响力...</div>
				</div> -->

				<div class="l_img" @click="jump('page_view')">
					<img src="../assets/img/tu11.png" alt="">
					<div>记录</div>
				</div>
				<div class="c_img" @click="jump('jiangli')">
					<img src="../assets/img/tu3.png" alt="">
					<div>奖励</div>
				</div>
				<div class="r_img" @click="jump('geren')">
					<img src="../assets/img/tu22.png" alt="">
					<div>个人</div>
				</div>

				<img src="../assets/img/close.png" @click="img3_show = false" class="close_img">
			</van-popup>
		</div>
	</div>
</template>
<script>
	import "../assets/js/jquery.cookie.js"

	export default {
		data() {
			return {
				img3_show: true,
				isApply: false, //报名
				isVip: false, //预约测评
				isSuccess: false, //报名成功
				isSuccess2: false, //免费课报名成功
				isBuy: false, //立即购买
				isguize: false, //活动规则弹窗
				code_txt: '获取验证码',
				code_time: 60,
				order_name: '', //预约姓名
				order_tel: '', //预约手机号
				order_code: '', //预约验证码
				is_tel: false, //手机号正则  
				hd_id: '', //活动ID
				centre_id: '',
				list: [], //活动数据
				share_img: '', //分享图片
				openid: '',
				parentopenid: '',
				c_status: null, //课程状态
				user_id: '',
				hd_name: '',
				pay_code: '', //消费码
				b_id: null,
				centre_list: '', //中心数据
				isManden: null,
				hh: {
					maxHeight: 100,
					minHeight: 50
				},
				red_type: 3, //有无红包
                gz_name:'',
                linkhref:"javascript:void(0)",
			}
		},
		created() {
			// $.cookie('openid', 'oxmpS08LzPTXCkzgdDp8qkSoWA1I', {expires: 300})
			// $.cookie('centre_id', '621', {expires: 300})
			// $.cookie('hd_id', '221', {expires: 300})
			$('body').removeClass('modal-backdrop')
			this.centre_id = $.cookie("centre_id")
			this.hd_id = $.cookie('hd_id')
			this.openid = $.cookie("openid")
			this.parentopenid = $.cookie("parentopenid")

			//判断是否首次进入页面
			this.isManden = $.cookie("isManden")
			if (this.isManden) {
				this.img3_show = false
			}

			this.course_type()
			this.get_data()
			this.get_share()
			//浏览记录
			record.get_record(this.hd_id, this.parentopenid, this.openid, '浏览了首页', this.centre_id)

			//调用微信分享
			wechat.getWechat(this.centre_id, this.hd_id, this.openid)
		},
		methods: {
			// 活动规则
			get_gz() {
				this.isguize = true
			},
			quxiao() {
				this.isguize = false
			},
			// 判断是否是免费课
			course_type() {
				let that = this
				axios.post('https://hdapi.codeclassroom.org/index/hd/is_free',
						qs.stringify({
							hd_id: that.hd_id,
						}))
					.then(res => {
						if (res.data.status == 1) {
							that.c_status = 1
						} else {
							that.c_status = 2
						}
					})
					.catch(err => {
						console.log(err.response.status)
					})
			},
			//首页数据
			get_data() {
				let that = this
				axios.post('https://hdapi.codeclassroom.org/index/hd/index',
						qs.stringify({
							hd_id: that.hd_id,
							centre_id: that.centre_id,
						}))
					.then(res => {
						if (res.data.status !== 2) {
							if (res.data.foot) {
								that.centre_list = res.data.foot
							}
							that.list = res.data.data.mb_info
							that.gz_name = res.data.data.hd_info.guize
							that.red_type = res.data.data.hd_info.red_type
                            that.linkhref = res.data.data.hd_info.linkhref
							let arr = res.data.data.mb_info
							for (let i in arr) {
								if (arr[i].zihao1 !== '') {
									that.list[i].zihao1 = (arr[i].zihao1) / 40
								}
								if (arr[i].zihao2 !== '') {
									that.list[i].zihao2 = (arr[i].zihao2) / 40
								}
								if (arr[i].zihao3 !== '') {
									that.list[i].zihao3 = (arr[i].zihao3) / 40
								}
							}
						} else {
							that.$toast.fail(res.data.msg)
						}
					})
					.catch(err => {
						console.log(err)
					})
			},
			// 分享图片
			get_share() {
				let that = this
				let url = `https://wx.codeclassroom.org/activity/index.html?parentopenid=${this.openid}&centre_id=${this.centre_id}&hdid=${this.hd_id}`;
				axios.post('https://hdapi.codeclassroom.org/index/hd/erwm',
						qs.stringify({
							hd_id: that.hd_id,
							url: url,
							openid: that.openid,
						}))
					.then(res => {
						if (res.data.status == 1) {
							that.share_img = res.data.img
						} else {
							that.$toast(res.data.msg)
						}
					})
					.catch(err => {
						console.log(err.response.status)
					})
			},
			//报名相关接口
			get_sign(n) {
				let that = this
				axios.post('https://hdapi.codeclassroom.org/index/hd/sign',
						qs.stringify({
							hd_id: that.hd_id,
							openid: that.openid,
							centre_id: that.centre_id,
							is_type: that.b_id,
							name: that.order_name,
							phone: that.order_tel,
							code: that.order_code,
						}))
					.then(res => {
						that.statusCode = res.data.status
						that.hd_name = res.data.hd_name
						that.pay_code = res.data.code
						that.user_id = res.data.user_id
						if (res.data.status == 1) { //报名成功
							that.isSuccess = true
							that.isVip = false
							record.get_record(that.hd_id, that.parentopenid, that.openid, '报名成功', that.centre_id)
						} else if (res.data.status == 3) { //去报名
							that.isVip = true
						} else if (res.data.status == 4) { //课时不足，购买
							that.isBuy = true
						} else if (res.data.status == 5) { //新报名客户，跳转购买页面
							that.buy()
						} else if (res.data.status == 6) { //免费课报名成功
							that.isVip = false
							that.isSuccess2 = true
						} else if (n == 1 && res.data.status !== 2) { //报名信息填写成功
							that.isVip = false
						} else { //错误信息
							that.$toast.fail(res.data.msg)
						}
					})
					.catch(err => {
						console.log(err)
					})
			},
			//课时不足,立即购买
			buy() {
				this.$router.push({
					path: '/pay',
					query: {
						user_id: this.user_id,
						hd_id: this.hd_id,
						centre_id: this.centre_id
					}
				})
			},
			//我要报名
			register() {
				let that = this
				axios.post('https://hdapi.codeclassroom.org/index/hd/is_sign',
						qs.stringify({
							hd_id: that.hd_id,
							openid: that.openid,
						}))
					.then(res => {
						if (res.data.status == 1) {
							if (that.c_status == 1) { //免费课
								that.get_sign()
							} else if (that.c_status == 2) { //收费课
								that.isApply = true
							}
						} else {
							that.$toast.fail(res.data.msg)
						}
					})
					.catch(err => {
						console.log(err.response.status)
					})
			},
			// 我是会员
			meitemer(i) {
				this.b_id = i
				this.isApply = false
				this.get_sign()
			},
			//报名预约
			refer() {
				//正则验证
				if (this.order_name == '') {
					this.$notify({
						message: '姓名不能为空',
						duration: 1500,
						background: 'darkorange'
					});
					return false;
				}

				if (this.order_tel == '' || !this.is_tel) {
					this.$notify({
						message: '请填写正确手机号',
						duration: 1500,
						background: 'darkorange'
					});
					return false;
				}

				// if (this.order_code == '') {
				// 	this.$notify({
				// 		message: '验证码不能为空',
				// 		duration: 1500,
				// 		background: 'darkorange'
				// 	});
				// 	return false;
				// }

				//报名信息填写成功
				this.get_sign(1)
			},
			//获取验证码
			get_code() {
				if (!this.order_tel || !this.is_tel) {
					this.$notify({
						message: '请输入正确的手机号',
						duration: 1500,
						background: 'darkorange'
					});
				} else {
					let that = this
					that.code_time = 60

                    axios.post('https://hdapi.codeclassroom.org/index/hd/send_code',
                        qs.stringify({
                            phone: that.order_tel
                        }))
                        .then(res => {
                            console.log(res);
                            if (res.data.status == 1) {
                                let interval = setInterval(() => {
                                    that.code_time--;
                                    if (that.code_time == 0) {
                                        clearInterval(interval)
                                    }
                                }, 1000)
                            }
                        })
                        .catch(err => {
                            console.log(err)
                        })

					// axios.post('https://bbss.gymbaby.org/dx/duanxinfs.php',
					// 		qs.stringify({
					// 			phone: `MjM4,${that.order_tel}`,
					// 		}))
					// 	.then(res => {
					// 		if (res.data == 1) {
					// 			let interval = setInterval(() => {
					// 				that.code_time--;
					// 				if (that.code_time == 0) {
					// 					clearInterval(interval)
					// 				}
					// 			}, 1000)
					// 		}
					// 	})
					// 	.catch(err => {
					// 		console.log(err)
					// 	})
				}
			},
			//手机号码正则
			isTel() {
				// let myreg = /^[1][3,4,5,7,8][0-9]{9}$/;
				let myreg = /^[1]\d{10}$/;
				if (!myreg.test(this.order_tel)) {
					this.is_tel = false
					this.$notify({
						message: '手机号格式有误',
						duration: 1500,
						background: 'darkorange'
					});
				} else {
					this.is_tel = true
				}
			},
			// 跳转页面
			jump(u) {
				this.$router.push({
					path: u
				})
			}
		},
		watch: {
			//验证码60秒倒计时
			code_time() {
				if (this.code_time >= 1) {
					this.code_txt = this.code_time + '秒后重试'
				} else {
					this.code_txt = '获取验证码'
				}
			}
		}
	}
</script>

<style scoped="scoped">
	a:active {
		color: black;
	}

	a:link {
		text-decoration: none;
	}

	.content {
		width: 100%;
		/* height: calc(100vh - 1.5rem); */
		position: relative;
	}

	.content .b_img {
		width: 100%;
	}

	.image-text {
		margin: 0.5rem;
		padding: 0.2rem 0 0.2rem 0.6rem;
		border: 1px solid;
		border-radius: 0.2rem;
	}

	.footer {
		width: 100%;
		height: 1.5rem;
		line-height: 1.5rem;
		background-color: white;
		display: flex;
		position: fixed;
		left: 0;
		bottom: 0;
		border-radius: 0.5rem 0.5rem 0 0;
	}

	.footer div {
		flex: 1;
	}

	.footer div .imgs {
		width: 1.3rem;
		vertical-align: middle;
		margin-top: -0.08rem;
		margin-right: 0.2rem;
	}

	.footer div span,
	a {
		color: black;
		font-weight: bold;
	}

	.center_img {
		width: 1.4rem;
		position: fixed;
		bottom: 0.5rem;
		left: 43%;
		z-index: 99;
	}

	.three_box .l_img,
	.c_img,
	.r_img {
		width: 2rem;
		height: 2rem;
		border-radius: 50%;
		background-color: white;
		text-align: center;
	}

	.l_img img {
		width: 0.9rem;
		margin-top: 0.3rem;
	}

	.c_img img {
		margin-top: 0.3rem;
		width: 1.1rem;
	}

	.r_img img {
		margin-top: 0.2rem;
		width: 0.8rem;
	}

	.three_box .l_img {
		position: fixed;
		left: 10%;
		bottom: 2.5rem;
	}

	.three_box .c_img {
		position: fixed;
		left: 40%;
		bottom: 3.7rem;
	}

	.three_box .r_img {
		position: fixed;
		right: 10%;
		bottom: 2.5rem;
	}

	.three_box .close_img {
		width: 1rem;
		position: fixed;
		right: 44%;
		bottom: 2rem;
	}

	.three_box .van-popup {
		width: 100%;
		height: 100%;
		background: none;
	}

	.two_btn {
		text-align: center;
		padding: 0.4rem 0;
	}

	.two_btn button {
		width: 85%;
		height: 0.95rem;
		background-color: #ff8f0c;
		border-radius: 0.07rem;
		border: 0;
		color: white;
		margin-top: 0.7rem;
	}


	.vip_box {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(0, 0, 0, 0.5);
		z-index: 999;
	}

	.vip_box .order_div {
		border-radius: 0.1rem;
		width: 85%;
		margin: 30% auto 0;
		background-color: white;
	}

	.order_div .right_img {
		width: 2rem;
		position: fixed;
		top: 11%;
		right: 0.5rem;
	}

	.order_div .apply_txt {
		font-size: 0.46rem;
		font-weight: bold;
		text-align: center;
		margin-bottom: 1.2rem;
	}

	.order_div .title {
		text-align: center;
		font-size: 0.45rem;
		clear: both;
		padding-top: 0.5rem;
	}

	.order_div .line {
		width: 85%;
		height: 1rem;
		border: 0.02rem solid #ff8f0c;
		border-radius: 0.05rem;
		margin: 0.4rem auto 0;
	}

	.order_div .line img {
		width: 0.5rem;
		vertical-align: middle;
		margin-left: 0.3rem;
		padding-right: 0.2rem;
	}

	.order_div .line input {
		width: 80%;
		height: 88%;
		border: 0;
		margin-top: 1%;
	}

	.order_div .line .code {
		color: #ff8f0c;
	}

	.order_div .btn {
		display: block;
		text-align: center;
		margin-top: 0.4rem;
		padding-bottom: 0.9rem;
	}

	.order_div .btn button {
		width: 92%;
		height: 1rem;
		background-color: #ff8f0c;
		color: white;
		border: 0;
		border-radius: 0.1rem;
		font-size: 0.4rem;
	}

	input::-webkit-input-placeholder {
		color: darkgray;
	}

	.noClick {
		pointer-events: none;
		color: darkgray;
	}

	.guize {
		width: 1.84rem;
		height: 0.84rem;
		position: fixed;
		right: 0.3rem;
		top: 0.3rem;
		color: #FFFFFF;
		background: url(../assets/img/hdgz_but.png) no-repeat;
		background-size: 100% 100%;
		text-align: center;
	}

	.gz_top {
		text-align: center;
		padding: 0.35rem 0;
	}

	.gz_top img:nth-child(1) {
		width: 0.18rem;
		height: 0.18rem;
		float: left;
		margin-left: 0.5rem;
	}

	.gz_top img:nth-child(2) {
		width: 2.5rem;
		height: 0.58rem;
	}

	.gz_top img:nth-child(3) {
		width: 0.18rem;
		height: 0.18rem;
		float: right;
		margin-right: 0.5rem;
	}

	.gz_foot {
		text-align: center;
		padding: 0.4rem 0;
	}

	.gz_foot img {
		width: 4.5rem;
		height: 0.8rem;
	}

	/* 底部文本 */
	.msg {
		margin: 0.25rem 0 0.5rem 0.5rem;
	}

	.msg .line {
		padding-top: 0.1rem;
	}

	.msg .line span {
		font-size: 0.43rem;
		font-weight: bold;
		color: black;
	}
</style>
