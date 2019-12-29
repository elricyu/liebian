<template>
	<div id="app" v-cloak style="font-size: 0.4rem;">
		<div v-if="!isShow">
			<p class="jilujian">您在本活动已赢得<span v-cloak>{{money}}</span>元红包，<span v-cloak>{{zjf}}</span>积分</p>
			<div class="container-fluid" style="height: 55vh">
				<div class="row" style="padding-top: 0.3rem;padding-bottom: 0.2rem;background: #FFF;margin-top:0.14rem " v-on:click="hongbao()">
					<div class="col-xs-12">
						<div style="position: relative">
							<a class="media-left" href="#">
								<img class="media-object" src="../assets/raw_img/hongbao.png" style="width:1.14rem ">
							</a>

							<div class="media-body">
								<p class="media-heading liebao libiao1">微信红包</p>
								<p class="color99">完成任务,点击详情领取奖励</p>
							</div>
							<div class="xiangqing xiang1">详情</div>
							<p class="text-center color99" style="padding-top: 0.08rem;text-align: left;">红包总数{{num}}个</p>
						</div>
					</div>
				</div>
			</div>

			<div class="container-fluid">
				<div class="row" style="padding-top: 0.54rem">
					<div class="col-xs-12 color99">
						<p class="text-center"> - - 规则说明 - - </p>
						<p class="">1、请留意页面中关于奖励的详细说明，通常情况下，商家将针对周边有效区域发放福利；</p>
						<p class="">2、发放后请在商家公众号或微信服务通知中领取，可直接收入微信零钱；</p>
						<p class="">3、涉嫌刷单行为的用户，或微信标记的不安全用户，将永远失去奖励资格</p>
					</div>
				</div>
			</div>

			<!--红包弹窗s-->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="background:rgba(0,0,0,0.3);font-size: 0.28rem ">
				<div class="modal-dialog" role="document" style="width:90%;margin: 25% auto 0">
					<div class="modal-content" style="border-radius: 0.1rem">
						<div class="modal-header fix" style="padding: 0.2rem 0.1rem 0 0.22rem;">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<!--<h4 class="modal-title text-center colorhua fon30" id="myModalLabel" style="height: 0.8rem;line-height: 0.8rem">分享方式</h4>-->
						</div>
						<div class="modal-body" style="padding-top: 0">
							<p class="text-center libiao1">微信红包</p>
							<div class="text-center" style="padding-top: 0.1rem;border-bottom:4px dashed #eee">
								<img src="../assets/raw_img/hongbao.png" style="width: 1.62rem" alt="">
								<p class="text-center color99" style="padding-top: 0.1rem;padding-bottom: .2rem">红包总数{{num}}个</p>
							</div>
							<div>
								<p style="line-height:0.8rem;font-size: 0.45rem;">任务说明</p>
								<p style="font-size: 0.4rem;line-height: 0.6rem" class="color99">点击下方按钮前往宣传页面：首次分享并报名体验，可获得随机微信红包；</p>
								<p style="font-size: 0.4rem;line-height: 0.6rem" class="color99">任务达成后点击领取红包按钮，红包在5分钟内自动发送到<span style="color:#f7882e ">编程云 青少儿编程</span>
									微信公众号上，请关注公众号领取。
								</p>
							</div>

						</div>
						<div class="modal-footer">
							<!--<button type="button" style="display: block;width: 100%"  class="btn btn-primary" data-dismiss="modal">去做任务</button>-->
							<div class="renwubtn" data-dismiss="modal" onclick="javascript:window.history.go(-1)" v-if="zhuat==1">去做任务</div>
							<div class="renwubtn" :class="num == 0 ? 'noClick' : ''" v-on:click='linq' v-if="zhuat==2">领取红包</div>
							<div class="renwubtn" v-if="zhuat==3" style="background:#ccc">已领取</div>
						</div>
					</div>
				</div>
			</div>
			<!--红包弹窗e-->

			<!--红包弹窗s-->
			<div class="modal fade" id="myModa2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="background:rgba(0,0,0,0.3);font-size: 0.28rem ">
				<div class="modal-dialog" role="document" style="width:85%;margin: 3rem auto 0">
					<div class="modal-content" style="border-radius: 0.1rem">
						<div class="modal-header fix" style="padding: 0.2rem 0.4rem 0 0.22rem;">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<!--<h4 class="modal-title text-center colorhua fon30" id="myModalLabel" style="height: 0.8rem;line-height: 0.8rem">分享方式</h4>-->
						</div>
						<div class="modal-body" style="padding-top: 0">
							<p class="text-center libiao2 " style="font-size: 0.28rem">红包、蜡笔等到店领取</p>
							<div class="text-center" style="padding-top: 0.1rem;border-bottom:4px dashed #eee">
								<img src="../assets/raw_img/libao.png" style="width: 1.62rem" alt="">
								<div class="fix" style="font-size: 0.24rem;padding: 0 0.5rem 0">
									<p class="text-center color99 fl" style="font-size: 0.24rem;padding-top: 0.1rem;padding-bottom: .1rem">所需积分10</p>
									<p class="text-center color99 fr" style="font-size: 0.24rem;padding-top: 0.1rem;padding-bottom: .1rem">库存充足</p>
								</div>
							</div>
							<div style="height: 3.8rem">
								<p style="line-height:0.8rem ">任务说明</p>
								<p style="font-size: 0.26rem;line-height: 0.4rem" class="color99">按点击点击下方方次点击下方点击下方击下方元微信红包；</p>
								<p style="line-height:0.8rem ">任务状态</p>
								<p style="font-size: 0.26rem;line-height: 0.4rem" class="color99">2018年02月28日</p>
							</div>
						</div>
						<div class="modal-footer fix">
							<!--<button type="button" style="display: block;width: 100%"  class="btn btn-primary" data-dismiss="modal">去做任务</button>-->
							<div class="renwubtn jigen fl" style="width: 45%;">还差70积分</div>
							<div class="renwubtn  fr" style="width: 45%;" data-dismiss="modal">去做任务</div>
						</div>
					</div>
				</div>
			</div>
			<!--红包弹窗e-->

			<div class="modal fade" id="as3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="background:rgba(0,0,0,0.3);font-size: 0.28rem ">
				<div class="modal-dialog" role="document" style="width:70%;margin: 30% auto 0">
					<div class="modal-content" style="border-radius: 0.1rem">
						<div class="modal-header fix" style="padding: 0.2rem 0.4rem 0 0.22rem;">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<!--<h4 class="modal-title text-center colorhua fon30" id="myModalLabel" style="height: 0.8rem;line-height: 0.8rem">分享方式</h4>-->
						</div>
						<div class="modal-body" style="padding-top: 0">
							<img src="../assets/raw_img/jia.jpg" alt="" class="suibb">
							<p class="text-center">长按识别此二维码，关注‘编程云 青少儿编程公众号’，获得红包</p>
						</div>
					</div>
				</div>
			</div>

			<!--优惠券s-->
			<div class="modal fade" id="myModa3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="background:rgba(0,0,0,0.3);font-size: 0.28rem ">
				<div class="modal-dialog" role="document" style="width:85%;margin: 2.2rem auto 0">
					<div class="modal-content" style="border-radius: 0.1rem">
						<div class="modal-header fix" style="padding: 0.2rem 0.4rem 0 0.22rem;">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<!--<h4 class="modal-title text-center colorhua fon30" id="myModalLabel" style="height: 0.8rem;line-height: 0.8rem">分享方式</h4>-->
						</div>
						<div class="modal-body" style="padding-top: 0">
							<p class="text-center libiao1">代金券 &nbsp; ￥ <span>1.00</span>元</p>
							<div class="text-center" style="padding-top: 0.1rem;border-bottom:4px dashed #eee">
								<img src="../assets/raw_img/youhuiq2.jpg" style="width: 1.62rem" alt="">
								<p class="text-center libiao1 " style="font-size: 0.24rem;padding-top: 0.1rem;padding-bottom: .1rem">所需积分10</p>
								<p class="text-center color66 " style="font-size: 0.24rem;padding-bottom: .2rem">库存充足</p>
							</div>
							<div style="height: 3.8rem">
								<p style="line-height:0.8rem ">任务说明</p>
								<p style="font-size: 0.26rem;line-height: 0.4rem" class="color99">点击下方按钮前往宣传页面：首次分享，可获得1元微信红包；</p>
								<p style="line-height:0.8rem ">有效期限</p>
								<p style="font-size: 0.26rem;line-height: 0.4rem" class="color99">2018年02月28日</p>
							</div>
						</div>
						<div class="modal-footer fix">
							<!--<button type="button" style="display: block;width: 100%"  class="btn btn-primary" data-dismiss="modal">去做任务</button>-->
							<div class="renwubtn fl" style="width: 45%;" data-dismiss="modal">去做任务</div>
							<div class="renwubtn colH fr" style="width: 45%;">积分不足</div>
						</div>
					</div>
				</div>
			</div>
			<!--优惠券s-->
		</div>

		<div v-if="isShow" style="text-align: center;margin-top: 30%;">
			<img src="../assets/img/nodata.jpg" alt="" style="width: 50%;margin-bottom: 25px;">
			<div>该活动暂无红包~</div>
		</div>

	</div>
</template>

<script>
	import "../assets/js/jquery.cookie.js"

	export default {
		data() {
			return {
				money: '',
				zjf: '',
				zhuat: '',
				centre_id: '',
				hd_id: '',
				openid: '',
				parentopenid: '',
				num: null, //红包个数
				isShow: false, //暂无红包
			}
		},
		mounted() {
			this.centre_id = $.cookie("centre_id")
			this.hd_id = $.cookie('hd_id')
			this.openid = $.cookie("openid")
			this.parentopenid = $.cookie("parentopenid")
			$.cookie('isManden', '1', {
				expires: 300
			})

			//判断本活动是否有红包
			this.red_packet()

			var self = this;
			$.ajax({
				"url": "https://hdapi.codeclassroom.org/index/hd/flrw",
				type: "post",
				dataType: 'json',
				data: {
					openid: self.openid,
					hd_id: self.hd_id
				},
				success: function(data) {
					self.money = data.data.money;
					self.zjf = data.data.zjf;
					self.num = data.red
				}
			});

			$.ajax({
				"url": "https://hdapi.codeclassroom.org/index/hd/hq",
				type: "post",
				dataType: 'json',
				data: {
					openid: self.openid,
				},
				success: function(data) {
					if (data == 2) {
						$('#as3').modal({
							"backdrop": "static"
						})
					}
				}
			});

			//浏览记录
			record.get_record(this.hd_id, this.parentopenid, this.openid, '浏览了奖励页面', this.centre_id)
		},
		destroyed() {
			$('#as3').modal('hide')
			$('#myModa').modal('hide');
			$('#myModa2').modal('hide')
			$('#myModa3').modal('hide')
			$('.modal-backdrop').remove() //去掉遮罩层
			window.location.reload()
		},
		methods: {
			red_packet() {
				let that = this;
				$.ajax({
					"url": "https://hdapi.codeclassroom.org/index/hd/is_flrw",
					type: "post",
					dataType: 'json',
					data: {
						hd_id: that.hd_id
					},
					success: function(data) {
						if (data.status == 2) {
							that.isShow = true
						}
					}
				});
			},
			hongbao: function() {
				var self = this;
				$('#myModal').modal({
					"backdrop": "static"
				})
				$.ajax({
					"url": "https://hdapi.codeclassroom.org/index/hd/lqhb",
					type: "post",
					dataType: 'json',
					data: {
						openid: self.openid,
						hd_id: self.hd_id
					},
					success: function(res) {
						self.zhuat = res.zhuat
						self.$toast(res.msg)
					}
				});
			},
			lingqu: function() {
				$('#myModa2').modal({
					"backdrop": "static"
				})
			},
			youhui: function() {
				$('#myModa3').modal({
					"backdrop": "static"
				})
			},
			linq: function() {
				var self = this;
				$.ajax({
					"url": "https://wx.codeclassroom.org/pay/redpackhdnew.php",
					type: "post",
					dataType: 'json',
					data: {
						openid: self.openid,
						hd_id: self.hd_id,
						centre_id: self.centre_id,
					},
					success: function(data) {
						if (data == 1) {
                            self.$toast.fail('红包领取成功')
							self.zhuat = 3
						} else if (data == 2) {
							self.$toast.fail('红包领取异常')
						} else {
							self.$toast.fail('红包领取异常')
						}
					}
				});
			}
		},
	}
</script>

<style lang="less" scoped>
	@import "../assets/css/gongyouyangshi.css";

	.noClick {
		pointer-events: none;
		background: gray !important;
	}

	.text-center {
		font-size: 0.4rem;
	}

	.suibb {
		width: 100%;
	}

	p,
	label {
		margin: 0;
		padding: 0
	}

	body {
		background: #eee;
	}

	.jilujian {
		background: #06aaed;
		color: #fff;
		padding: 0.3rem 0.4rem;

	}

	.liebao {
		font-size: 0.4rem;
	}

	.color66 {
		color: #666;
	}

	.color99 {
		color: #999;
	}

	.libiao1 {
		color: #f7882e;
	}

	.libiao2 {
		color: #06aaed;
	}

	.xiangqing {
		position: absolute;
		right: 0.1rem;
		top: 0.2rem;
		width: 1.68rem;
		height: 0.8rem;
		line-height: 0.78rem;
		border-radius: 0.4rem;
		text-align: center;
		font-size: 0.35rem;
		box-sizing: border-box;
	}

	.xiang1 {
		border: 2px solid #f7882e;
		color: #f7882e;
	}

	.xiang2 {
		border: 2px solid #06aaed;
		color: #06aaed;
	}

	.renwubtn {
		width: 100%;
		height: 1rem;
		line-height: 1rem;
		text-align: center;
		color: #fff;
		background: #00a0e8;
		border-radius: 0.2rem;
		font-size: 14px;
	}

	.jigen {
		box-sizing: border-box;
		border: 1px solid #f7882e;
		color: #f7882e;
		background: none;
	}

	.colH {
		background: #ccc;
	}
</style>
