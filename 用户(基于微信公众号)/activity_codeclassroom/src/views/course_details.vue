<template>
	<div id="app" v-cloak style="font-size: 0.4rem;">
		<div class="header">{{order_list.status == 1 ? '待使用' : '已使用'}}</div>
		<div class="big_title">{{order_list.jc_name}}</div>
		<div class="content">
			<div class="line" style="font-size: 0.45rem;font-weight: bold;">商品信息</div>
			<div class="line">课时包名称 <span>{{order_list.s_name}}</span></div>
			<div class="line">课程数量 <span>{{order_list.k_shu}}节</span></div>
			<div class="line">课程价格 <span>{{order_list.price}}元</span></div>
			
			<!-- 已使用 -->
			<div class="line" v-if="order_list.status == 2">耗课时 <span>{{order_list.xiaohao}}节</span></div>
			<div class="line" v-if="order_list.status == 2">使用日期 <span>{{order_list.create_time}}</span></div>
			
			<div class="line">使用方式 <span>持消费码到中心直接使用</span></div>
			<!-- 已使用 -->
			<div class="line" style="font-size: 0.45rem;font-weight: bold;">
				消费码: <span :class="order_list.status == 1 ? 'stay' : 'yet'">{{order_list.spend_code}}</span>
			</div>
			<div class="line">中心名称 <span>{{order_list.centre}}</span></div>
			<div class="line">中心地址 <span>{{order_list.site}}</span></div>
			<div class="line">中心电话 <span>{{order_list.c_phone}}</span></div>
			<div class="line" style="font-size: 0.45rem;font-weight: bold;">订单信息</div>
			<div class="line">联系人 <span>{{order_list.l_name}}</span></div>
			<div class="line">电话 <span>{{order_list.l_phone}}</span></div>
			<div class="line">参与时间 <span>{{order_list.hd_start_time}}</span></div>
		</div>
		
	</div>
</template>

<script>
	export default{
		data(){
			return{
				order_id:'',
				order_list:'',
				centre_id:'',
				hd_id:'',
				openid:'',
				parentopenid:'',
			}
		},
		created() {
			this.order_id = this.$route.query.order_id
			this.centre_id = $.cookie("centre_id")
			this.hd_id = $.cookie('hd_id')
			this.openid = $.cookie("openid")
			this.parentopenid = $.cookie("parentopenid")
			
			this.get_order()
			//浏览记录
			record.get_record(this.hd_id,this.parentopenid,this.openid,'浏览了订单详情页',this.centre_id)
		},
		methods:{
			get_order(){
				let that = this
				axios.post('https://hdapi.codeclassroom.org/index/hd/order_dateli',
					qs.stringify({
						order_id:that.order_id,
					}))
				    .then(res => {
						if(res.data.status == 1){
							that.order_list = res.data.data[0]
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
	 .header{
		margin: 22px;
		font-size: 0.45rem;
	 }
	 .big_title{
		 color: #ff8f0c;
		 text-align: center;
		 margin-top: 30px;
		 margin-bottom: 5px;
		 font-size: 0.5rem;
	 }
	 
	 .back{
		 margin: 0 20px;
		 height: 55px;
		 line-height: 55px;
		 span{
			margin-left: 15px;
			font-size: 0.45rem;
			color: white;
		 }
	 }
	 
	 .content{
		 margin: 0 25px 20px;
		 .line{
			 padding-top: 16px;
			 span{
				 float: right;
			 }
			 .stay{
				 color: rgb(255, 143, 12);
			 }
			 .yet{
				 color: gray;
				 text-decoration: line-through;
			 }
		 }
	 }
	 
</style>
