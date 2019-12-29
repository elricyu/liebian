<template>
	<div id="app" v-cloak style="font-size: 0.4rem;">
		<div class="gerenh" v-bind:style="{backgroundImage: 'url(' +head_image+ ')'}" style="background-size: 100% auto;color: #fff">
			<div class="zhezhao">
				<div class="gerentx">
					<img :src="head_image" style="width: 100%;height: 100%" alt="">
				</div>
				<p class="text-center" style="font-size: 0.34rem" v-cloak>{{user_name}}</p>
				<p class="text-center" v-cloak>{{wxss}}</p>
				<div class="container-fluid">
					<div class="row" style="padding-top: 0.3rem">
						<div class="col-xs-4 text-center" style="padding-left: 0;padding-right: 0">
							<p>累计红包</p>
							<p>￥ <span id="">{{money}}</span>元</p>

						</div>
						<div class="col-xs-4 text-center" style="padding-left: 0;padding-right: 0">
							<div class="zidongBtn" v-on:click="gengxin">
								自动更新
							</div>
						</div>
						<div class="col-xs-4 text-center" style="padding-left: 0;padding-right: 0">
							<p>影响力指数</p>
							<p id="effect">{{influence}}</p>
						</div>
					</div>
				</div>

			</div>
		</div>

		<div class="container-fluid" style="padding-top: 0.3rem;margin-bottom: 2px">
			<div class="row" style="background: #fff;padding-top: 0.43rem;">
				<div class="col-xs-12 gerenlist">
					<p>真实姓名
						<span v-cloak>{{real_name}}</span> 
						<img src="../assets/raw_img/xiugai.png" onclick="$('#myModal').modal({'backdrop':'static'})" style="width: 0.5rem;float: right" alt="">
					</p>
				</div>

				<div class="col-xs-12 gerenlist">
					<p>手机号码
						<span v-cloak>{{phone}}</span>
					</p>
				</div>

				<div class="col-xs-12 gerenlist">
					<p>出生日期
						<span v-cloak>{{birthday}}</span>
					</p>
				</div>
			</div>
		</div>


		<div class="container-fluid" style="padding-top: 0.3rem;margin-bottom: 2px">
			<div class="row" style="background: #fff;padding-top: 0.43rem;">
				<div class="col-xs-12 gerenlist">
					<p>所在省市
						<span>{{shengshi}}</span> 
						<img src="../assets/raw_img/xiugai.png" v-on:click="address2" style="width: 0.5rem;float: right" alt="">
					</p>
				</div>

				<div class="col-xs-12 gerenlist">
					<p>收货地址
						<span>{{address}}</span>
					</p>
				</div>
			</div>
		</div>

		<p style="height: 1.38rem;line-height: 1.38rem;color: #999" class="text-center">
			个人资料及信息保护遵循《微信隐私保护指引》
		</p>

		<!--修改会员信息s-->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="background:rgba(0,0,0,0.3) ">
			<div class="modal-dialog" role="document">
				<div class="modal-content" style="border-radius: 0.1rem;margin: 20% 5% 0;width: 55%;">
					<div class="modal-header" style="padding: 0.15rem 0.2rem 0.15rem 0.22rem;">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<p class="modal-title text-center" style="padding-top: 0.3rem;">修改会员信息</p>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-xs-12 color99" style="line-height: 34px">真实姓名</div>
							<div class="col-xs-12" style="margin-bottom: 0.1rem">
								<input type="text" class="form-control" v-model="real_name" placeholder="请输入您的姓名"></div>

							<div class="col-xs-12 color99" style="line-height: 34px">手机号</div>
							<div class="col-xs-12" style="margin-bottom: 0.1rem">
								<input type="text" class="form-control" id="shoujih" v-model="phone" placeholder="请输入您的手机号"></div>
							<!--<div class="col-xs-8"><input id="yanzhengm" type="text" class="form-control" placeholder="验证码"></div>-->
							<!--<div class="col-xs-4" style="padding-left: 0">-->
								<!--<input class="form-control huoquytanzheng Hyanzheng" type="button" id="btn" v-on:click="huoqu" value="获取验证码" />-->
							<!--</div>-->
							<div class="col-xs-12 color99" style="line-height: 34px">出生日期</div>
							<div class="col-xs-12" style="margin-bottom: 0.1rem"><input type="date" v-model="birthday" class="form-control"
								 placeholder="请选择您的生日"></div>
						</div>
					</div>
					<div class="modal-footer">
						<div class="baocunBtn" v-on:click="baocun">保存</div>

					</div>
				</div>
			</div>
		</div>

		<!--修改会员信息e-->

		<!--修改会员信息s-->
		<div class="modal fade" id="myModa2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="background:rgba(0,0,0,0.3) ">
			<div class="modal-dialog" role="document">
				<div class="modal-content" style="border-radius: 0.1rem;margin-top: 2.76rem">
					<div class="modal-header" style="padding: 0.15rem 0.2rem 0.15rem 0.22rem;">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<p class="modal-title text-center" style="margin-top: 0.2rem;">修改快递信息</p>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-xs-12 color99" style="line-height: 34px">选择省/直辖市</div>
							<div class="col-xs-12" style="margin-bottom: 0.1rem">
								<select name="" class="form-control" v-model="shengid" v-on:change="shiqu($event)">
									<option v-for="she in shengbox" :value="she.REGION_ID">{{she.REGION_NAME}}</option>
								</select>
							</div>

							<div class="col-xs-12 color99" style="line-height: 34px">选择市/区</div>
							<div class="col-xs-12" style="margin-bottom: 0.1rem">
								<select name="" class="form-control" v-model="shiid2">
									<option v-for="shi in shibox" :value="shi.REGION_ID">{{shi.REGION_NAME}}</option>
								</select>
							</div>

							<div class="col-xs-12 color99" style="line-height: 34px">输入详细地址</div>
							<div class="col-xs-12" style="margin-bottom: 0.1rem">
								<input v-model="xiangxi" type="text" class="form-control" placeholder="输入详细地址">
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<div class="baocunBtn" v-on:click="baocun2">保存</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import "../assets/js/jquery.cookie.js"

	export default {
		data() {
			return {
				openid: '',
				hd_id: '',
				head_image: '',
				user_name: '',
				wxss: '',
				phone: '',
				real_name: '',
				birthday: '',
				shengshi: '',
				address: '',
				effect: '',
				shengid: '',
				shiid: '',
				shiid2: '',
				shengbox: '',
				shibox: '',
				xiangxi: '',
				money: '',
				influence: '',
                is_tel:false	//手机号验证状态
			}
		},
		created() {
			this.hd_id = $.cookie('hd_id')
			this.openid=$.cookie("openid")
			this.parentopenid=$.cookie("parentopenid")
			this.centre_id= $.cookie('centre_id')

			var self = this;
			$.ajax({
				"url": "https://hdapi.codeclassroom.org/index/hd/hyk",
				type: "post",
				dataType: 'json',
				data: {
					openid: self.openid,
					hd_id: self.hd_id
				},
				success: function(data) {
					self.head_image = data.data.head_image;
					self.user_name = data.data.user_name;
					self.wxss = data.data.wxss;
					self.phone = data.data.phone;
					self.real_name = data.data.real_name;
					self.birthday = data.data.birthday;
					self.shengshi = data.data.shengshi;
					self.address = data.data.address;
					self.effect = data.data.effect;
					self.shengid = data.data.sheng;
					self.shiid = data.data.shi;
					self.money = data.data.money;
					self.influence = data.data.influence;
					
					$.ajax({
						"url": "https://hdapi.codeclassroom.org/index/hd/shi",
						type: "post",
						dataType: 'json',
						data: {
							sheng_id: self.shengid
						},
						success: function(data) {
							self.shibox = data;
							//  不默认选中的原因是市的option里没有循环完成而select就已经赋值了，不知道怎么做循环完成之后的判断，所以选用定时器做延时
							setTimeout(function() {
								self.shiid2 = self.shiid;
							}, 1000)

						}
					});
				}
			});
			
			$.ajax({
				"url": "https://hdapi.codeclassroom.org/index/hd/sheng",
				type: "post",
				dataType: 'json',
				data: {
					id: 1
				},
				success: function(data) {
					if(data.status == 1){
						self.shengbox = data.data
					}else{
						self.$toast.fail(data.msg)
					}
					
				}
			});
			
			//浏览记录
			record.get_record(this.hd_id,this.parentopenid,this.openid,'浏览了个人信息页面',this.centre_id)
		},
		methods: {
			address2: function() {
				$('#myModa2').modal({
					'backdrop': 'static'
				})
			},
// 			address2: function() {
// 				var self = this;
// 				self.openid = $.cookie("openid");
// 				self.hd_id = $.cookie("hd_id");
// 				$.ajax({
// 					"type": "post",
// 					"url": "https://hdapi.codeclassroom.org/index/hd/edit_check_finalvisit",
// 					"dataType": "json",
// 					"data": {
// 						openid: self.openid,
// 						hd_id: self.hd_id
// 					},
// 					success: function(data) {
// 						if (data.status == 1) {
// 							$('#myModa2').modal({
// 								'backdrop': 'static'
// 							})
// 						} else {
// 							var res = confirm('当前所在地址与填写地址不匹配，是否修改为当前地址？')
// 							if (res) { 
// 								//确定设成当前地址
// 								$.ajax({
// 									"url": "https://hdapi.codeclassroom.org/index/hd/xdz",
// 									type: "post",
// 									dataType: 'json',
// 									data: {
// 										openid: self.openid,
// 										hd_id: self.hd_id,
// 										tag: 1
// 									},
// 									success: function(data) {
// 										if (data.status == 1) {
// 											location.reload()
// 										}
// 									}
// 								});
// 							}
// 						}
// 					}
// 				});
// 			},
			huoqu: function() {
				if (this.phone == undefined) {
					$('#shoujih').focus();
					return false;
				} else {
					var self = this;
					// $.ajax({
					// 	"type": "post",
					// 	"url": "https://bbss.gymbaby.org/dx/duanxinfs.php",
					// 	"dataType": "json",
					// 	"data": {
					// 		"phone": 'MjM4' + ',' + self.phone
					// 	},
					// 	success: function(data) {
					// 		//alert("获取成功");
					// 	},
					// 	error: function(data) {
					// 		//alert("失败");
					// 	}
					// });
                    $.ajax({
                        "type": "post",
                        "url": "https://hdapi.codeclassroom.org/index/hd/send_code",
                        "dataType": "json",
                        "data": {
                            "phone": self.phone
                        },
                        success: function(data) {
                            //alert("获取成功");
                        },
                        error: function(data) {
                            //alert("失败");
                        }
                    });

					
					var obj = $('.Hyanzheng');
					self.settime(obj);
				}
			},
			settime(val) {
				var countdown = 60;
				if (countdown == 1) {
					val.get(0).removeAttribute("disabled");
					val.val("获取验证码")
					countdown = 60;
					return false;
				} else {
					countdown--;
					val.val(countdown + "秒后重发");
					val.get(0).setAttribute("disabled", true);
				}
			},
            //手机号码正则
            isTel() {
                // let myreg = /^[1][3,4,5,7,8][0-9]{9}$/;
                let myreg = /^[1]\d{10}$/;
                if (!myreg.test(this.phone)) {
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
			baocun: function() {
				var self = this;
				var openid = $.cookie('openid');
				if (self.phone == '' || !self.is_tel) {
					$('#shoujih').focus();
					return false
				} else {

					var hd_id = $.cookie('hd_id');
					$.ajax({
						"type": "post",
						"url": "https://hdapi.codeclassroom.org/index/hd/xghy",
						"dataType": "json",
						"data": {
							openid: openid,
							"real_name": self.real_name,
							"phone": self.phone,
							"code": $('#yanzhengm').val(),
							"birthday": self.birthday,
							"hd_id": hd_id
						},
						success: function(data) {
							if (data.status == 1) {
								self.$toast.success(data.msg)
								setTimeout(()=>{
									location.reload()
								},1000)
							} else {
								self.$toast.fail(data.msg)
							}
						}
					});
				}
			},
			shiqu: function(ev) {
				var shengiid = $(ev.target).val()
				var self = this;
				$.ajax({
					"url": "https://hdapi.codeclassroom.org/index/hd/shi",
					type: "post",
					dataType: 'json',
					data: {
						sheng_id: shengiid
					},
					success: function(data) {
						self.shibox = data.data
					}
				});
			},
			baocun2: function() {
				var openid = $.cookie('openid');
				var self = this;
				$.ajax({
					"url": "https://hdapi.codeclassroom.org/index/hd/xdz",
					type: "post",
					dataType: 'json',
					data: {
						openid: openid,
						sheng: self.shengid,
						shi: self.shiid2,
						tag: 2,
						hd_id: self.hd_id,
						address: self.xiangxi
					},
					success: function(data) {
						if (data.status == 1) {
							self.$toast.success(data.msg)
							setTimeout(()=>{
								location.reload()
							},1000)
						}else{
							self.$toast.fail(data.msg)
						}
					}
				});
			},
			gengxin: function() {
				location.reload();
			}
		}
	}
</script>

<style scoped>
	@import "../assets/css/gongyouyangshi.css";
	@import "../assets/css/animate.css";
	@import "../assets/css/laydate.css";
	
	.modal-body{
		padding: 0.4rem 0.5rem 0;
	}
	.form-control{
		margin-bottom: 0.2rem;
	}
	.color99 {
		color: #999;
	}
	.gerenh{
	    width: 100%;
	}
	.zhezhao{
	    height: 100%;
	    background: rgba(0,0,0,0.6);
	    padding-top: 0.2rem;
	}
	.gerentx{
	    width: 1.9rem;
	    height: 1.9rem;
	    border-radius: 0.98rem;
	    border: 1px solid #fff;
	    margin: 0.2rem auto 0;
	    overflow: hidden;
	}
	.zidongBtn{
	    width:90%;
	    height:0.95rem;
	    line-height: 0.85rem;
	    background:#00a0e8;
	    border: 2px solid #fff;
	    border-radius: 0.4rem;
		margin: 0 auto;
	}
	.gerenlist{
	    padding-left: 0.56rem ;
	    padding-right: 0.48rem;
	    padding-bottom: 0.43rem;
	    color: #999;
	}
	.huoquytanzheng{
	    border: 2px solid #00a0e8;
	    color:#00a0e8 ;
	}
	
	.baocunBtn{
	    width: 80%;
	    height: 1rem;
	    line-height: 1rem;
	    border-radius: 0.4rem;
	    text-align: center;
	    color: #fff;
	    margin: 0 auto;
	    background: #00a0e8;
	}
	.modal-footer{
		padding: 0.3rem 0.4rem 0.5rem;
	}
</style>
