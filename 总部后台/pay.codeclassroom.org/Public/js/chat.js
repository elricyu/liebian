$(function(){
	var tmp = `<div id="wrap">
			<div class="right_bottom" @click='get_linkman'>
				<img src="http://www.c.com/public/tx/img/tu.png" ><span>新消息</span>
			</div>
						<transition name="el-zoom-in-top">
			<div class="chatroom" v-if="chat_show">
				<div :style="{backgroundColor:(backg ? backg : '#6256a3')}">
					<div class="header2">
						<img src="http://www.c.com/public/tx/img/tu.png" class="head_img">
						<span>
							<img src="http://www.c.com/public/tx/img/bei.png" @click="back_dialogVisible = true">
							<img src="http://www.c.com/public/tx/img/audio.png" >
							<img src="http://www.c.com/public/tx/img/close.png" @click="chat_show = false">
						</span>
					</div>
					<div class="border5"></div>
					<div class="search">
						<img src="http://www.c.com/public/tx/img/search2.png" >
						<input type="text" name="" id="" value="" placeholder="搜索" />
					</div>
				</div>
				<ul class="tabs">
					<li v-for="(item,index) in img_list" @click="tabs_index = index">
						<img :src="tabs_index == index ? item.img1 : item.img2" >
					</li>
				</ul>
				
			
				<div class="content2" v-if="tabs_index == 0" style="margin-top: 8px;">
					<div v-for="(item,index) in linkman_data" @click="choose(item.username,item.times,item.msg,item.to,index,item.read_status)" class="line2">
						<img src="http://www.c.com/public/tx/img/tu.png" class="head_img2">
						<span class="time2">{{item.times}}</span>
						<div class="name">{{item.username}}</div>
						<div class="txt2">{{item.msg}} <span v-if="item.read_status == 1" class="red_dot"></span></div>
					</div>
				</div>
				
				<div class="content2 content4" v-if="tabs_index == 1">
					<el-collapse accordion v-for="(item,index) in branch_data" style="padding: 2px 0 0 10px;">
					  <el-collapse-item>
						<template slot="title">{{item.department}}（{{item.count}}）</template>
						<div v-for="(item2,index2) in item.user" @click="choose(item2.username,'',item2.phone,item2.uid,index2,'')" class="line2" style="padding: 0 0 0 6px;">
							<img src="http://www.c.com/public/tx/img/tu.png" class="head_img2">
							<span class="name" style="margin-bottom: 0;">{{item2.username}}</span>
							<div class="txt2">{{item2.phone}}</div>
						</div>
					  </el-collapse-item>
					</el-collapse>
				</div>
					
			
				<div class="content2 content3" v-if="tabs_index == 2">
					<el-collapse accordion style="margin-top: 2px;background-color: #7e73b5;">
					  <el-collapse-item style="text-align: center;">
						<template slot="title">{{checked_name}}</template>
						<div class="line3" v-for="(item,index) in data" @click="task_type(index,item.name)">
							<span style="float: left;">{{item.name}}</span>
							<img :src="checked_index == index ? 'http://www.c.com/public/tx/img/yes.png' : ''" width="12" style="float: right;margin-top: 6px;">
							<div style="clear: both;"></div>
						</div>
					  </el-collapse-item>
					</el-collapse>
					
					<div class="line4" v-for="(item,index) in task_data">
					   <el-checkbox @change="update_task(item.id)" v-model="item.status">{{item.content}}</el-checkbox>
					   <span style="float: right;color: gray;">已看</span>
					   <div class="box3">
							<img v-for="item1 in item.img" :src="item1" >
					   </div>
					   <div class="txt">{{item.create_time}} {{item.foot}}</div>
					</div>
				</div>	
			</div>
			</transition>
			
			<transition name="el-zoom-in-top">
				<div class="chat_boxes divWrap" v-if="chat_show2">
					<div class="header3" id="move2">
						<div class="search3">
							<img src="http://www.c.com/public/tx/img/search2.png" >
							<input type="text" name="" id="" value="" placeholder="搜索" />
						</div>
						<div class="title_s">{{title_name}}</div>
						<div class="right_div">
							<img src="http://www.c.com/public/tx/img/1.png" @click="chat_show2 = false">
							<img src="http://www.c.com/public/tx/img/close.png" @click="chat_show2 = false">
						</div>
					</div>
					
					<ul class="listbox">
						<li v-for="(item,index) in news" @click="get_record(item.g_id,index)" :class="s_index == index ? 'active2' : ''" >
							<div style="height: 6px;"></div>
							<span class="circle" style="margin-top: 0;"></span>
							<div>{{item.name}}<span class="s2">{{item.times}}</span></div>
							<div class="txt">{{item.txt}}</div>
							<!-- <span class="s3">
								<img src="http://www.c.com/public/tx/img/del1.png" class="del">
							</span> -->
						</li>						
					</ul>
					
					<div class="chatroom2" :class="is_edit ? 'chatroom3' : 'chatroom2'">
						<el-tabs v-model="activeName" @tab-click="handleClick">
							<el-tab-pane label="聊天" name="1" class="down2">
								<div v-for="(item,index) in record_data">
									<div class="times">{{item.date}}</div>
									<div v-for="(item1,index) in item.data">
										<div v-if="item1.side == 1">
											<span class="circle" style="float: right;"></span>
											<div v-if="item1.type == 1" class="ask reply">{{item1.msg}}</div>
											<div v-if="item1.type == 2" class="ask_img"><img :src="item1.msg"></div>
											<div v-if="item1.type == 3" class="ask reply">{{item1.msg}}</div>
											<div v-if="item1.type == 4" class="ask_img"><img src="http://www.c.com/public/tx/img/wenjian.jpg" ></div>
										</div>
										<div v-if="item1.side == 2" style="margin-bottom: 10px;">
											<span class="circle"></span>
											<div v-if="item1.type == 1" class="ask">{{item1.msg}}</div>
											<div v-if="item1.type == 2" class="ask_img"><img :src="item1.msg" width="100"></div>
											<div v-if="item1.type == 3" class="ask">{{item1.msg}}</div>
											<div v-if="item1.type == 4" class="ask_img"><img src="http://www.c.com/public/tx/img/wenjian.jpg" ></div>
										</div>
										<div style="clear: both;"></div>
									</div>
									<div style="height: 45px;"></div>
								</div>
							</el-tab-pane>
							
							<el-tab-pane label="任务" name="2">
								<div style="float: left;width: 320px;">
									<div class="title_s">选择抄送人</div>
									<div class="search">
										<img src="http://www.c.com/public/tx/img/search.png" >
										<input type="text" name="" id="" value="" placeholder="搜索" />
									</div>
									<div class="box2" v-for="(item,index) in database">
										<el-checkbox @change="select_people(index,item.status,item.uid)" v-model="item.status" style="position: absolute;right: 0;"></el-checkbox>
										<img src="http://www.c.com/public/tx/img/tu.png" width="36">
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
										<!-- <div class="more">更多 <img src="http://www.c.com/public/tx/img/duo.png" ></div> -->
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
										<td><a href="item.msg" :download="item.file_name"><img src="http://www.c.com/public/tx/img/download.png" ></a></td>
									</tr>
								</table>
							</el-tab-pane>
						</el-tabs>
						
			
						<div class="write" v-if="activeName == 1">
							<form id="forms" action="" enctype="multipart/form-data" method="post">
								<span style="position: relative;">
									<img src="http://www.c.com/public/tx/img/img1.png" class="img3" @mouseover="img_file = 1">
									<input type="file" accept="image/*" name="img_upload" @change="upload_img" class="img_upload1" v-if="img_file == 1" />
									<input type="hidden" name="filename" value="img_upload" v-if="img_file == 1" >
									<input type="hidden" name="type" value="2" v-if="img_file == 1" />
								</span>
								
								<span style="position: relative;">
									<img src="http://www.c.com/public/tx/img/wen2.png" class="img3" @mouseover="img_file = 2">
									<input type="file" name="img_upload" @change="upload_img" class="img_upload1" v-if="img_file == 2" />
									<input type="hidden" name="filename" value="img_upload" v-if="img_file == 2">
									<input type="hidden" name="type" value="4" v-if="img_file == 2" />
								</span>
								<img src="http://www.c.com/public/tx/img/yin3.png" class="img3">
								<img src="http://www.c.com/public/tx/img/record.png" style="float: right;">
							</form>
							<textarea v-model="edit_msg" rows="" cols=""></textarea>
							<div class="btn">
								<button @click="send_msg(1)">发送</button>
							</div>
						</div>
			
						<div class="write" v-if="activeName == 2">
							<form id="forms2" action="" enctype="multipart/form-data" method="post">
								<span style="position: relative;">
									<img src="http://www.c.com/public/tx/img/img1.png" class="img3" @mouseover="img_file = 1">
									<input type="file" @change="imgs_upload" name="img_upload[]" multiple="multiple" accept="image/*" class="img_upload1" v-if="img_file == 1" />
									<input type="hidden" name="filename" value="img_upload" v-if="img_file == 1">
									<input type="hidden" name="type" value="2"  v-if="img_file == 1" />
								</span>
								
								<span style="position: relative;">
									<img src="http://www.c.com/public/tx/img/wen2.png" class="img3" @mouseover="img_file = 2">
									<input type="file" @change="imgs_upload" name="img_upload[]" multiple="multiple" class="img_upload1" v-if="img_file == 2">
									<input type="hidden" name="filename" value="img_upload" v-if="img_file == 2">
									<input type="hidden" name="type" value="4" v-if="img_file == 2" />
								</span>
								<img src="http://www.c.com/public/tx/img/yin3.png" class="img3" >
								<img src="http://www.c.com/public/tx/img/record.png" style="float: right;">
							</form>
							<textarea v-model="edit_msg2" rows="" cols=""></textarea>
							<div class="btn">
								<button @click="send_msg2">发送</button>
							</div>
						</div>
						<!-- 公告 -->
						<div class="write" v-if="activeName == 3">
							<form id="forms2" action="" enctype="multipart/form-data" method="post">
								<span style="position: relative;">
									<img src="http://www.c.com/public/tx/img/img1.png" class="img3" @mouseover="img_file = 1">
									<input type="file" @change="imgs_upload" name="img_upload[]" multiple="multiple" accept="image/*" class="img_upload1" v-if="img_file == 1" />
									<input type="hidden" name="filename" value="img_upload" v-if="img_file == 1">
									<input type="hidden" name="type" value="2"  v-if="img_file == 1" />
								</span>
								
								<span style="position: relative;">
									<img src="http://www.c.com/public/tx/img/wen2.png" class="img3" @mouseover="img_file = 2">
									<input type="file" @change="imgs_upload" name="img_upload[]" multiple="multiple" class="img_upload1" v-if="img_file == 2">
									<input type="hidden" name="filename" value="img_upload" v-if="img_file == 2">
									<input type="hidden" name="type" value="4" v-if="img_file == 2" />
								</span>
								<img src="http://www.c.com/public/tx/img/yin3.png" class="img3" >
								<img src="http://www.c.com/public/tx/img/record.png" style="float: right;">
							</form>
							<textarea v-model="edit_msg3" rows="" cols=""></textarea>
							<div class="btn">
								<button @click="send_msg3">发送</button>
							</div>
						</div>
					</div>
				</div>
			</transition>
			
			<el-dialog
			  title="更换背景"
			  custom-class="dialog"
			  :modal="false"
			  top="30vh"
			  :visible.sync="back_dialogVisible"
			  width="25%">
			  <div class="box5">
				  <!-- <img src="http://www.c.com/public/tx/img/logo.png" @click="backg = 'img/queren.png'"> -->
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
				  <img v-for="img in task_alert.img" :src="img" style="max-width: 160px;" alt="">
			  </div>
			  <span slot="footer" class="dialog-footer">
			    <el-button @click="service_dialogVisible = false">取 消</el-button>
			    <el-button type="primary" @click="service_dialogVisible = false">确 定</el-button>
			  </span>
			</el-dialog>
			
			<!-- 公告弹框 -->
			<el-dialog
			  :visible.sync="service_dialogVisible1"
			  width="60%">
			  <div>发布人：{{public_alert.username}}</div>
			  <div>公告内容：{{public_alert.msg}}</div>
			  <div>
				   <img v-for="img in public_alert.img" :src="img" style="max-width: 160px;" alt="">
			  </div>
			  <div v-if="public_alert.file != ''">
					<a v-for="(fj,index) in public_alert.file" :href="fj">附件{{index+1}}</a>
			  </div>
			  <span slot="footer" class="dialog-footer">
			    <el-button @click="service_dialogVisible1 = false">取 消</el-button>
			    <el-button type="primary" @click="service_dialogVisible1 = false">确 定</el-button>
			  </span>
			</el-dialog>
		</div>`
		
		$('#point').html(tmp);
		
}())