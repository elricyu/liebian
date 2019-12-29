<template>
	<div id="app" v-cloak style="font-size: 0.4rem;">
		<img src="../assets/raw_img/youjiangheader.png" style="width: 100%" alt="">
		<div class="container-fluid">
		    <div class="row">
		        <div class="col-xs-12" style="line-height: 1rem;color: #999;padding: 0;background: #fff">
		            <img src="../assets/raw_img/libao2.png" style="width: 1rem" alt="">
		            <span>进入人物页面，点击下方按钮，根据规定赢奖励</span>
		        </div>
		    </div>
		</div>
		<div class="container-fluid">
		    <div class="row" v-for="list1 in tasklist" @click="tiao(list1.hd_id)" style="border-bottom: 1px solid #ccc;padding-top:0.23rem;padding-bottom: 0.23rem;background: #fff">
		        <div class="col-xs-12" style="padding: 0 0.5rem">
		            <div class="media">
		                <a class="media-left" href="#" style="padding-right:0.26rem ">
		                    <img class="media-object" :src="list1.img" :onerror="noImg" style="width: 2rem">
		                </a>
		                <div class="media-body">
		                    <div class="fix">
		                        <div class="media-heading hang1" style="color:#1ba8ea;width: 3.94rem;float: left" v-cloak>{{list1.title}}</div>
		                        <span style="float: right;font-size: 0.7rem" class="fa fa-angle-right"></span>
		                    </div>
		                    <p style="height: 1rem;" class="hang2" v-cloak>{{list1.fx_describe}}</p>
		                </div>
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
				tasklist:'',
				centre_id:'',
				hd_id:'',
				openid:'',
				parentopenid:'',
				noImg:'this.src="'+ require('../assets/raw_img/default.jpg') +'"',
			}
		},
		created() {
			this.hd_id = $.cookie('hd_id')
			this.openid=$.cookie("openid")
			this.parentopenid=$.cookie("parentopenid")
			this.centre_id= $.cookie('centre_id')
			
			var self=this;
			$.ajax({
			    type : "post",
			    url : "https://hdapi.codeclassroom.org/index/hd/yjrw",
			    dataType : "json",
			    data:{
			        centre_id:self.centre_id
			    },
			    success : function(data) {
					if(data.status == 1){
						self.tasklist=data.data;
					}else{
						self.$toast.fail(data.msg)
					}
			    }
			})
			//浏览记录
			record.get_record(this.hd_id,this.parentopenid,this.openid,'浏览了有奖任务页面',this.centre_id)
		},
		methods: {
			tiao:function (id) {
				// this.$router.push({
				// 	path:'/',
				// 	query:{
				// 		parentopenid:0, 
				// 		centre_id:this.centre_id,
				// 		hdid:id
				// 	}
				// })
				
			    location.href="https://wx.codeclassroom.org/activity/index.php?parent_openid="+0+'&centre_id='+this.centre_id+'&hdid='+id
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
	.hang1{
	    overflow: hidden;
	    text-overflow:ellipsis;
	    white-space: nowrap;
	}
	.hang2{
	    overflow: hidden;
	    text-overflow: ellipsis;
	    display: -webkit-box;
	    -webkit-line-clamp: 2;
	    -webkit-box-orient: vertical;
	}
	.hang3{
	    overflow: hidden;
	    text-overflow: ellipsis;
	    display: -webkit-box;
	    -webkit-line-clamp: 3;
	    -webkit-box-orient: vertical;
	}
</style>
