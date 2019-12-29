<template>
	<div id="app" v-cloak style="font-size: 0.4rem;">
		<div style="background: #fff" @click="jump">
			<img src="../assets/raw_img/duihuanjl.png" style="width: 1.1rem" alt="">
			<span style="color: #999;line-height: 1.12rem">兑换记录</span>
		</div>

		<div class="media" style="background: #fff;margin-top: 0;padding: 0.3rem 0 0.3rem" v-for="lie in lieb">
			<p style="padding-bottom: 0.1rem;padding-left: 0.24rem" class="hang1" v-cloak>{{lie.centre}}</p>

			<div style="margin-top: 0.2rem" v-for="lie1 in lie.data">
				<a class="media-left" href="javascript:;" style="padding-left:0.24rem;padding-right: 0.24rem">
					<div style="display: inline-block;width: 1.65rem;height: 1.65rem;overflow:hidden">
						<img class="media-object w100" style="width: 100%" :src="lie1.imgpath" alt="暂无图片">
					</div>
				</a>
				<div class="media-body" style="width: 5.1rem">
					<p style="margin-left: -0.1rem">【<span v-cloak>{{lie1.good_name}}</span>】</p>
					<p style="padding-top: 0.1rem;padding-bottom: 0.28rem">订单号：<span v-cloak>{{lie.order_num}}</span></p>
					<div class="jiagep"><img src="../assets/raw_img/db.jpg" alt=""> <span v-cloak>{{lie1.rules}}</span>积分
						<!-- <div v-if="lie.is_rec==2" class="zhuangt ylq">已领取</div> -->
						<!-- <div v-if="lie.is_rec==1" class="zhuangt wlq">未领取</div> -->
					</div>
				</div>
			</div>
		</div>
		<div v-if="lieb == ''" style="text-align: center;margin-top: 25%;">
			<img src="../assets/img/nodata.jpg" alt="" style="width: 50%;margin-bottom: 25px;">
			<div>暂无数据~</div>
		</div>
	</div>
</template>

<script>
	import "../assets/js/jquery.cookie.js"

	export default {
		data() {
			return {
				lieb:"",
			}
		},
		created() {
			var parentopenid = $.cookie("parentopenid")
			var openid=$.cookie("openid");
			var centre_id=$.cookie("centre_id");
			var hd_id = $.cookie('hd_id');
			
			var self=this;		
			$.ajax({
			    "url":"https://hdapi.codeclassroom.org/index/Hd/get_order_goods",
			    type:"post",
			    dataType:'json',
			    data:{
			        openid:openid,
			        hd_id:hd_id
			    },
			    success:function(data){
					if(data.status == 1){
						self.lieb=data.data;
					}
			    }
			});
			
			record.get_record(hd_id,parentopenid,openid,'浏览了兑换记录页面',centre_id)
		},
		methods: {
			jump(){
				this.$router.push({path:'geren'})
			}
		}
	}
</script>

<style scoped>
	@import "../assets/css/gongyouyangshi.css";
	@import "../assets/css/animate.css";
	@import "../assets/css/laydate.css";
	
	p{
		margin: 0;
	}
	.jiagep img {
		width: 0.5rem;
		vertical-align: middle;
	}

	.zhuangt {
		float: right;
		width: 1rem;
		text-align: center;
		line-height: 0.48rem;
		border-radius: 0.06rem;
		font-size: 0.24rem;
		height: 0.45rem;
		color: #fff;
		margin-right: 0.1rem;
	}

	.zhuangt.ylq {
		background: #5cb85c;
	}

	.zhuangt.wlq {
		background: #428bca;
	}

	.hang1 {
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.w100 {
		width: 100%;
	}
</style>
