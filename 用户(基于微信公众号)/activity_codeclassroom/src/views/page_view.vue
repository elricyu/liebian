<template>
	<div id="app" v-cloak style="font-size: 0.4rem;">
		<div class="line" v-for="item in pageView" @click="look(item.open_id)">
			<img :src="item.head_image" alt="">
			<span class="name">{{item.user_name}}</span> <span class="right_txt">浏览了{{item.num}}次</span>
			<div style="clear: both;"></div>
		</div>
		<div v-if="pageView == ''" style="text-align: center;margin-top: 30%;">
			<img src="../assets/img/nodata.jpg" alt="" style="width: 50%;margin-bottom: 25px;">
			<div>暂无数据~</div>
		</div>
		
		
		<!-- 浏览详情弹框 -->
		<transition name="custom-classes-transition" enter-active-class="animated tada" leave-active-class="animated bounceOutRight">
			<div class="vip_box" v-if="isLook">
				<div class="order_div">
				   <img src="../assets/img/tu6.png" class="right_img">
				   <div class="apply_txt">访问详情</div>
				   <div class="line" style="padding: 20px 30px;">
						<img :src="title_list.head_image" alt="">
						<span class="name">{{title_list.user_name}}</span> <span class="right_txt">浏览了{{title_list.num}}次</span>
						<div style="clear: both;"></div>
				   </div>
				   <div class="content" style="max-height: 30vh;overflow-y: scroll;">
					   <div class="box" v-for="item in list">
							<span style="float: none;display: inline-block;width: 3rem;">{{item.cz_name}}</span>
							<span>{{item.create_time}}</span>
					   </div>
				   </div>
				</div>
				<div style="text-align: center;overflow: hidden;">
					<img src="../assets/img/close1.png" @click="isLook = false" style="height: 3.8rem;margin-top: -1.8rem;">
				</div>
			</div>
		</transition>
	</div>
</template>

<script>
	export default{
		data(){
			return{
				isLook:false,//浏览详情弹框
				hd_id:'',
				openid:'',
				pageView:[],
				title_list:'',
				list:[],
				centre_id:'',
				parentopenid:'',
			}
		},
		created() {
			this.centre_id = $.cookie("centre_id")
			this.hd_id = $.cookie('hd_id')
			this.openid = $.cookie("openid")
			this.parentopenid = $.cookie("parentopenid")
			$.cookie('isManden', '1', {expires: 300})
			
			this.get_data()
			//浏览记录
			record.get_record(this.hd_id,this.parentopenid,this.openid,'浏览了记录页面',this.centre_id)
		},
		methods:{
			get_data(){
				let that = this
				axios.post('https://hdapi.codeclassroom.org/index/hd/jl',
					qs.stringify({
						hd_id:that.hd_id,
						openid:that.openid,
					}))
				    .then(res => {
						if(res.data.status == 1){
							that.pageView = res.data.data
						}
					})
					.catch(err => {
						console.log(err)
					})
			},
			//查看详情
			look(i){
				let that = this
				axios.post('https://hdapi.codeclassroom.org/index/hd/jldj',
					qs.stringify({
						hd_id:that.hd_id,
						openid:i,
					}))
				    .then(res => {
						that.isLook = true
						if(res.data.status == 1){
							that.title_list = res.data.data.zong
							that.list = res.data.data.fen
						}else{
							that.$toast.fail(res.data.msg)
						}
					})
					.catch(err => {
						console.log(err)
					})
			},
		}
	}
</script>

<style lang="less" scoped>
	 // @import "../assets/css/animate.css@3.5.1.css";
	
	 .line{
		 padding: 20px 25px 0;
		 border-bottom: 0.01rem solid gainsboro;
		 img{
			 width: 35px;
			 float: left;
		 }
		 .name{
			display: inline-block;
			margin-top: 17px;
			margin-left: 18px;
		 }
		 .right_txt{
			 float: right;
			 margin-top: 19px;
			 color: gray;
			 font-size: 15px;
		 }
	 }
	 
	 .vip_box{
	 	position: fixed;
	 	top: 0;
	 	left: 0;
	 	width: 100%;
	 	height: 100%;
	 	background: rgba(0,0,0,0.5);
	 	z-index: 999;
		.order_div{
			border-radius: 0.1rem;
			width: 85%;
			margin: 30% auto 0;
			background-color: white;
			.right_img{
				width: 48px;
				position: fixed;
				top: 11%;
				right: 50px;
			}
			.apply_txt{
				font-size: 20px;
				font-weight: bold;
				text-align: center;
				padding-top: 18px;
			}
			.content{
				padding: 0 25px 25px;
				border-top: 0.01rem solid gainsboro;
				.box{
					 margin-top: 12px;
					 span{
						 float: right;
					 }
				}
			}
		}
	 }
</style>
