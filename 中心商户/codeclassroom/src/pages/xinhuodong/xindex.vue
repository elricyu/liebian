<template>
	<div style="width: 100%;height: 80vh;background:#F5F5F5;">
		<div class="box_z">
			<div class="youce">
				<div class="right_top">
					<div class="t_right_box">
						<el-dropdown @command="handleCommand" style="cursor: pointer;float:right;margin-right:60px;margin-top:15px;" class="box_right">
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


		<div class="had">
			<ul>
				<li v-for="(item,index) in had_list" :class="idx==index?'vary':''" @click="tab(index)">{{item.name}}</li>
			</ul>
			<p style="width:250px;margin-right:60px;">
				<img src="../../../static/img/genjin/search.png" />
				<input type="text" placeholder="输入关键词" v-model="sou" @keyup.enter="get_sou" />
			</p>
		</div>
		<div style="clear: both;"></div>
		<div class="con">
			<ul v-show="sta">
				<li v-for="(item,index) in twotab" :class="idx2==index?'violet':''" @click="tab2(index)">{{item.name}}</li>
			</ul>
			<div v-show="idx==0&&idx2<5">
				<div class="box" @click="templet()">
					<div class="found1">+</div>
					<div class="found2">空白创建</div>
				</div>
				<div class="box" @mouseover="change1(index)" @mouseout="change2(index)" v-for="(my,index) in my_list">
					<div class="box1" v-if="index !== point_index">
						<div class="box1_1">
							<img :src="my.img" class="blur" />
							<div class="box1_1_2">
								<img :src="my.img" />
							</div>
							<div class="box1_1_tag">{{my.fb_type}}</div>
						</div>
						<div class="box1_2">
							<p>{{my.jc_name}}</p>
							<p style="text-align: center;">
								<img v-if="my.sc_status==1" @click="get_collects(my.id)" src="../../../static/img/heart.png" style="width: 13px;height: 12px;vertical-align: middle;" />
								<img v-if="my.sc_status==2" @click="get_collects(my.id)" src="../../../static/img/heart0.png" style="width: 13px;height: 12px;vertical-align: middle;" />
								<span style="vertical-align: middle;"> {{my.shoucang}}</span>
							</p>
						</div>
					</div>
					<div class="box2" v-if="index == point_index">
						<i class="el-icon-error icon" v-if="my.fb_type=='未发布'" @click="tan_dels(my.id,my.hd_id)"></i>
						<div class="box2_1">
							<img class="box2_1_img" :src="my.hd_qrcode" alt="暂无二维码" v-if="my.hd_qrcode" />
							<img class="box2_1_img" src="../../../static/img/notcode.png" v-if="!my.hd_qrcode" style="width:100px;height:100px;top: 50px;left:60px;" />
							<div class="box2_1_1">
								<el-button size="mini" @click="get_edit(my.id,my.hd_id)" v-if="my.hd_type==4&&my.fb_type!=='已完成'" plain>编辑</el-button>
							</div>
							<div class="box2_1_2">
								<p>{{my.jc_name}}</p>
								<p>
									<img v-if="my.sc_status==1&&my.fb_type!=='已完成'" @click="get_collects(my.id)" src="../../../static/img/heart.png"
									 style="width: 13px;height: 12px;vertical-align: middle;" />
									<img v-if="my.sc_status==2&&my.fb_type!=='已完成'" @click="get_collects(my.id)" src="../../../static/img/heart0.png"
									 style="width: 13px;height: 12px;vertical-align: middle;" />
									<span style="vertical-align: middle;"> {{my.shoucang}}</span>
								</p>
							</div>
						</div>
						<div class="box2_2" v-if="my.fb_type=='未发布'">
							<el-button size="mini" @click="get_edit(my.id,my.hd_id)" plain>编辑</el-button>
							<el-button size="mini" @click="get_release(my.id)" plain>发布</el-button>
						</div>
						<div class="box2_2" v-if="my.fb_type=='进行中'">
							<el-button size="mini" v-if="my.hd_type==4" @click="link_yy(my.hd_id)" plain>预约</el-button>
							<el-button size="mini" v-if="my.hd_type!==4" @click="get_edit(my.id,my.hd_id)" plain>编辑</el-button>
							<el-button size="mini" @click="link_tj(my.hd_id)" plain>统计</el-button>
							<el-button size="mini" @click="get_use(2,my.hd_id)" plain>禁用</el-button>
						</div>
						<div class="box2_2" v-if="my.fb_type=='已完成'">
							<el-button size="mini" @click="link_tj(my.hd_id)" plain>统计</el-button>
						</div>
						<div class="box2_2" v-if="my.fb_type=='已禁用'">
							<el-button size="mini" v-if="my.hd_type==4" @click="link_yy(my.hd_id)" plain>预约</el-button>
							<el-button size="mini" v-if="my.hd_type!==4" @click="get_edit(my.id,my.hd_id)" plain>编辑</el-button>
							<el-button size="mini" @click="link_tj(my.hd_id)" plain>统计</el-button>
							<el-button size="mini" @click="get_use(1,my.hd_id)" plain>启用</el-button>
						</div>
					</div>
				</div>
				<div style="clear: both;"></div>
				<div style="width: 95%;margin:20px auto;">
					<p>共有{{my_count}}条记录 当前{{my_page}}页/共{{my_pagetotal}}页</p>
					<div class="table-bottom" style="margin-bottom: 10px;text-align: center;">
						<div>
							<el-pagination @current-change="my_fanye" :current-page="my_page" :page-count="my_pagetotal" :page-size="my_pageone"
							 background layout="prev, pager, next">
							</el-pagination>
						</div>
					</div>
				</div>
			</div>
			<!-- 收藏 -->
			<div v-show="idx==0&&idx2==5">
				<div class="box" @mouseover="change1(index)" @mouseout="change2(index)" v-for="(co,index) in collect">
					<div class="box1" v-if="index !== point_index">
						<div class="box1_1">
							<img :src="co.img" class="blur" />
							<div class="box1_1_2">
								<img :src="co.img" />
							</div>
						</div>
						<div class="box1_2">
							<p>{{co.jc_name}}</p>
							<p>
								<img v-if="co.sc_status==1" @click="get_collects(co.template_id)" src="../../../static/img/heart.png" style="width: 13px;height: 12px;vertical-align: middle;" />
								<img v-if="co.sc_status==2" @click="get_collects(co.template_id)" src="../../../static/img/heart0.png" style="width: 13px;height: 12px;vertical-align: middle;" />
								<span style="vertical-align: middle;"> {{co.shoucang}}</span>
							</p>
						</div>
					</div>
					<div class="box2" v-if="index == point_index">
						<div class="box2_1">
							<div class="box1_1" style="height: 200px;">
								<img :src="co.img" class="blur" />
								<div class="box1_1_2">
									<img :src="co.img" />
								</div>
							</div>
							<div class="box2_1_2">
								<p>{{co.jc_name}}</p>
								<p>
									<img v-if="co.sc_status==1" @click="get_collects(co.template_id)" src="../../../static/img/heart.png" style="width: 13px;height: 12px;vertical-align: middle;" />
									<img v-if="co.sc_status==2" @click="get_collects(co.template_id)" src="../../../static/img/heart0.png" style="width: 13px;height: 12px;vertical-align: middle;" />
									<span style="vertical-align: middle;"> {{co.shoucang}}</span>
								</p>
							</div>
						</div>
						<div class="box2_2">
							<el-button size="mini" style="width:160px ;" @click="get_yulang(co.template_id)" plain>预览</el-button>
						</div>
					</div>
				</div>
			</div>
			<!-- 统计 -->
			<div v-show="idx==0&&idx2==6">
				<div class="tj_date">
					<el-date-picker style="float: right;" v-model="tj_date" type="daterange" value-format="yyyy-MM-dd" range-separator="至"
					 start-placeholder="开始日期" end-placeholder="结束日期" size="mini">
					</el-date-picker>
				</div>
				<el-row :gutter="20">
					<el-col :span="6">
						<div class="bg">
							<div style="float: left;width: 35%;text-align: center;">
								<img src="../../../static/img/huodong_z.png" style="width:66px;height: 44px;margin-top: 10px;" />
							</div>
							<div style="float: left;width: 65%;">
								<p style="font-size:15px;font-weight: bold;padding-left: 13px;">总浏览量</p>
								<p style="font-size:15px;font-weight: bold;padding-left: 13px;">{{zong.browse}}人</p>
							</div>
							<div style="clear: both;"></div>
						</div>
					</el-col>
					<el-col :span="6">
						<div class="bg">
							<div style="float: left;width: 35%;text-align: center;">
								<img src="../../../static/img/huodong_c.png" style="width:44px;height:44px;margin-top: 10px;" />
							</div>
							<div style="float: left;width: 65%;">
								<p style="font-size:15px;font-weight: bold;padding-left: 13px;">参与人数</p>
								<p style="font-size:15px;font-weight: bold;padding-left: 13px;">{{zong.zrs}}人</p>
							</div>
							<div style="clear: both;"></div>
						</div>
					</el-col>
					<el-col :span="6">
						<div class="bg">
							<div style="float: left;width: 35%;text-align: center;">
								<img src="../../../static/img/huodong_f.png" style="width:48px;height: 48px;margin-top:6px;" />
							</div>
							<div style="float: left;width:65%;">
								<p style="font-size:15px;font-weight: bold;padding-left: 13px;">分享人数</p>
								<p style="font-size:15px;font-weight: bold;padding-left: 13px;">{{zong.fxs}}人</p>
							</div>
							<div style="clear: both;"></div>
						</div>
					</el-col>
					<el-col :span="6">
						<div class="bg">
							<div style="float: left;width:35%;text-align: center;">
								<img src="../../../static/img/huodong_zs.png" style="width:44px;height:46px;margin-top: 10px;" />
							</div>
							<div style="float: left;width:65%;">
								<p style="font-size:15px;font-weight: bold;padding-left: 13px;">总收入</p>
								<p style="font-size:20px;font-weight: bold;padding-left: 13px;">{{zong.zsr}}元</p>
							</div>
							<div style="clear: both;"></div>
						</div>
					</el-col>
				</el-row>
				<div style="width: 96%;margin:30px auto;">
					<table style="margin-bottom: 0;background: #fff;">
						<thead style="background:#e8e5ff ">
							<tr>
								<th>序号</th>
								<th>活动名称</th>
								<th>活动时间</th>
								<th>活动类型</th>
								<th>预览次数</th>
								<th>参与人数</th>
								<th>分享数</th>
								<th>收入</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(z,index) in ztj_list">
								<td>{{index+1}}</td>
								<td @click="link_tj(z.hd_id)" style="color: rgb(51, 122, 183); cursor: pointer;">{{z.jc_name}}</td>
								<td>{{z.hd_start_time}} 至 {{z.hd_end_time}}</td>
								<td>{{z.hd_type}}</td>
								<td>{{z.browse}}</td>
								<td>{{z.zrs}}</td>
								<td>{{z.fxs}}</td>
								<td>{{z.zsr}}</td>
							</tr>
						</tbody>
					</table>
					<div style="width: 95%;margin:20px auto;">
						<p>共有{{tj_count}}条记录 当前{{tj_page}}页/共{{tj_pagetotal}}页</p>
						<div class="table-bottom" style="margin-bottom: 10px;text-align: center;">
							<div>
								<el-pagination @current-change="tj_fanye" :current-page="tj_page" :page-count="tj_pagetotal" :page-size="tj_pageone"
								 background layout="prev, pager, next">
								</el-pagination>
							</div>
						</div>
					</div>
				</div>

			</div>
			<!-- 钱包 -->
			<div v-show="idx==0&&idx2==7">
				<el-row :gutter="20" style="width:90%;margin: auto;">
					<el-col :span="12">
						<div class="count">
							<div class="c1">
								<span>红包金额</span>
								<!-- <span style="color:#FF797C">{{qianbao.total}}</span> -->
								<el-button plain size="mini" class="zhuanBtn" v-on:click="chongtan(2)">充值</el-button>
							</div>
							<div class="c3">
								<div class="c3_box e">
									<div class="tub" id="e1" style="margin-left: 5px"></div>
								</div>
								<div class="c3_box">
									<p style="margin-top: 45px;">
										<span class="dian" style="background:#7C72C1;"></span>
										<span style="font-size: 15px;">余额：{{qianbao.red_remaining}}元</span>
									</p>
									<p>
										<span class="dian"></span>
										<span style="font-size: 15px;">总额：{{qianbao.red_total}}元</span>
									</p>
								</div>
								<div style="clear: both;"></div>
							</div>
						</div>
					</el-col>
					<el-col :span="12">
						<div class="count">
							<div class="c1">
								<span>使用情况</span>
							</div>
							<div class="c3">
								<div class="c3_box e">
									<div class="tub" id="e2" style="margin-left: 5px"></div>
								</div>
								<div class="c3_box">
									<p style="margin-top: 45px;">
										<span class="dian" style="background:#7C72C1;"></span>
										<span style="font-size: 15px;">举办活动次数：{{shiyong.cs}}次</span>
									</p>
									<p>
										<span class="dian"></span>
										<span style="font-size: 15px;">使用红包次数：{{shiyong.whb}}次</span>
									</p>
								</div>
								<div style="clear: both;"></div>
							</div>
						</div>
					</el-col>
				</el-row>



				<div style="width: 96%;margin: auto; padding:10px 0px;font-size:16px;font-weight: bold;">充值记录</div>
				<div style="width:98%;margin: auto;height: 45px;">
					<div style="float: left;width: 200px;">
						<ul class="choicetime">
							<li v-bind:class="{activea1:(chos==1)}" v-on:click="chose1(1)">本周</li>
							<li v-bind:class="{activea1:(chos==2)}" v-on:click="chose1(2)">本月</li>
						</ul>
					</div>
					<div style="float: left;width: 250px;">
						<el-date-picker style="width: 100%" value-format="yyyy-MM-dd" v-model="qb_date" type="daterange" range-separator="-"
						 start-placeholder="开始日期" end-placeholder="结束日期" size="mini">
						</el-date-picker>
					</div>
					<div style="clear:both;"></div>
				</div>
				<div style="width: 96%;margin: auto;">
					<table style="margin-bottom: 0;">
						<thead style="background:#e8e5ff ">
							<tr>
								<th>序号</th>
								<th>时间</th>
								<th>金额</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(qb,index) in qb_list">
								<td>{{index+1}}</td>
								<td>{{qb.create_time}}</td>
								<td>{{qb.money}}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!-- 官方模版 -->
			<div v-show="idx>0">
				<div class="box" @mouseover="change1(index)" @mouseout="change2(index)" v-for="(co,index) in my_list">
					<div class="box1" v-if="index !== point_index">
						<div class="box1_1">
							<img :src="co.img" class="blur" />
							<div class="box1_1_2">
								<img :src="co.img" />
							</div>
						</div>
						<div class="box1_2">
							<p>{{co.jc_name}}</p>
							<p>
								<img v-if="co.sc_status==1" @click="get_collects(co.id)" src="../../../static/img/heart.png" style="width: 13px;height: 12px;vertical-align: middle;" />
								<img v-if="co.sc_status==2" @click="get_collects(co.id)" src="../../../static/img/heart0.png" style="width: 13px;height: 12px;vertical-align: middle;" />
								<span style="vertical-align: middle;"> {{co.shoucang}}</span>
							</p>
						</div>
					</div>
					<div class="box2" v-if="index == point_index">
						<div class="box2_1">
							<div class="box1_1" style="height: 200px;">
								<img :src="co.img" class="blur" />
								<div class="box1_1_2">
									<img :src="co.img" />
								</div>
							</div>
							<div class="box2_1_2">
								<p>{{co.jc_name}}</p>
								<p>
									<img v-if="co.sc_status==1" @click="get_collects(co.id)" src="../../../static/img/heart.png" style="width: 13px;height: 12px;vertical-align: middle;" />
									<img v-if="co.sc_status==2" @click="get_collects(co.id)" src="../../../static/img/heart0.png" style="width: 13px;height: 12px;vertical-align: middle;" />
									<span style="vertical-align: middle;"> {{co.shoucang}}</span>
								</p>
							</div>
						</div>
						<div class="box2_2">
							<el-button size="mini" style="width:160px ;" @click="get_yulang(co.id,co.hd_id)" plain>预览</el-button>
						</div>
					</div>
				</div>
				<div style="clear: both;"></div>
				<div style="width: 95%;margin:20px auto;">
					<p>共有{{my_count}}条记录 当前{{my_page}}页/共{{my_pagetotal}}页</p>
					<div class="table-bottom" style="margin-bottom: 10px;text-align: center;">
						<div>
							<el-pagination @current-change="my_fanye" :current-page="my_page" :page-count="my_pagetotal" :page-size="my_pageone"
							 background layout="prev, pager, next">
							</el-pagination>
						</div>
					</div>
				</div>
			</div>

		</div>
		<!-- 设置模板 -->
		<el-dialog title="" :visible.sync="stencildialog" width="950px" center>
			<div style="width: 100%;float:left;font-size:18px;padding-bottom:5px;font-weight: bold;">设置</div>
			<div class="stencil1">
				<div class="stencil1_img">
					<div id="drags" @click="drag()" @dblclick="getdra(1)" v-show="poster" :style="{top:mb.top1+'px',left:mb.left1+'px',border:' 1px dashed '+red1,}">
						<div contenteditable="false" class="textareas" :style="{fontFamily:mb.ziti1,fontSize:mb.zihao1+'px',letterSpacing:mb.jianju1+'px',color:mb.color1,fontWeight:mb.yangshi1,textDecoration:mb.underline1}"
						 disabled>{{mb.text1}}</div>
					</div>

					<div id="drags2" @click="drag2()" @dblclick="getdra(2)" v-show="poster" :style="{top:mb.top2+'px',left:mb.left2+'px',border:' 1px dashed '+red2,}">
						<div contenteditable="false" class="textareas" :style="{fontFamily:mb.ziti2,fontSize:mb.zihao2+'px',letterSpacing:mb.jianju2+'px',color:mb.color2,fontWeight:mb.yangshi2,textDecoration:mb.underline2}"
						 disabled>{{mb.text2}}</div>
					</div>

					<div id="drags3" @click="drag3()" @dblclick="getdra(3)" v-show="poster" :style="{top:mb.top3+'px',left:mb.left3+'px',border:' 1px dashed '+red3,}">
						<div contenteditable="false" class="textareas" :style="{fontFamily:mb.ziti3,fontSize:mb.zihao3+'px',letterSpacing:mb.jianju3+'px',color:mb.color3,fontWeight:mb.yangshi3,textDecoration:mb.underline3}"
						 disabled>{{mb.text3}}</div>
					</div>
					<div class="up_but" v-show="!poster">上传图片</div>
					<img :src="poster" alt="" onclick="$('#file2').click()" class="beijing">
					<input @change="fileUp($event)" accept="image/jpeg,image/jpg,image/png" type="file" name="file" ref="pathClear2"
					 id="file2" style="display: none" />
				</div>
				<div class="stencil1_switch">
					<div><img src="../../../static/img/shang.png" @click="upper()" /></div>
					<div>{{pagination}}</div>
					<div><img src="../../../static/img/xia.png" @click="down()" /></div>
				</div>
				<div class="stencil1_tips">
					*图片尺寸建议为:750*1056
				</div>
			</div>
			<div class="stencil2">
				<p class="stencil2p">文本编辑</p>
				<el-form ref="form" label-width="90px" label-position="left" size="mini">
					<el-form-item label="文本内容">
						<el-input type="textarea" v-model="text" placeholder="双击左边文本框编辑/单击移动"></el-input>
					</el-form-item>
					<el-form-item label="字体">
						<el-select v-model="ziti" placeholder="请选择字体">
							<el-option label="微软雅黑" value="微软雅黑"></el-option>
							<el-option label="宋体" value="宋体"></el-option>
						</el-select>
					</el-form-item>
					<el-form-item label="字号">
						<el-select v-model="zihao" placeholder="请选择字号">
							<el-option label="大" value="20"></el-option>
							<el-option label="中" value="15"></el-option>
							<el-option label="小" value="12"></el-option>
						</el-select>
					</el-form-item>
					<el-form-item label="间距">
						<el-select v-model="jianju" placeholder="请选择间距">
							<el-option label="15px" value="15"></el-option>
							<el-option label="10px" value="10"></el-option>
							<el-option label="5px" value="5"></el-option>
							<el-option label="0" value="0"></el-option>
						</el-select>
					</el-form-item>
					<el-form-item label="颜色">
						<el-color-picker v-model="color"></el-color-picker>
					</el-form-item>
					<el-form-item label="样式">
						<span class="yangshi" :class="yixd==1?'y':''" @click="ybut(1)">B</span>
						<span class="yangshi" :class="yixd==2?'y':''" @click="ybut(2)" style="margin-left: 20px;">U</span>
						<span class="yangshi" :class="yixd==3?'y':''" @click="ybut(3)" style="margin-left: 20px;">无</span>
					</el-form-item>
				</el-form>
			</div>

			<span slot="footer" class="dialog-footer">
				<el-button @click="storager()" plain>保 存</el-button>
				<el-button @click="next_step()" plain>下一步</el-button>
			</span>
		</el-dialog>

		<!-- 模板预览 -->
		<el-dialog title="" :visible.sync="previewdialog" width="950px" center>
			<div style="width: 100%;float:left;font-size:18px;padding-bottom:10px;font-weight: bold;">预览</div>
			<div style="width: 35%;margin: auto;">
				<div class="stencil1_img">
					<img :src="poster" alt="" class="beijing">
				</div>
				<div class="stencil1_switch">
					<div><img src="../../../static/img/shang.png" @click="preview_s()" /></div>
					<div>{{pagination}}</div>
					<div><img src="../../../static/img/xia.png" @click="preview_x()" /></div>
				</div>
			</div>
			<div style="clear: both;"></div>
			<span slot="footer" class="dialog-footer">
				<el-button @click="get_quote()" plain>立即使用</el-button>
			</span>
		</el-dialog>

		<!-- 基础/分享设置 -->
		<el-dialog title="" :visible.sync="setupdialog" width="960px" center>
			<div style="width: 100%;float:left;font-size:18px;padding-bottom:10px;font-weight: bold;">设置</div>
			<div class="stencil1">
				<div class="stencil1_img">
					<div id="dragsa" :style="{top:mb.top1+'px',left:mb.left1+'px',border:0,background:0,position:+'absolute'}">
						<div contenteditable="false" class="textareas" :style="{fontFamily:mb.ziti1,fontSize:mb.zihao1+'px',letterSpacing:mb.jianju1+'px',color:mb.color1,fontWeight:mb.yangshi1,textDecoration:mb.underline1}"
						 disabled>{{mb.text1}}</div>
					</div>
					<div  id="drags2a" :style="{top:mb.top2+'px',left:mb.left2+'px',border:0,background:0,position:+'absolute'}">
						<div contenteditable="false" class="textareas" :style="{fontFamily:mb.ziti2,fontSize:mb.zihao2+'px',letterSpacing:mb.jianju2+'px',color:mb.color2,fontWeight:mb.yangshi2,textDecoration:mb.underline2}"
						 disabled>{{mb.text2}}</div>
					</div>
					<div id="drags3a" :style="{top:mb.top3+'px',left:mb.left3+'px',border:0,background:0,position:+'absolute'}">
						<div contenteditable="false" class="textareas" :style="{fontFamily:mb.ziti3,fontSize:mb.zihao3+'px',letterSpacing:mb.jianju3+'px',color:mb.color3,fontWeight:mb.yangshi3,textDecoration:mb.underline3}"
						 disabled>{{mb.text3}}</div>
					</div>
					<img :src="poster" alt="" class="beijing">
				</div>
				<div class="stencil1_switch">
					<div><img src="../../../static/img/shang.png" @click="preview_s()" /></div>
					<div>{{pagination}}</div>
					<div><img src="../../../static/img/xia.png" @click="preview_x()" /></div>
				</div>
				<div class="stencil1_tips">
					*图片尺寸建议为:750*1056
				</div>
			</div>
			<div class="stencil2" style="height: 590px">
				<p class="shez">
					<span v-for="(item,index) in she_list" :class="sidx==index?'ss':''" @click="sbut(index)">{{item.name}}</span>
				</p>
				<el-form ref="form" :model="forma" label-width="85px" label-position="left" size="mini" v-show="sidx==0">
					<el-form-item label="活动类型">
						<el-radio-group v-model="forma.resource" @change="get_re">
							<!--<el-radio :label="1">砍价活动</el-radio>-->
							<!--<el-radio :label="2">拼团活动</el-radio>-->
							<el-radio :label="3">促单(线上)</el-radio>
							<el-radio :label="4">促单(线上+线下)</el-radio>
						</el-radio-group>
					</el-form-item>
					<el-form-item label="活动名称">
						<el-input v-model="forma.name"></el-input>
					</el-form-item>
					<el-form-item label="活动描述">
						<el-input type="textarea" v-model="forma.desc"></el-input>
					</el-form-item>
					<el-form-item label="了解更多">
						<el-input placeholder="请输入了解更多链接" v-model="forma.linkhref"></el-input>
					</el-form-item>
					<el-form-item label="报名时间" v-if="forma.resource==4">
						<el-date-picker v-model="forma.enrolldate" type="daterange" value-format="yyyy-MM-dd" range-separator="至"
						 start-placeholder="开始日期" end-placeholder="结束日期">
						</el-date-picker>
					</el-form-item>
					<el-form-item label="活动时间" v-if="forma.resource!==4">
						<el-date-picker v-model="forma.activitydate" type="daterange" value-format="yyyy-MM-dd" range-separator="至"
						 start-placeholder="开始日期" end-placeholder="结束日期"></el-date-picker>
					</el-form-item>
					<el-form-item label="活动时间" v-if="forma.resource==4">
						<el-date-picker v-model="forma.activitydate1" value-format="yyyy-MM-dd" type="date" placeholder="选择日期">
						</el-date-picker>
					</el-form-item>
					<el-row :gutter="20" v-if="forma.resource==4">
						<el-col :span="16">
							<el-form-item label="活动地址">
								<el-input v-model="forma.a_address"></el-input>
							</el-form-item>
						</el-col>
						<el-col :span="7">
							<el-form-item label="耗课数">
								<el-input v-model="forma.haoke"></el-input>
							</el-form-item>
						</el-col>
						<div style="padding-top:5px;">节</div>
					</el-row>
					<el-row :gutter="20">
						<el-col :span="16">
							<el-form-item label="课时包名称">
								<!--<el-select v-model="forma.k_region" placeholder="请选择课时包" @change="choose(forma.k_region)">-->
									<!--<el-option v-for="k in ke_list" :label="k.s_name" :value="k.s_id"></el-option>-->
								<!--</el-select>-->
								<el-input v-model="forma.s_name"></el-input>
							</el-form-item>
						</el-col>
						<el-col :span="7">
							<el-form-item label="课时包数量">
								<!--<el-input v-model="forma.k_number" disabled></el-input>-->
								<el-input v-model="forma.k_number"></el-input>
							</el-form-item>
						</el-col>
						<div style="padding-top:5px;">节</div>
					</el-row>
					<el-row :gutter="20">
						<el-col :span="16">
							<el-form-item label="类型">
								<el-radio-group v-model="forma.type">
									<el-radio :label="1">免费赠送</el-radio>
									<el-radio :label="2">购买</el-radio>
								</el-radio-group>
							</el-form-item>
						</el-col>
						<el-col :span="7" v-if="forma.type==2">
							<el-form-item label="课时包价格">
								<el-input v-model="forma.k_price"></el-input>
							</el-form-item>
						</el-col>
						<div v-if="forma.type==2" style="padding-top:5px;">元</div>
					</el-row>
					<el-form-item label="中心名称">
						<el-input v-model="forma.core" disabled></el-input>
					</el-form-item>
					<el-row :gutter="20">
						<el-col :span="10">
							<el-form-item label="联系电话">
								<el-input v-model="forma.phone"></el-input>
							</el-form-item>
						</el-col>
						<el-col :span="14">
							<el-form-item label="中心地址">
								<el-input v-model="forma.address"></el-input>
							</el-form-item>
						</el-col>
					</el-row>
				</el-form>
				<el-form ref="form" :model="f_share" class="fenxs" label-width="98px" label-position="left" size="mini" v-show="sidx==1">

					<el-form-item label="分享名称">
						<el-input v-model="f_share.name"></el-input>
					</el-form-item>
					<el-form-item label="分享描述">
						<el-input type="textarea" v-model="f_share.desc"></el-input>
					</el-form-item>
					<el-form-item label="活动规则">
						<el-input type="textarea" v-model="f_share.guize"></el-input>
					</el-form-item>
					<el-form-item label="分享图片">
						<div class="shareimg">
							<span v-if="!share_img">点击上传</span>
							<img :src="share_img" alt="" onclick="$('#file3').click()" style="width:100%;height:100%;position: absolute;">
							<input @change="fileupload($event)" accept="image/jpeg,image/jpg" type="file" name="file" ref="pathClear3" id="file3"
							 style="display: none" />
						</div>
						<div style="margin-left:70px;margin-top: 26px;">建议尺寸30*30</div>
					</el-form-item>
					<el-form-item label="红包配置">
						<el-radio-group v-model="f_share.packet">
							<el-radio :label="3">无红包</el-radio>
							<el-radio :label="1">随机金额</el-radio>
							<el-radio :label="2">固定金额</el-radio>
						</el-radio-group>
					</el-form-item>
					<el-row :gutter="20" v-if="f_share.packet==1">
						<el-col :span="12">
							<el-form-item label="红包总额">
								<el-input v-model="f_share.hje_z" maxlength="15" size="mini" placeholder="金额为整数,最低10元" onkeyup="this.value=this.value.replace(/[^\d]/g,'') "
								 onafterpaste="this.value=this.value.replace(/[^\d]/g,'')"></el-input>
							</el-form-item>
						</el-col>
						<el-col :span="12">
							<el-form-item label="红包个数">
								<el-input v-model="f_share.hgs" maxlength="15" size="mini" placeholder="红包数为整数,最低10个" onkeyup="this.value=this.value.replace(/[^\d]/g,'') "
								 onafterpaste="this.value=this.value.replace(/[^\d]/g,'')"></el-input>
							</el-form-item>
						</el-col>
					</el-row>
					<el-row :gutter="20" v-if="f_share.packet==2">
						<el-col :span="12">
							<el-form-item label="单个红包金额">
								<el-input v-model="f_share.hje_d" maxlength="15" size="mini" placeholder="金额为整数,最低1元" onkeyup="this.value=this.value.replace(/[^\d]/g,'') "
								 onafterpaste="this.value=this.value.replace(/[^\d]/g,'')"></el-input>
							</el-form-item>
						</el-col>
						<el-col :span="12">
							<el-form-item label="红包个数">
								<el-input v-model="f_share.hgs" maxlength="15" size="mini" placeholder="红包数为整数,最低10个" onkeyup="this.value=this.value.replace(/[^\d]/g,'') "
								 onafterpaste="this.value=this.value.replace(/[^\d]/g,'')"></el-input>
							</el-form-item>
						</el-col>
					</el-row>
					<!--<el-row :gutter="20">-->
						<!--<el-col :span="11">-->
							<!--<el-form-item label="积分商城设置">-->
							<!--</el-form-item>-->
						<!--</el-col>-->
						<!--<el-col :span="8">-->
							<!--<el-form-item label="会员折扣">-->
								<!--<el-input style="width:200px" v-model="f_share.zhek_s" size="mini" placeholder="输入值0~9.9之间，0代表不打折"></el-input>-->
							<!--</el-form-item>-->
						<!--</el-col>-->
						<!--<div style="padding-top:5px;">0~9.9之间</div>-->
					<!--</el-row>-->
					<!--<div style="max-height:80px;overflow: hidden;overflow-y:auto; ">-->
						<!--<div style="margin-bottom: 10px" v-for="(item,index) in jfbox" :key="index">-->
							<!--<span>积分：</span>-->
							<!--<el-input style="width:60px" v-model="item.shabian" ref="shabian_list" size="mini" placeholder=""></el-input>-->
							<!--<span>兑换：</span>-->
							<!--<div class="shang_box el-input__inner" v-model="item.shabian2" @click="goodstan(index)">-->
								<!--<span v-if="item.shabian2">{{item.shabian2}}</span>-->
								<!--<span style="color: #7c72c1;" v-else>点击选择商品</span>-->
							<!--</div>-->
							<!--<el-input style="display: none;" v-model="item.shabian3" ref="shabian3_list"></el-input>-->
							<!--<el-button type="primary" size="mini" v-if="index==0" @click="addhui">添加</el-button>-->
							<!--<el-button type="primary" size="mini" v-if="index>0" @click="delhui(index)">删除</el-button>-->
						<!--</div>-->
					<!--</div>-->
					<div style="font-weight: bold;">积分设置</div>
					<div style="margin-bottom: 6px;color: #666;">
						<label class="labelys" @change="clickMe"><input type="checkbox" v-model="fenBox" value="1" style="margin: 0;vertical-align: middle">
							分享后每增加一次点击可为分享者增加积分 </label>
						<el-input style="width:100px" v-model="zhek_a" :disabled="fenBox.indexOf('1')<0" size="mini" onkeyup="this.value=this.value.replace(/[^\d]/g,'') "
						 onafterpaste="this.value=this.value.replace(/[^\d]/g,'')"></el-input>
						<span>积分</span>
					</div>
					<div style="margin-bottom:6px;color: #666;">
						<label class="labelys" @change="clickMe"><input type="checkbox" v-model="fenBox" value="2" style="margin: 0;vertical-align: middle">
							每增加一个到访（试听）客增加 </label>
						<el-input style="width:100px" v-model="zhek_b" :disabled="fenBox.indexOf('2')<0" size="mini" onkeyup="this.value=this.value.replace(/[^\d]/g,'') "
						 onafterpaste="this.value=this.value.replace(/[^\d]/g,'')"></el-input>
						<span>积分</span>
					</div>
					<!-- <div style="margin-bottom:6px;color: #666;">
						<label class="labelys" @change="clickMe"><input type="checkbox" v-model="fenBox" value="3" style="margin: 0;vertical-align: middle">
							每增加一个留言信息增加 </label>
						<el-input style="width:100px" v-model="zhek_c" :disabled="fenBox.indexOf('3')<0" size="mini" onkeyup="this.value=this.value.replace(/[^\d]/g,'') "
						 onafterpaste="this.value=this.value.replace(/[^\d]/g,'')"></el-input>
						<span>积分</span>
					</div>
					<div style="margin-bottom:6px;color: #666;">
						<label class="labelys" @change="clickMe"><input type="checkbox" v-model="fenBox" value="4" style="margin: 0;vertical-align: middle">
							签订合同增加
						</label>
						<el-input style="width:100px" v-model="zhek_d" :disabled="fenBox.indexOf('4')<0" size="mini" onkeyup="this.value=this.value.replace(/[^\d]/g,'') "
						 onafterpaste="this.value=this.value.replace(/[^\d]/g,'')"></el-input>
						<span>积分</span>
					</div> -->
				</el-form>
			</div>

			<span slot="footer" class="dialog-footer">
				<el-button @click="get_sure(1)" plain>保 存</el-button>
				<el-button @click="sidx=1" v-show="sidx==0" plain>下一步</el-button>
				<el-button @click="get_sure(2)" v-show="sidx==1" plain>发布</el-button>
			</span>
		</el-dialog>

		<!-- 成功显示二维码 -->
		<el-dialog title="" :visible.sync="codedialog" width="800px" center>
			<div style="width:60%;margin:20px auto;">
				<div style="text-align: center;padding-bottom: 15px;">
					<img src="../../../static/img/succeeds.png" style="width: 30px;height: 30px;" />
					<span style="padding-left: 10px;font-size: 14px;font-weight: bold;">恭喜您，活动 ({{code.jc_name}}) 已经创建成功！</span>
				</div>
				<div style="text-align: center;padding-bottom: 15px;">
					<img :src="code.hd_qrcode" style="width: 150px;height: 150px;" />
				</div>
				<div style="text-align: center;padding-bottom:35px;">
					链接：
					<el-input size="mini" prefix-icon="el-icon-document" style="width:70%;" disabled v-model="code.url">
					</el-input>
					<el-button size="mini" type="primary" style="background:#7C72C1 ;border: 0;" @click="get_copy(code.url)">复制</el-button>
				</div>
				<div style="text-align: center;">
					快快下载二维码或者复制链接，开始火速推广吧！
				</div>
			</div>
		</el-dialog>

		<!-- 积分商城——商品 -->
		<el-dialog class="tanchuang tanindex" title="选择商品" :visible.sync="xuanshang" :close-on-click-modal='false' width="80%">
			<div style="overflow-y: scroll">
				<el-row>

					<el-col :span="24" class="marBo2">
						<div class="fflex fflexV fflexC" style="margin-bottom: 10px">
							<span>商品类别:</span>
							<select name="" class="xiala" v-on:change="indexSelect" v-model="supplierid" style="width: 120px;margin-right: 10px;margin-left: 5px">
								<option value="">请选择</option>
								<option v-for="time in good_types" :value="time.type_name">{{time.type_name}}</option>
							</select>
							<span>供应商:</span>
							<select name="" class="xiala " v-on:change="indexSelect" v-model="typeid" style="width: 120px;margin-right: 10px;margin-left: 5px">
								<option value="">请选择</option>
								<option v-for="time in suppliers" value="time.supplier_name">{{time.supplier_name}}
								</option>
							</select>
							<span>商品名称:</span>

							<el-input size="small" placeholder="商品名称查询" v-model="search_s" style="width: 120px;margin-right: 10px;margin-left: 5px"></el-input>
							<el-button type="success" @click="goods_search" size="small">查询</el-button>
						</div>
					</el-col>


					<el-col :span="24">
						<div class="tableBox">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th></th>
										<th>商品编号</th>
										<th>商品名称</th>
										<th>图片</th>
										<th>规格</th>
										<th>单位</th>
										<th>销售价格</th>
										<th>采购价</th>
										<th>上次采购价</th>
										<th>可用库存</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="(goods,index) in goods_list">
										<td>
											<input type="radio" name="shang" @change="getCity(goods.id,goods.good_name)" v-model="shangid" :value="goods.id">
										</td>
										<td ref="goods"> {{goods.good_num}}</td>
										<td>{{goods.good_name}}</td>
										<td><img style="width: 10px;height: 10px" preview="1" :src="goods.imgpath" alt="">
										</td>
										<td>{{goods.spec}}</td>
										<td>{{goods.unit}}</td>
										<td>{{goods.real_price}}</td>
										<td>{{goods.purchase_price}}</td>
										<td>{{goods.prev_purprice}}</td>
										<td>{{goods.stock}}</td>
									</tr>

								</tbody>
							</table>
							<p>共有{{pod_count}}条记录 当前{{page}}页/共{{page_count}}页</p>

							<div class="table-bottom" style="margin-bottom: 10px">
								<div>
									<el-pagination @current-change="fanye" :current-page="currentpage" :page-count="page_count" :page-size="pageone"
									 background layout="prev, pager, next">
									</el-pagination>
								</div>
							</div>
						</div>
					</el-col>

					<el-col :span="24" style="text-align: center">
						<el-button type="primary" round size="mini" class="purBtnsz" @click="quetian">确认添加</el-button>
					</el-col>

				</el-row>
			</div>
		</el-dialog>
		<!-- 作废提示弹框 -->
		<el-dialog title="" :visible.sync="del_dialog" width="40%" center>
			<div class="frame">
				是否删除活动？
			</div>
			<span slot="footer" class="dialog-footer">
				<el-button style="color:#7C72C1;border-color:#7C72C1" @click="dels()" size="small">确定</el-button>
				<span style="padding: 0 80px;"></span>
				<el-button style="color:#7C72C1;border-color:#7C72C1" @click="del_dialog=false" size="small">取消</el-button>
			</span>
		</el-dialog>

		<!--引入bootstrap充值弹窗s-->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="background:rgba(0,0,0,0.3) ">
			<div class="modal-dialog" role="document" style="width:694px;margin-top: 12%">
				<div class="modal-content" style="border-radius:10px">
					<div class="modal-header" style="padding: 10px 5px 5px 10px;background:#7c72c1">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close" v-on:click="guanbanniu" style=";font-weight: normal;color: #fff;opacity: 1;text-shadow: none;"><span
							 style="font-size: 26px" aria-hidden="true">&times;</span></button>
						<h5 class="modal-title text-center" style="color: #fff">充值</h5>
					</div>
					<div class="modal-body">
						<div class="container-fluid">
							<div class="row">
								<div class="col-sm-5" style="padding: 0">
									<div class="erweimaBox">
										<div class="erweima">
											<img :src="erw" alt="">
										</div>
									</div>
								</div>
								<div class="col-sm-7" style="padding: 0">
									<div style="padding-top:120px">
										<div class="text-center" style="line-height: 40px;color: #999;font-size: 20px">
											请输入金额 :<input class="jineinp" v-model="qian" type="text">元
										</div>
										<p class="text-center" style="color: #999;line-height: 40px;font-size: 12px">
											提示：微信官方收取手续费
										</p>
										<div style="padding-top: 10px;padding-right: 30px" class="fix">
											<button type="button" v-if="qian" v-on:click="quedingc" class="el-button biganniu el-button--primary is-round"
											 style="padding: 0;width: 86px;min-width: 86px;height: 32px;line-height: 32px;float: right;">
												<span>确定</span>
											</button>
											<!-- <button type="button" v-if="!qian"  class="el-button biganniu el-button--default is-round" style="padding: 0;width: 86px;min-width: 86px;height: 32px;line-height: 32px;float: right;">
                                                        <span>请先输入金额</span>
                                                    </button> -->

											<p v-if="!qian" class="text-center">请先输入金额</p>

										</div>
										<div id="myDiv" class="text-center"></div>
									</div>
								</div>
							</div>
						</div>
						<!--<div style="width: 180px;height: 180px;margin: 10px auto 20px;overflow: hidden;background:#7f7f7f ">-->

						<!--<img class="erweima"  src="" alt="">-->

						<!--</div>-->

						<!--<p class="text-center" style="color: #999;line-height: 40px;font-size: 12px">-->
						<!--提示：微信官网收取手续费-->
						<!--</p>-->
					</div>
				</div>
			</div>
		</div>


	</div>
</template>

<script>
	export default {
		data() {
			return {
				token: '',
				// 一级数据
				had_list: [{
						name: '我的',
						i: 1
					},
					{
						name: '主题活动',
						i: 2
					},
					{
						name: '节日活动',
						i: 3
					}
				], // 一级数据e
				idx: 0, //一级数据状态切换值
				idx2: 0, //二级数据状态切换值
				sta: true, //显示二级数据状态值
				// 二级数据s
				twotab: [{
						name: '全部作品',
						i: 1
					},
					{
						name: '进行中',
						i: 2
					},
					{
						name: '已完成',
						i: 3
					},
					{
						name: '未发布',
						i: 4
					},
					{
						name: '已禁用',
						i: 5
					},
					{
						name: '收藏',
						i: 6
					},
					{
						name: '统计',
						i: 7
					},
					{
						name: '钱包',
						i: 8
					}
				], // 二级数据e
				my_list: '', //我的活动列表
				my_count: 0, //我的分页
				my_page: 1, //我的分页
				my_pageone: 0, //我的分页
				my_pagetotal: 0, //我的分页
				collect: '', //收藏列表
				pages1: 1, //当前页
				reveals: true, // 鼠标状态
				stencildialog: false, //模版弹窗
				setupdialog: false, //基础设置/分享设置
				previewdialog: false, //预览模版
				codedialog: false, //活动成功后弹窗
				del_dialog: false, //作废活动
				text: '', //文本内容
				ziti: '', //字体
				zihao: '', //字号
				jianju: '', //间距
				color: '#000', //颜色
				yangshis: '', //样式加粗
				underline: '', //样式下划线
				yixd: 3, //样式切换状态值
				fenimg: '', //背景图空值
				poster: '', //模版背景海报图
				imgss1: [], //上传的海报背景图
				pagination: 1, //页数
				template_id: '', //模版id
				xiabiao: '', //模板预览页数
				mb: {
					//文本框1
					left1: '10', //左(距离)
					top1: '20', //上(距离)
					text1: '', //文本内容
					ziti1: '', //字体
					zihao1: '', //字号
					jianju1: '', //间距
					color1: '#000', //颜色
					yangshi1: '', //样式加粗
					underline1: '', //样式下划线
					//文本框2
					left2: ' 10',
					top2: '150',
					text2: '',
					ziti2: '',
					zihao2: '',
					jianju2: '',
					color2: '#000',
					yangshi2: '',
					underline2: '',
					//文本框3
					left3: ' 10',
					top3: '350',
					text3: '',
					ziti3: '',
					zihao3: '',
					jianju3: '',
					color3: '#000',
					yangshi3: '',
					underline3: '',
				},
				dra_idx: '', //文本框切换状态值
				ke_list: '', //课时包列表
				centre_id: "", //中心id
				hd_id: '', //设置ID
				// 基础设置s
				forma: {
					resource: '', //活动类型
					name: '', //活动名称
					desc: '', //活动描述
                    linkhref: '', //了解更多
					enrolldate: '', //报名时间
					activitydate: '', //活动时间
					activitydate1: '',
					a_address: '', // 活动地址
					haoke: '', //耗课数
					// k_region: '', //课时包
					k_number: '', //课时包数量
					type: '', //类型
					k_price: '', //课时包价格
					core: '', //中心名称
					phone: '', //联系电话
					address: '', //中心地址
					s_name:'',	//新课时包名称
				}, // 基础设置e
				//分享设置s
				f_share: {
					name: '', //标题
					desc: '', //描述
					guize:'',//活动规则
					img: '', //分享图
					packet: 3, //红包状态值
					hje_z: '', //红包总金额
					hje_d: '', //单个红包
					hgs: '', //红包个数
					zhek_s: '', //会员折扣
				}, //分享设置e
				she_list: [{
					name: '基础设置'
				}, {
					name: '分享设置'
				}, ],
				sidx: 0, //基础和分享切换状态值
				fenimg2: '', //分享图空值
				share_img: '', //分享图
				imgss2: '', //分享的海报背景图
				jfbox: [{}], //积分商城设置
				xuanshang: false, //商品弹框状态值
				storindex: "", //商品对应下标
				shangid: '', //商品id
				good_types: '', //商品类别
				supplierid: '', //商品类别id
				suppliers: '', //供应商
				typeid: '', //供应商id
				search_s: '', //商品搜索
				goods_list: '', //商品列表
				pod_count: '', //商品列表总条数
				page: 1, //商品分页
				page_count: '', //商品总分页
				currentpage: 1,
				pageone: 5,
				rules: '', //积分商城商品数组
				fenBox: [],
				zhek_s: '', //积分设置s
				zhek_a: '', //积分设置a
				zhek_b: '', //积分设置b
				zhek_c: '', //积分设置c
				zhek_d: '', //积分设置s
				success1: false,
				success2: false,
				success3: false,
				success4: false,
				tj_date: [], //总统计时间查询
				zong: '', //总统计
				ztj_list: '', //总统计列表
				tj_count: 0, //总统计分页
				tj_page: 1, //总统计分页
				tj_pageone: 0, //总统计分页
				tj_pagetotal: 0, //总统计分页
				pages1: 1, //当前页
				point_index: -1, //
				qb_date: '', //钱包时间选择值
				chos: '1', //钱包本月本周状态
				qb_list: '', //钱包列表
				shiyong: '', //钱包使用数据
				qianbao: '', //钱包数据
				erw: '', //充值二维码
				qian: '', //金额
				hd_qrcode: "", //判断有无充值二维码
				code: '',
				red1: '',
				red2: '',
				red3: '',
				del_id: '', //作废模版id
				del_hdid: '', //作废活动id
				sou: '', //搜索
				chongzhiji: 2,
				handleCommand:'',
				username:''

			}
		},
		mounted: function() {
			var that = this
			that.token = that.$cookies.get("token")
			// that.shangpin_list()
			that.get_core()
			that.get_my()
			that.get_collect()
			that.get_qb()
			that.get_ztj()


			that.token = $.cookie('token');
			// console.log(that.token);
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

// 			$.ajax({
// 				url: "https://hdapi.codeclassroom.org/index/notice/index",
// 				type: "post",
// 				dataType: "json",
// 				async: false,
// 				data: {type: 2},
// 				success: function(data) {
// // 					if (data.status == 1) {
// // 						that.noticedialog = true
// // 						that.tong = data.data
// // 					} else {
// // 						that.noticedialog = false
// // 					}
// 				}
// 			})

			// this.get_data3()
			// this.get_data4()
            // this.check_user_info()
            this.$router.push('/xindex');
		},
		methods: {
            login_out_sys: function() {
                $.cookie('token', null);
                this.$router.push("login")
                // location.href = "https://hdapi.codeclassroom.org/index/index?loginout=out"
            },
            tiaoye(d, index,pid) {
                this.$router.push(d)
                this.idx = index
                if(pid > 0){
                    this.xuan = pid;
                }
            },
			get_re: function() {
				console.log(this.forma.resource)
			},
			// 复制二维码
			get_copy: function(data) {
				var input = document.createElement("input") // 直接构建input
				input.value = data // 设置内容
				document.body.appendChild(input) // 添加临时实例
				input.select() // 选择实例内容
				document.execCommand("Copy"); // 执行复制
				document.body.removeChild(input); // 删除临时实例
				this.$message({
					message: '已成功复制到剪切板',
					type: 'success'
				});
			},
			// 立即使用
			get_quote: function() {
				this.shezhi_del()
				this.moban_del()
				this.get_core()
				this.pagination = 1
				this.moban_preview()
				this.previewdialog = false
				this.stencildialog = true
			},
			// 编辑
			get_edit: function(i, id) {
				this.stencildialog = true
				this.pagination = 1
				this.template_id = i
				this.moban_preview(2)
				this.hd_id = id
			},
			// 编辑显示
			get_editors: function() {
				var that = this
				$.ajax({
					url: "https://hdapi.codeclassroom.org/index/huodong/get_huodong",
					type: "post",
					dataType: 'json',
					data: {
						token: that.token,
						hd_id: that.hd_id,
					},
					success: function(e) {
						if (e.stauts == 1) {
							that.forma.resource = Number(e.data.hd_type) //活动类型
							that.forma.name = e.data.jc_name //活动名称
							that.forma.desc = e.data.jc_describe //活动描述
                            that.forma.linkhref = e.data.linkhref //了解更多
							that.forma.a_address = e.data.hd_site // 活动地址
							that.forma.haoke = e.data.xiaohao //耗课数
							// that.forma.k_region = e.data.s_id //课时包
							that.forma.s_name = e.data.s_name //课时包名称
							that.forma.k_number = e.data.k_shu //课时包数量
							that.forma.k_price = e.data.s_price //课时包价格
							that.forma.type = e.data.is_free //类型 1免费赠送 2购买
							that.centre_id = e.data.centre_id //中心id
							that.forma.phone = e.data.c_phone //中心电话
							that.forma.address = e.data.c_site //中心地址
							that.f_share.name = e.data.title //分享标题
							that.f_share.desc = e.data.fx_describe //描述
							that.f_share.guize = e.data.guize //活动规则
							that.share_img = e.data.img //分享图（显示）
							that.imgss2 = e.data.img //分享图
							that.f_share.packet = e.data.red_type //红包配置
							that.f_share.hje_z = e.data.red_total //红包总金额
							that.f_share.hje_d = e.data.red_single //单个红包金额
							that.f_share.hgs = e.data.red_num //红包个数
							that.f_share.zhek_s = e.data.discount //会员折扣
							// that.jfbox = e.rules //积分商城数组
							// that.rules = e.rules //积分商城数组
							that.zhek_a = e.data.integral_type1 //积分设置
							that.zhek_b = e.data.integral_type2 //积分设置
							that.zhek_c = e.data.integral_type3 //积分设置
							that.zhek_d = e.data.integral_type4 //积分设置
							if (e.data.bm_start_time) {
								that.forma.enrolldate = [e.data.bm_start_time, e.data.bm_end_time] //报名时间
							}
							if (that.forma.resource == 4) {
								that.forma.activitydate1 = e.data.hd_start_time //活动时间
							} else {
								that.forma.activitydate = [e.data.hd_start_time, e.data.hd_end_time] //活动时间
							}

						}

					}
				});

			},
			//作废弹出框
			tan_dels: function(id, hd_id) {
				this.del_dialog = true
				this.del_id = id
				this.del_hdid = hd_id
			},
			// 作废
			dels: function(id, hd_id) {
				var that = this

				$.ajax({
					url: "https://hdapi.codeclassroom.org/index/huodong/zuofei",
					type: "post",
					dataType: 'json',
					data: {
						token: that.token,
						template_id: that.del_id,
						hd_id: that.del_hdid,
					},
					success: function(e) {
						if (e.status == 1) {
							that.$message({
								message: e.msg,
								type: 'success'
							});
							that.del_dialog = false
							that.my_page = 1
							that.get_my()
						} else(
							that.$message.error(e.msg)
						)
					}
				});
			},
			// 发布
			get_release: function(id) {
				var that = this
				$.ajax({
					url: "https://hdapi.codeclassroom.org/index/huodong/fabu",
					type: "post",
					dataType: 'json',
					data: {
						token: that.token,
						template_id: id,
					},
					success: function(e) {
						if (e.status == 1) {
							that.$message({
								message: e.msg,
								type: 'success'
							});
							that.get_my()
						} else(
							that.$message.error(e.msg)
						)
					}
				});
			},
			// 收藏
			get_collects: function(id) {
				var that = this
				$.ajax({
					url: "https://hdapi.codeclassroom.org/index/huodong/sc_change",
					type: "post",
					dataType: 'json',
					data: {
						token: that.token,
						template_id: id,
					},
					success: function(e) {
						if (e.status == 1) {
							that.$message({
								message: e.msg,
								type: 'success'
							});
							that.get_my()
							that.get_collect()
						} else(
							that.$message.error(e.msg)
						)
					}
				});
			},
			//启用禁用
			get_use: function(i, id) {
				var that = this
				if (i == 1) {
					$.ajax({
						url: "https://hdapi.codeclassroom.org/index/huodong/kaiqi",
						type: "post",
						dataType: 'json',
						data: {
							token: that.token,
							hd_id: id,
						},
						success: function(e) {
							if (e.status == 1) {
								that.$message({
									message: e.msg,
									type: 'success'
								});
								that.get_my()
							}
						}
					});
				} else if (i == 2) {
					$.ajax({
						url: "https://hdapi.codeclassroom.org/index/huodong/jinyong",
						type: "post",
						dataType: 'json',
						data: {
							token: that.token,
							hd_id: id,
						},
						success: function(e) {
							if (e.status == 1) {
								that.$message({
									message: e.msg,
									type: 'success'
								});
								that.get_my()
							}
						}
					});
				}

			},
			// 跳转活动统计页面
			link_tj: function(i) {
				this.$router.push({
					name: 'xtongji',
					query: {
						id: i
					}
				})
			},
			// 跳转预约页面
			link_yy: function(i) {
				this.$router.push({
					name: 'xyuyue',
					query: {
						id: i
					}
				})
			},
			// 充值
			chongtan: function() {
				var that = this;
				that.erw = "";
				that.qian = "";
				$("#myModal").modal({
					backdrop: "static"
				});
			},
			guanbanniu: function() {
				clearInterval(this.myIntval);
			},
			// 钱包 本月/本周
			chose1: function(i) {
				this.chos = i
				this.qb_date = ""
				this.get_qb()
			},
			// 总统计分页
			tj_fanye: function(val) {
				this.tj_page = val
				this.get_ztj()
			},
			// 总统计列表
			get_ztj: function() {
				var that = this
				$.ajax({
					url: "https://hdapi.codeclassroom.org/index/huodong/zongtj",
					type: "post",
					dataType: 'json',
					data: {
						token: that.token,
						time_s: that.tj_date[0],
						time_e: that.tj_date[1],
						page: that.tj_page,
					},
					success: function(e) {
						// console.log(e.data)
						if (e.stauts == 1) {
							that.zong = e.zong
							that.ztj_list = e.data
							that.tj_count = Number(e.map.count)
							that.tj_page = Number(e.map.page)
							that.tj_pageone = Number(e.map.pageone)
							that.tj_pagetotal = Number(e.map.pagetotal)
						} else {
							that.ztj_list = ''
							that.tj_count = 0
							that.tj_page = 0
							that.tj_pageone = 0
							that.tj_pagetotal = 0
						}
					}
				});
			},
			// 我的收藏
			get_collect: function() {
				var that = this
				$.ajax({
					url: "https://hdapi.codeclassroom.org/index/huodong/shoucang ",
					type: "post",
					dataType: 'json',
					data: {
						token: that.token,
					},
					success: function(e) {
						// console.log(e)
						if (e.status == 1) {
							that.collect = e.data
						} else {
							that.collect = ''
						}
					}
				});
			},
			// 我的分页
			my_fanye: function(e) {
				// console.log(e)
				this.my_page = e
				this.get_my()
			},
			// 搜索
			get_sou: function() {
				this.idx = 0
				this.idx2 = 0
				this.my_page = 1
				this.get_my()
			},
			// 我的活动列表
			get_my: function() {
				var that = this
				var page = 4
				if (this.tp_type > 0) {
					page = 5
				}
                $.ajax({
					url: "https://hdapi.codeclassroom.org/index/huodong/index",
					type: "post",
					dataType: 'json',
					data: {
						token: that.token,
						type: that.idx2,
						tp_type: that.idx,
						page: that.my_page,
						pageone: page,
						set: that.sou,
					},
					success: function(e) {
						// console.log(e)
						if (e.stauts == 1) {
							that.my_list = e.data
							that.my_count = Number(e.map.count)
							that.my_page = Number(e.map.page)
							that.my_pageone = Number(e.map.pageone)
							that.my_pagetotal = Number(e.map.pagetotal)
						} else {
							that.my_list = ''
							that.my_count = 0
							that.my_page = 0
							that.my_pageone = 0
							that.my_pagetotal = 0
						}
					}
				});
			},
			// 基础设置 分享设置——保存/创建 
			get_sure: function(i) {
				this.nextfn1()
				this.nextfn2()
				// this.nextfn3()
				// this.nextfn4()
				if (this.success1 && this.success2 ) {
					if (this.forma.enrolldate == "") {
						this.forma.enrolldate = ['', '']
					}
					var hd_dade1 = ''
					var hd_dade2 = ''
					if (this.forma.resource == 4) {
						hd_dade1 = this.forma.activitydate1
						hd_dade2 = this.forma.activitydate1
					} else {
						hd_dade1 = this.forma.activitydate[0]
						hd_dade2 = this.forma.activitydate[1]
					}
					//提交所有数据
					var Data = [];
					// console.log(this.idx)
					Data.push({
						hd_id: this.hd_id, //编辑传的id
						tp_type: this.idx, //模版类型
						type: i, //1保存  2创建
						template_id: this.template_id, //模版id
						hd_type: this.forma.resource, //活动类型
						jc_name: this.forma.name, //活动名称
						jc_describe: this.forma.desc, //活动描述
                        linkhref: this.forma.linkhref, //了解更多
						bm_start_time: this.forma.enrolldate[0], //报名开始时间
						bm_end_time: this.forma.enrolldate[1], //报名开始时间
						hd_start_time: hd_dade1, //活动开始时间
						hd_end_time: hd_dade2, //活动结束时间
						hd_site: this.forma.a_address, // 活动地址
						xiaohao: this.forma.haoke, //耗课数
						// s_id: this.forma.k_region, //课时包
						s_name: this.forma.s_name, //课时包
						k_shu: this.forma.k_number, //课时包
						s_price: this.forma.k_price, //课时包价格
						is_free: this.forma.type, //类型 1免费赠送 2购买
						centre_id: this.centre_id, //中心id
						c_phone: this.forma.phone, //中心电话
						c_site: this.forma.address, //中心地址
						title: this.f_share.name, //分享标题
						fx_describe: this.f_share.desc, //描述
						guize:this.f_share.guize,//活动规则
						img: this.imgss2, //分享图
						red_type: this.f_share.packet, //红包配置
						red_total: this.f_share.hje_z, //红包总金额
						red_single: this.f_share.hje_d, //单个红包金额
						red_num: this.f_share.hgs, //红包个数
						discount: this.f_share.zhek_s, //会员折扣
						rules: this.rules, //积分商城数组
						integral_type1: this.zhek_a, //积分设置
						integral_type2: this.zhek_b, //积分设置
						integral_type3: this.zhek_c, //积分设置
						integral_type4: this.zhek_d, //积分设置
					});
					// console.log(Data)
					var that = this
					$.ajax({
						url: "https://hdapi.codeclassroom.org/index/huodong/set_huodong",
						type: "post",
						dataType: 'json',
						data: {
							token: that.token,
							Data: Data,
						},
						success: function(e) {
							// console.log(e)
							if (e.status == 1) {
								that.$message({
									message: '已保存至未发布',
									type: 'success'
								});
								that.setupdialog = false
								that.my_page = 1
								that.get_my()
							} else if (e.status == 2) {
								that.setupdialog = false
								that.codedialog = true
								that.code = e.data
								// 								that.$message({
								// 									message: '创建成功！',
								// 									type: 'success'
								// 								});
								that.my_page = 1
								that.get_my()
							} else {
								that.$message.error(e.msg);
							}

						}
					});
				} else {
					this.success1 = false;
					this.success2 = false;
					this.success3 = false;
					this.success4 = false;
				}


			},
			// 中心信息
			get_core: function() {
				var that = this
				$.ajax({
					url: "https://hdapi.codeclassroom.org/index/huodong/getinfo",
					type: "post",
					dataType: 'json',
					data: {
						token: that.token
					},
					success: function(e) {
						if (e.stauts == 1) {
							that.ke_list = e.data.goods //课时包
							that.k_number = e.data.centre.centre_id //课时包数量
							that.forma.core = e.data.centre.centre //中心名称
							that.forma.phone = e.data.centre.c_phone //联系电话
							that.forma.address = e.data.centre.site //中心地址
							that.forma.a_address = e.data.centre.site //初始默认活动地址	
							that.centre_id = e.data.centre.centre_id //中兴id
						}
					}
				});
			},
			//积分设置
			clickMe: function() {
				if (this.fenBox.indexOf('1') < 0) {
					this.zhek_a = ''
				}
				if (this.fenBox.indexOf('2') < 0) {
					this.zhek_b = ''
				}
				if (this.fenBox.indexOf('3') < 0) {
					this.zhek_c = ''
				}
				if (this.fenBox.indexOf('4') < 0) {
					this.zhek_d = ''
				}
			},
			// 商品数据*************************************
			shangpin_list: function() {
				var that = this
				var arr = {
					'search': that.search,
					'typeid': that.typeid,
					'supplierid': that.supplierid,
					'type': 'pd',
					'page': that.page,
					'pageone': 3
				}

				$.ajax({
					url: "https://hdapi.codeclassroom.org/index/JxcGoods/goodslist2",
					type: "post",
					dataType: 'json',
					data: {
						token: that.token,
						map: arr
					},
					success: function(e) {
						// console.log(e)
						that.goods_list = e.data
						that.page_count = Number(e.map.pagetotal)
						that.pod_count = Number(e.map.count)
						that.good_types = e.good_types
						that.suppliers = e.suppliers

					}
				});
			},
			// 商品数据end*************************************

			//商品分页
			fanye: function(i) {
				this.page = i;
				this.shangpin_list();
				this.shangid = ''
			},
			getCity: function(id, name) {
				this.jfbox[this.storindex].shabian2 = name
			},
			// 选择商品
			quetian: function() {
				if (this.shangid) {
					this.xuanshang = false
					this.jfbox[this.storindex].shabian3 = this.shangid
				} else {
					this.$message({
						message: '请选择商品！',
						type: 'warning'
					});
				}
			},
			// 商品下拉框筛选
			indexSelect: function() {
				this.shangpin_list();
			},
			// 商品输入框筛选
			goods_search: function() {
				this.search = this.search_s
				this.shangpin_list();
			},
			// 积分商城设置-选择商品
			goodstan: function(i) {
				this.storindex = i;
				this.xuanshang = true;
				this.shangid = ''
			},
			// 积分商城设置添加
			addhui: function() {
				this.jfbox.push({})
			},
			// 积分商城设置删除
			delhui: function(i) {
				this.jfbox.splice(i, 1)
			},
			// 模版保存按钮
			storager: function() {
				if (this.poster == "") {
					this.$message.error('请上传图片!');
					return;
				}
				var i = 2
				var aa = 2
				this.moban_submit(i, aa)
			},
			// 下一步设置
			next_step: function() {
				if (this.poster == "") {
					this.$message.error('请上传图片!');
					return;
				}
				var i = 3
				var aa = 2
				this.sidx = 0
				this.moban_submit(i, aa)
				this.get_core()
				this.get_editors()
			},
			// 模版页面切换（上）
			upper: function() {
				if (this.pagination > 1) {
					this.pagination = this.pagination - 1
					this.moban_preview()
				} else {
					this.$message.error('已经是第1页!');
				}
			},
			// 模版页面切换（下）
			down: function() {
				if (this.poster == "") {
					this.$message.error('请上传图片!');
					return;
				}
				var i = 1
				var aa = 1
				this.moban_submit(i, aa)
			},
			// 模版预览-上
			preview_s() {
				if (this.pagination > 1) {
					this.pagination = this.pagination - 1
					this.moban_preview()
				} else {
					this.$message.error('已经是第1页!');
				}
			},
			// 模版预览-下
			preview_x() {
				if (this.pagination < this.xiabiao) {
					this.pagination = this.pagination + 1
					this.moban_preview()
				} else {
					this.$message.error('已经是最后1页!');
				}

			},
			// 模版数据提交保存
			moban_submit: function(i, aa) {
				var that = this
				// console.log(i)
				$.ajax({
					url: "https://hdapi.codeclassroom.org/index/huodong/template",
					type: "post",
					dataType: 'json',
					data: {
						token: that.token,
						tp_type: that.idx,
						template_id: that.template_id, //模版id
						baocun: aa, //保存
						img: that.imgss1, //背景图
						p: that.pagination, //页数
						top1: that.mb.top1,
						left1: that.mb.left1,
						text1: that.mb.text1,
						ziti1: that.mb.ziti1,
						zihao1: that.mb.zihao1,
						jianju1: that.mb.jianju1,
						color1: that.mb.color1,
						yangshi1: that.mb.yangshi1,
						underline1: that.mb.underline1,
						top2: that.mb.top2,
						left2: that.mb.left2,
						text2: that.mb.text2,
						ziti2: that.mb.ziti2,
						zihao2: that.mb.zihao2,
						jianju2: that.mb.jianju2,
						color2: that.mb.color2,
						yangshi2: that.mb.yangshi2,
						underline2: that.mb.underline2,
						top3: that.mb.top3,
						left3: that.mb.left3,
						text3: that.mb.text3,
						ziti3: that.mb.ziti3,
						zihao3: that.mb.zihao3,
						jianju3: that.mb.jianju3,
						color3: that.mb.color3,
						yangshi3: that.mb.yangshi3,
						underline3: that.mb.underline3,
					},
					success: function(e) {
						// console.log(e)
						if (i == 2) {
							if (e.status == 1) {
								that.stencildialog = false
								that.$message({
									message: '保存成功！',
									type: 'success'
								});
								that.my_page = 1
								that.get_my()
							}
						} else if (i == 3) {
							if (e.status == 1) {
								that.template_id = e.template_id
								that.pagination = 1
								that.moban_preview()
								that.stencildialog = false
								that.setupdialog = true

							}
						} else {
							if (e.status == 1) {
								that.template_id = e.template_id
								that.pagination = Number(e.p) + 1
								that.moban_del()
								that.moban_preview()

							}
						}

					}
				});
			},
			//模版预览1111
			moban_preview: function(base) {
				var that = this
				$.ajax({
					url: "https://hdapi.codeclassroom.org/index/huodong/shang",
					type: "post",
					async: false,
					data: {
						token: that.token,
						template_id: that.template_id,
						p: that.pagination, //页数
					},
					success: function(e) {
						// console.log(e)
						if (e.status == 1) {
							that.poster = e.data.img
							that.mb = e.data
							that.xiabiao = e.data.xiabiao
							that.imgss1 = e.data.img

						}
					}
				})
			},
			// 分享设置基础设置切换
			sbut: function(i) {
				this.sidx = i
			},
			// 弹出模版弹框
			templet: function() {
				this.template_id = ""
				this.hd_id = ""
				this.pagination = 1
				this.stencildialog = true
				this.moban_del()
				this.shezhi_del()
				if ($('#file2').val()) {
					this.$refs.pathClear2.value = ''
				}
				if ($('#file3').val()) {
					this.$refs.pathClear3.value = ''
				}
			},
			// 模版预览
			get_yulang: function(id, hd) {
				this.template_id = id
				this.hd_id = hd
				this.pagination = 1
				this.previewdialog = true
				this.moban_preview()
			},
			// 清空模板样式
			moban_del: function() {
				this.poster = ""
				this.share_img = ""
				this.imgss1 = ""
				this.imgss2 = ""
				this.text = ''
				this.ziti = ''
				this.zihao = ''
				this.jianju = ''
				this.color = '#000'
				this.yangshis = ''
				this.underline = ''
				this.yixd = 3
				this.mb.left1 = '10'
				this.mb.top1 = '20'
				this.mb.text1 = ''
				this.mb.ziti1 = ''
				this.mb.zihao1 = ''
				this.mb.jianju1 = ''
				this.mb.color1 = '#000'
				this.mb.yangshi1 = ''
				this.mb.underline1 = ''
				this.mb.left2 = '10'
				this.mb.top2 = '150'
				this.mb.text2 = ''
				this.mb.ziti2 = ''
				this.mb.zihao2 = ''
				this.mb.jianju2 = ''
				this.mb.color2 = '#000'
				this.mb.yangshi2 = ''
				this.mb.underline2 = ''
				this.mb.left3 = '10'
				this.mb.top3 = '350'
				this.mb.text3 = ''
				this.mb.ziti3 = ''
				this.mb.zihao3 = ''
				this.mb.jianju3 = ''
				this.mb.color3 = '#000'
				this.mb.yangshi3 = ''
				this.mb.underline3 = ''
				this.red1 = ''
				this.red2 = ''
				this.red3 = ''
			},
			// 清空设置
			shezhi_del: function() {
				this.forma.resource = '' //活动类型
				this.forma.name = '' //活动名称
				this.forma.desc = '' //活动描述
                this.forma.linkhref = '' //了解更多
				this.forma.enrolldate = '' //报名时间
				this.forma.activitydate = '' //活动时间
				this.forma.a_address = '' // 活动地址
				this.forma.haoke = '' //耗课数
				// this.forma.k_region = '' //课时包
				this.forma.s_name = '' //课时包
				this.forma.k_number = '' //课时包数量
				this.forma.type = '' //类型
				this.forma.k_price = '' //课时包价格
				this.forma.core = '' //中心名称
				this.forma.phone = '' //联系电话
				this.forma.address = '' //中心地址
				this.f_share.name = '' //标题
				this.f_share.desc = '' //描述
				this.f_share.guize = '' //活动规则
				this.f_share.img = '' //分享图
				this.f_share.packet = 3 //红包状态值
				this.f_share.hje_z = '' //红包总金额
				this.f_share.hje_d = '' //单个红包
				this.f_share.hgs = '' //红包个数
				this.f_share.zhek_s = '' //会员折扣
				this.fenBox = []
				this.zhek_a = ''
				this.zhek_b = ''
				this.jfbox = [{}]
			},
			// 样式切换
			ybut: function(i) {
				this.yixd = i
				if (this.yixd == 1) {
					this.yangshis = 'bold'
					this.underline = ''
				} else if (this.yixd == 2) {
					this.yangshis = ''
					this.underline = 'underline'
				} else {
					this.yangshis = ''
					this.underline = ''
				}
				if (this.dra_idx == 1) {
					this.mb.yangshi1 = this.yangshis
					this.mb.underline1 = this.underline
				} else if (this.dra_idx == 2) {
					this.mb.yangshi2 = this.yangshis
					this.mb.underline2 = this.underline
				} else if (this.dra_idx == 3) {
					this.mb.yangshi3 = this.yangshis
					this.mb.underline3 = this.underline
				}
			},
			// 课时包数量获取
			choose: function(value) {
				var choosenItem = this.ke_list.filter(k => k.s_id === value)[0];
				this.forma.k_number = choosenItem.k_shu
			},
			// 一级切换
			tab: function(i) {
				this.idx = i
                this.my_page = 1
				this.get_my()
				if (this.idx == 0) {
					this.sta = true
					this.idx2 = 0
				} else {
					this.sta = false
				}
			},
			// 我的 二级切换
			tab2: function(i) {
				this.idx2 = i
				if (i < 5) {
					this.my_page = 1
					this.get_my()
				}
				if (i == 5) {
					this.get_collect()
				}
				if (i == 6) {
					this.tj_page = 1
					this.get_ztj()
				}
				if (i == 7) {
					this.get_qb()
				}
			},
			//鼠标移入移出状态
			change1: function(i) {
				this.point_index = i
			},
			change2: function() {
				this.point_index = -1
			},
			// 模版背景图上传
			fileUp: function(el) {
				let _this = this;
				if (!el.target.files[0].size) {
					this.fenimg = ''
				} else {
					this.poster = URL.createObjectURL(el.target.files[0])
					let file = el.target.files[0]
					let reader = new FileReader();
					let image = new Image();
					let img = [];
					reader.readAsDataURL(file);
					reader.onload = function() {
						file.src = this.result;
						img.push(this.result)
						_this.imgss1 = img
					}
				}
			},
			// 分享图上传
			fileupload: function(el) {
				let _this = this;
				if (!el.target.files[0].size) {
					this.fenimg2 = ''
				} else {
					this.share_img = URL.createObjectURL(el.target.files[0])
					let file = el.target.files[0]
					let reader = new FileReader();
					let image = new Image();
					let img = [];
					reader.readAsDataURL(file);
					reader.onload = function() {
						file.src = this.result;
						img.push(this.result)
						_this.imgss2 = img
					}
				}
			},
			// 文本框拖拽功能
			drag: function() {
				var that = this
				var oDiv = document.getElementById('drags');
				var disX = 0,
					disY = 0;
				oDiv.onmousedown = function(ev) {
					var oEvent = ev || event;
					disX = oEvent.clientX - oDiv.offsetLeft;
					disY = oEvent.clientY - oDiv.offsetTop;
					document.onmousemove = function(ev) {
						var oEvent = ev || event;
						var l = oEvent.clientX - disX;
						var t = oEvent.clientY - disY;
						if (l <= 0) {
							l = 0
						}
						if (t < 0) {
							t = 0
						}
						if (l > 265 - oDiv.offsetWidth) {
							l = 265 - oDiv.offsetWidth;
						}
						if (t > 480 - oDiv.offsetHeight) {
							t = 480 - oDiv.offsetHeight;
						}
						oDiv.style.left = l + 'px';
						oDiv.style.top = t + 'px';
						that.mb.left1 = l
						that.mb.top1 = t
					}
					document.onmouseup = function(ev) {
						document.onmousemove = null;
						document.onmouseup = null;
					}

				}

			},
			drag2: function() {
				var that = this
				var oDiv = document.getElementById('drags2');
				var disX = 0,
					disY = 0;
				oDiv.onmousedown = function(ev) {
					var oEvent = ev || event;
					disX = oEvent.clientX - oDiv.offsetLeft;
					disY = oEvent.clientY - oDiv.offsetTop;
					document.onmousemove = function(ev) {
						var oEvent = ev || event;
						var l = oEvent.clientX - disX;
						var t = oEvent.clientY - disY;
						if (l <= 0) {
							l = 0
						}
						if (t < 0) {
							t = 0
						}
						if (l > 265 - oDiv.offsetWidth) {
							l = 265 - oDiv.offsetWidth;
						}
						if (t > 480 - oDiv.offsetHeight) {
							t = 480 - oDiv.offsetHeight;
						}
						oDiv.style.left = l + 'px';
						oDiv.style.top = t + 'px';
						that.mb.left2 = l
						that.mb.top2 = t
					}

					document.onmouseup = function(ev) {
						document.onmousemove = null;
						document.onmouseup = null;
					}

				}
			},
			drag3: function() {
				var that = this
				var oDiv = document.getElementById('drags3');
				var disX = 0,
					disY = 0;
				oDiv.onmousedown = function(ev) {
					var oEvent = ev || event;
					disX = oEvent.clientX - oDiv.offsetLeft;
					disY = oEvent.clientY - oDiv.offsetTop;
					document.onmousemove = function(ev) {
						var oEvent = ev || event;
						var l = oEvent.clientX - disX;
						var t = oEvent.clientY - disY;
						if (l <= 0) {
							l = 0
						}
						if (t < 0) {
							t = 0
						}
						if (l > 265 - oDiv.offsetWidth) {
							l = 265 - oDiv.offsetWidth;
						}
						if (t > 480 - oDiv.offsetHeight) {
							t = 480 - oDiv.offsetHeight;
						}
						oDiv.style.left = l + 'px';
						oDiv.style.top = t + 'px';
						that.mb.left3 = l
						that.mb.top3 = t
					}

					document.onmouseup = function(ev) {
						document.onmousemove = null;
						document.onmouseup = null;
					}

				}
			},

			// 文本样式设置
			getdra: function(i) {
				if (i == 1) {
					this.dra_idx = 1
					this.text = this.mb.text1
					this.ziti = this.mb.ziti1
					this.zihao = this.mb.zihao1
					this.jianju = this.mb.jianju1
					this.color = this.mb.color1
					if (this.mb.yangshi1) {
						this.yixd = 1
					} else if (this.mb.underline1) {
						this.yixd = 2
					} else {
						this.yixd = 3
					}
					this.red1 = 'red'
					this.red2 = ''
					this.red3 = ''
				} else if (i == 2) {
					this.dra_idx = 2
					this.text = this.mb.text2
					this.ziti = this.mb.ziti2
					this.zihao = this.mb.zihao2
					this.jianju = this.mb.jianju2
					this.color = this.mb.color2
					if (this.mb.yangshi2) {
						this.yixd = 1
					} else if (this.mb.underline2) {
						this.yixd = 2
					} else {
						this.yixd = 3
					}
					this.red1 = ''
					this.red2 = 'red'
					this.red3 = ''
				} else if (i == 3) {
					this.dra_idx = 3
					this.text = this.mb.text3
					this.ziti = this.mb.ziti3
					this.zihao = this.mb.zihao3
					this.jianju = this.mb.jianju3
					this.color = this.mb.color3
					if (this.mb.yangshi3) {
						this.yixd = 1
					} else if (this.mb.underline3) {
						this.yixd = 2
					} else {
						this.yixd = 3
					}
					this.red1 = ''
					this.red2 = ''
					this.red3 = 'red'
				}
			},
			// 基础设置验证
			nextfn1: function() {
				this.success1 = false;
				if (this.forma.resource == '') {
					this.$message({
						message: '活动类型不能为空！',
						type: 'warning'
					});
					return;
				}
				if (this.forma.name == '') {
					this.$message({
						message: '活动名称不能为空！',
						type: 'warning'
					});
					return;
				}
				if (this.forma.desc == '') {
					this.$message({
						message: '活动描述不能为空！',
						type: 'warning'
					});
					return;
				}
				if (this.f_share.guize == '') {
					this.$message({
						message: '活动规则不能为空！',
						type: 'warning'
					});
					return;
				}
				if (this.forma.resource == 4) {
					if (this.forma.activitydate1 == '' || this.forma.activitydate1 == undefined) {
						this.$message({
							message: '活动时间不能为空！',
							type: 'warning'
						});
						return;
					}
				} else {
					if (this.forma.activitydate == '' || this.forma.activitydate == undefined) {
						this.$message({
							message: '活动时间不能为空！',
							type: 'warning'
						});
						return;
					}
				}

				if (this.forma.type == '') {
					this.$message({
						message: '请选择类型！',
						type: 'warning'
					});
					return;
				}
				if (this.forma.type == 2) {
					if (this.forma.k_price == '') {
						this.$message({
							message: '请选择课时包价格！',
							type: 'warning'
						});
						return;
					}
					// if (this.forma.k_region == '') {
					// 	this.$message({
					// 		message: '请选择课时包！',
					// 		type: 'warning'
					// 	});
					// 	return;
					// }
                    if (this.forma.s_name == '') {
                        this.$message({
                            message: '请填写课时包！',
                            type: 'warning'
                        });
                        return;
                    }
                    if (this.forma.k_number == '') {
                        this.$message({
                            message: '请填写课时数量！',
                            type: 'warning'
                        });
                        return;
                    }
				}
				var re = /^0\d{2,3}-?\d{7,8}$/;
				if (this.forma.phone == '') {
					this.$message({
						message: '电话不能为空！',
						type: 'warning'
					});
					return;
				}
				if (this.forma.address == '') {
					this.$message({
						message: '中心地址不能为空！',
						type: 'warning'
					});
					return;
				}
				if (this.forma.resource == 4) {
					if (this.forma.enrolldate == '' || this.forma.enrolldate == undefined) {
						this.$message({
							message: '报名时间不能为空！',
							type: 'warning'
						});
						return;
					}
					if (this.forma.haoke == '') {
						this.forma.haoke = 0
					}
					if (this.forma.a_address == '') {
						this.$message({
							message: '活动地址不能为空！',
							type: 'warning'
						});
						return;
					}
				}
				this.success1 = true
			},
			// 红包配置验证
			nextfn2: function() {
				this.success2 = false;
				var that = this
				var re = /^[0-9]+.?[0-9]*$/;
				if (this.f_share.packet == 1) {
					if (!re.test(that.f_share.hje_z) || that.f_share.hje_z < 10) {
						this.$message({
							message: '金额为整数，最低10元！',
							type: 'warning'
						});
						return;
					}
					if (!re.test(that.f_share.hgs) || that.f_share.hgs < 10) {
						this.$message({
							message: '红包为整数，最低10个！',
							type: 'warning'
						});
						return;
					}
				}
				if (this.f_share.packet == 2) {
					if (!re.test(that.f_share.hje_d) || that.f_share.hje_d < 1) {
						this.$message({
							message: '金额为整数，最低1元！',
							type: 'warning'
						});
						return;
					}
					if (!re.test(that.f_share.hgs) || that.f_share.hgs < 10) {
						this.$message({
							message: '红包为整数，最低10个！',
							type: 'warning'
						});
						return;
					}
				}
				this.success2 = true
			},
			// 积分商城设置验证
			nextfn3: function(i) {
				this.success3 = false;
				var ru = [];
				this.rules = ''
				var gid = '';
				var good = '';
				if (this.f_share.zhek_s) {
					if (this.f_share.zhek_s < 0 || this.f_share.zhek_s > 9.9 || !/^(-?\d+)(\.\d+)?$/.test(this.f_share.zhek_s)) {
						this.$message({
							message: '请设置会员折0~9.9！',
							type: 'warning'
						});
						return;
					}
				}
				for (var i = 0; i < this.$refs.shabian_list.length; i++) {
					if (this.$refs.shabian_list[i].value) {
						if (this.$refs.shabian3_list[i].value == "" || this.$refs.shabian3_list[i].value == undefined) {
							this.$message({
								message: '请选择商品！',
								type: 'warning'
							});
							return;
						}
					}
					if (this.$refs.shabian3_list[i].value) {
						if (this.$refs.shabian_list[i].value == "" || this.$refs.shabian_list[i].value == undefined) {
							this.$message({
								message: '积分设置不能为空！',
								type: 'warning'
							});
							return;
						}
					}
					var good = this.$refs.shabian_list[i].value
					var jf = this.$refs.shabian3_list[i].value
					ru.push({
						gid: jf,
						score: good
					});
				}
				this.rules = ru
				this.success3 = true
			},
			// 积分设置验证
			nextfn4: function() {
				this.success4 = false;
				for (var i = 0; i < this.fenBox.length; i++) {
					if (this.fenBox[i] == 1) {
						if (this.zhek_a == "") {
							this.$message({
								message: '积分填写不能为空！',
								type: 'warning'
							});
							return;
							this.zhek_a = 0
						}
					}
					if (this.fenBox[i] == 2) {
						if (this.zhek_b == "") {
							this.$message({
								message: '积分填写不能为空！',
								type: 'warning'
							});
							return;
							this.zhek_b = 0
						}
					}
					if (this.fenBox[i] == 3) {
						if (this.zhek_c == "") {
							this.$message({
								message: '积分填写不能为空！',
								type: 'warning'
							});
							return;
							this.zhek_c = 0
						}
					}
					if (this.fenBox[i] == 4) {
						if (this.zhek_d == "") {
							this.$message({
								message: '填写不能为空！',
								type: 'warning'
							});
							return;
							this.zhek_d = 0
						}
					}
				}
				if (this.zhek_a == "") {
					this.zhek_a = 0
				}
				if (this.zhek_b == "") {
					this.zhek_b = 0
				}
				if (this.zhek_c == "") {
					this.zhek_c = 0
				}
				if (this.zhek_d == "") {
					this.zhek_d = 0
				}
				this.success4 = true

			},

			// 钱包
			get_qb: function() {
				var that = this
				$.ajax({
					url: "https://hdapi.codeclassroom.org/index/huodong/qianbao",
					type: "post",
					dataType: "json",
					data: {
						token: that.token,
						chose: that.chos,
						time_s: that.qb_date[0],
						time_e: that.qb_date[1],
					},
					success: function(e) {
						// console.log(e)
						that.qb_list = e.data.data.list
						that.qianbao = e.data.data.hd
						that.shiyong = e.data.data.shiyong
						var echarts1 = Number(that.qianbao.red_remaining)
						var echarts2 = Number(that.qianbao.red_total)
						var echarts3 = Number(that.shiyong.whb)
						var echarts4 = Number(that.shiyong.cs)
						var myChart = echarts.init(document.getElementById("e1"));
						var option = {
							tooltip: {
								formatter: "{a} <br/>{c} {b}"
							},
							toolbox: {
								show: false
							},
							legend: {
								show: false
							},
							series: [{
								name: "转速",
								type: "gauge",
								center: ["50%", "85%"],
								radius: "130%",
								min: 0,
								max: 1000,
								startAngle: 180,
								endAngle: 0,
								splitNumber: 7,
								axisLine: {
									// 坐标轴线
									lineStyle: {
										// 属性lineStyle控制线条样式
										width: 8,
										color: [
											[echarts1 / echarts2, "#7C72C1"],
											[1, "#f1f1f1"]
										]
									}
								},
								axisLabel: {
									show: false
								},
								axisTick: {
									// 坐标轴小标记
									show: false
								},
								splitLine: {
									show: false // 分隔线
								},
								pointer: {
									show: false
								},
								title: {
									show: false // x, y，单位px
								},
								detail: {
									offsetCenter: [0, "-25%"],
									formatter: echarts1 + "/" + echarts2 + "元",
									textStyle: {
										fontSize: 14,
										color: "#7C72C1" // 主标题文字颜色
									}
								},
								data: [{
									value: echarts1,
									name: "50%"
								}]
							}]
						};
						myChart.setOption(option);
						window.onresize = myChart.resize;

						var myChart2 = echarts.init(document.getElementById("e2"));
						option = {
							tooltip: {
								formatter: "{a} <br/>{c} {b}"
							},
							toolbox: {
								show: false
							},
							legend: {
								show: false
							},
							series: [{
								name: "转速",
								type: "gauge",
								center: ["50%", "85%"],
								radius: "130%",
								min: 0,
								max: 1000,
								startAngle: 180,
								endAngle: 0,
								splitNumber: 7,
								axisLine: {
									// 坐标轴线
									lineStyle: {
										// 属性lineStyle控制线条样式
										width: 8,
										color: [
											[echarts3 / echarts4, "#7C72C1"],
											[1, "#f1f1f1"]
										]
									}
								},
								axisLabel: {
									show: false
								},
								axisTick: {
									// 坐标轴小标记
									show: false
								},
								splitLine: {
									show: false // 分隔线
								},
								pointer: {
									show: false
								},
								title: {
									show: false // x, y，单位px
								},
								detail: {
									offsetCenter: [0, "-25%"],
									formatter: echarts3 + "/" + echarts4 + "次",
									textStyle: {
										fontSize: 14,
										color: "#7C72C1" // 主标题文字颜色
									}
								},
								data: [{
									value: echarts3,
									name: "50%"
								}]
							}]
						};
						myChart2.setOption(option);
						window.onresize = myChart2.resize;
					}
				});
			},
			// 充值
			quedingc: function() {
				var self = this;
				self.erw = "";
				if (self.chongzhiji == 1) {
					if (!isNaN(self.qian) && Number(self.qian) >= 1) {
						$.ajax({
							url: "https://hdapi.codeclassroom.org/weixinapi/example/a_money_erwm.php",
							type: "post",
							dataType: "json",
							data: {
								centre_id: self.centre_id,
								money: self.qian
							},
							success: function(data) {
								self.erw = "https://hdapi.codeclassroom.org/weixinapi/example/qrcode.php?data=" + data.er;

								var dindan = data.dindan;
								self.myIntval = setInterval(function() {
									load();
								}, 1000);

								function load() {
									$.ajax({
										type: "post",
										url: "https://hdapi.codeclassroom.org/weixinapi/example/a_money_order.php",
										dataType: "json",
										data: {
											out_trade_no: dindan
										},
										success: function(trade_state) {
											if (trade_state == "SUCCESS") {
												document.getElementById("myDiv").innerHTML = "支付成功";
												clearInterval(self.myIntval);
												self.$message({
													message: "恭喜你，支付成功",
													type: "success"
												});
												setTimeout(function() {
													// window.location.href = "zhuanqianb.html";
													// self.jump('weiy');
													location.reload()
												}, 2000);
												// window.location.href="zhuanqianb.html";
											} else if (trade_state == "REFUND") {
												// document.getElementById("myDiv").innerHTML='转入退款';
												clearInterval(self.myIntval);
											} else if (trade_state == "NOTPAY") {
												document.getElementById("myDiv").innerHTML = "请扫码支付";
											} else if (trade_state == "CLOSED") {
												clearInterval(self.myIntval);
											} else if (trade_state == "REVOKED") {
												clearInterval(self.myIntval);
											} else if (trade_state == "USERPAYING") {
												clearInterval(self.myIntval);
											} else if (trade_state == "PAYERROR") {
												clearInterval(self.myIntval);
											}
										}
									});
								}
							}
						});
					} else {
						alert("请输入金额格式为数字!必须是整数");
					}
				} else if (self.chongzhiji == 2) {
					if (!isNaN(self.qian) && Number(self.qian) >= 1) {
						$.ajax({
							url: "https://hdapi.codeclassroom.org/weixinapi/example/a_money_red_erwm.php",
							type: "post",
							dataType: "json",
							data: {
								centre_id: self.centre_id,
								money: self.qian
							},
							success: function(data) {
								// console.log(data);
								self.erw = "https://hdapi.codeclassroom.org/weixinapi/example/qrcode.php?data=" + data.er;
								var dindan = data.dindan;
								self.myIntval = setInterval(function() {
									load();
								}, 1000);

								function load() {
									$.ajax({
										type: "post",
										url: "https://hdapi.codeclassroom.org/weixinapi/example/a_money_red_order.php",
										dataType: "json",
										data: {
											out_trade_no: dindan
										},
										success: function(trade_state) {
											if (trade_state == "SUCCESS") {
												document.getElementById("myDiv").innerHTML = "支付成功";
												clearInterval(self.myIntval);
												self.$message({
													message: "恭喜你，支付成功",
													type: "success"
												});
												setTimeout(function() {
													// window.location.href = "zhuanqianb.html";
													// self.jump('weiy');
													location.reload();
												}, 2000);
											} else if (trade_state == "REFUND") {
												// document.getElementById("myDiv").innerHTML='转入退款';
												clearInterval(self.myIntval);
											} else if (trade_state == "NOTPAY") {
												document.getElementById("myDiv").innerHTML = "请扫码支付";
											} else if (trade_state == "CLOSED") {
												clearInterval(self.myIntval);
											} else if (trade_state == "REVOKED") {
												clearInterval(self.myIntval);
											} else if (trade_state == "USERPAYING") {
												clearInterval(self.myIntval);
											} else if (trade_state == "PAYERROR") {
												clearInterval(self.myIntval);
											}
										}
									});
								}
							}
						});
					} else {
						alert("请输入金额格式为数字!,必须是整数");
					}
				}
			},
		},
		watch: {
			// 实时触发模版样式
			text(val) {
				if (this.dra_idx == 1) {
					this.mb.text1 = this.text
				} else if (this.dra_idx == 2) {
					this.mb.text2 = this.text
				} else if (this.dra_idx == 3) {
					this.mb.text3 = this.text
				}
			},
			ziti() {
				if (this.dra_idx == 1) {
					this.mb.ziti1 = this.ziti
				} else if (this.dra_idx == 2) {
					this.mb.ziti2 = this.ziti
				} else if (this.dra_idx == 3) {
					this.mb.ziti3 = this.ziti
				}
			},
			zihao() {
				if (this.dra_idx == 1) {
					this.mb.zihao1 = this.zihao
				} else if (this.dra_idx == 2) {
					this.mb.zihao2 = this.zihao
				} else if (this.dra_idx == 3) {
					this.mb.zihao3 = this.zihao
				}
			},
			jianju() {
				if (this.dra_idx == 1) {
					this.mb.jianju1 = this.jianju
				} else if (this.dra_idx == 2) {
					this.mb.jianju2 = this.jianju
				} else if (this.dra_idx == 3) {
					this.mb.jianju3 = this.jianju
				}
			},
			color() {
				if (this.dra_idx == 1) {
					this.mb.color1 = this.color
				} else if (this.dra_idx == 2) {
					this.mb.color2 = this.color
				} else if (this.dra_idx == 3) {
					this.mb.color3 = this.color
				}
			},
			// 总统计时间查询				
			tj_date() {
				if (this.tj_date == null) {
					this.tj_date = []
				}
				this.get_ztj()
			},
			qb_date() {
				if (this.qb_date == null) {
					this.qb_date = []
				}
				if (this.qb_date) {
					this.chos = ''
				}
				this.get_qb()
			},
			sou() {
				if (this.sou == '') {
					this.my_page = 1
					this.get_my()
				}

			},
		}
	}
</script>

<style scoped>
	.el-radio+.el-radio {
		margin-left: 10px;
	}

	.el-button:hover {
		border: 1px solid #7C72C1;
		color: #7C72C1;
	}

	.el-dialog__header {
		padding-top: 10px;
	}

	.had {
		padding: 20px 45px;
		height: 70px;
		background: #FFFFFF;
	}

	.had>ul>li {
		padding: 8px 22px;
		float: left;
		list-style: none;
		margin-left: 10px;
		border-radius: 6px;
		font-weight: bold;
		font-size: 15px;
		cursor: pointer;
	}

	.vary {
		background: #7C72C1;
		color: #FFFFFF;
	}

	.violet {
		color: #7C72C1;
	}

	.had>ul>li:nth-child(1) {
		padding: 8px 35px;
	}

	.had>p {
		float: right;
		width: 30%;
		border: 1px solid #999;
		border-radius: 4px;
	}

	.had>p>img {
		width: 20px;
		height: 20px;
		margin-left: 10px;
		vertical-align: middle
	}

	.had>p>input {
		padding-left: 0.1rem;
		width: 80%;
		height: 30px;
		outline: none;
		vertical-align: middle;
		border: 0;
	}

	.con {
		width: 100%;
		padding: 0px 25px;
	}

	.con>ul {
		height: 40px;
	}

	.con>ul>li {
		padding: 8px 22px;
		float: left;
		list-style: none;
		margin-left: 10px;
		border-radius: 6px;
		font-weight: bold;
		font-size: 15px;
		cursor: pointer;
	}

	.box {
		width: 220px;
		height: 300px;
		background: #FFFFFF;
		float: left;
		margin-left: 1.5%;
		margin-top: 20px;
		cursor: pointer;
		position: relative;
	}

	.box:hover {
		box-shadow: 1px 1px 5px #7C72C1;
	}

	.found1 {
		width: 220px;
		height: 250px;
		line-height: 250px;
		text-align: center;
		font-size: 20px;
		font-weight: bold;
		border: 1px solid #E0E0E0;
	}

	.found2 {
		width: 220px;
		height: 50px;
		line-height: 50px;
		text-align: center;
		font-size: 16px;
		font-weight: bold;
	}

	.box1_1 {
		width: 100%;
		height: 256px;
		position: relative;
		overflow: hidden;
	}

	.blur {
		-webkit-filter: blur(3px);
		-moz-filter: blur(3px);
		-ms-filter: blur(3px);
		filter: blur(3px);
	}

	.box1_1_2 {
		width: 70%;
		height: 256px;
		margin: auto;
		overflow: hidden;
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		z-index: 9;
	}

	.box1_1_2 img {
		width: 100%;
		height: 100%;
	}

	.box1_1_tag {
		width: 70px;
		height: 25px;
		line-height: 25px;
		background: #76838F;
		color: #FFFFFF;
		text-align: center;
		position: absolute;
		top: 0;
		left: 0;
		z-index: 99;
	}

	.box1_2 {
		width: 83%;
		height: 44px;
		line-height: 44px;
		margin: auto;
	}

	.box1_2 p:nth-child(1),
	.box2_1_2 p:nth-child(1) {
		width: 70%;
		float: left;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.box1_2 p:nth-child(2),
	.box2_1_2 p:nth-child(2) {
		width: 30%;
		float: right;
	}

	.box2_1 {
		width: 100%;
		height: 250px;
		position: relative;
		border-bottom: 1px solid #F2F2F2;
	}

	.box2_1_img {
		width: 150px;
		height: 150px;
		position: absolute;
		top: 20px;
		left: 35px;
	}

	.box2_1_1 {
		width: 100%;
		height: 50px;
		line-height: 50px;
		position: absolute;
		bottom: 30px;
		text-align: center;
	}

	.box2_1_2 {
		width: 200px;
		height: 30px;
		position: absolute;
		left: 25px;
		bottom: 0px;
	}

	.box2_2 {
		width: 100%;
		height: 50px;
		line-height: 50px;
		text-align: center;
	}

	.stencil1 {
		width: 35%;
		height: 500px;
		float: left;
		border-right: 1px solid #999999;
	}

	.stencil1_img {
		width: 85%;
		height: 480px;
		background: #999999;
		float: left;
		position: relative;
		background-size: 100% 100%;
		-webkit-tab-highlight-color: rgba(0, 0, 0, 0);
	}

	.textareas {
		min-width: 80px;
		min-height: 80px;
		max-width: 200px;
		max-height: 200px;
		overflow: auto;
		word-break: break-all;
		overflow: auto;
		word-break: break-all;
		background: transparent;
		border-style: none;
		resize: none
	}

	#drags,
	#drags2,
	#drags3 {
		min-width: 80px;
		min-height: 80px;
		max-width: 200px;
		max-height: 200px;
		border: 1px dashed #000;
		position: absolute;
		z-index: 999;
		background-color: rgba(0, 0, 0, 0.2);
	}

	#drags {
		top: 20px;
		left: 10px;
	}

	#drags2 {
		top: 150px;
		left: 10px;
	}

	#drags3 {
		top: 350px;
		left: 10px;
	}

	.beijing {
		width: 100%;
		height: 100%;
		position: absolute;
	}

	.up_but {
		width: 150px;
		height: 35px;
		line-height: 35px;
		background: #7C72C1;
		border-radius: 3px;
		text-align: center;
		position: absolute;
		top: 180px;
		left: 55px;
		color: #fff;
	}

	.stencil1_switch {
		width: 15%;
		height: 400px;
		line-height: 420px;
		float: left;
		text-align: center;
	}

	.stencil1_switch>div {
		cursor: pointer;
		width: 100%;
		height: 35px;
		text-align: center;
		color: #7C72C1;
	}

	.stencil1_switch>div>img {
		width: 20px;
		height: 12px;
	}

	.stencil1_tips {
		width: 100%;
		height: 20px;
		line-height: 20px;
		float: left;
		color: #7C72C1;
	}

	.stencil2 {
		width: 65%;
		height: 500px;
		float: left;
		padding: 0 23px;
	}

	.shez {
		padding-bottom: 13px;
	}

	.shez span {
		padding: 5px 15px;
		border-radius: 3px;
		cursor: pointer;
		font-weight: bold;
	}

	.ss {
		color: #FFFFFF;
		background: #7C72C1;
	}

	.stencil2p {
		font-size: 18px;
		padding: 10px 0;
		font-weight: bold;
	}

	.yangshi {
		padding: 8px 10px;
		border-radius: 3px;
		color: #666;
		font-weight: bold;
		cursor: pointer;
		border: 1px solid #dcdfe6;
	}

	.y {
		color: #fff;
		background: #7C72C1;
		border: 1px solid #7C72C1;
	}

	.shareimg {
		width: 60px;
		height: 55px;
		background: #D1D1D1;
		text-align: center;
		line-height: 55px;
		border-radius: 3px;
		cursor: pointer;
		position: relative;
		float: left;
	}

	.shareimg img {
		top: 0;
		left: 0;
	}

	.icon {
		font-size: 20px;
		position: absolute;
		z-index: 10;
		right: -5px;
		top: -5px;
		color: red;
	}

	.labelys {
		color: #666;
		font-weight: 500;
		font-size: 12px;
		width: 260px;
	}

	.shang_box {
		width: 150px;
		height: 28px;
		line-height: 28px;
		vertical-align: middle;
		text-align: center;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.fenxs .el-form-item--mini.el-form-item,
	.fenxs .el-form-item--small.el-form-item {
		margin-bottom: 8px
	}

	.tj_date {
		height: 50px;
	}

	.bg {
		width: 88%;
		margin: auto;
		background: #fff;
		padding: 20px 10px;
		border-radius: 10px;
		box-shadow: 0 2px 20px #cfcfcf;
	}

	.qbao {
		width: 90%;
		margin: auto;
		background: #E8E5FF;
		padding: 20px 45px;
		border-radius: 10px;
		box-shadow: 0 2px 20px #cfcfcf;
	}

	.chong {
		float: right;
		padding: 5px 28px;
		border-radius: 3px;
		background: #7C72C1;
		color: #FFFFFF;
	}

	.choicetime li {
		display: inline-block;
		padding: 5px 14px;
		border-radius: 20px;
		text-align: center;
		cursor: pointer;
		font-size: 15px;
		font-weight: bold;
	}

	.choicetime li.activea1 {
		color: #7c72c1;
	}

	table {
		width: 100%;
		border-radius: 10px 10px 0 0;
		box-shadow: 0 2px 20px #cfcfcf;
	}

	table thead tr {
		border-radius: 10px 0 0 0;
	}

	table thead tr th {
		padding: 12px 0;
		text-align: center;
		font-size: 14px;
	}

	table thead tr th:nth-child(1) {
		border-radius: 10px 0 0 0;
	}

	table thead tr th:nth-last-child(1) {
		border-radius: 0 10px 0 0;
	}

	table tbody tr td {
		padding: 12px 0;
		text-align: center;
	}

	table tbody tr:hover {
		box-shadow: 0 2px 20px #E8E5FF;
	}

	.count {
		background: #E8E5FF;
		padding: 10px 18px;
		border-radius: 10px;
		box-shadow: 0 2px 20px #cfcfcf;
	}

	.c1 {
		padding-bottom: 15px;
		font-size: 20px;
		font-weight: bold;
	}

	.c1 span {
		padding-left: 30px;
	}

	.c3_box {
		width: 50%;
		float: left;
		text-align: center;
	}

	.dian {
		display: inline-block;
		width: 12px;
		height: 12px;
		border-radius: 50px;
	}

	.zhuanBtn {
		padding: 6px 25px;
		float: right;
		background: #7C72C1;
		color: #FFFFFF;
	}

	.erweimaBox {
		width: 100%;
		height: 350px;
		background: url("/static/img/zhuanqb1.png") no-repeat center;
		background-size: 259px auto;
		padding-top: 120px;
	}

	.erweima {
		width: 185px;
		height: 185px;
		margin: 0 auto 0px;
		overflow: hidden;
		background: #fff;
	}

	.erweima img {
		width: 100%;
		height: 100%;
	}

	.jineinp {
		width: 120px;
		height: 30px;
		border-radius: 15px;
		background: #e8e5ff;
		border: none;
		padding-left: 20px;
		padding-right: 20px;
		outline: none;
	}

	.tub {
		width: 152px;
		height: 100px;
		margin: 0 auto;
	}

	.frame {
		width: 100%;
		text-align: center;
		margin: 30px auto;
		font-size: 18px;
		font-weight: bold;
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
	#dragsa,#drags2a,#drags3a{
		position: absolute;
	}
</style>
