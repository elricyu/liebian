<template>
	<div id="app" v-cloak style="font-size: 0.4rem;">
		<div class="header">
			<img src="../assets/img/ding.png" alt="" style="width: 0.5rem;">
			<span>订单详情</span>
		</div>
		<div class="content">
			<div class="line">课程名称 <span>{{list.s_name}}</span></div>
			<div class="line">课程节数 <span>{{list.k_shu}}节</span></div>
			<div class="line">课程价格 <span>￥{{list.price}}</span></div>
		</div>
		
		<div class="btn">
			<button @click="pay">支付</button>
		</div>
	</div>
</template>

<script>
	export default{
		data(){
			return{
				user_id:'',
				hd_id:'',
				list:'',
			}
		},
		created() {
			this.user_id = this.$route.query.user_id
			this.hd_id = this.$route.query.hd_id
			this.centre_id = this.$route.query.centre_id
			
			this.get_pay()
		},
		methods:{
			get_pay(){
				let that = this
				axios.post('https://hdapi.codeclassroom.org/index/hd/pay',
					qs.stringify({
						hd_id:that.hd_id,
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
			// 支付
			pay(){
				let u = "https://wx.codeclassroom.org/pay/hdpay.php?hd_id="+this.hd_id+"&user_id="+this.user_id+"&c_id="+this.centre_id
				location.href = u
				// location.href=`https://wx.codeclassroom.org/kf/pay/hdpay.php?hd_id=${this.hd_id}&user_id=${this.user_id}&c_id=${this.centre_id}`
			}
		}
	}
</script>

<style lang="less" scoped>
	 .header{
		 margin: 20px;
		 img{
			 float: left;
			 margin-right: 10px;
		 }
	 }
	 .content{
		 margin: 0 20px;
		 .line{
			 margin-top: 17px;
			 span{
				 float: right;
			 }
		 }
	 }
	 
	 .btn{
		 display: block;
		 text-align: center;
		 margin-top: 60%;
		 button{
		 	width: 75%;
		 	height: 1rem;
		 	background-color: #ff8f0c;
		 	color: white;
		 	border: 0;
		 	border-radius: 0.1rem;
		 	font-size: 0.4rem;
		 }
	 }
</style>
