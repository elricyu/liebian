<template>
	<div id="app" v-cloak style="font-size: 0.4rem;">
		<img src="../assets/raw_img/jifenheader.png" style="width: 100%" alt="">
		<div class="container-fluid">
			<div class="row toplist" style="margin-bottom: 0.16rem">
				<div class="col-xs-4">
					<div class="text-center toplistBox">
						<p class="color99">累计积分</p>
						<p v-cloak>{{jifen.ljjf}}</p>
					</div>
				</div>
				<div class="col-xs-4">
					<div class="text-center toplistBox">
						<p class="color99">已用积分</p>
						<p v-cloak>{{jifen.yyjf}}</p>
					</div>

				</div>
				<div class="col-xs-4">
					<div class="text-center ">
						<p class="color99">可用积分</p>
						<p v-cloak>{{jifen.kyjf}}</p>
					</div>
				</div>
			</div>

			<div class="row" style="background: #fff;">
				<div class="fix" style="padding-left: 0.2rem;padding-right: 0.2rem">
					<div class="col-xs-4 shangBox" v-on:click="tanchu(shang.id)" v-for="shang in liebiao">
						<div class="tuBox">
							<img :src="shang.imgpath" style="width: 100%" alt="">
						</div>
						<p class="hang1 text-center" v-cloak>{{shang.good_name}}</p>
						<p class="text-center jiagep"><img src="../assets/raw_img/db.jpg" alt=""> <span v-cloak>{{shang.rules}}积分</span></p>
					</div>
				</div>
			</div>
		</div>

		<!--优惠券弹窗s-->
		<div class="modal fade" id="myModa3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="background:rgba(0,0,0,0.3);font-size: 0.28rem ">
			<div class="modal-dialog" role="document" style="width:90%;margin: 25% auto 0">
				<div class="modal-content" style="border-radius: 0.1rem">
					<div class="modal-header fix" style="padding: 0.2rem 0.2rem 0 0.22rem;border: none">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size: 0.7rem;">
							<span aria-hidden="true">&times;</span>
						</button>
						<!--<h4 class="modal-title text-center colorhua fon30" id="myModalLabel" style="height: 0.8rem;line-height: 0.8rem">分享方式</h4>-->
					</div>
					<div class="modal-body" style="padding-top: 0">
						<p class="text-center libiao1" v-cloak></p>
						<div class="text-center" style="padding-top: 0.1rem;border-bottom:4px dashed #eee">
							<div style="margin: 0 auto;width: 1.62rem;height: 1.62rem;overflow: hidden">
								<img :src="xiangq.imgpath" class="w100" style="width: 100%" alt="">
							</div>

							<p class="text-center libiao1 " style="font-size: 0.4rem;padding-top: 0.1rem;padding-bottom: .1rem"> <span
								 v-cloak>{{xiangq.good_name}}</span> </p>
							<p class="text-center color66" style="font-size: 0.5rem;padding-bottom: 0.2rem">库存: <span v-cloak>{{xiangq.number}}</span>
								&nbsp;&nbsp; 所需积分: <span v-cloak>{{xiangq.rules}}</span>
							</p>
						</div>
						<div style="font-size: 0.4rem;">
							<p style="line-height:0.8rem">商品简介</p>
							<p style="line-height: 0.4rem" class="color99" v-cloak>{{xiangq.notes ? xiangq.notes : '暂无'}}</p>
							<!--<p style="line-height:0.8rem ">有效期限</p>-->
							<!--<p style="font-size: 0.26rem;line-height: 0.4rem" class="color99"><span v-cloak></span>  - <span v-cloak></span></p>-->
						</div>

					</div>
					<div class="modal-footer fix" style="font-size: 0.35rem;">
						<!--<button type="button" style="display: block;width: 100%"  class="btn btn-primary" data-dismiss="modal">去做任务</button>-->
						<div class="renwubtn jigen fl" style="width: 45%;" data-dismiss="modal" @click="jump">去赚积分</div>
						<div class="renwubtn colH fr" style="width: 45%;" v-if="xiangq.jf==2">积分不足</div>
						<div class="renwubtn fr" :class="xiangq.number<1 ? 'noClick' : ''" style="width: 45%;" v-if="xiangq.jf==1" v-on:click="duihuan(xiangq.id,xiangq.good_name)">兑换</div>
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
				openid:'',
				centre_id:'',
				liebiao:'',
				xiangq:'',
				jifen:'',
				hd_id:'',
				parentopenid:'',
			}
		},
		created() {
			this.openid = $.cookie("openid")
			this.centre_id = $.cookie("centre_id")
			this.hd_id = $.cookie('hd_id')
			this.parentopenid = $.cookie("parentopenid")
			
			var self = this;
			$.ajax({
			    "url":"https://hdapi.codeclassroom.org/index/Hd/show_goods",
			    type:"post",
			    dataType:'json',
			    data:{
			        centre_id:self.centre_id,
			        openid:self.openid,
			        hd_id:self.hd_id
			    },
			    success:function(data){
					if(data.status == 1){
						self.liebiao=data.data.info;
						self.jifen=data.data.jf;
					}else{
						self.$toast.fail(data.msg);
					}
			    }
			});
			
			record.get_record(this.hd_id,this.parentopenid,this.openid,'浏览了积分商城页面',this.centre_id)
		},
		methods: {
			 jump(){
				  this.$router.push({
					path:'/',
					query:{
						parentopenid:this.parentopenid,
						centre_id:this.centre_id,
						hdid:this.hd_id,
					}
				 })
			 },
			 duihuan:function (aid,name) {
				this.txt = `兑换了:${name}`
				
			    var self=this;
			    $.ajax({
			        "url":"https://hdapi.codeclassroom.org/index/Hd/deal",
			        type:"post",
			        dataType:'json',
			        data:{
			            centre_id:self.centre_id,
			            openid:self.openid,
			            id:aid,
			            hd_id:self.hd_id
			        },
			        success:function(data){
			            if(data.status==1){
			                self.$toast.success("兑换成功")
							$('#myModa3').modal('hide')
							record.get_record(self.hd_id,self.parentopenid,self.openid,self.txt,self.centre_id)
			            }else{
			                self.$toast.fail(data.error)
			            }
			
			        }
			    });
			},
			tanchu:function (iid) {
			    $('#myModa3').modal({"backdrop":"static"})
			    var self=this;
				
			    $.ajax({
			        "url":"https://hdapi.codeclassroom.org/index/Hd/deta",
			        type:"post",
			        dataType:'json',
			        data:{
			            centre_id:self.centre_id,
			            openid:self.openid,
			            id:iid,
			            hd_id:self.hd_id
			        },
			        success:function(data){
			            self.xiangq=data.data;
			        }
			    });
			}
		}
	}
</script>

<style scoped>
	@import "../assets/css/gongyouyangshi.css";
	@import "../assets/css/animate.css";
	@import "../assets/css/laydate.css";
	
	.noClick{
		pointer-events: none;
		background: gray !important;
	}
	.toplist .col-xs-4 {
	    padding-left: 0;
	    padding-right: 0;
	    padding-top: 0.3rem;
	    padding-bottom: 0.3rem;
	    box-sizing: border-box;
	    background: #fff;
	}
	.toplistBox {
	    border-right: 2px solid #c2c2c2;
	}
	.w100{
		width:100%;
	}
	body{
	    background: #eee;
	    font-size: 0.28rem;
	}
	
	.color66{
	     color: #666;
	 }
	.color99{
	    color: #999;
	}
	.libiao1{
	    color:#f7882e ;
	}
	.libiao2{
	    color: #06aaed;
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
	
	.tuBox{
	    width: 1.77rem;
	    height: 1.77rem;
	    margin: 0 auto;
	    overflow: hidden;
	    margin-bottom: 0.1rem;
	}
	
	.shangBox{
	    padding:0.2rem 0 0.2rem;
	}
	.jiagep{
	    padding-top: 0.1rem;padding-bottom: 0.1rem
	}
	.jiagep img{
	    width: 0.5rem;
		margin-right: 0.05rem;
		vertical-align: middle;
	}
	.renwubtn{
	    width: 100%;
	    height: 1rem;
	    line-height: 1rem;
	    text-align: center;
	    color: #fff;
	    background: #00a0e8;
	    border-radius: 0.2rem;
	}
	.jigen{
	    box-sizing: border-box;
	    border: 1px solid #f7882e;
	    color: #f7882e;
	    background: none;
	}
	.color99{
	    color: #999;
	}
	.colH{
	    background: #ccc;
	}
	.libiao1{
	    color:#f7882e ;
	}
</style>
