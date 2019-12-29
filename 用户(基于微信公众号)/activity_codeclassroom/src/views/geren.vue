<template>
	<div id="app" v-cloak style="font-size: 0.4rem;">
		<div class="container-fluid">
			<div class="row" @click="jihuohui">
				<div class="col-xs-12">
					<div class="vipcard">
						<p>编程云</p>
						<p class="card">VIP</p>
						<div class="jihuo fix">
							<p style="float: left;margin-top: 0.1rem" id="kahao">点击激活</p>
							<span class="fa fa-qrcode" style="font-size: 0.5rem;float: right"></span>
						</div>
					</div>
				</div>
			</div>

			<div class="row toplist" style="margin-bottom: 0.16rem">
				<div class="col-xs-4">
					<div class="text-center toplistBox">
						<p class="color99">累计积分</p>
						<p id="leiji">0</p>
					</div>
				</div>
				<div class="col-xs-4">
					<div class="text-center toplistBox">
						<p class="color99">已用积分</p>
						<p id="yiyong">0</p>
					</div>

				</div>
				<div class="col-xs-4">
					<div class="text-center ">
						<p class="color99">可用积分</p>
						<p id="keyong">0</p>
					</div>
				</div>
			</div>

			<div class="row gerenlist" @click="jump('jifenshangc')">
				<div class="col-xs-12">
					<div class="genrenlistbox">
						<img src="../assets/raw_img/jifens.png" style="width: 0.75rem;float: left;" alt="">
						<span style="padding-left: 0.27rem;margin-top: 0.01rem;display: inline-block;">积分商城</span>
						<span class=" fa fa-angle-right youjian"></span>
					</div>
				</div>
			</div>

			<div class="row gerenlist" @click="jump('duihuanjl')">
				<div class="col-xs-12">
					<div class="genrenlistbox">
						<img src="../assets/raw_img/duihuanj.png" style="width: 0.75rem;float: left;" alt="">
						<span style="padding-left: 0.27rem;margin-top: 0.01rem;display: inline-block;">兑换记录</span>
						<span class=" fa fa-angle-right youjian"></span>
					</div>
				</div>
			</div>

			<div class="row gerenlist" @click="jump('youjiangrw')">
				<div class="col-xs-12">
					<div class="genrenlistbox">
						<img src="../assets/raw_img/youjiangr.png" style="width: 0.75rem;float: left;" alt="">
						<span style="padding-left: 0.27rem;margin-top: 0.01rem;display: inline-block;">有奖任务</span>
						<span class=" fa fa-angle-right youjian"></span>
					</div>
				</div>
			</div>

			<div class="row gerenlist" @click="jump('gerenxinx')">
				<div class="col-xs-12">
					<div class="genrenlistbox">
						<img src="../assets/raw_img/gerenx.png" style="width: 0.75rem;float: left;" alt="">
						<span style="padding-left: 0.27rem;margin-top: 0.01rem;display: inline-block;">个人信息</span>
						<span class=" fa fa-angle-right youjian"></span>
					</div>
				</div>
			</div>
			
			<div class="row gerenlist" @click="jump('all_course')">
				<div class="col-xs-12">
					<div class="genrenlistbox">
						<img src="../assets/raw_img/jifens.png" style="width: 0.75rem;float: left;" alt="">
						<span style="padding-left: 0.27rem;margin-top: 0.01rem;display: inline-block;">我的订单</span>
						<span class=" fa fa-angle-right youjian"></span>
					</div>
				</div>
			</div>
			<p style="line-height: 1.18rem" class="text-center color99">
				编程云®提供技术支持
			</p>

			<!--激活会员卡弹窗s-->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="background:rgba(0,0,0,0.3) ">
				<div class="modal-dialog" role="document" style="width: 90%;margin: 25% auto 0;">
					<div class="modal-content" style="border-radius: 0.1rem">
						<div class="modal-header" style="padding: 0.15rem 0.2rem 0.15rem 0.22rem;">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<p class="modal-title text-center" style="padding-top: 0.3rem;">激活会员卡</p>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-xs-12 color99" style="line-height: 34px">手机号</div>
								<div class="col-xs-12" style="margin-bottom: 0.3rem">
									<input type="text" class="form-control" maxlength="11" id="shoujihao" placeholder="请输入您的手机号">
								</div>
								<!--<div class="col-xs-8"><input type="text"maxlength="6" v-model="code" class="form-control" id="yanzhengm" placeholder="验证码"></div>-->
								<!--<div class="col-xs-4" style="padding-left: 0">-->
									<!--<input class="form-control huoquytanzheng" style="padding: 0;" :disabled="code_txt !== '获取验证码'"-->
									 <!--@click="Hyanzheng" type="button" id="btn" v-model="code_txt" />-->
								<!--</div>-->
								<div class="col-xs-12 color99" style="line-height: 34px;margin-top: 0.1rem;">出生日期</div>
								<div class="col-xs-12" style="margin-bottom: 0.3rem">
									<input type="date" id="bir" class="form-control" placeholder="请选择您的生日">
								</div>

								<div class="col-xs-12 color99" style="line-height: 34px">
									<input id="check1" type="checkbox" checked style="width: 0.4rem;height: 0.4rem;vertical-align: middle;margin-top: -0.1rem;"> 同意提供上述信息
								</div>
								<div class="col-xs-12 color99" style="margin-bottom: 0.3rem;font-size: 0.35rem">
									个人资料及信息保护遵循《微信隐私保护指引》
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<div class="text-center btn btn-primary btn-sm " style="display: block;border-radius: 0.36rem;color: #fff" @click="tijiao">提交</div>
						</div>
					</div>
				</div>
			</div>
			<!--激活会员卡弹窗e-->
		</div>
	</div>
</template>

<script>
	import "../assets/js/jquery.cookie.js"
	
	export default{
		data(){
			return{
				kdian:1,
				openid:'',
				centre_id:'',
				hd_id:'',
				code:'',//验证码
				code_txt:'获取验证码',
				code_num:60,
				parentopenid:'',
			}
		},
		created() {
			this.openid = $.cookie("openid")
			this.centre_id = $.cookie("centre_id")
			this.hd_id = $.cookie('hd_id')
			this.parentopenid = $.cookie("parentopenid")
			$.cookie('isManden', '1', {expires: 300})
			
			this.get_one()
			this.get_two()
			record.get_record(this.hd_id,this.parentopenid,this.openid,'浏览了个人页面',this.centre_id)
		},
		destroyed() {
			$('#myModa').modal('hide');
			$('.modal-backdrop').remove() //去掉遮罩层
			window.location.reload()
		},
		methods:{
			jump(u){
				this.$router.push({path:u})
			},
			get_one(){
				var that = this
				$.ajax({
					"type": "post",
					"url": "https://hdapi.codeclassroom.org/index/hd/hyk",
					"dataType": "json",
					"data": {
						"openid": that.openid,
						"hd_id": that.hd_id
					},
					success: function(res) {
						if(res.status == 1){
							if (res.data.is_active == 2) {
								that.kdian = 0;
								$('#kahao').html(res.data.vip_card);
								$('.vipcard').css('background', '#00a0e8')
							}
						}else{
							that.$toast(res.msg)
						}
					},
					error: function(data) {
						
					}
				});
			},
			get_two(){
				var that = this
				$.ajax({
					"url": "https://hdapi.codeclassroom.org/index/Hd/show_goods",
					type: "post",
					dataType: 'json',
					data: {
						centre_id: that.centre_id,
						openid: that.openid,
						hd_id: that.hd_id
					},
					success: function(data) {
						// if(data.status == 1){
							if (data.data.jf) {
								$('#leiji').html(data.data.jf.ljjf);
								$('#yiyong').html(data.data.jf.yyjf);
								$('#keyong').html(data.data.jf.kyjf);
							}
						// }
					}
				});
			},
			jihuohui() {
				if (this.kdian == 0) {
					return false;
				} else {
					$('#myModal').modal({
						"backdrop": "static"
					})
				}
			},
			Hyanzheng(){
				var yanzhengm = $('#yanzhengm').val();
				var shoujihao = $('#shoujihao').val();
				
				if (shoujihao == '') {
					$('#shoujihao').focus();
					return false;
				} else {
					// $.ajax({
					// 	"type": "post",
					// 	"url": "https://bbss.gymbaby.org/dx/duanxinfs.php",
					// 	"dataType": "json",
					// 	"data": {
					// 		"phone": 'MjM4' + ',' + shoujihao
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
                            "phone": shoujihao
                        },
                        success: function(data) {
                            //alert("获取成功");
                        },
                        error: function(data) {
                            //alert("失败");
                        }
                    });
					
					var that = this
					that.code_num = 60
					var interval = setInterval(function(){
						that.code_num--
						if(that.code_num == 0){
							clearInterval(interval)
						}
					},1000)
				}
			},
            //手机号码正则
            isTel(phone) {
                // let myreg = /^[1][3,4,5,7,8][0-9]{9}$/;
                let myreg = /^[1]\d{10}$/;
                if (!myreg.test(phone)) {
                    return false
                } else {
                    return true;
                }
            },
			tijiao(){
				var yanzhengm = $('#yanzhengm').val();
				var shoujihao = $('#shoujihao').val();
				if ($('#check1').get(0).checked == false) {
					return false;
				}
				if (shoujihao == '' || !this.isTel(shoujihao)) {
					$('#shoujihao').focus();
					alert("手机号验证失败")
					return false;
				}
				// if (yanzhengm == '') {
				// 	$('#yanzhengm').focus();
				// 	return false;
				// }
				
				var that = this
				$.ajax({
					"type": "post",
					"url": "https://hdapi.codeclassroom.org/index/hd/jh",
					"dataType": "json",
					"data": {
						"phone": shoujihao,
						"openid": that.openid,
						"birthday": $('#bir').val(),
						"hd_id": that.hd_id,
						"code": yanzhengm
					},
					success: function(data) {
						if (data.status == 1) {
							that.$toast.success(data.msg)
							setTimeout(()=>{
								$('.vipcard').css('background', '#00a0e8')
								$('#myModal').modal('hide')
							},1000)
						} else {
							that.$toast.fail(data.msg)
						}
					},
					error: function(data) {
						//alert("失败");
					}
				});
			}
		},
		watch:{
			code_num(){
				if(this.code_num >= 1){
					this.code_txt = this.code_num + '秒后重试'
				}else{
					this.code_txt = '获取验证码'
				}
			}
		}
	}
</script>

<style scoped>
	@import "../assets/css/gongyouyangshi.css";
	@import "../assets/css/animate.css";
	@import "../assets/css/laydate.css";
	/* @import "../assets/css/font-awesome.css"; */
	
	.btn-sm, .btn-group-sm > .btn{
		font-size: 0.35rem;
		padding: 0.2rem;
	}
	.modal-footer{
		padding: 0.2rem 0.5rem 0.6rem;
	}
	.form-control{
		font-size: 0.4rem;
	}
	.modal-body{
		padding: 0.1rem 0.5rem;
	}
	
	.vipcard{
	    width: 90%;
	    margin:0.25rem auto ;
	    background:#ccc;
	    color: #fff;
	    padding: 0.4rem;
	    border-radius: 0.35rem;
	}
	.card{
	    font-size: 1rem;
	}
	.jihuo{
	    padding-top: 0.5rem;
	}
	.toplist .col-xs-4{
	    padding-left: 0;
	    padding-right: 0;
	    padding-top: 0.3rem;
	    padding-bottom: 0.3rem;
	    box-sizing: border-box;
	    background: #fff;
	}
	.toplistBox{
	    border-right: 2px solid #c2c2c2;
	}
	.color99{
	    color: #999;
	}
	.color66{
	    color: #666;
	}
	.gerenlist .col-xs-12{
	    padding-left: 0.38rem;
	    padding-right: 0.38rem;
	    background: #fff;
	    margin-bottom:3px ;
	    padding-top: 0.3rem;
	    padding-bottom: 0.3rem;
	}
	.genrenlistbox{
	    line-height:0.7rem;
	}
	.youjian{
	    float: right;
	    line-height: 0.7rem;
	    font-size: 0.5rem;
	    padding-right: 0.27rem;
	    color: #999;
	}
	.huoquytanzheng{
	    border-color:#06aaed;
	    color: #06aaed;
	
	    text-align: center;
	}
</style>
