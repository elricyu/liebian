<template>
	<div>
		<div class="had"></div>
		<el-row :gutter="20" style="width:90%;margin: auto;">
			<el-col :span="8">
				<div class="count">
					<div class="c1">
						<span>总浏览量：</span>
						<span style="color:#7C72C1">{{zong.browse}}</span>
					</div>
					<div class="c2" style="padding-bottom: 40px;"></div>
					<div class="c3">
						<div class="c3_box">
							<p>
								<span class="dian" style="background: #E8E5FF;"></span>
								<span style="font-size: 15px;">参与人数</span>
							</p>
							<p style="text-indent:1.5rem;font-size: 20px;font-weight: bold;">{{zong.zrs}}</p>
						</div>
						<div class="c3_box">
							<p>
								<span class="dian" style="background: #E8E5FF;"></span>
								<span style="font-size: 15px;">领取红包人数</span>
							</p>
							<p style="text-indent:1.5rem;font-size: 20px;font-weight: bold;">{{zong.red}}</p>
						</div>
						<div style="clear: both;"></div>
					</div>
					<div class="c2" style="padding-bottom: 50px;"></div>
				</div>
			</el-col>
			<el-col :span="8">
				<div class="count">
					<div class="c1">
						<span>红包剩余量：</span>
						<span style="color:#FF797C">{{zong.red_sheng}}</span>
					</div>
					<div class="c2" style="padding-bottom: 45px;"></div>
					<div class="c3">
						<div class="c3_box e">
							<div class="tub" id="e2" style="margin-left: 5px"></div>
						</div>
						<div class="c3_box">
							<p style="margin-top: 35px;">
								<span class="dian" style="background: #FF797C;"></span>
								<span style="font-size: 15px;">余额：{{zong.red_balance}}元</span>
							</p>
							<p>
								<span class="dian"></span>
								<span style="font-size: 15px;">总额：{{zong.red_total}}元</span>
							</p>
						</div>
						<div style="clear: both;"></div>
					</div>
				</div>
			</el-col>
			<el-col :span="8">
				<div class="count">
					<div class="c1">
						<span>用户领取积分：</span>
						<span style="color:#00CCE4">{{zong.zjf}}</span>
					</div>
					<div class="c2">
						<span>用户使用积分：</span>
						<span style="color:#00CCE4">{{zong.yhl}}</span>
					</div>
					<div class="c3">
						<div class="c3_box e">
							<div class="tub" id="e1" style="margin-left: 5px"></div>
						</div>
						<div class="c3_box">
							<p style="margin-top: 35px;">
								<span class="dian" style="background: #00CCE4;"></span>
								<span style="font-size: 15px;">使用积分：{{zong.yhl}}</span>
							</p>
							<p>
								<span class="dian"></span>
								<span style="font-size: 15px;">领取积分：{{zong.zjf}}</span>
							</p>
						</div>
						<div style="clear: both;"></div>
					</div>
				</div>
			</el-col>
		</el-row>
		<div class="tables">
			<table style="margin-bottom: 0;">
				<thead style="background:#e8e5ff ">
					<tr>
						<th>活动名称</th>
						<th>活动时间</th>
						<th>耗课时</th>
						<th>耗课额</th>
						<th>预约人数</th>
						<th>实到人数</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>{{zong.jc_name}}</td>
						<td>{{zong.hd_start_time}} 至 {{zong.hd_end_time}}</td>
						<td>{{zong.xiaohao}}</td>
						<td>{{zong.s_price}}</td>
						<td>{{zong.yuy_id}}</td>
						<td>{{zong.qian_id}}</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="list">
			<ul>
				<li class="li" v-bind:class="{ative:(status==1)}" v-on:click="tab_but(1)">全部</li>
				<li class="li" v-bind:class="{ative:(status==2)}" v-on:click="tab_but(2)">会员</li>
				<li class="li" v-bind:class="{ative:(status==3)}" v-on:click="tab_but(3)">潜客</li>
				<li class="li" v-bind:class="{ative:(status==4)}" v-on:click="tab_but(4)" v-if="zong.hd_type==3">已报名</li>
				<li class="li" v-bind:class="{ative:(status==5)}" v-on:click="jump()"  style="float: right">返回</li>
			</ul>
		</div>
		<div class="tables">
			<table style="margin-bottom: 0;">
				<thead style="background:#e8e5ff ">
					<tr>
						<th>序号</th>
						<th>微信昵称</th>
						<th>姓名</th>
						<th>电话</th>
						<th>付款时间</th>
						<th>付款金额</th>
						<th>领取红包金额</th>
						<th>积分总数</th>
						<th>分享总数</th>
						<th>浏览总数</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(ta,index) in tabledata">
						<td>{{index+1}}</td>
						<td @click="goavtive(ta.id)" style="color: #337ab7;cursor:pointer">{{ta.user_name}}</td>
						<td>{{ta.real_name}}</td>
						<td>{{ta.phone}}</td>
						<td>{{ta.fktime}}</td>
						<td>{{ta.price}}</td>
						<td>{{ta.money}}</td>
						<td>{{ta.zjf}}</td>
						<td>{{ta.share_num}}</td>
						<td>{{ta.llzs}}</td>
					</tr>
				</tbody>
			</table>

			<p style="padding: 0 10px 0;margin-top: 25px;color: #999;font-size: 14px">共有<span v-cloak>{{datatotal}}</span>条数据，当前第<span
				 v-cloak>{{page}}</span>页共<span v-cloak>{{pagetotal}}</span>页</p>

			<div style="background: #fff;margin-top: 20px;padding-bottom:10px;display: flex;display: -webkit-flex;justify-content:center;align-items:center">
				<el-pagination background :page-size="pageone" @current-change="tan" layout="prev, pager, next" :total="datatotal">
				</el-pagination>
			</div>

		</div>

	</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				token: "",
				zong: '',
				id: '',
				hdmc: '',
				tabledata: '', //活动参与人数列表
				status: 1, //切换状态值
				page: 1, //分页
				datatotal: 0,
				pageone: 0,
				pagetotal: 0,
			}
		},
		mounted: function() {
			var that = this
			that.id = this.$route.query.id;
			that.token = that.$cookies.get("token");
			that.get()
			$.ajax({
				url: "https://hdapi.codeclassroom.org/index/huodong/deta",
				type: "post",
				dataType: 'json',
				data: {
					hd_id: that.id,
					token: that.token
				},
				success: function(data) {
					// console.log(data)
					that.zong = data
					var echarts1 = that.zong.red_balance;
					var echarts2 = that.zong.red_total;
					var echarts3 = that.zong.yhl;
					var echarts4 = that.zong.zjf;
					var myChart2 = echarts.init(document.getElementById('e2'));
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
								name: '转速',
								type: 'gauge',
								center: ['50%', '85%'],
								radius: '130%',
								min: 0,
								max: 1000,
								startAngle: 180,
								endAngle: 0,
								splitNumber: 7,
								axisLine: { // 坐标轴线
									lineStyle: { // 属性lineStyle控制线条样式
										width: 8,
										color: [
											[echarts1 / echarts2, '#ff797c'],
											[1, '#f1f1f1']
										]
									}
								},
								axisLabel: {
									show: false
								},
								axisTick: { // 坐标轴小标记
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
									offsetCenter: [0, '-25%'],
									formatter: echarts1 + '/' + echarts2,
									textStyle: {
										fontSize: 14,
										color: '#ff797c' // 主标题文字颜色
									}
								},
								data: [{
									value: echarts1,
									name: '50%'
								}]
							},


						]
					};
					myChart2.setOption(option);
					window.onresize = myChart2.resize;

					var myChart = echarts.init(document.getElementById('e1'));
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
								name: '转速',
								type: 'gauge',
								center: ['50%', '70%'],
								radius: '140%',
								min: 0,
								max: 1000,
								startAngle: 180,
								endAngle: 0,
								splitNumber: 7,
								axisLine: { // 坐标轴线
									lineStyle: { // 属性lineStyle控制线条样式
										width: 8,
										color: [
											[echarts3 / echarts4, '#00cce4'],
											[1, '#f1f1f1']
										]
									}
								},
								axisLabel: {
									show: false
								},
								axisTick: { // 坐标轴小标记
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
									offsetCenter: [0, '-25%'],
									formatter: echarts3 + '/' + echarts4,
									textStyle: {
										fontSize: 14,
										color: '#00cce4' // 主标题文字颜色
									}
								},
								data: [{
									value: echarts3,
									name: '50%'
								}]
							},


						]
					};
					myChart.setOption(option);
					window.onresize = myChart.resize;


				}
			});
		},
		methods: {
            jump: function() {
                let that = this;
                that.$router.push('xindex')
            },
			tab_but: function(i) {
				this.status = i
				this.page = 1
				this.get()
			},
			tan: function(val) {
				this.page = val
				this.get()
			},
			goavtive: function(i) {
				this.$router.push({
					name: 'xwei',
					query: {
						id: i
					}
				})
			},
			// 列表
			get: function() {
				var that = this
				$.ajax({
					url: "https://hdapi.codeclassroom.org/index/huodong/gethd",
					type: "post",
					dataType: 'json',
					data: {
						hd_id: that.id,
						p: that.page,
						status: that.status,
						token: that.token
					},
					success: function(data) {
						// console.log(data)
						if (data) {
							that.tabledata = data.data
							that.datatotal = Number(data.map.num)
							that.pageone = Number(data.map.pageone)
							that.pagetotal = Number(data.map.pagetotal)
							that.page = Number(data.map.page)
						} else {
							that.tabledata = ''
							that.datatotal = 0
							that.pageone = 1
							that.pagetotal = 0
							that.page = 1
						}
					}
				});
			},
		},
	}
</script>

<style scoped>
	.tub {
		width: 152px;
		height: 100px;
		margin: 0 auto;
	}

	.had {
		padding: 10px 20px;
		font-size: 16px;
		font-weight: bold;
	}

	.count {
		background: #fff;
		padding: 10px 18px;
		border-radius: 10px;
		box-shadow: 0 2px 20px #cfcfcf;
	}

	.c1,
	.c2 {
		padding-bottom: 15px;
		font-size: 20px;
		font-weight: bold;
	}

	.c1 span,
	.c2 span {
		padding-left: 45px;
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

	.list {
		width: 90%;
		margin: auto;
	}

	.li {
		list-style: none;
		display: inline-block;
		padding: 6px 25px;
		border-radius: 3px;
		margin-left: 5px;
		background: #E8E5FF;
		color: #7C72C1;
		cursor: pointer;
	}

	.ative {
		list-style: none;
		display: inline-block;
		padding: 6px 25px;
		border-radius: 3px;
		margin-left: 5px;
		background: #E8E5FF;
		background: #7C72C1;
		color: #FFFFFF;
	}

	.tables {
		width: 90%;
		margin: auto;
		padding: 20px 0;
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
