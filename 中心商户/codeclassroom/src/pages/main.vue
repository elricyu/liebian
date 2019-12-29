<template>
	<div id="app">


		<div class="box_z"  style="background:red;height:200px;">
			<div class="youce">
				<div class="right_top">
					<div class="t_right_box">
						<el-dropdown @command="handleCommand" style="cursor: pointer;" class="box_right">
							<span class="el-dropdown-link" style="line-height: 40px;">{{username}}<i class="el-icon-arrow-down el-icon--right"></i></span>
							<el-dropdown-menu slot="dropdown">
								<el-dropdown-item command="a">
									<p @click="login_out_sys()">注销</p>
								</el-dropdown-item>
								<!--<el-dropdown-item command="b" @click="tiaoye('geren')">个人中心</el-dropdown-item>-->
							</el-dropdown-menu>
						</el-dropdown>
					</div>
				</div>

				<div class="centerbox">
					<router-view></router-view>
				</div>

				<div class="right_bottom" style="">

				</div>
			</div>
		</div>



		<!--帮助弹窗s-->
		<el-dialog :visible.sync="dialogTableVisible2" width="698px" class="tanchuang2" :close-on-click-modal=false>
			<div slot="title" class="dialog-footer" style="text-align: center;position: relative;padding: 10px 20px 10px 75px;border-bottom: 1px solid #f1f1f1">
				<img src="/static/img/tuyisheng.png" style="width: 105px;height: 108px;position: absolute;left:234px;top:-70px;z-index: 999"
				 alt="">
				小助手
			</div>
			<div id="liaobox">
				<table id="bangzhua" width="100%">
					<tr>
						<div class="clearfloat" style="width: 100%;margin-bottom: 20px">
							<div style="float: left;max-width: 70%;">
								<div class="yongtou2">
									<img src="/static/img/shabitoux.png" style="width: 50px" alt="">
								</div>
								<img style="width: 7px;margin-top: 20px;float: left" src="/static/img/sanjiaolb.png" alt="">
								<div class="yLbox2">您有什么需要询问的吗？</div>
							</div>
						</div>
					</tr>
					<tr>
						<div class="clearfloat" style="width: 100%;margin-bottom: 20px">
							<div style="float: right;max-width: 70%;">
								<div class="yongtou"><img src="/static/img/yonghuh.png" style="width: 100%" alt=""> </div>
								<img style="width: 7px;margin-top: 20px;float: right" src="/static/img/sanjiaorh.png" alt="">
								<div class="yLbox">AAAAAAAAAAAAAA</div>
							</div>
						</div>
					</tr>
					<tr>
						<div class="clearfloat" style="width: 100%;margin-bottom: 20px">
							<div style="float: left;max-width: 70%;">
								<div class="yongtou2">
									<img src="/static/img/shabitoux.png" style="width:50px;" alt="">
								</div>
								<img style="width: 7px;margin-top: 20px;float: left" src="/static/img/sanjiaolb.png" alt="">
								<div class="yLbox2">您的问题暂时没有收录</div>
							</div>
						</div>
					</tr>

				</table>
			</div>

			<div slot="footer" class="clearfloat">
				<div class="modal-footer">
					<textarea name="" id="neirong1" v-model="question" style="width: 500px;height: 70px;resize: none;float: left;border: none"
					 placeholder="请输入需要帮助的问题"></textarea>
					<div class="fasongBtn" style="width: 120px;" @click="bangzhu">
						发送
					</div>
					<!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>-->
				</div>
			</div>

		</el-dialog>
		<!--帮助弹窗e-->

		<!-- Modal -->
		<div class="modal fade" id="gotovi" tabindex="-1" role="dialog" style="background-color:transparent" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document" style="width: 300px;height:100px">
				<div class="modal-content">
					<div class="modal-header" style="padding: 15px 15px 0 15px">
						<!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
						<h5 class="modal-title" style="color: black;">是否确定访问VI系统?</h5>
					</div>
					<!--<div class="modal-body" style="color:black;font-size: 16px;font-weight: normal">-->
					<!--是否确定访问VI系统?-->
					<!--</div>-->
					<div class="modal-footer" style="text-align: center">
						<a :href="'http://vi.gymbaby.org/index/crmtovi?u='+username" style="margin:0" target="_blank" @click="closegotovi"
						 class="btn btn-default btn-sm ">确定</a>
						<button type="button" class="btn btn-default  btn-sm" style="margin:0" data-dismiss="modal">关闭</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Modalgw -->
		<div class="modal fade" id="getgw" tabindex="-1" role="dialog" style="background-color:transparent" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document" style="width: 300px;height:100px">
				<div class="modal-content">
					<div class="modal-header" style="padding: 15px 15px 0 15px">
						<!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
						<h5 class="modal-title" style="color: black;">是否确定访问官网管理后台?</h5>
					</div>
					<!--<div class="modal-body" style="color:black;font-size: 16px;font-weight: normal">-->
					<!--是否确定访问VI系统?-->
					<!--</div>-->
					<div class="modal-footer" style="text-align: center">
						<a :href="'https://zcbapi.gymbaby.cn/manage/index.html?token='+token" style="margin:0" target="_blank" @click="getgwdel" class="btn btn-default btn-sm ">确定</a>
						<button type="button" class="btn btn-default  btn-sm" style="margin:0" data-dismiss="modal">关闭</button>
					</div>
				</div>
			</div>
		</div>


		<!-- 智能客服 -->
		<div class="service" v-if="service_dialog">
			<div class="content">
				<div class="header2">
					<img src="/" alt="">
					<div><img src="../../static/img/kefu/kefu.png" alt=""></div>
					<span>智能顾问</span>
					<img @click="service_dialog = false" class="close_img" src="../../static/img/kefu/close.png" alt="">
				</div>
				<div class="left_box">
					<!-- 智能客服 -->
					<div class="chat_box" ref="chat_boxes">
						<div class="reply" v-for="(item,index) in hello_list">
							<div class="img"><img src="../../static/img/kefu/kefu.png" alt=""></div>
							<div class="name">智能顾问</div>
							<div style="height: 5px;"></div>
							<div class="chatroom">{{item}}</div>
							<div style="height: 20px;clear: both;"></div>
						</div>

						<div v-for="(item,index1) in news">
							<div style="margin-top: 18px;" v-if="item.type==1">
								<div class="ask">
									<div class="img"><img src="../../static/img/kefu/ren.png" alt=""></div>
									<div class="name">ID_654545335</div>
									<div style="height: 5px;"></div>
									<div class="chatroom">{{item.name}}</div>
								</div>
								<div style="height: 1px;clear: both;"></div>
							</div>

							<div class="reply" v-if="item.type==2">
								<div class="img"><img src="../../static/img/kefu/kefu.png" alt=""></div>
								<div class="name">智能顾问</div>
								<div style="height: 5px;"></div>
								<div class="chatroom">
									<span v-if="item.txt">{{item.txt}}</span>
									<a :href="item.downloads" v-if="item.downloads" download="附件">下载附件</a>
									<a :href="item.url" v-if="item.url" target="_blank">跳转详情页</a>
								</div>
								<div style="height: 20px;clear: both;"></div>
							</div>

							<div class="issue" v-if="item.type==3">
								<ul v-for="(item2,index2) in item.data" v-if="index2<pageSize">
									<li v-for="(item3,index3) in item2.arr" @click="answer(item3.w_id)">
										<span></span>{{item3.wen}}
									</li>
								</ul>
								<div style="height: 1px;clear: both;"></div>

								<div class="point" v-if="index1==news.length-1&&load_more==1">
									<span @click="pageSize+=3">
										<img src="../../static/img/kefu/point.png" alt=""><a>点击加载更多</a>
									</span>
								</div>
								<div style="height: 1px;clear: both;"></div>
							</div>
						</div>
						<div style="height: 200px;"></div>
					</div>

					<div style="background-color: white;">
						<div class="page">
							<ul class="right_top2" v-if="list2.length !== 0">
								<li v-for="(item,index) in list2" @click="answer(item.w_id)">{{item.wen}}</li>
							</ul>
						</div>
						<div class="footer">
							<textarea v-model="sendText" @keyup.enter="sendMsg" class="msg" cols="30" rows="10"></textarea>
							<button @click="sendMsg" @keyup.enter="sendMsg" class="btn2">发送<span class="trigon"></span></button>
						</div>
					</div>
				</div>

				<div class="right_box">
					<ul class="right_top2">
						<li v-for="(item,index) in list3" :class="index==c_index ? 'active2' : ''" @click="choose(item.type,index)">
							{{item.type}}
						</li>
					</ul>
					<div style="height: 15px;clear: both;"></div>
					<ul class="right_bottom2">
						<li>
							<img class="hot_img" src="../../static/img/kefu/hot.png" alt=""><span>热门问题</span>
						</li>
						<li v-for="(item,index) in list4" @click="answer(item.w_id)">{{index+1}}. {{item.wen}}</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- 通告 -->
		<el-dialog :visible.sync="noticedialog" width="60%" :close-on-click-modal='false' center>
			<div style="width: 100%;text-align: center;font-weight: bold;font-size: 16px;padding-bottom: 10px;">{{tong.title}}</div>
			<div style="max-height:400px;min-height: 150px;overflow-x:scroll;">
				<div v-html="tong.content"></div>
			</div>
			<span v-if="tong.url">
			<a :href="tong.url" target="view_frame">查看详情</a>	</span>
		</el-dialog>
		
		<!-- <chatRoom></chatRoom> -->
	
	</div>
</template>

<script>
	// import chatRoom from '../components/chat.vue'
	export default {
		data() {
			return {
				xuan: '',
				dialogTableVisible: false,
				dialogTableVisible2: false,
				noticedialog: false,
				tong: '',
				token: '',
				quanxian: '',
				auth: [],
				username: '',
				question: '',
				centre: '',
				idx: 0, //判断选中样式
				glf_auth: '',
				centre_id: '',
				// 智能客服
				service_dialog: false, //客服系统
				pageSize: 3, //显示问题数量
				chatText: '', //聊天内容
				replyText: '', //客服回复
				replyUrl: '', //跳转到问题页面
				sendText: '',
				news: [],
				list2: [], //选择问题
				list3: [], //常见问题
				list4: [], //热门问题
				hello_list: [], //问候语
				load_more: null, //是否展示加载更多
				c_index: '',
			};
		},
		components:{
			// chatRoom,
		},
		mounted() {
			let that = this
			that.token = that.$cookies.get('token');
			$.ajax({
				url: "https://hdapi.codeclassroom.org/index/index/get_user_auth",
				type: "post",
				dataType: "json",
				async: false,
				data: {
					token: that.token
				},
				success: function(data) {
					that.auth = data.auth;
					that.quanxian = data.jauth;
					that.username = data.username
					that.centre = data.centre;
					that.glf_auth = data.glf_auth
					that.centre_id = data.centre_id
					if (data.jauth == 4) {
						that.$router.push('qiandao');
					}
				}
			})

			$.ajax({
				url: "https://hdapi.codeclassroom.org/index/notice/index",
				type: "post",
				dataType: "json",
				async: false,
				data: {type: 2},
				success: function(data) {
// 					if (data.status == 1) {
// 						that.noticedialog = true
// 						that.tong = data.data
// 					} else {
// 						that.noticedialog = false
// 					}
				}
			})

			this.get_data3()
			this.get_data4()
            this.check_user_info()
            this.$router.push('/login');
		},

		methods: {
		    check_user_info(){
		        let that = this;
                $.ajax({
                    url: "https://hdapi.codeclassroom.org/index/user/check_user_info",
                    type: "post",
                    dataType: "json",
                    data: {
                        token: that.token
                    },
                    success: function(data) {

                    }
                })
			},
			get_officer() {
				$('#getgw').modal();
			},
			getgwdel() {
				$('#getgw').modal('hide');
			},

			closegotovi() {
				$('#gotovi').modal('hide');
				// $.ajax({
				//     url: "http://vi.gymbaby.org/ViIndex/loginOut",
				//     type: "post",
				//     async: false,
				//     data: {"parm": 'none'},
				//     success: function (e) {
				//
				//     }
				// })
			},
			savesession() {
				// let that = this;
				// $.ajax({
				//     url: "https://hdapi.codeclassroom.org/ViIndex/save_session",
				//     type: "post",
				//     async: false,
				//     data: {"username": that.username},
				//     success: function (e) {
				
				//     }
				// })
				// let that = this;
				// if (that.glf_auth == 1) {
				// 	that.$message.error("中心管理费已到期,请续约后使用");
				// } else {
				// 	$('#gotovi').modal();
				// }
			},
			panduanshouye() {
				let self = this;
				if (self.quanxian == 4) {
					self.$router.push('qiandao');
				} else if(self.quanxian == 1){
                    self.$router.push('jiaoanx');
				} else if(self.quanxian == 5){
                    self.$router.push('jiaoanx2');
                }else {
					self.$router.push('follow');
				}
			},
			bangzhu: function() {
				let self = this;
				var maninfo =
					`<tr><div class="clearfloat" style="width: 100%;margin-bottom: 20px">
                    <div style="float: right;max-width: 70%;">
                        <div class="yongtou"><img src="/static/img/yonghuh.png" style="width: 100%" alt=""> </div>
                        <img style="width: 7px;margin-top: 20px;float: right" src="/static/img/sanjiaorh.png" alt="">
                        <div class="yLbox">${self.question}</div>
                    </div>
                </div></tr>`;
				$('#bangzhua').append(maninfo);
				$('#liaocBoxa').scrollTop($('#bangzhua').height());
				$.ajax({
					'type': 'post',
					'url': 'https://hdapi.codeclassroom.org/index/index/wen',
					'data': {
						'token': self.token,
						'wen': self.question
					},
					success: function(data) {
						self.question = '';
						var da = data.data;
						if (da.length > 0) {
							for (var i = 0; i < da.length; i++) {
								var sysinfo =
									`<tr><div class="clearfloat" style="width: 100%;margin-bottom: 20px">
                                            <div style="float: left;max-width: 70%;">
                                                <div class="yongtou2">
                                                    <img src="/static/img/shabitoux.png" style="width;50px" alt="">
                                                </div>
                                                <img style="width: 7px;margin-top: 20px;float: left" src="/static/img/sanjiaolb.png" alt="">
                                                <div class="yLbox2">问题:${da[i].wen}<br/>答:${da[i].da}</div>
                                            </div>
                                        </div></tr>`;
								$('#bangzhua').append(sysinfo);
								$('#liaocBoxa').scrollTop($('#bangzhua').height());
							}
						} else {
							var sysinfo =
								`<tr><div class="clearfloat" style="width: 100%;margin-bottom: 20px">
                                            <div style="float: left;max-width: 70%;">
                                                <div class="yongtou2">
                                                    <img src="/static/img/shabitoux.png" style="width:50px;" alt="">
                                                </div>
                                                <img style="width: 7px;margin-top: 20px;float: left" src="/static/img/sanjiaolb.png" alt="">
                                                <div class="yLbox2">您的问题暂时没有收录</div>
                                            </div>
                                        </div></tr>`;
							$('#bangzhua').append(sysinfo);
							$('#liaocBoxa').scrollTop($('#bangzhua').height());
						}
					}
				})
			},
			is_ipad: function() {
				var ua = navigator.userAgent.toLowerCase();
				if (ua.match(/iPad/i) == "ipad") {
					return true;

				} else {
					return false;
				}
			},
			login_out_sys: function() {
				$.cookie('token', null);
				this.tiaoye('login');
				// location.href = "https://hdapi.codeclassroom.org/index/index?loginout=out"
			},
			inarr: function(nav_id) {
				var self = this;
				var tmp_auth = [];
				var tag = false;
				for (var i = 0; i < self.auth.length; i++) {
					if (self.auth[i] == nav_id) {
						tag = true;
						break;
					}
				}
				return tag;
			},
			// 活动板块
			huod(d, index,pid) {
				this.$router.push(d)
				this.idx = index
                if(pid > 0){
                    this.xuan = pid;
                }
			},
			//潜客跟进
			qianke(d, index,pid) {
				this.$router.push(d)
				this.idx = index
                if(pid > 0){
                    this.xuan = pid;
                }
			},
			//进销存
			tiaoye(d, index,pid) {
				this.$router.push(d)
				this.idx = index
                if(pid > 0){
                    this.xuan = pid;
                }
			},
			// 系统设置
			xitong: function(d, index,pid) {
				this.$router.push(d)
				this.idx = index
                if(pid > 0){
                    this.xuan = pid;
                }
			},
			jupm: function(d, index) {
				this.xuan = index;
				this.$router.push(d)
				this.idx = 0
			},
			tan1: function() {
				this.dialogTableVisible = true
			},
			helpt: function() {
				this.dialogTableVisible2 = true
			},
			handleCommand(command) {
				if (command == 'b') {
					this.$router.push('geren')
				}
			},
			// 智能客服
			service_system() {
				this.service_dialog = true

				//判断是否第一次登陆
				let that = this
				$.ajax({
					url: 'https://hdapi.codeclassroom.org/index/question/is_indicate',
					type: 'post',
					data: {
						token: that.token
					},
					success: function(res, status) {
						if (res.status == 1) {
							that.hello_list = res.data
							$.cookie("token", res.token, {
								expires: 300
							})
						}
					},
					fail: function(err, status) {
						console.log(err)
					}
				})
			},
			// 点击加载更多
			loader() {
				if (this.pageSize < this.news.length) {
					this.pageSize += 3
				}
			},
			//发送消息
			sendMsg() {
				if (this.sendText) {
					this.news.push({
						name: this.sendText,
						type: 1
					})

					let that = this
					$.ajax({
						url: 'https://hdapi.codeclassroom.org/index/question/selects',
						type: 'post',
						data: {
							token: that.token,
							conn: that.sendText
						},
						success: function(res, status) { //客服自动回复
							that.sendText = ''
							if (res.status == 1) {
								let tmp = []
								for (let i in res.data) {
									tmp.push({
										arr: res.data[i]
									})
								}
								that.news.push({
									data: tmp,
									type: 3
								})
							} else if (res.status == 2) {
								that.$message.error(res.msg)
							} else if (res.status == 6) {
								that.news.push({
									txt: res.data,
									type: 2
								})
							} else { //status:3,4,5
								that.news.push({
									downloads: res.data.url,
									url: res.data.detail_url,
									type: 2
								})
							}
						},
						fail: function(err, status) {
							console.log(err)
						}
					})

					let wrap = this.$refs.chat_boxes
					wrap.scrollTop = wrap.scrollHeight
				}
			},
			//选择常见问题
			choose(t, i) {
				this.pageSize = 3
				this.c_index = i

				let that = this
				$.ajax({
					url: 'https://hdapi.codeclassroom.org/index/question/class_hot',
					type: 'post',
					data: {
						token: that.token,
						type: t
					},
					success: function(res, status) {
						if (res.status == 1) {
							that.list2 = res.data
							that.load_more = res.show
							let tmp = [];
							for (let i in res.info) {
								tmp.push({
									arr: res.info[i]
								})
							}
							that.news.push({
								data: tmp,
								type: 3
							})
						} else if (res.status == 2) {
							that.$message.error(res.msg)
						} else if (res.status == 6) {
							that.list2 = res.data
							that.news.push({
								txt: res.info,
								type: 2
							})
						} else { //status:3,4,5
							that.list2 = res.data
							that.news.push({
								downloads: res.info.url,
								url: res.info.detail_url,
								type: 2
							})
						}
					},
					fail: function(err, status) {
						console.log(err)
					}
				})
				let wrap = this.$refs.chat_boxes
				wrap.scrollTop = wrap.scrollHeight
			},
			//问题的答案
			answer(i) {
				let that = this
				$.ajax({
					url: 'https://hdapi.codeclassroom.org/index/question/reply',
					type: 'post',
					data: {
						token: that.token,
						w_id: i
					},
					success: function(res, status) {
						that.get_data4()
						if (res.status == 1) {
							that.news.push({
								txt: res.data.conn,
								type: 2
							})
						} else if (res.status == 2) {
							that.$message.error(res.msg)
						} else { //status:3,4,5
							that.news.push({
								downloads: res.data.url,
								url: res.data.detail_url,
								type: 2
							})
						}
					},
					fail: function(err, status) {
						console.log(err)
					}
				})

				//回到底部
				let wrap = this.$refs.chat_boxes
				wrap.scrollTop = wrap.scrollHeight
			},
			//常见问题
			get_data3() {
				let that = this
				$.ajax({
					url: 'https://hdapi.codeclassroom.org/index/question/get_class',
					type: 'post',
					data: {
						token: that.token
					},
					success: function(res, status) {
						if (res.status == 1) {
							that.list3 = res.data
							// that.choose(res.data[0].type)
						} else {
							// that.$message.error(res.msg)
						}
					},
					fail: function(err, status) {
						console.log(err)
					}
				})
			},
			//热门问题
			get_data4() {
				let that = this
				$.ajax({
					url: 'https://hdapi.codeclassroom.org/index/question/hot',
					type: 'post',
					data: {
						token: that.token
					},
					success: function(res, status) {
						if (res.status == 1) {
							that.list4 = res.data
						}
					},
					fail: function(err, status) {
						console.log(err)
					}
				})
			},
		}

	}
</script>
<style>
	html {
		/*隐藏滚动条，当IE下溢出，仍然可以滚动*/
		-ms-overflow-style: none;
		/*火狐下隐藏滚动条*/
		overflow: -moz-scrollbars-none;

	}

	/*Chrome下隐藏滚动条，溢出可以透明滚动*/
	html::-webkit-scrollbar {
		width: 0px
	}
</style>
<style scoped>
	a{
		text-decoration: none;
	}
	#app {
		min-height: 100vh;
		overflow: hidden;
		background: #ffffff;
	}
	
	.jump_m{
		display: inline-block;
		color: rgb(0, 0, 0);
		border: 1px solid darkgray;
		line-height: 21px;
		border-radius: 3px;
		padding: 0 5px;
	}

	.clearfloat:after {
		display: block;
		clear: both;
		content: "";
		visibility: hidden;
		height: 0
	}

	.clearfloat {
		zoom: 1
	}

	.topbar {
		height: 40px;
		padding-left: 70px;
		padding-right: 70px;
		display: flex;
		display: -webkit-flex;
		justify-content: space-between;
	}

	.logo {
		width: 39px;
		margin-right: 10px;
	}

	.toptitle {
		font-size: 20px;
		vertical-align: top;
		line-height: 40px;
		color: #354B60;

	}

	.box_z {
		width: 98%;
		margin: 0 auto;
		position: relative;
		height: 99vh;
		border-radius: 15px;
		overflow: hidden;
	}

	/* .navbar-box {
		position: absolute;
		left: 0;
		top: 0;
		z-index: 10;
		height: 99vh;
		overflow-y: scroll;
		-moz-overflow-y: scroll;
		overflow-x: visible;
		-moz-overflow-x: visible;
		padding-top:30px;
		padding-bottom: 30px;
		background: #7c72c1;
		width: 63px;
		border-radius: 15px 0 0 15px;
	} */

	/* .youce {

		padding-left: 63px;

	} */

	@media screen and (min-width:1500px) {
		.youce {
			width: 100%;
		}
	}

	.navbar-box {

		/*隐藏滚动条，当IE下溢出，仍然可以滚动*/
		-ms-overflow-style: none;
		/*火狐下隐藏滚动条*/
		overflow: -moz-scrollbars-none;
	}

	.navbar-box::-webkit-scrollbar {
		width: 0;
	}

	.navbarList {
		position: relative;
		padding-top: 5px;
		padding-bottom: 5px;
		/*height: 50px;*/
		transition: 0.3s linear;
		cursor: pointer;
	}

	.navbarList:hover {
		/* padding-left: 4px; */
		background: rgb(160, 151, 228);
	}

	.navbarList.active {
		background: rgb(160, 151, 228);
	}

	.navpic {
		margin-left: 15px;
		height: 30px;
		width: 33px;
		vertical-align: top;
	}

	.tu3 {
		width: 3px;
		opacity: 0;
		position: absolute;
		left: 0;
		transition: 0.3s linear;
	}

	.navbarList:hover .tu3 {
		opacity: 1;
	}

	.box_l_zi {
		padding-top: 3px;
		height: 20px;
		width: 63px;
		color: #fff;
		text-align: center;
		font-size: 14px;
	}

	.right_top {
		height: 8vh;
		line-height: 8vh;
		border-radius: 0 15px 0 0;
		padding-right: 20px;
		padding-left: 20px;
		background: #e8e6fe;
		display: flex;
		display: -webkit-flex;
		justify-content: space-between;
	}
	.home {
		width: 60px;
		vertical-align: middle;
		margin-right: 10px;
		cursor: pointer;
	}

	.center {
		font-size: 14px;
		color: #666;
		vertical-align: top;
	}

	.t_right_box {
		/* display: flex;
		display: -webkit-flex; */
		line-height: 5vh;
		/* align-items: center; */
	}

	.zi_btn {
		display: inline-block;
		background: #7c72c1;
		border: none;
		border-radius: 16px;
		padding: 3px 10px;
		font-size: 12px;
		line-height: 1.5;
		color: #fff;
		margin-right: 10px;
		cursor: pointer;
		box-shadow: 0 2px 12px 0 rgba(0, 0, 0, .1)
	}

	.bangzhuBtn {
		width: 62px;
		height: 24px;

	}

	.centerbox {
		background: #fff;
		height: 80vh;
		overflow-y: scroll;
	}

	.right_bottom {
		font-size: 12px;
		height: 5vh;
		line-height: 5vh;
		width: 100%;
		background: #e8e6fe;
		text-align: center;
		position: absolute;
		bottom: 0;
	}

	.pop {
		padding: 6px 10px;
		width: 120px;
	}

	.pop div {
		text-align: center;
		padding: 6px 15px;
		cursor: pointer;
		font-size: 14px;
	}

	.is:hover {
		color: #7C72C1;
		padding: 6px 15px;
		border-radius: 5px;
		background: #E8E5FF;
		font-weight: bold;
		font-size: 16px;
	}

	.in {
		color: #FFF;
		padding: 6px 15px;
		border-radius: 5px;
		background: #7C72C1;
		font-weight: bold;
		font-size: 16px;
	}

	.in:hover {
		font-size: 16px;
	}

	.onlineserve {
		font-size: 12px;

	}

	.yongtou2 {
		float: left;
		width: 100%;
		height: 50px;
		border-radius: 25px;
		overflow: hidden;
	}

	.yLbox2 {
		margin-left: 57px;
		padding: 17px 10px 17px 10px;
		line-height: 20px;
		font-size: 14px;
		background: #eee;
		border-radius: 10px;
	}

	.yongtou {
		float: right;
		width: 50px;
		height: 50px;
		border-radius: 25px;
		overflow: hidden;
	}

	.yLbox {
		margin-right: 57px;
		padding: 17px 10px 17px 10px;
		line-height: 20px;
		font-size: 14px;
		background: #fdb06a;
		border-radius: 10px;
	}

	.fasongBtn {
		width: 130px;
		height: 32px;
		text-align: center;
		line-height: 32px;
		color: #fff;
		font-size: 16px;
		border-radius: 16px;
		float: left;
		cursor: pointer;
		margin-top: 20px;
		background: #fdb06a;
		box-shadow: #f7dcc4 3px 5px 10px;
	}

	.help {
		font-size: 14px;
		cursor: pointer;
		margin-right: 12px;
	}

	.help span {
		padding: 4px 7px;
		background-color: #7c72c1;
		border-radius: 50%;
		color: white;
		margin-right: 3px;
	}
</style>
<style>
	/*.tanchuang .el-dialog__header {*/
	/*padding: 10px 20px 5px;*/
	/*color: #fff;*/
	/*background: #7c72c1;*/
	/*}*/
	.tanchuang .el-dialog__header .el-dialog__title {

		color: #fff;

	}

	#app .tanchuang .el-dialog .el-dialog__header {
		padding: 10px 20px 5px;
		color: #fff;
		background: #7c72c1;
		text-align: center;
	}

	.tanchuang .el-dialog__headerbtn {
		right: 10px;
		top: 10px;
		color: #fff;
	}

	.tanchuang2 .el-dialog__body {
		padding: 15px;
		height: 270px;
		overflow-y: scroll;
	}

	.tanchuang2 .el-dialog__footer {
		border-top: 1px solid #e5e5e5;
	}

	.tanchuang .el-dialog__headerbtn .el-dialog__close {
		color: #fff;
	}
</style>

<!-- 智能客服 -->
<style scoped="scoped">
	li {
		list-style: none;
	}

	.service {
		width: 100%;
		height: 100%;
		background: rgba(0, 0, 0, 0.5);
		position: fixed;
		top: 0;
		left: 0;
		z-index: 999;
	}

	.content {
		width: 90%;
		height: 90%;
		border-radius: 10px;
		margin: 3% auto;
		background-color: white;
		overflow: hidden;
	}

	.header2 {
		padding: 10px 15px;
		background-color: #f4f3ff;
		box-shadow: 0 2px 0px gainsboro;
	}

	.header2 div {
		display: inline-block;
		border: 1px solid gainsboro;
		border-radius: 5px;
		padding: 0 5px;
		margin-right: 5px;
	}

	.header2 span {
		font-size: 15px;
		font-weight: bold;
	}

	.header2 .close_img {
		width: 20px;
		float: right;
		margin-right: 30px;
		margin-top: 14px;
		cursor: pointer;
	}

	.header2 .close_img:hover {
		opacity: 0.8;
	}

	.left_box {
		width: calc(100% - 301px);
		float: left;
	}

	.left_box .chat_box {
		height: 50vh;
		overflow-y: scroll;
		padding: 50px 15px 10px 30px;
		margin-top: 3px;
		margin-right: 2px;
		border-bottom: 1px solid gainsboro;
	}

	/* 重置滚动条样式 */
	.chat_box::-webkit-scrollbar {
		width: 10px;
	}

	.chat_box::-webkit-scrollbar-track {
		background-color: #EDEDED;
		border-radius: 2em;
		-webkit-border-radius: 2em;
		-moz-border-radius: 2em;
	}

	.chat_box::-webkit-scrollbar-thumb {
		background-color: darkgray;
		border-radius: 2em;
		-webkit-border-radius: 2em;
		-moz-border-radius: 2em;
	}

	.left_box .reply {
		position: relative;
		margin-bottom: 11px;
	}

	.left_box .reply .img {
		display: inline-block;
		border: 1px solid gainsboro;
		border-radius: 5px;
		padding: 0 4px;
		margin-right: 12px;
		float: left;
	}

	.reply .img img {
		width: 25px;
	}

	.reply .chatroom {
		position: relative;
		margin-left: 50px;
		color: black;
		display: block;
		border: 1px solid darkgray;
		border-radius: 4px;
		padding: 7px 20px;
	}

	.reply .chatroom a {
		text-decoration: underline;
		margin-left: 20px;
	}

	.reply .name {
		position: absolute;
		left: 52px;
		top: -15px;
		transform: scale(0.9);
	}

	.reply .chatroom::before {
		content: '';
		display: block;
		border-right: 7px solid #fff;
		border-left: 7px solid transparent;
		border-top: 7px solid transparent;
		border-bottom: 7px solid transparent;
		width: 0px;
		height: 0px;
		position: absolute;
		top: 8px;
		left: -14px;
		z-index: 3;
	}

	.reply .chatroom::after {
		content: '';
		display: block;
		border-right: 8px solid gray;
		border-left: 8px solid transparent;
		border-top: 8px solid transparent;
		border-bottom: 8px solid transparent;
		width: 0px;
		height: 0px;
		position: absolute;
		top: 7px;
		left: -16.5px;
		z-index: 2;
	}


	.left_box .ask {
		position: relative;
		float: right;
		margin-bottom: 10px;
	}

	.left_box .ask .img {
		display: inline-block;
		margin-left: 12px;
		float: right;
	}

	.ask .img img {
		width: 33px;
	}

	.ask .chatroom {
		position: relative;
		margin-right: 50px;
		color: black;
		display: block;
		border: 1px solid darkgray;
		border-radius: 4px;
		padding: 7px 20px;
		background-color: #e8e5ff;
	}

	.ask .name {
		position: absolute;
		right: 52px;
		top: -15px;
		transform: scale(0.9);
	}

	.ask .chatroom::before {
		content: '';
		display: block;
		border-right: 7px solid transparent;
		border-left: 7px solid #e8e5ff;
		border-top: 7px solid transparent;
		border-bottom: 7px solid transparent;
		width: 0px;
		height: 0px;
		position: absolute;
		top: 8px;
		right: -14px;
		z-index: 3;
	}

	.ask .chatroom::after {
		content: '';
		display: block;
		border-right: 8px solid transparent;
		border-left: 8px solid darkgray;
		border-top: 8px solid transparent;
		border-bottom: 8px solid transparent;
		width: 0px;
		height: 0px;
		position: absolute;
		top: 7px;
		right: -16.5px;
		z-index: 2;
	}

	.page {
		clear: both;
		text-align: center;
		border-bottom: 1px solid gainsboro;
	}

	.page .right_top2 {
		margin-top: 5px;
		width: 95%;
		white-space: nowrap;
		overflow-x: scroll;
		display: inline-flex;
		margin-bottom: 3px;
	}

	.page .right_top2 li {
		margin: 0 20px 5px 0;
		padding: 2px 11px;
		color: #666666;
		border: 1px solid #666666;
		border-radius: 3px;
		float: left;
		cursor: pointer;
	}

	.page .right_top2 li:hover {
		color: black;
		border-color: black;
	}

	/* 重置滚动条样式 */
	.page .right_top2::-webkit-scrollbar {
		height: 10px;
	}

	.page .right_top2::-webkit-scrollbar-track {
		background-color: #EDEDED;
		border-radius: 2em;
		-webkit-border-radius: 2em;
		-moz-border-radius: 2em;
	}

	.page .right_top2::-webkit-scrollbar-thumb {
		background-color: darkgray;
		border-radius: 2em;
		-webkit-border-radius: 2em;
		-moz-border-radius: 2em;
	}

	.issue {
		margin: 0 24px 20px 45px;
	}

	.issue ul {
		float: left;
		display: inline-block;
		width: 260px;
		background-color: #f1f1f1;
		border-radius: 3px;
		padding-bottom: 15px;
		margin-right: 21px;
		margin-top: 20px;
	}

	.issue ul li {
		margin-top: 15px;
		font-size: 15px;
		font-weight: bold;
		cursor: pointer;
		width: 240px;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		color: #666666;
	}

	.issue ul li:hover {
		color: black;
	}

	.issue ul span {
		width: 8px;
		height: 8px;
		border-radius: 50%;
		display: inline-block;
		background-color: #7c72c1;
		margin: 7px 11px 0;
		float: left;
	}

	.point {
		text-align: center;
		margin-top: 10px;
		transform: scale(0.9);
	}

	.point span {
		display: inline-block;
		cursor: pointer;
	}

	.point img {
		vertical-align: middle;
		margin: -5px 10px 0 0;
	}

	.point a {
		color: gray;
	}

	.point a:hover {
		text-decoration: underline;
	}


	.right_box {
		display: inline-block;
		width: 300px;
		height: 100%;
		border-left: 2px solid #d1d1d1;
		font-weight: bold;
		font-size: 14px;
	}

	.right_box .right_top2 {
		height: 46vh;
		overflow-y: scroll;
		padding-right: 16px;
		padding-left: 5px;
		padding-top: 10px;
	}

	.right_box .right_top2 li {
		margin-left: 20px;
		margin-top: 17px;
		padding: 2px 11px;
		color: #666666;
		border: 1px solid #666666;
		border-radius: 3px;
		float: left;
		cursor: pointer;
	}

	.right_box .right_top2 .active2 {
		color: black;
		border-color: black;
	}

	.right_bottom2 li:first-child {
		font-size: 15px;
	}

	.right_box .right_bottom2 {
		border-top: 2px solid #d1d1d1;
		padding: 16px 0 0 23px;
		height: 30vh;
		overflow-y: scroll;
	}

	.right_box .right_bottom2 li {
		margin-top: 10px;
		cursor: pointer;
	}

	.right_box .right_bottom2 li:hover {
		color: black;
	}

	.right_bottom2 .hot_img {
		margin-top: -4px;
		margin-right: 10px;
	}

	.footer {
		text-align: center;
		width: 100%;
		height: 20vh;
		position: relative;
	}

	.footer .msg {
		width: 95%;
		display: inline-block;
		height: 100px;
		margin-top: 10px;
		outline: none;
		border: 0;
		resize: none;
	}

	.footer .btn2 {
		position: absolute;
		right: 35px;
		bottom: 5px;
		display: inline-block;
		width: 120px;
		height: 40px;
		line-height: 40px;
		color: white;
		background-color: #7c72c1;
		border: 0;
		border-radius: 5px;
		font-size: 15px;
		letter-spacing: 3px;
		cursor: pointer;
		float: right;
		padding-left: 33px;
		text-align: left;
		outline: none;
	}

	.footer .btn2:hover {
		opacity: 0.9;
	}

	.footer .trigon {
		width: 0;
		height: 0;
		border-left: 5px solid transparent;
		border-right: 5px solid transparent;
		border-top: 6px solid white;
		float: right;
		margin: 17px 31px 0 0;
	}


	/* 媒体查询 */
	@media (min-width:1366px) {
		.chatroom {
			max-width: 600px;
		}
	}

	@media (min-width:1920px) {
		.chatroom {
			max-width: 900px;
		}

		.issue ul {
			width: 390px;
		}

		.issue ul li {
			width: 370px;
		}

		.issue ul span {
			margin: 7px 11px 0 20px;
		}
	}
</style>
