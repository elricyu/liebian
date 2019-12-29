<template>
	<div id="app" v-cloak style="font-size: 0.4rem;">
		<div class="header">
			<span v-for="(item,index) in title_list" @click="tabs(index+1)" :class="tab_index == index+1 ? 'active' : ''" :key="index">{{item.name}}</span>
		</div>
		
		<div class="content" v-for="(item,index) in all_course" :key="index">
			<div @click="jump('course_details',item.order_id)">
				<div v-if="item.status == 1" class="back" :style="{ background:'url('+ backlog +') no-repeat',backgroundSize:'100% 100%' }">
					<span>{{item.jc_name}}</span>
				</div>
				<div v-if="item.status == 2" class="back" :style="{ background:'url('+ already +') no-repeat',backgroundSize:'100% 100%' }">
					<span>{{item.jc_name}}</span>
				</div>
				<div class="line">课程名称 <span>{{item.s_name}}</span></div>
				<div class="line">课程节数 <span>{{item.k_shu}}节</span></div>
				<div class="line">支付金额 <span>￥{{item.price}}</span></div>
				<div class="line" v-if="item.status == 2">耗课数 <span>{{item.xiaohao}}节</span></div>
				<div class="line" v-if="item.status == 2">活动时间 <span>{{item.hd_start_time}}</span></div>
				<div class="line">参与时间 <span>{{item.create_time}}</span></div>
				<div class="line" style="font-size: 0.55rem;font-weight: bold;">
					消费码: <span :class="item.status == 1 ? 'not' : 'yet'">{{item.spend_code}}</span>
				</div>
			</div>
			<div class="line" style="margin-top: 60px;">分享浏览 <span @click="jump('page_view',item.order_id)" class="btn">查看</span></div>
		</div>
		<div v-if="all_course == ''" style="text-align: center;margin-top: 25%;">
			<img src="../assets/img/nodata.jpg" alt="" style="width: 50%;margin-bottom: 25px;">
			<div>暂无数据~</div>
		</div>
	</div>
</template>

<script>
	export default{
		data(){
			return{
				title_list:[
					{name:'全部'},
					{name:'待使用'}
				],
				tab_index:1,
				backlog: require('../assets/img/dai.png'),
				already: require('../assets/img/yi.png'),
				centre_id:'',
				openid:'',
				hd_id:'',
				parentopenid:'',
				all_course:[],
			}
		},
		created() {
			this.centre_id = $.cookie("centre_id")
			this.hd_id = $.cookie('hd_id')
			this.openid=$.cookie("openid")
			this.parentopenid=$.cookie("parentopenid")
			
			this.get_course()
			//浏览记录
			record.get_record(this.hd_id,this.parentopenid,this.openid,'浏览了订单页面',this.centre_id)
		},
		methods:{
			get_course(){
				let that = this
				axios.post('https://hdapi.codeclassroom.org/index/hd/order_info',
					qs.stringify({
						centre_id:that.centre_id,
						openid:that.openid,
						type:that.tab_index
					}))
				    .then(res => {
						if(res.data.status == 1){
							that.all_course = res.data.data
						}
					})
					.catch(err => {
						console.log(err)
					})
			},
			//课程
			tabs(i){
				this.tab_index = i
				if(i == 2){
					this.get_course()
				}else{
					this.get_course()
				}
			},
			//详情页
			jump(u,i){
				this.$router.push({
					path:u,
					query:{
						order_id:i
					}
				})
			}
		}
	}
</script>

<style lang="less" scoped>
	 .header{
		margin: 22px;
		span{
			margin-right: 25px;
		}
		.active{
			color: #ff8f0c;
		}
	 }
	 
	 
	 .content{
		 margin: 0 20px 20px;
		 padding-bottom: 20px;
		 box-shadow: 1px 1px 5px gray;
		 .back{
			 height: 55px;
			 line-height: 55px;
			 span{
				margin-left: 15px;
				font-size: 18px;
				color: white;
			 }
		 }
		 .line{
			 padding: 16px 12px 0;
			 span{
				 float: right;
			 }
			 .btn{
				border: 1px solid #ff8f0c;
				padding: 6px 15px;
				border-radius: 5px;
				color: #ff8f0c;
				vertical-align: middle;
				margin-top: -7px;
			 }
			 .not{
				 color: #ff8f0c;
			 }
			 .yet{
				 text-decoration: line-through;
			 }
		 }
	 }
	 
</style>
