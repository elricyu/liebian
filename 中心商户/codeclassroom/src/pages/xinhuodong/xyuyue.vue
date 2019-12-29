<template>
	<div style="width: 95%;margin:30px auto;">
		<div class="had">
			<p>
				<img src="../../../static/img/genjin/search.png" />
				<input type="text" placeholder="输入关键词" v-model="search" @input="aa"  @keyup.enter="get_s" />
			</p>
			<div class="sou" v-if="shows">
				<ul v-if="search">
					<li v-for="(hui,index) in list" @click="confirm(hui.baobao_name)">{{hui.baobao_name}}</li>
					<li v-if="list==''" style="text-align: center;">暂无数据</li>
				</ul>
			</div>
		</div>
		<div class="title">会员预约</div>
		<div class="tables">
			<table style="margin-bottom: 0;">
				<thead style="background:#e8e5ff ">
					<tr>
						<th>姓名</th>
						<th>月龄</th>
						<th>电话</th>
						<th>活动耗课时</th>
						<th width="15%">状态</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(hui,index) in hui_list">
						<td>{{hui.baobao_name}}</td>
						<td>{{hui.yueling}}</td>
						<td>
							<span>{{hui.phone1}}</span>
							<span v-if="hui.phone1==null">{{hui.phone2}}</span>
						</td>
						<td>{{hui.xiaohao}}</td>
						<td>
							<button class="but1" @click="but(hui.user_id,hui.baobao_name,hui.jc_name)">预约</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="title">已预约( {{yu_idx}} 人)</div>
		<div class="tables">
			<table style="margin-bottom: 0;">
				<thead style="background:#e8e5ff ">
					<tr>
						<th>序号</th>
						<th>状态</th>
						<th>姓名</th>
						<th>月龄</th>
						<th>电话</th>
						<th>活动耗课时</th>
						<th width="15%">状态</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(yu,index) in yuyue_list">
						<td>{{index+1}}</td>
						<td>
							<span v-if="yu.vip==0">潜客</span>
							<span v-if="yu.vip==1">会员</span>
							<span v-if="yu.vip==2">vip</span>
						</td>
						<td>{{yu.baobao_name}}</td>
						<td>{{yu.yueling}}</td>
						<td>
							<span>{{yu.phone1}}</span>
							<span v-if="yu.phone1==null">{{yu.phone2}}</span></td>
						<td>{{yu.xiaohao}}</td>
						<td>
							<button class="bu2" @click="but2(yu.user_id,yu.baobao_name,yu.jc_name)" @mouseover="change1(index)" @mouseout="change2()">
								<span v-show="seen&&index==current">取消预约</span>
								<span v-show="!seen&&index!==current">已预约</span>
								<span v-show="seen&&index!==current">已预约</span>
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div style="width: 95%;margin:20px auto;">
			<p>共有{{count}}条记录 当前{{page}}页/共{{pagetotal}}页</p>
			<div class="table-bottom" style="margin-bottom: 10px;text-align: center;">
				<div>
					<el-pagination @current-change="fanye" :current-page="page" :page-count="pagetotal" :page-size="pageone"
					 background layout="prev, pager, next">
					</el-pagination>
				</div>
			</div>
		</div>

		<el-dialog title="" :visible.sync="dialog" width="40%" center>
			<div class="frame">
				是否为 {{bao_name}} 预约 {{jc_name}}？
			</div>
			<span slot="footer" class="dialog-footer">
				<el-button style="background:#7C72C1;border-color:#7C72C1" @click="get_sure()" type="primary" size="small">确定</el-button>
				<span style="padding: 0 80px;"></span>
				<el-button style="color:#7C72C1;border-color:#7C72C1" @click="dialog=false" size="small">取消</el-button>
			</span>
		</el-dialog>
		<el-dialog title="" :visible.sync="dialog2" width="40%" center>
			<div class="frame">
				是否为 {{bao_name}} 取消 {{jc_name}} 活动预约？
			</div>
			<span slot="footer" class="dialog-footer">
				<el-button style="background:#7C72C1;border-color:#7C72C1" @click="get_del()" type="primary" size="small">确定</el-button>
				<span style="padding: 0 80px;"></span>
				<el-button style="color:#7C72C1;border-color:#7C72C1" @click="dialog2=false" size="small">取消</el-button>
			</span>
		</el-dialog>

	</div>
</template>

<script>
	export default {
		data() {
			return {
				token: "",
				shows:false,
				dialog: false,
				dialog2: false,
				hd_id: '', //
				user_id: '', //
				bao_name: '', //宝宝姓名
				jc_name: '', //活动名称
				search: '', //搜索
				page: 1, //分页
				count: 0,
				pageone:0,
				pagetotal:0,
				list: '', //搜索列表
				hui_list: '', //会员预约
				yuyue_list: '', //已预约
				yu_idx: '',
				seen: false,
				current: -1,

			}
		},
		mounted: function() {
			var that = this
			that.hd_id = this.$route.query.id;
			that.token = that.$cookies.get("token");
			that.get_vip()
			that.get_list()

		},
		methods: {
			// 搜索预约
			get_s: function() {
				var that = this
				$.ajax({
					url: "https://hdapi.codeclassroom.org/index/huodong/yylist ",
					type: "post",
					dataType: 'json',
					data: {
						hd_id: that.hd_id,
						set: that.search,
						token: that.token
					},
					success: function(data) {
						if (data.status == 1) {
							that.list = data.w;
						} else {
							that.list = '';
						}
					}
				});
			},
			aa:function(){
				this.shows=true
			},
			confirm: function(name) {
				this.search = name
				this.shows=false
				this.get_vip()
			},
			// 会员列表
			get_vip: function() {
				var that = this
				$.ajax({
					url: "https://hdapi.codeclassroom.org/index/huodong/yylist ",
					type: "post",
					dataType: 'json',
					data: {
						hd_id: that.hd_id,
						set: that.search,
						token: that.token
					},
					success: function(data) {
						if (data.status == 1) {
							that.hui_list = data.w;
							that.get_list()
							// that.search = ""
						} else {
							that.hui_list = '';
						}
					}
				});

			},
			//
			fanye: function(e) {
				this.page = e
				this.get_list()
			},
			// 预约列表
			get_list: function() {
				var that = this
				$.ajax({
					url: "https://hdapi.codeclassroom.org/index/huodong/yyylist",
					type: "post",
					dataType: 'json',
					data: {
						hd_id: that.hd_id,
						p: that.page,
						token: that.token
					},
					success: function(data) {
						if (data.status == 1) {
							that.yuyue_list = data.y
							that.yu_idx = data.y.length
							that.count = Number(data.map.count)
							that.pageone = Number(data.map.pageone)
							that.pagetotal = Number(data.map.pagetotal)
							that.page = Number(data.map.page)
						} else {
							that.yuyue_list = ''
							that.yu_idx = 0
							that.count = 0
							that.pageone = 1
							that.pagetotal = 0
							that.page = 1
						}
					}
				});

			},
			// 点击预约
			but: function(id, name, jc_name) {
				this.dialog = true
				this.user_id = id
				this.bao_name = name
				this.jc_name = jc_name
			},
			// 取消预约
			but2: function(id, name, jc_name) {
				this.dialog2 = true
				this.user_id = id
				this.bao_name = name
				this.jc_name = jc_name
			},
			//确定预约
			get_sure: function() {
				var that = this
				$.ajax({
					url: "https://hdapi.codeclassroom.org/index/huodong/yuyue",
					type: "post",
					dataType: 'json',
					data: {
						token: that.token,
						hd_id: that.hd_id,
						user_id: that.user_id,
					},
					success: function(data) {
						console.log(data)
						if (data.status == 1) {
							that.$message({
								message: '预约成功！',
								type: 'success'
							});
							that.dialog = false
							that.hui_list = '';
							that.get_list()
						} else {
							that.$message.error(data.msg);
							that.hui_list = '';
						}
					}
				});
			},
			// 取消预约
			get_del: function() {
				var that = this
				$.ajax({
					url: "https://hdapi.codeclassroom.org/index/huodong/quxiaoyy",
					type: "post",
					dataType: 'json',
					data: {
						token: that.token,
						hd_id: that.hd_id,
						user_id: that.user_id,
					},
					success: function(data) {
						console.log(data)
						if (data.status == 1) {
							that.$message({
								message: '取消成功！',
								type: 'success'
							});
							that.dialog2 = false
							that.get_list()
							that.hui_list = '';
						}
					}
				});
			},
			change1: function(i) {
				this.seen = true;
				this.current = i;
			},
			change2: function() {
				this.seen = false;
				this.current = null;
			},
		},
		watch: {
			// 实时触发模版样式
			search(val) {
				this.get_s()
			},
		},
	}
</script>

<style scoped>
	.el-button--small,
	.el-button--small.is-round {
		padding: 9px 36px;
	}

	.frame {
		width: 100%;
		text-align: center;
		margin: 30px auto;
		font-size: 18px;
		font-weight: bold;
	}

	.had {
		position: relative;
	}

	.had>p {
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

	.sou {
		width: 30%;
		max-height: 200px;
		background: #FFFFFF;
		position: absolute;
		overflow: hidden;
		overflow-y: auto;
		cursor: pointer;
	}

	.sou li {
		padding: 6px;
	}

	.sou li:hover {
		background: rgb(232, 229, 255);
	}

	.title {
		width: 100%;
		height: 56px;
		line-height: 56px;
		font-size: 16px;
		font-weight: bold;
	}

	button {
		border: 0;
		border-radius: 30px;
		outline: none;
	}

	.but1 {
		width: 90px;
		height: 25px;
		background: #E8E5FF;
		color: #7C72C1;
		font-weight: bold;
	}

	.bu2 {
		width: 100px;
		height: 25px;
		background: #E1E1E1;
		color: #999;
		font-weight: bold;
	}

	.bu2:hover {
		width: 100px;
		height: 25px;
		background: #FFFFFF;
		color: #7C72C1;
		border: 1px solid #7C72C1;
		font-weight: bold;
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
		padding: 15px 0;
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
		padding: 15px 0;
		text-align: center;
	}

	table tbody tr:hover {
		box-shadow: 0 2px 20px #E8E5FF;
	}
</style>
