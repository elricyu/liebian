<template>
	<div id="app" v-cloak style="font-size: 0.4rem;">
		<div class="header">
			<img src="../assets/img/ding.png" alt="">
			<span>支付成功</span>
		</div>
		<div class="code">
			<span>消费码</span>
			<div class="right_txt">
				<div class="top_txt">{{list.spend_code}}</div>
				<div class="txt">(一定要妥善保存哦)</div>
			</div>
		</div>
		<div class="content">
			<div class="line">课程名称 <span>{{list.s_name}}</span></div>
			<div class="line">课程节数 <span>{{list.k_shu}}节</span></div>
			<div class="line">课程价格 <span>￥{{list.price}}</span></div>
		</div>
		
	</div>
</template>

<script>
	export default{
		data(){
			return{
				order_id:'',
				list:'',
			}
		},
		created() {
			this.order_id = this.$route.query.order_id
			
			this.get_pay()
		},
		methods:{
			get_pay(){
				let that = this
				axios.post('https://hdapi.codeclassroom.org/index/hd/back_pay',
					qs.stringify({
						order_id:that.order_id,
					}))
					.then(res => {
						if(res.data.status == 1){
							that.list = res.data.data 
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
		 margin: 25px 0 20px;
		 text-align: center;
		 img{
			 vertical-align: middle;
			 margin-right: 8px;
		 }
		 span{
			 vertical-align: middle;
		 }
	 }
	 
	 .code{
		 height: 76px;
		 margin: 0 20px;
		 background-color: #ff8f0c;
		 color: white;
		 border-top-left-radius: 5px;
		 border-top-right-radius: 5px;
		 span{
			 float: left;
			 padding-left: 18px;
			 font-size: 20px;
			 margin-top: 25px;
		 }
		 .right_txt{
			 float: right;
			 margin-right: 25px;
			 .top_txt{
				 font-size: 21px;
				 margin-top: 18px;
			 }
			 .txt{
				 font-size: 13px;
				 text-align: center;
			 }
		 }
	 }
	 
	 .content{
		 margin: 0 20px;
		 .line{
			 margin-top: 19px;
			 span{
				 float: right;
			 }
		 }
	 }
	 
</style>
