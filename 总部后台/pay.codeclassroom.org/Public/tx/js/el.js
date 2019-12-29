
var app = new Vue({
    	el: '#wrap',
		data() {
			return {
				token: '',
				week_month:[
					{name:'本周',val:1},
					{name:'本月',val:2}
				],
				cut_index:1,//周月切换
				cut_index2:1,
				img_list:[
					{img1:'img/t1.png',img2:'img/t11.png'},
					{img1:'img/t2.png',img2:'img/t22.png'},
					{img1:'img/t3.png',img2:'img/t33.png'},
				],
				tabs_index:0,
				chat_show:false,
				chat_show2:false,
				news:[],
				data:[
					{name:'未完成的',imgs:''},
					{name:'已完成的',imgs:''},
					{name:'我发出的',imgs:''},
					{name:'我执行的',imgs:''},
				],
				checked_index:0,
				checked1:true,
				checked2:true,
				activeName: '1',
				e_data:'',
				e_data2:'',
				e_data3:'',
				currentDate:'',
				people_num:0,
				linkman_data:[],//最近联系人
				branch_data:[],
				record_data:[],//聊天记录
				edit_msg:'',//编辑框中的消息
				new_msg:'',//新消息
				database:[],//图片数据
				get_id:'',
				select_id:'',
				is_edit: true,
				s_index:null,
				uid:'',
				title_name:'',//聊天人
				upload_type:1,//上传类型 1.文字 2.图片 3.语音 4.文件
				img_file: 1,//上传的类型 图片,文件
				back_dialogVisible:false,//更改聊天背景
				backg:'',
				checked_name:'',
				task_data:[],//任务
				bgColor:[
					'#00D4A4',
					'#FF9800',
					'#00BCD4',
					'#F45E63',
					'#79BE3C',
					'#673AB7',
				],
				edit_msg2:'',
				imgs_data:[],
				arrs:[],
				people:[],
				task_list:[],
				before_task:[],
				online_num:[],
			}
		},
		created(){
			this.get_uid()
		},
		mounted() {
			this.get_date()
			this.drag()
		},
		methods: { 
			//选择抄送人
			select_people(i,state,id){
				if(state){
					this.people.push(id)
				}else{
					this.people.splice(i,1)
				}
			},
			send_msg2(){
				var self = this
				if(self.edit_msg2 && self.people){
					if(self.img_file == 1){
						$.ajax({
							type: 'POST',
							url: "http://www.notice.com/index/task/publish",
							data: {
								from_id: self.uid,
								img: self.imgs_data,
								content: self.edit_msg2,
								to_ids: self.people,
							},
							success: function (res) {
								if(res.status == 1){
									self.edit_msg2 = ''
								}
							},
							fail: function (err){
								console.log(err)
							}
						});
					}else if(self.img_file == 2){
						$.ajax({
							type: 'POST',
							url: "http://www.notice.com/index/task/publish",
							data: {
								from_id: self.uid,
								file: self.imgs_data,
								content: self.edit_msg2,
								to_ids: self.people,
							},
							success: function (res) {
								if(res.status == 1){
									self.edit_msg2 = ''
								}
							},
							fail: function (err){
								console.log(err)
							}
						});
					}
					self.get_task()
				}
			},
			get_task(){
				let self = this
				$.ajax({
					type: 'POST',
					url: "http://www.notice.com/index/task/past",
					data: {
						uid: self.uid,
					},
					success: function (res) {
						if(res.status == 1){
							self.before_task = res.data
						}
					},
					fail: function (err){
						console.log(err)
					}
				});
			},
			imgs_upload(e){
				this.arrs.push(e.target.files[0])
				console.log(this.arrs) 
				var formData = new FormData($('#forms2')[0]);
				let self = this
				$.ajax({      
				    // url:'http://www.notice.com/index/upload/file_uploads',
				    url:'http://www.notice.com/index/upload/file_uploads',
				    type:"post",
				    async:false,
				    processData: false, // 不处理发送的数据，因为data值是Formdata对象，不需要对数据做处理
				    contentType: false, // 不设置Content-type请求头
				    data:formData,
				    success:function(res){
				    	if(res.status == 1){
				           self.imgs_data = res.data
						   console.log(self.imgs_data)
						   
						}
				    },
					fail: function (err) {
					    console.log(err)
					}
				})
			},
			// 更换背景
			update_bg(bg,index){
				this.backg = bg
				
				let self = this
				$.ajax({
					type: 'POST',
					url: "http://www.notice.com/index/index/up_background",
					data: {
						uid: self.uid,
						background:index
					},
					success: function (res) {
						self.get_uid()
					},
					fail: function (err){
						console.log(err)
					}
				});
			},
			// 修改任务状态
			update_task(id){
				let self = this
				$.ajax({
					type: 'POST',
					url: "http://www.notice.com/index/task/complete",
					data: {
						id: id
					},
					success: function (res) {
						if(res.status == 1){
							self.task_type(0,'未完成')
						}
					},
					fail: function (err){
						console.log(err)
					}
				});
			},
			//切换任务状态
			task_type(i,name){
				this.checked_index = i
				this.checked_name = name
				
				let self = this
				$.ajax({
					type: 'POST',
					url: "http://www.notice.com/index/task/present_task",
					data: {
						uid: self.uid,
						type: i+1
					},
					success: function (res) {
						if(res.status == 1){
							self.task_data = res.data
						}else{
							self.task_data = []
						}
					},
					fail: function (err){
						console.log(err)
					}
				});
			},
			//聊天弹窗拖拽
			drag(){
				$.fn.dragDiv = function(divWrap) {
					return this.each(function() {
						var $divMove = $(this);//鼠标可拖拽区域
						var $divWrap = divWrap ? $divMove.parents(divWrap) : $divMove;//整个移动区域
						var mX = 0, mY = 0;//定义鼠标X轴Y轴
						var dX = 0, dY = 0;//定义div左、上位置
						var isDown = false;//mousedown标记
						if(document.attachEvent) {//ie的事件监听，拖拽div时禁止选中内容，firefox与chrome已在css中设置过-moz-user-select: none; -webkit-user-select: none;
							$divMove[0].attachEvent('onselectstart', function() {
								return false;
							});
						}
						$divMove.mousedown(function(event) {
							var event = event || window.event;
							mX = event.clientX;
							mY = event.clientY;
							dX = $divWrap.offset().left;
							dY = $divWrap.offset().top;
							isDown = true;//鼠标拖拽启动
						});
						$(document).mousemove(function(event) {
							var event = event || window.event;
							var x = event.clientX;//鼠标滑动时的X轴
							var y = event.clientY;//鼠标滑动时的Y轴
							if(isDown) {
								$divWrap.css({"left": x - mX + dX, "top": y - mY + dY});//div动态位置赋值
							}
						});
						$divMove.mouseup(function() {
							isDown = false;//鼠标拖拽结束
						});
					});
				};
			},
			// 图片上传
			upload_img(){
				var formData = new FormData($('#forms')[0]);
				let self = this
				$.ajax({      
				    // url:'http://www.notice.com/index/upload/file_uploads',
				    url:'http://www.notice.com/index/upload/file_uploads',
				    type:"post",
				    async:false,
				    processData: false, // 不处理发送的数据，因为data值是Formdata对象，不需要对数据做处理
				    contentType: false, // 不设置Content-type请求头
				    data:formData,
				    success:function(res){
				    	if(res.status == 1){
				           self.upload_type = 4
						   self.oldname = res.oldname
						   self.send_server(res.data)
						}
				    },
					fail: function (err) {
					    console.log(err)
					}
				})
			},
			// 获取uid
			get_uid(){
				let self = this
				$.ajax({
					type: 'POST',
					url: "http://www.notice.com/index/index/get_uid",
					data: {
						user_id: 47,
						type: 1
					},
					success: function (res) {
						if(res.status == 1){
							self.uid = res.uid
							self.get_socket()
							let i = res.background
							self.backg = self.bgColor[i-1]
						}
					},
					fail: function (err){
						console.log(err)
					}
				});
			},
			//发送消息
			send_msg(i){
				if(this.edit_msg){
					this.upload_type = i
					this.send_server(this.edit_msg)
				}
			},
			//发送到服务端
			send_server(m){
				let self = this
				$.ajax({
					type: 'POST',
					url: "http://www.notice.com/index/index/send_msg",
					data: {
						from: self.uid,
						to: self.get_id,
						msg: m,
						sendtype: 2,
						type: self.upload_type,
						oldname: self.oldname,
						online_num: self.online_num
					},
					success: function (res) {
						self.edit_msg = ''
						self.get_record(self.get_id)
					},
					fail: function (err){
						console.log(err)
					}
				});
			
					
			
			},
			//是否未读消息
			get_reddot(){
				let self = this
				$.ajax({
					type: 'POST',
					url: "http://www.notice.com/index/connect/message_log",
					data: {
						send_uid:self.uid,
						get_uid:self.select_id
					},
					success: function (res) {
						self.chat_show = true
						if(res.status == 1){
							self.record_data = res.data
							self.get_linkman()
							document.getElementsByClassName('down2')[0].scrollIntoView(0);
						}
					},
					fail: function (err){
						console.log(err)
					}
				});
			},
			//获取当前用户的聊天记录
			get_record(g_id,i){
				this.select_id = g_id
				this.s_index = i
				
				let self = this
				$.ajax({
					type: 'POST',
					url: "http://www.notice.com/index/index/update_read_status",
					data: {
						send_uid:self.uid,
						get_uid:g_id
					},
					success: function (res) {
						if(res.status == 1){
							self.get_reddot()
							$("#move2").dragDiv(".divWrap"); //聊天窗口拖动
						}
					},
					fail: function (err){
						console.log(err)
					}
				});
			},
			//选择联系人
			choose(n,t,c,id,index,read_status){
				this.title_name = n
				this.s_index = index
				this.get_id = id
				
				this.chat_show2 = true
				var tag = true;
				for(let i in this.news){
					if(this.news[i].g_id == id){
						tag = false;
						return;
					}
				}
				if(tag){
					this.news.push({name:n,times:t,txt:c,g_id:id,read_status:read_status})
				}
				this.get_record(id)
			},
			// 聊天窗口切换
			handleClick(tab, event) {
				let num = tab.$options.propsData.name
				if(num == 2){
					// this.get_list('http://www.notice.com/index/task/get_person')
					this.get_list('http://www.notice.com/index/task/get_person')
					this.get_task()
				}else if(num == 3){
					this.get_list('http://www.notice.com/index/connect/past_notice')
				}else if(num == 4){
					this.get_list('http://www.notice.com/index/connect/img_log')
				}else if(num == 5){
					this.get_list('http://www.notice.com/index/connect/file_log')
				}
			},
			//获取图片
			get_list(u){
				let self = this
				$.ajax({
					type: 'POST',
					url: u,
					data: {
						from_id:self.uid,
						to_id:self.get_id
					},
					success: function (res) {
						if(res.status == 1){
							self.database = res.data
						}
					},
					fail: function (err){
						console.log(err)
					}
				});
			},
			//获取最近联系人
			get_linkman(e){
				let self = this
				$.ajax({
					type: 'POST',
					url: "http://www.notice.com/index/connect/get_call",
					data: {
						uid:self.uid
					},
					success: function (res) {
						self.chat_show = true
						if(res.status == 1){
							self.linkman_data = res.data
						}
					},
					fail: function (err){
						console.log(err)
					}
				});
			},
			//获取联系人 部门
			get_branch(){
				let self = this
				$.ajax({
					type: 'POST',
					url: "http://www.notice.com/index/connect/conn_person",
					data: {
						uid:self.uid
					},
					success: function (res) {
						self.chat_show = true
						if(res.status == 1){
							self.branch_data = res.data
						}
					},
					fail: function (err){
						console.log(err)
					}
				});
			},
			//在线报名 消息统计 任务统计
			get_echarts1(){
				let self = this
				$.ajax({
					type: 'POST',
					url: "http://www.notice.com/index/First/index",
					data: {},
					success: function (res) {
						if(res.status == 1){
							self.e_data = res
							self.get_num()
						}
					},
					fail: function (err){
						console.log(err)
					}
				});
			},
			//实时计算在线人数
			get_num(){
				let self = this
				$.ajax({
					type: 'POST',
					url: "http://www.notice.com/index/First/ratio_rs",
					data: {
						num: self.e_data.total_rs,
						count: self.people_num
					},
					success: function (res) {
						if(res.status == 1){
							self.e_data.ratio_rs = res.ratio_rs
						}
					},
					fail: function (err){
						console.log(err)
					}
				});
			},
			//周，月切换(每日消息)
			cut2(i){
				this.cut_index2 = i
				this.get_echarts2(i)
			},
			// 在线人数
			get_echarts2(t){
				let self = this
				$.ajax({
					type: 'POST',
					url: "http://www.notice.com/index/First/day_people",
					data: {
						type: t
					},
					success: function (res) {
						if(res.status == 1){
							self.e_data2 = res
							self.get_number()
						}
					},
					fail: function (err){
						console.log(err)
					}
				});
			},
			//周，月切换(每日消息)
			cut(i){
				this.cut_index = i
				this.get_echarts3(i)
			},
			//每日消息
			get_echarts3(t){
				let self = this
				$.ajax({
					type: 'POST',
					url: "http://www.notice.com/index/First/day_message",
					data: {
						type: t
					},
					success: function (res) {
						if(res.status == 1){
							self.e_data3 = res
							self.get_message()
						}
					},
					fail: function (err){
						console.log(err)
					}
				});
			},
			//socket初始化
			get_socket(){
				let self = this;
				// 连接服务端
				var socket = io('http://123.57.85.202:2120');
				// 连接后登录
				socket.on('connect', function(){
					socket.emit('login', self.uid);
				});
				// 后端推送来消息时
				socket.on('new_msg', function(msg){
					self.new_msg = msg
					
					if(self.select_id == msg.from){
						self.get_reddot(self.select_id)
					}else{
						self.get_linkman()
					}
					
				});
				// 后端推送来在线数据时
				socket.on('update_online_count', function(online_stat){
					// console.log(online_stat)
				});
				socket.on('online_now', function(online_stat){
					self.online_num = online_stat.all
					var num = online_stat.count
					if(num == undefined){
						num = 0
					}
					self.people_num = num
					self.on_line(online_stat.count)
				});
			},
			// 每日在线人数
			on_line(num){
				$.ajax({
					type: 'POST',
					url: "http://www.notice.com/index/First/on_line",
					data: {
						count: num
					},
					success: function (res) {
						
					},
					fail: function (err){
						console.log(err)
					}
				});
			},
			//在线人数
			get_number() {
				let myChart = echarts.init(document.getElementById("box2"));
				myChart.resize()
				option = {
					color:'#6357a3',
					xAxis: {
						type: 'category',
						data: this.e_data2.date
					},
					yAxis: {
						type: 'value'
					},
					grid: {
						left: '3%',   //图表距边框的距离
						right: '3%',
						bottom: '5%',
						containLabel: true
					},
					series: [{
						data: this.e_data2.data,
						type: 'line',
						smooth: true,
						itemStyle : {
							normal : {
								lineStyle:{
									width: 4,//折线宽度
								}
							}
						},
					}]
				};
				myChart.setOption(option, true);
			},
			//每日消息
			get_message() {
				let myChart = echarts.init(document.getElementById("box3"));
				myChart.resize()
				option = {
					color:'#6357a3',
					xAxis: {
						type: 'category',
						data: this.e_data3.date
					},
					yAxis: {
						type: 'value'
					},
					grid: {
						left: '3%',   //图表距边框的距离
						right: '3%',
						bottom: '5%',
						containLabel: true
					},
					series: [{
						data: this.e_data3.data,
						type: 'line',
						smooth: true,
						itemStyle : {
							normal : {
								lineStyle:{
									width: 4,//折线宽度
								}
							}
						},
					}]
				};
				myChart.setOption(option, true);
			},
			//跳转页面
			jump(u){
				location.href = u
			},
			//获取当前日期
			get_date(){
				let t = new Date()
				let y = t.getFullYear()
				let m = t.getMonth()+1
				let d = t.getDate()
				this.currentDate = `${y}-${m}-${d}`
			},
		},
		watch:{
			people_num(){
				if(this.e_data.total_rs){
					this.get_num()
				}
			},
			//消息列表,联系人,任务
			tabs_index(){
				if(this.tabs_index == 1){
					this.get_branch()
				} else if(this.tabs_index == 2){
					this.task_type(0,'未完成的')
				}
			},
			//切换聊天窗口
			activeName(){
				if(this.activeName == 3 || this.activeName == 4 || this.activeName == 5){
					this.is_edit = false
					$('.chat_boxes .el-tabs__content').height(380)
				}else{
					$('.chat_boxes .el-tabs__content').height(280)
					this.is_edit = true
				}
			},
		},
	})	