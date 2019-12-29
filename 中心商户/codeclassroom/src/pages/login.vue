<template>
	<div class="loginbox" style="position: relative">
		<p class="dis" style="width: 100%;position: absolute;left: 0 ;top:20px;text-align: center;font-size: 16px">编程云会员管理系统</p>
		<p class="dis" style="width: 100%;position: absolute;left: 0 ;bottom:20px;text-align: center;font-size: 16px">版本号{{version}}</p>
		<div class="flexbox">
			<img class="boximg" src="/static/img/login/gymbaby.png" style="width:800px;margin-right:30px " alt="">
			<div class="box_2" v-if="login_switch==true">
				<div class="box_2_1">
					<div class="box_2_1_1">
						密码登录
						<img src="../../static/img/login_er.png" class="login_img1" @click="click_switch1()" />
						<img src="../../static/img/login_sao.png" class="login_img2" />
					</div>
				</div>
				<div class="box_2_2">
					<div class="box_2_2_1">
						<span style="color: #1f8c22"></span>
					</div>

					<div class="box_2_2_2">
						<input type="text" v-model="phone" placeholder="手机号" name="phone"><br>
						<input type="password" v-model="pwd" placeholder="登录密码" name="pad" @keyup.enter="denglu">
					</div>
					<div class="box_2_2_3">
						<input type="checkbox" v-model="xuanz" style="vertical-align: middle"><span style="font-size: 11px">
							我已阅读并同意遵守《编程云服务协议》</span>
					</div>
					<input type="hidden" name="ip" id="ipdz" value="111.193.230.34">
					<div class="box_2_2_4">
						<input type="button" @click="denglu()" value="登 录" class="deng">
					</div>
				</div>
			</div>
			<div class="box_3" v-if="login_switch==false">
				<div class="box_2_1">
					<div class="box_2_1_1">
						扫码登录
						<img src="../../static/img/login_dian.png" class="login_img1" @click="click_switch2" />
						<img src="../../static/img/login_mi.png" class="login_img2" />
					</div>
				</div>
				<div class="box_3_2">
					<div class="box_3_2_1">
						<img :src="code_img" id="img1" alt="暂无二维码,请刷新网页" />
					</div>
					<div class="box_3_2_2">
						<div class="box_3_2_2_1">
							<img src="../../static/img/login_saomiao.png" />
							<span>打开微信</span>
							<span></span>
							<span>扫一扫登陆</span>

						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="ban">
			版本：{{version}}
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				phone: '',
				pwd: '',
				xuanz: true,
				version: '',
				login_switch: true,
				code_img: '',
                xunhuan:'',
                login_time:''
			};
		},
		methods: {
			// 点击切换
			click_switch1: function() {
                this.get_qrcode();
				this.login_switch = false
			},
			click_switch2: function() {
                clearInterval(this.xunhuan)
				this.login_switch = true

			},
			denglu: function() {
				// alert(11);return;
				let self = this;
				if (!self.xuanz) {
					self.$message({
						message: '未勾选协议',
						type: 'error'
					});
					return;
				}
				$.ajax({
					type: 'POST',
					url: "https://hdapi.codeclassroom.org/index/Login/index",
					data: {
						phone: self.phone,
						pad: self.pwd,
						xie: self.xuanz
					},
					success: function(data) {
						if (data.status == 1) {
							//                        console.log(data)
							//                        self.$cookies.set("token",data.token)
							$.cookie('token', null)
							$.cookie("token", data.token)
							// alert(data.token)
							// if (data.gangwei == '老师') {
								self.$router.push('/xindex')
							// } else {
								// self.$router.push('/xindex')
							// }
						} else if (data.status == 4) {
							self.$message({
								message: '密码错误',
								type: 'error'
							});
						} else if (data.status == 2) {
							self.$message({
								message: '请同意用户协议',
								type: 'warning'
							});
						} else if (data.status == 3) {
							self.$message({
								message: '手机号码不存在',
								type: 'error'
							});
						} else if (data.status == 6) {
							self.$message({
								message: '中心状态异常',
								type: 'error'
							});
						} else if (data.status == 5) {
							$.cookie('tokenre', data.token)
							self.$message({
								message: '首次登陆系统，建议您修改初始密码，我们将在3秒钟后跳转初始密码页面',
								type: 'success'
							});
							setTimeout(function() {
								self.$router.push('/xindex')
							}, 2000)

						}
					}
				});
			},
			get_qrcode:function () {
			    let self = this;
                // 二维码登陆
                $.ajax({
                    type: 'POST',
                    url: "https://hdapi.codeclassroom.org/index/login/set_qrcode",
                    data: {id:1},
                    success: function(e) {
                        if(e.status==1){
                            // var path = "data:image/png;base64,"+e.img;
                            self.code_img = e.img;
                            $("#img1").attr("src",e.img)
                            self.login_time = e.login_time;
                            //循环执行 访问登录状态
                            // check_login(login_time);
                           self.xunhuan =  setInterval(function(){
                               $.ajax({
                                   url:'https://hdapi.codeclassroom.org/index/Login/smdl',
                                   type:'post',
                                   dataTyoe:'json',
                                   data:{time:self.login_time},
                                   success:function(data){
                                       if(data.status == 1){
                                           $.cookie('token', null)
                                           $.cookie("token", data.token)
                                           if (data.gangwei == '老师') {
                                               self.$router.push('/xindex')
                                           } else {
                                               self.$router.push('/xindex')
                                           }
                                       }
                                   }
                               })
                            },3000);
                        }else{
                            location.reload();
                        }
                    }
                })
            },
		},
		mounted: function() {
			let self = this;
			$.ajax({
				type: 'POST',
				url: "https://hdapi.codeclassroom.org/index/Login/get_version",
				data: {},
				success: function(data) {
					if (data.status == 1) {
						self.version = data.version;
					}
				}
			})
		},
		//    methods: {
		//        denglu:function () {
		//            this.$cookies.set("token",true,"16h")
		//         //    this.$store.commit('changeLogin','100')
		//            this.$router.push({ path: 'xindex' })
		//        }
		//    }
	}
</script>

<style scoped>
	.dis {
		display: none
	}

	.loginbox {
		width: 100%;
		height: 100vh;
		background: url(/static/img/login/login.png);
		background-size: 100% auto;
		display: flex;
		display: -webkit-flex;
		justify-content: center;
		align-items: center;
	}

	.flexbox {
		display: flex;
		display: -webkit-flex;
	}

	.box_2 {
		text-align: center;

	}

	.box_2_1 {
		width: 300px;
		height: 60px;
		line-height: 60px;
		letter-spacing: 4px;
		border-radius: 5px 5px 0 0;
		color: #080808;
		background-color: #fff;
	}

	.box_2_1_1 {
		width: 230px;
		height: 60px;
		margin: auto;
		text-align: left;
		font-weight: bold;
		position: relative;
	}

	.login_img1 {
		width: 40px;
		height: 40px;
		float: right;
		margin-top: 16px;
		cursor: pointer;
	}

	.login_img2 {
		width: 77px;
		height: 26px;
		float: right;
		margin-top: 16px;
	}

	.box_2_2 {
		background-color: #ffffff;
		width: 300px;
		border-radius: 0 0 4px 4px;
		font-size: 12px;
	}

	.box_2_2_1 {
		height: 20px;
		line-height: 105px;
		text-align: left;
		padding-left: 35px;
	}

	.box_2_2_2 {
		height: 85px;
	}

	.box_2_2_3 {
		margin-top: 5px;
		height: 70px;
		font-size: 8px;
	}

	.box_2_2_4 {
		height: 98px;
		box-shadow: 2px 2px 4px #5184c3;
	}

	.box_2_2_2 input {
		height: 35px;
		margin-top: 5px;
		width: 230px;
		border: 1px solid #d6d6d6;
		padding-left: 10px;
	}

	.box_2_2_3 {
		margin-top: 5px;
		height: 70px;
		font-size: 8px;
	}

	.box_3_2 {
		width: 300px;
		background: #FFFFFF;
	}

	.box_3_2_1 {
		width: 300px;
		height: 200px;
		text-align: center;
	}

	.box_3_2_1 img {
		width: 150px;
		height: 150px;
		margin-top: 25px;
	}

	.box_3_2_2 {
		width: 300px;
		height: 78px;
		box-shadow: 2px 2px 4px #5184c3;
	}

	.box_3_2_2_1 {
		width: 100px;
		height: 78px;
		margin: auto;
	}

	.box_3_2_2_1 img {
		width: 25px;
		height: 25px;
		float: left;
		margin-top: 5px;
	}

	.box_3_2_2_1 span {
		width: 75px;
		height: 8px;
		display: block;
		float: left;
		text-indent: 0.5em;
	}

	.deng {
		background-color: #46a9ec;
		border-radius: 4px;
		width: 230px;
		height: 40px;
		color: #fff;
		line-height: 40px;
		margin: 0 auto;
		border: #3491eb;
		letter-spacing: 5px;
		font-size: 14px;
	}

	.ban {
		position: fixed;
		width: 100px;
		height: 30px;
		font-size: 18px;
		bottom: 1px;
		right: 15px;
	}

	@media screen and (max-width: 900px) {
		.loginbox {
			background: #62AEFC;
		}

		.boximg {
			display: none
		}

		.dis {
			display: block
		}

		.ban {
			display: none
		}
	}
</style>
