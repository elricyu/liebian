<template>
	<div>
		<!-- 右下角聊天窗口 -->
		<div class="right_bottom" @click="get_linkman">
			<img src="/static/img/chat/img/tu.png" ><span>新消息</span>
		</div>
		<transition name="el-zoom-in-top">
		<div class="chatroom" v-if="chat_show">
			<!-- <div :style="{background:(backg ? 'url('+ backg +') no-repeat 100% 100%' : '#6256a3')}"> -->
			<div :style="{backgroundColor:(backg ? backg : '#6256a3')}">
				<div class="header2">
					<img src="/static/img/chat/img/tu.png" class="head_img">
					<span>
						<img src="/static/img/chat/img/bei.png" @click="back_dialogVisible = true">
						<img src="/static/img/chat/img/audio.png" >
						<img src="/static/img/chat/img/close.png" @click="chat_show = false">
					</span>
				</div>
				<div class="border5"></div>
				<div class="search">
					<img src="/static/img/chat/img/search2.png" >
					<input type="text" name="" id="" value="" placeholder="搜索" />
				</div>
			</div>
			<ul class="tabs">
				<li v-for="(item,index) in img_list" @click="tabs_index = index">
					<img :src="tabs_index == index ? item.img1 : item.img2" >
				</li>
			</ul>
			
			<!-- 消息列表 -->
			<div class="content2" v-if="tabs_index == 0" style="margin-top: 8px;">
				<div v-for="(item,index) in linkman_data" @click="choose(item.username,item.times,item.msg,item.to,index,item.read_status)" class="line2">
					<img src="/static/img/chat/img/tu.png" class="head_img2">
					<span class="time2">{{item.times}}</span>
					<div class="name">{{item.username}}</div>
					<div class="txt2">{{item.msg}} <span v-if="item.read_status == 1" class="red_dot"></span></div>
				</div>
			</div>
			
			<!-- 联系人 -->
			<div class="content2 content4" v-if="tabs_index == 1">
				<el-collapse accordion v-for="(item,index) in branch_data" style="padding: 2px 0 0 10px;">
				  <el-collapse-item>
					<template slot="title">{{item.department}}（{{item.count}}）</template>
					<div v-for="(item2,index2) in item.user" @click="choose(item2.username,'',item2.phone,item2.uid,index2,'')" class="line2" style="padding: 0 0 0 6px;">
						<img src="/static/img/chat/img/tu.png" class="head_img2">
						<span class="name" style="margin-bottom: 0;">{{item2.username}}</span>
						<div class="txt2">{{item2.phone}}</div>
					</div>
				  </el-collapse-item>
				</el-collapse>
			</div>
				
			<!-- 任务 -->
			<div class="content2 content3" v-if="tabs_index == 2">
				<el-collapse class="col" accordion style="margin-top: 2px;background-color: #7e73b5;">
				  <el-collapse-item style="text-align: center;">
					<template slot="title">{{checked_name}}</template>
					<div class="line3" v-for="(item,index) in data" @click="task_type(index,item.name)">
						<span style="float: left;">{{item.name}}</span>
						<img :src="checked_index == index ? '/static/img/chat/img/yes.png' : ''" width="12" style="float: right;margin-top: 6px;">
						<div style="clear: both;"></div>
					</div>
				  </el-collapse-item>
				</el-collapse>
				<div class="line4" v-for="(item,index) in task_data">
				   <el-checkbox @change="update_task(item.id)" v-model="item.status" v-if="checked_index == 0 || checked_index == 1">{{item.content}}</el-checkbox>
				   <span style="float: right;color: gray;">已看</span>
				   <div class="box3">
						<img v-for="item1 in item.img" :src="item1" >
				   </div>
				   <div class="txt">{{item.create_time}} {{item.foot}}</div>
				</div>
			</div>	
		</div>
		</transition>
		
		
		<!-- 聊天窗口 -->
		<transition name="el-zoom-in-top">
			<div class="chat_boxes divWrap" v-if="chat_show2">
				<div class="header3" id="move2" :style="{backgroundColor:(backg ? backg : '#6256a3')}">
					<div class="search3">
						<img src="/static/img/chat/img/search2.png" >
						<input type="text" name="" id="" value="" placeholder="搜索" :style="{backgroundColor:(backg ? backg : '#6256a3')}" />
					</div>
					<div class="title_s">{{title_name}}</div>
					<div class="right_div">
						<img src="/static/img/chat/img/1.png" @click="chat_show2 = false">
						<img src="/static/img/chat/img/close.png" @click="chat_show2 = false">
					</div>
				</div>
				
				<ul class="listbox">
					<li v-for="(item,index) in news" @click="get_record(item.g_id,index)" :class="s_index == index ? 'active2' : ''" >
						<div style="height: 6px;"></div>
						<span class="circle" style="margin-top: 0;"></span>
						<div>{{item.name}}<span class="s2">{{item.times}}</span></div>
						<div class="txt">{{item.txt}}</div>
						<!-- <span class="s3">
							<img src="/static/img/chat/img/del1.png" class="del">
						</span> -->
					</li>						
				</ul>
				
				<!-- 聊天窗口 -->
				<div class="chatroom2" :class="is_edit ? 'chatroom3' : 'chatroom2'">
					<el-tabs v-model="activeName" @tab-click="handleClick" class="tab1">
						<el-tab-pane label="聊天" name="1" class="down2">
							<div v-for="(item,index) in record_data">
								<div class="times">{{item.date}}</div>
								<div v-for="(item1,index) in item.data">
									<div v-if="item1.side == 1">
										<span class="circle" style="float: right;"></span>
										<div v-if="item1.type == 1" class="ask reply">{{item1.msg}}</div>
										<div v-if="item1.type == 2" class="ask_img"><img :src="item1.msg"></div>
										<div v-if="item1.type == 3" class="ask reply">{{item1.msg}}</div>
										<div v-if="item1.type == 4" class="ask_img"><img src="/static/img/chat/img/wenjian.jpg" ></div>
									</div>
									<div v-if="item1.side == 2" style="margin-bottom: 10px;">
										<span class="circle"></span>
										<div v-if="item1.type == 1" class="ask">{{item1.msg}}</div>
										<div v-if="item1.type == 2" class="ask_img"><img :src="item1.msg" width="100"></div>
										<div v-if="item1.type == 3" class="ask">{{item1.msg}}</div>
										<div v-if="item1.type == 4" class="ask_img"><img src="/static/img/chat/img/wenjian.jpg" ></div>
									</div>
									<div style="clear: both;"></div>
								</div>
								<div style="height: 45px;"></div>
							</div>
						</el-tab-pane>
						
						<el-tab-pane label="任务" name="2">
							<div style="float: left;width: 330px;border-right: 2px solid #e8e5ff;">
								<div class="title_s">选择抄送人</div>
								<div class="search">
									<img src="/static/img/chat/img/search.png" >
									<input type="text" name="" id="" value="" placeholder="搜索" />
								</div>
								<div class="box2" v-for="(item,index) in database">
									<el-checkbox @change="select_people(index,item.status,item.uid)" v-model="item.status" style="position: absolute;right: 0;"></el-checkbox>
									<img src="/static/img/chat/img/tu.png" width="36">
									<span class="name2">{{item.username}}</span>
								</div>
							</div>
							<div class="right_box">
								<div v-for="item in before_task">
									<div>{{item.content}}</div>
									<img v-for="item1 in item.img" :src="item1" class="img1">
									<div class="time2">{{item.time}} <span>{{item.is_status}}</span></div>
									<div class="shrink">
										<span v-for="item3 in item.task_detail">{{item3}}</span>
									</div>
									<!-- <div class="more">更多 <img src="/static/img/chat/img/duo.png" ></div> -->
									<div style="clear: both;height: 30px;"></div>
								</div>
								
							</div>
						</el-tab-pane>
						
						<el-tab-pane label="公告" name="3">
							<div class="notice" v-for="(item,index) in database">
								<div class="txt5">{{item.msg}}</div>
								<span class="s5">{{item.msg}}发表于 {{item.create_time}}</span>
								<div style="clear: both;"></div>
							</div>
							<div style="height: 30px;"></div>
						</el-tab-pane>
						<el-tab-pane label="图片" name="4">
							<div class="img_box" v-for="item in database">
								<div class="title_s">{{item.create_time}}</div>
								<img v-for="item2 in item.img" :src="item2" >
							</div>
						</el-tab-pane>
						<el-tab-pane label="文件" name="5">
							<div style="margin: 10px 0 0 4px;">共{{database.length}}份文件</div>
							<table class="table1" style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<th>名称</th>
									<th>上传时间</th>
									<th>上传者</th>
									<th>下载</th>
								</tr>
								<tr v-for="item in database">
									<td>{{item.file_name}}</td>
									<td>{{item.create_time}}</td>
									<td>{{item.username}}</td>
									<td><a href="item.msg" :download="item.file_name"><img src="/static/img/chat/img/download.png" ></a></td>
								</tr>
							</table>
						</el-tab-pane>
					</el-tabs>
					
		
					<div class="write" v-if="activeName == 1">
						<form id="forms111" action="" enctype="multipart/form-data" method="post">
							<span style="position: relative;">
								<img src="/static/img/chat/img/img1.png" class="img3" @mouseover="img_file = 1">
								<input type="file" accept="image/*" name="img_upload" @change="upload_imgs" class="img_upload1" v-if="img_file == 1" />
								<input type="hidden" name="filename" value="img_upload" v-if="img_file == 1" >
								<input type="hidden" name="type" value="2" v-if="img_file == 1" />
							</span>
							
							<span style="position: relative;">
								<img src="/static/img/chat/img/wen2.png" class="img3" @mouseover="img_file = 2">
								<input type="file" name="img_upload" @change="upload_imgs" class="img_upload1" v-if="img_file == 2" />
								<input type="hidden" name="filename" value="img_upload" v-if="img_file == 2">
								<input type="hidden" name="type" value="4" v-if="img_file == 2" />
							</span>
							<img src="/static/img/chat/img/yin3.png" class="img3">
							<img src="/static/img/chat/img/record.png" style="float: right;">
						</form>
						<textarea v-model="edit_msg" rows="0" cols="0"></textarea>
						<div class="btn3">
							<button @click="send_msg(1)">发送</button>
						</div>
					</div>
					
					<!-- 多图上传 -->                                                                                                            
					<div class="write" v-if="activeName == 2">
						<form id="forms2" action="" enctype="multipart/form-data" method="post">
							<span style="position: relative;">
								<img src="/static/img/chat/img/img1.png" class="img3" @mouseover="img_file = 1">
								<input type="file" @change="imgs_upload" name="img_upload[]" multiple="multiple" accept="image/*" class="img_upload1" v-if="img_file == 1" />
								<input type="hidden" name="filename" value="img_upload" v-if="img_file == 1">
								<input type="hidden" name="type" value="2"  v-if="img_file == 1" />
							</span>
							<span style="position: relative;">
								<img src="/static/img/chat/img/wen2.png" class="img3" @mouseover="img_file = 2">
								<input type="file" @change="imgs_upload" name="img_upload[]" multiple="multiple" class="img_upload1" v-if="img_file == 2">
								<input type="hidden" name="filename" value="img_upload" v-if="img_file == 2">
								<input type="hidden" name="type" value="4" v-if="img_file == 2" />
							</span>
							<img src="/static/img/chat/img/yin3.png" class="img3" >
							<img src="/static/img/chat/img/record.png" style="float: right;">
						</form>
						<textarea v-model="edit_msg2" rows="0" cols="0"></textarea>
						<div class="btn3">
							<button @click="send_msg2">发送</button>
						</div>
					</div>
					
					<!-- 公告 -->
					<div class="write" v-if="activeName == 3">
						<form id="forms2" action="" enctype="multipart/form-data" method="post">
							<span style="position: relative;">
								<img src="/static/img/chat/img/img1.png" class="img3" @mouseover="img_file = 1">
								<input type="file" @change="imgs_upload" name="img_upload[]" multiple="multiple" accept="image/*" class="img_upload1" v-if="img_file == 1" />
								<input type="hidden" name="filename" value="img_upload" v-if="img_file == 1">
								<input type="hidden" name="type" value="2"  v-if="img_file == 1" />
							</span>
							
							<span style="position: relative;">
								<img src="/static/img/chat/img/wen2.png" class="img3" @mouseover="img_file = 2">
								<input type="file" @change="imgs_upload" name="img_upload[]" multiple="multiple" class="img_upload1" v-if="img_file == 2">
								<input type="hidden" name="filename" value="img_upload" v-if="img_file == 2">
								<input type="hidden" name="type" value="4" v-if="img_file == 2" />
							</span>
							<img src="/static/img/chat/img/yin3.png" class="img3" >
							<img src="/static/img/chat/img/record.png" style="float: right;">
						</form>
						<textarea v-model="edit_msg3" rows="" cols=""></textarea>
						<div class="btn3">
							<button @click="send_msg3">发送</button>
						</div>
					</div>
				</div>
			</div>
		</transition>
		
		<!-- 更改聊天弹窗背景 -->
		<el-dialog
		  title="更换背景"
		  custom-class="dialog"
		  :modal="false"
		  top="30vh"
		  :visible.sync="back_dialogVisible"
		  width="25%">
		  <div class="box5">
			  <!-- <img src="/static/img/chat/img/logo.png" @click="backg = '/static/img/chat/img/queren.png'"> -->
			  <span v-for="(item,index) in bgColor" @click="update_bg(item,index+1)" :style="{background:item}"></span>
		  </div>
		  <span slot="footer" class="dialog-footer">
			<el-button @click="back_dialogVisible = false" size="mini">取 消</el-button>
			<el-button type="primary" @click="back_dialogVisible = false" size="mini">确 定</el-button>
		  </span>
		</el-dialog>
		
		<!-- 任务弹框 -->
		<el-dialog
		  :visible.sync="service_dialogVisible"
		  width="60%">
		  <div>发布人：{{task_alert.username}}</div>
		  <div>任务内容：{{task_alert.content}}</div>
		  <div>
			  <img v-for="img in task_alert.img" :src="img" style="max-width: 150px;margin-left: 10px;" alt="">
		  </div>
		  <div v-if="task_alert.file != ''">
			  <a v-for="(fj,index) in task_alert.file" :href="fj">附件{{index+1}}</a>
		  </div>
		  <span slot="footer" class="dialog-footer">
		    <el-button @click="service_dialogVisible = false" size="mini">取 消</el-button>
		    <el-button type="primary" @click="service_dialogVisible = false" size="mini">确 定</el-button>
		  </span>
		</el-dialog>
		
		<!-- 公告弹框 -->
		<el-dialog
		  :visible.sync="service_dialogVisible1"
		  width="60%">
		  <div>发布人：{{public_alert.username}}</div>
		  <div>任务内容：{{public_alert.msg}}</div>
		  <div>
			  <img v-for="img in public_alert.img" :src="img" style="max-width: 150px;margin-left: 10px;" alt="">
		  </div>
		  <div v-if="public_alert.file != ''">
			  <a v-for="(fj,index) in public_alert.file" :href="fj">附件{{index+1}}</a>
		  </div>
		  <span slot="footer" class="dialog-footer">
		    <el-button @click="service_dialogVisible1 = false" size="mini">取 消</el-button>
		    <el-button type="primary" @click="service_dialogVisible1 = false" size="mini">确 定</el-button>
		  </span>
		</el-dialog>
	</div>
</template>

<script>
export default {
    data() {
        return {
			token:'',
			service_dialogVisible:false,   // 任务弹框
			service_dialogVisible1:false,  // 公告弹框
			cut_index:1,//周月切换
			cut_index2:1,
			img_list:[
				{img1:'/static/img/chat/img/t1.png',img2:'/static/img/chat/img/t11.png'},
				{img1:'/static/img/chat/img/t2.png',img2:'/static/img/chat/img/t22.png'},
				{img1:'/static/img/chat/img/t3.png',img2:'/static/img/chat/img/t33.png'},
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
			img_file:1,//上传的类型 图片,文件
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
			edit_msg3:'',
			imgs_data:[],
			files_data:[],
			arrs:[],
			people:[],
			task_list:[],
			before_task:[],
			user_id:'',
			online_num:[],
			task_alert:'',  // 任务弹框数据
			public_alert:'', // 公告弹框数据
        };
    },
    created(){
		this.token = this.$cookies.get('token')
    	this.get_userid()
    },
    mounted() {
    	// this.get_echarts1()
    	// this.get_echarts2()
    	// this.get_echarts3()
    	
    	this.get_date()
    	this.drag()
    },
    methods:{
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
        				url: "http://txapi.gymbaby.cn/index/task/publish",
        				data: {
        					from_id: self.uid,
        					img: self.imgs_data,
							file:self.files_data,
        					content: self.edit_msg2,
        					to_ids: self.people,
							online_num:self.online_num
        				},
        				success: function (res) {
        					if(res.status == 1){
        						self.edit_msg2 = ''
								self.imgs_data = [];
        					}
        				},
        				fail: function (err){
        					console.log(err)
        				}
        			});
        		}else if(self.img_file == 2){
        			$.ajax({
        				type: 'POST',
        				url: "http://txapi.gymbaby.cn/index/task/publish",
        				data: {
        					from_id: self.uid,
        					img: self.imgs_data,
							file:self.files_data,
        					content: self.edit_msg2,
        					to_ids: self.people,
							online_num:self.online_num
        				},
        				success: function (res) {
        					if(res.status == 1){
        						self.edit_msg2 = ''
								self.imgs_data = [];
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
        // 发送公告
        send_msg3(){
        	let self = this;
        	$.ajax({
        		type: 'POST',
        		url: "http://txapi.gymbaby.cn/index/connect/release",
        		data: {
        			uid: self.uid,
        			img: self.imgs_data,
        			msg: self.edit_msg3,
        			file:self.files_data
        		},
        		success: function (res) {
        			if(res.status == 1){
        				self.edit_msg3 = ''
        				self.imgs_data = [];
        				self.files_data = [];
        			}
        		},
        		fail: function (err){
        			console.log(err)
        		}
        	});
        },
		get_task(){
        	let self = this
        	$.ajax({
        		type: 'POST',
        		url: "http://txapi.gymbaby.cn/index/task/past",
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
        	var formData = new FormData($('#forms2')[0]);
        	let self = this
        	$.ajax({      
        	    url:'http://txapi.gymbaby.cn/index/upload/file_uploads',
        	    type:"post",
        	    async:false,
        	    processData: false, // 不处理发送的数据，因为data值是Formdata对象，不需要对数据做处理
        	    contentType: false, // 不设置Content-type请求头
        	    data:formData,
        	    success:function(res){
        	    	if(res.status == 1){
        	    		if(self.img_file == 1){
        	    			self.imgs_data = res.data
        	    		}
        	    		if(self.img_file == 2){
        	    			self.files_data = res.data;
        	    		}
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
        		url: "http://txapi.gymbaby.cn/index/index/up_background",
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
        		url: "http://txapi.gymbaby.cn/index/task/complete",
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
        	if(i != 2){
				$.ajax({
					type: 'POST',
					url: "http://txapi.gymbaby.cn/index/task/present_task",
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
			}else{
				$.ajax({
					type: 'POST',
					url: "http://txapi.gymbaby.cn/index/task/past",
					data: {
						uid: self.uid,
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
			}
        	
        	
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
        upload_imgs(e){
			if(this.img_file == 1){ //判断上传的是文件 图片
				 this.upload_type = 2
			}else{
				 this.upload_type = 4
			}
			
        	var formData1 = new FormData($('#forms111')[0]);
        	let self = this
        	$.ajax({
        	    url:'http://txapi.gymbaby.cn/index/upload/file_uploads',
        	    type:"post",
        	    async:false,
        	    processData: false, // 不处理发送的数据，因为data值是Formdata对象，不需要对数据做处理
        	    contentType: false, // 不设置Content-type请求头
        	    data:formData1,
        	    success:function(res){
        	    	if(res.status == 1){
        			   self.oldname = res.oldname
        			   self.send_server(res.data)
        			}
        	    },
        		fail: function (err) {
        		    console.log(err)
        		}
        	})
        },
		get_userid(){
			let self = this
			$.ajax({
				type: 'POST',
				url: "http://www.saas.com/index/login/get_id",
				data: {
					token:self.token
				},
				success: function (res) {
					if(res.status == 1){
						self.user_id = res.user_id
						self.get_uid()
					}
				},
				fail: function (err){
					console.log(err)
				}
			});
		},
        // 获取uid
        get_uid(){
        	let self = this
        	$.ajax({
        		type: 'POST',
        		url: "http://txapi.gymbaby.cn/index/index/get_uid",
        		data: {
        			user_id: self.user_id,
        			type: 2
        		},
        		success: function (res) {
        			if(res.status == 1){
        				self.uid = res.uid
        				self.get_socket()
        				let i = res.background
        				self.backg = self.bgColor[i-1]
						self.issee_task();
						self.public_input();
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
        		url: "http://txapi.gymbaby.cn/index/index/send_msg",
        		data: {
        			from: self.uid,
        			to: self.get_id,
        			msg: m,
        			sendtype: 2,
        			type: self.upload_type,
        			oldname: self.oldname,
					online_num:self.online_num
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
        		url: "http://txapi.gymbaby.cn/index/connect/message_log",
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
        		url: "http://txapi.gymbaby.cn/index/index/update_read_status",
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
        		// this.get_list('http://txapi.gymbaby.cn/index/task/get_person')
        		this.get_list('http://txapi.gymbaby.cn/index/task/get_person')
        		this.get_task()
        	}else if(num == 3){
        		this.get_list('http://txapi.gymbaby.cn/index/connect/past_notice')
        	}else if(num == 4){
        		this.get_list('http://txapi.gymbaby.cn/index/connect/img_log')
        	}else if(num == 5){
        		this.get_list('http://txapi.gymbaby.cn/index/connect/file_log')
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
        get_linkman(){
        	let self = this
        	$.ajax({
        		type: 'POST',
        		url: "http://txapi.gymbaby.cn/index/connect/get_call",
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
        		url: "http://txapi.gymbaby.cn/index/connect/conn_person",
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
				if(msg.msg_type == 1){
					self.new_msg = msg
					if(self.select_id == msg.from){
						self.get_reddot(self.select_id)
					}else{
						self.get_linkman()
					}							
				}else if(msg.msg_type == 2){
					// 调用弹出任务框
					// console.log(msg);
					self.task_input(msg.input_id);
				}else if(msg.msg_type == 3){
					self.public_input();
					console.log(msg);
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
		// 公告弹框请求接口
		public_input(){
			let self = this;
			$.ajax({
				url:'http://txapi.gymbaby.cn/index/connect/check_notice',
				type:'post',
				dataType:'json',
				data:{
					uid:self.uid,
				},
				success:function(data){
					if(data.status == 1){
						self.service_dialogVisible1 = true;
						self.public_alert = data.data;		
						self.issee_public(data.data.id)
					}
				}
			})
		},
		// 判断当前用户是否已查看公告
		issee_public(msg_id){
			let self = this;
			$.ajax({
				url:'http://txapi.gymbaby.cn/index/connect/see_notice',
				type:'post',
				dataType:'json',
				data:{
					uid:self.uid,
					msg_id:msg_id
				},
				success:function(data){
					
				}
			})
		},
		// 任务弹框请求的接口
		task_input(task_id){
			let self = this;
			$.ajax({
				url:'http://txapi.gymbaby.cn/index/task/task_see',
				type:'post',
				dataType:'json',
				data:{
					uid:self.uid,
					task_id:task_id
				},
				success:function(data){
					self.service_dialogVisible = true;
					self.task_alert = data.data;
				}
			})
		},
        // 判断当前用户是否有任务没有查看
        issee_task(){
			let self = this;
			$.ajax({
				url:'http://txapi.gymbaby.cn/index/task/issee_task',
				type:'post',
				dataType:'json',
				data:{
					uid:self.uid,
				},
				success:function(data){
					if(data.status == 1){
						self.task_input(data.task_id);
					}
				}
			})
        },
		// 每日在线人数
        on_line(num){
        	$.ajax({
        		type: 'POST',
        		url: "http://txapi.gymbaby.cn/index/First/on_line",
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
	    	if(this.activeName == 4 || this.activeName == 5){
	    		this.is_edit = false
	    		$('.el-tabs__content').height(360)
	    	}else{
	    		$('.el-tabs__content').height(280)
	    		this.is_edit = true
	    	}
	    },
	}

    }
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
	li{
		list-style: none;
	}
	.box1{
		border: 1px solid #d4d0e5;
		border-top: none;
		font-weight: bold;
		padding-top: 5px;
		box-shadow: 1px 2px 2px #d4d0e5;
	}
	.box1 .imgs{
		float: left;
		margin: -1px 10px 18px 0;
	}
	.box1 .txt{
		float: right;
		width: 50px;
		text-align: center;
	}
	.box1 .txt2{
		float: none;
		display: inline-block;
	}
	.box4 .small_title{
		float: left;
		margin-right: 20px;
		margin-top: -5px;
	}
	.box4 .line{
		clear: both;
		margin: 25px 0 0 15px;
		position: relative;
	}
	.box4 .line .right_txt{
		position: absolute;
		top: -4px;
		right: 22px;
	}
	
	.box1 .right_text{
		float: right;
		margin-right: 15px;
		width: 65px;
		font-size: 12px;
		display: flex;
	}
	.box1 .right_text span{
		cursor: pointer;
		flex: 1;
		text-align: center;
	}
	.box1 .right_text .isActive{
		color: #6357a3 !important;
	}
	
	
	.box4 .el-progress-bar{
		width: 85%;
	}
	
	.right_bottom{
		position: fixed;
		right: 10px;
		bottom: 20px;
		display: inline-block;
		width: 115px;
		height: 52px;
		line-height: 56px;
		border-radius: 5px;
		background-color: #fff;
		border: 2px solid #e9e5ff;
		box-shadow: 2px 2px 2px #6256a3;
		cursor: pointer;
		z-index: 999;
	}
	.right_bottom img{
		float: left;
		margin-top: 4px;
		margin-left: 15px;
	}
	.right_bottom span{
		margin-left: 10px;
	}
	
	.chatroom{
		width: 220px;
		height: 420px;
		background-color: #fff;
		display: inline-block;
		position: fixed;
		right: 8px;
		bottom: 6px;
		z-index: 9999;
		border: 1px solid gainsboro;
	}
	
	.chatroom .header2{
		height: 37px;
		/* background-color: #6256a3; */
		padding: 0 10px;
	}
	.chatroom .border5{
		width: 100%;
		height: 1px;
		background: #fff;
		transform: scaleY(0.5);
	}
	.chatroom .header2 .head_img{
		width: 16px;
		float: left;
		margin-top: 8px;
		margin-left: 3px;
	}
	.chatroom .header2 span{
		float: right;
		margin-top: 14px;
	}
	.header2 span img{
		margin-left: 9px;
		cursor: pointer;
	}
	.chatroom .search{
		clear: both;
		height: 30px;
		padding: 3px 0;
		/* background-color: #7e72b5; */
	}
	.chatroom .search img{
		float: left;
		margin-top: 7px;
		margin-left: 15px;
	}
	.chatroom .search input{
		width: 170px;
		height: 23px;
		background-color: transparent;
		/* background-color: #7e72b5; */
		border: 0;
		outline: none;
		padding-left: 10px;
		box-sizing: border-box;
		color: #fff;
		font-size: 12px;
	}
	.chatroom .search input::-webkit-input-placeholder{
		color: #fff;
	}
	.chatroom .tabs{
		height: 30px;
		padding: 9px 15px 0;
		box-sizing: border-box;
		display: flex;
		box-shadow: 0 3px 2px #e9e5ff;
	}
	.chatroom .tabs li{
		flex: 1;
		text-align: center;
		
	}
	.chatroom .tabs .active{
		border-bottom: 2px solid #6256a3;
	}
	.chatroom .content2{
		height: 325px;
		overflow-y: scroll;
	}
	.chatroom .content4 .el-collapse-item__header{
		width: 100%;
		height: 35px;
		line-height: 35px;
		display: inline-block;
	}
	.chatroom .content4 .el-collapse-item__arrow{
		line-height: 35px;
	}
	.chatroom .content4 .el-collapse-item__content{
		padding-bottom: 10px;
	}
	.chatroom .content4 .txt2{
		height: 18px;
	}
	.chatroom .content4 .line2{
		margin-top: 5px;
	}
	.chatroom .content3 .el-collapse-item__header{
		width: 85px;
		height: 30px;
		line-height: 30px;
		display: inline-block;
		background: none;
		border: 0;
		color: #fff;
	}
	.chatroom .content3 .el-collapse-item__content{
		padding-bottom: 0;
	}
	.chatroom .content3 .el-collapse-item__arrow{
		line-height: 30px;
	}
	.chatroom .content3 .el-checkbox__label{
		font-size: 12px;
	}
	.chatroom .content3 .box3{
		margin-left: 15px;
	}
	.content3 .box3 img{
		height: 45px;
		margin-left: 8px;
	}
	.chatroom .content3 .line4{
		margin:12px 0 0 10px;
		font-size: 12px;
	}
	.content3 .line4 .txt{
		color: gray;
		margin-top: 3px;
		margin-left: 20px;
	}
	.chatroom .line2{
		padding: 6px 0 6px 10px;
		font-size: 12px;
		cursor: pointer;
		box-sizing: border-box;
	}
	.chatroom .line3{
		padding: 2px 10px;
		font-size: 12px;
		cursor: pointer;
		box-sizing: border-box;
		color: #fff;
		background-color: #7e73b5;
		border-top: 1px solid #988fc4;
	}
	.chatroom .line2:hover{
		background-color: gainsboro;
	}
	.chatroom .line2 .head_img2{
		float: left;
		width: 28px;
		margin-right: 10px;
	}
	.chatroom .line2 .name{
		display: inline-block;
		margin-top: 3px;
		margin-bottom: 1px;
	}
	.chatroom .line2 .time2{
		float: right;
		color: gray;
		margin-top: 5px;
	}
	.chatroom .line2 .txt2{
		height: 20px;
		color: gray;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}
	
	
	.circle{
		display: inline-block;
		width: 26px;
		height: 26px;
		background-color: #7368ac;
		border-radius: 50%;
		float: left;
		margin-right: 10px;
		margin-top: -2px;
	}
	
	.chat_boxes{
		width: 670px;
		height: 460px;
		background-color: #fff;
		display: inline-block;
		border: 1px solid gainsboro;
		position: absolute;
		top: calc(50% - 235px);
		left:calc(50% - 340px);
		box-shadow: 5px 5px 5px gray;
		z-index: 999;
	}
	.chat_boxes .header3{
		width: 100%;
		height: 40px;
		/* background-color: #6256a3; */
		padding: 14px 12px 6px 14px;
		box-sizing: border-box;
		text-align: center;
		color: #fff;
		cursor: move;
	}
	.header3 .search3{
		float: left;
		width: 140px;
		display: inline-block;
	}
	.header3 .search3 img{
		margin-top: 2px;
		float: left;
	}
	.header3 .search3 input{
		float: left;
		width: 110px;
		padding-left: 8px;
		outline: none;
		/* background-color: #6256a3; */
		border: 0;
		font-size: 12px;
		color: white;
	}
	.header3 .search3 input::-webkit-input-placeholder{
		color: #fff;
	}
	.chat_boxes .header3 .title_s{
		display: inline-block;
		text-align: center;
	}
	.chat_boxes .header3 .right_div{
		width: 120px;
		text-align: right;
		float: right;
	}
	.header3 .right_div img{
		vertical-align: middle;
		cursor: pointer;
		margin-left: 8px;
	}
	.chat_boxes .listbox{
		width: 160px;
		height: 418px;
		overflow-y: scroll;
		border-right: 2px solid #e0dded;
		display: inline-block;
		margin-top: 1px;
		font-size: 12px;
	}
	.chat_boxes .listbox li{
		/* padding: 6px 0 6px 8px; */
		padding-left: 8px;
		height: 45px;
		cursor: pointer;
		box-sizing: border-box;
		position: relative;
	}
	.chat_boxes .listbox .active2{
		background-color: #e5e5e5;
	}
	
	.red_dot{
		display: inline-block;
		width: 7px;
		height: 7px;
		background-color: red;
		border-radius: 50%;
		float: right;
		margin-top: 6px;
		margin-right: 2px;
	}
	
	.chat_boxes .listbox .s3{
		float: right;
		margin-top: -15px;
		margin-right: 8px;
		display: inline-block;
		width: 30px;
		opacity: 0;
		z-index: 9999;
		background-color: #e5e5e5;
		text-align: right;
	}
	.listbox li:hover .s3{
		opacity: 1;
	}
	.chat_boxes .listbox li:hover{
		background-color: #e5e5e5;
	}
	
	.chat_boxes .listbox .txt{
		width: 120px;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		transform: scale(0.8);
		color: #999999;
		position: absolute;
		left: 31px;
	}
	.chat_boxes .listbox .s2{
		float: right;
		transform: scale(0.9);
		color: #999999;
		margin-top: 2px;
	}
	.chat_boxes .listbox .del{
		margin-top: 9px;
	}
	.chat_boxes .chatroom2{
		float: right;
		width: calc(100% - 163px);
		/* height: 320px;
		overflow-y: scroll; */
		color: gray;
	}
	
	
	.chat_boxes .chatroom2 .el-tabs__header{
		margin: 0;
	}
	.chat_boxes .chatroom2 .el-tabs__nav-wrap{
		padding-left: 10px;
	}
	.chat_boxes .chatroom2 .el-tabs__content{
		margin-left: 10px;
	}
	.chat_boxes .chatroom2 .title_s{
		margin-top: 10px;
	}
	.chat_boxes .chatroom2 .search{
		width: 190px;
		height: 28px;
		background-color: #f1f1f1;
		padding-left: 15px;
		box-sizing: border-box;
		font-size: 12px;
		margin: 8px 0;
	}
	.chatroom2 .search img{
		float: left;
		margin: 5px 7px 0 0;
	}
	.chatroom2 .search input{
		width: 150px;
		height: 98%;
		border: 0;
		outline: none;
		background-color: #f1f1f1;
	}
	.chatroom2 .el-checkbox__input.is-checked .el-checkbox__inner, .el-checkbox__input.is-indeterminate .el-checkbox__inner{
		border-radius: 50%;
	}
	.chatroom2 .el-checkbox__inner{
		border-radius: 50%;
	}
	.chatroom2 .right_box{
		float: right;
		width: 150px;
		/* height: 262px;
		overflow-y: scroll; */
		padding: 9px 11px;
	}
	.chatroom2 .right_box .img1{
		width: 50px;
		height: 50px;
		margin-bottom: 5px;
	}
	.right_box .img1:nth-child(2n+1){
		margin-left: 8px;
	}
	.right_box .time2 span{
		display: inline-block;
		transform: scale(0.8);
		float: right;
		margin-top: -2px;
	}
	.right_box .shrink{
		transform: scale(0.8);
		float: left;
		margin-left: -6px;
	}
	.chatroom2 .right_box .more{
		clear: both;
		float: right;
		transform: scale(0.9);
		cursor: pointer;
	}
	
	
	.chatroom2 .box2{
		display: inline-block;
		width: 60px;
		position: relative;
		text-align: center;
	}
	.chatroom2 .box2 .name2{
		display: inline-block;
		width: 60px;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}
	.chatroom3{
		height: 420px !important;
	}
	.chatroom2 .img_box .title_s{
		margin-top: 10px;
		margin-left: 10px;
	}
	.chatroom2 .img_box img{
		width: 74px;
		margin-left: 10px;
		margin-top: 10px;
	}
	.chatroom2 .table1 tr:first-child{
		background-color: #f3f3f3;
	}
	.chatroom2 .table1 th{
		padding: 5px 3px;
		text-align: center;
	}
	.chatroom2 .table1 th:first-child{
		text-align: left;
	}
	.chatroom2 .table1 td{
		text-align: center;
		padding: 5px;
	}
	.chatroom2 .table1 td:first-child{
		text-align: left;
	}
	.chatroom2 .notice{
		border-radius: 5px;
		margin-top: 12px;
		margin-right: 10px;
		padding: 10px;
		box-sizing: border-box;
		background-color: #f1f1f1;
	}
	.chatroom2 .notice .txt5{
		margin-right: 30px;
	}
	.chatroom2 .notice .s5{
		margin-top: 20px;
		float: right;
	}
	
	.chat_boxes .times{
		margin: 20px 0;
		text-align: center;
	}
	.chat_boxes .ask{
		display: inline-block;
		line-height: 20px;
		min-height: 30px;
		border-radius: 5px;
		background-color: #f1f1f1;
		padding: 9px 20px 9px 15px;
		box-sizing: border-box;
	}
	.chat_boxes .ask_img{
		display: inline-block;
		margin-bottom: 15px;
		margin-right: 10px;
		float: right;
	}
	.chat_boxes .ask_img img{
		max-width: 160px;
	}
	.chat_boxes .reply{
		background-color: #e8e5ff;
		float: right;
		margin-right: 10px;
		margin-bottom: 10px;
	}
	.chat_boxes .write{
		position: absolute;
		bottom: 0;
		right: 10px;
		width: calc(100% - 171px);
		height: 100px;
		border-top: 2px solid #e9e5ff;
		padding: 10px;
		box-sizing: border-box;
		z-index: 999;
		background-color: #fff;
	}
	.chat_boxes .write img{
		cursor: pointer;
	}
	.chat_boxes .write .img3{
		margin-right: 10px;
	}
	.chat_boxes .write textarea{
		width: 100%;
		height: 35px;
		font-size: 12px;
		border: 0;
		outline: none;
		resize: none;
		margin-top: 3px;
	}
	.chat_boxes .write .btn3{
		display: inline-block;
		float: right;
	}
	.write .btn3 button{
		width: 50px;
		height: 22px;
		line-height: 22px;
		text-align: center;
		background-color: #6256a3;
		color: #fff;
		border-radius: 3px;
		border: 0;
		cursor: pointer;
		font-size: 12px;
	} 
	.write .img_upload1{
		position: absolute;
		left: 0;
		top: -3px;
		width: 18px;
		height: 15px;
		opacity: 0;
		cursor: pointer;
	}
	
	
	
	.dialog .el-dialog__header{
		background-color: #7C72C1;
		padding: 10px 20px;
	}
	.dialog .el-dialog__body{
		padding: 10px 20px;
	}
	.dialog .el-dialog__headerbtn{
		font-size: 20px;
		top: 13px;
	}
	.dialog .el-dialog__header .el-dialog__title{
		color: #fff;
	}
	
	.dialog .box5 span{
		display: inline-block;
		width: 90px;
		height: 85px;
		margin-left: 20px;
		margin-top: 13px;
		cursor: pointer;
	}
	.dialog .box5 span:hover{
		opacity: 0.7;
	}
	
</style>
<style>
	.tab1 .el-tabs__content{
		height: 280px;
		overflow-y: scroll;
	}
	.tab1 .el-tabs__header{
		margin: 0 !important;
	}
	.col .el-collapse-item__header{
		width: 100%;
		height: 35px;
		line-height: 35px;
		display: inline-block;
	}
	.col .el-collapse-item__arrow{
		line-height: 35px;
	}
	.col .el-collapse-item__content{
		padding-bottom: 10px;
	}
</style>
