<template>
   <div style="background:#ededed ;">
		<!-- <div style='background:#e8e5ff'>
			<a href="./#/weiy">
				<img src="/static/img/top.png" style='width:800px' alt="">
			</a>
		</div> -->
    <div id="app" class="zhuti" style='padding-top:0'>
        <el-row :gutter="30">
            <div style="padding: 20px">
                <div  style="background: #fff;padding: 10px 0 20px 20px;border-radius: 10px;height: 50px;" class="fix">
                    <el-col :span="24">
                        <div class="title_t" style="line-height:40px;font-size: 18px;">
                            <a href="javascript:;" class="zhuIco2" style="font-size: 26px;color: #7c72c1">
                                <svg class="icon" aria-hidden="true" >
                                    <use xlink:href="#icon-gerenxinxi"></use>
                                </svg>
                            </a>
                            <span v-cloak>{{userinfo.user_name}}</span>个人信息</div>
                    </el-col>

                    <el-col :span="7" :offset="1">
                        <div class="titlez" style="padding-top: 14px;padding-bottom: 10px;padding-left: 5px">
                            <el-row>
                                <el-col :span="12" class="text-center">
                                    <div class="touxiang" >
                                        <img :src="userinfo.head_image" class="touxiangp" alt="">
                                        <p class="toustutas" style="background: rgba(0,0,0,0.3)">{{userinfo.baoming}}</p>
                                    </div>
                                </el-col>
                                <el-col :span="12">
                                    <p style="color: #666;font-size: 12px;line-height: 20px">
                                        昵称：<span v-cloak>{{userinfo.user_name}}</span>
                                    </p>
                                    <p style="color: #666;font-size: 12px;line-height: 20px">
                                        姓名：<span v-cloak>{{userinfo.real_name}}</span>
                                    </p>
                                    <p style="color: #666;font-size: 12px;line-height: 20px">
                                        电话：<span v-cloak>{{userinfo.phone}}</span>
                                    </p>
                                    <p style="color: #666;font-size: 12px;line-height: 20px">
                                        来源：<span v-cloak>{{userinfo.hd_title}}</span>
                                    </p>
                                    <p style="color: #666;font-size: 12px;line-height: 20px" class="hang2">
                                        地址：<span v-cloak>{{userinfo.address}}</span>
                                    </p>
                                </el-col>
                            </el-row>
                        </div>
                    </el-col>

                    <el-col :span="7">
                        <div class="titlez" style="padding: 10px 0;">
                            <el-row :gutter="5">
                                <el-col :span="13">
                                    <P class="title_t" style="padding-bottom: 10px;font-size: 14px;padding-left: 20px">
                                        总人数:
                                        <span style="font-size: 16px;color: #7c72c1 "><span v-cloak>{{userinfo.share_total}}</span>/<span v-cloak>{{userinfo.people_total}}</span></span>
                                    </P>
                                    <div class="tub" id="e2"></div>
                                </el-col>
                                <el-col :span="11" class="fonx" style="padding-top: 100px" >
                                    <p style="margin-bottom: 5px;padding-left: 24px"> <span class="land" style="background: #7c72c1" ></span>  人数：<span v-cloak>{{userinfo.share_total}}</span>人</p>
                                    <p style="padding-left: 22px">总人数：<span v-cloak>{{userinfo.people_total}}</span>人</p>
                                </el-col>

                            </el-row>
                        </div>
                    </el-col>

                    <el-col :span="7" >
                        <div class="titlez" style="padding: 10px 0;">
                            <el-row :gutter="5">
                                <el-col :span="13">
                                    <P class="title_t" style="padding-bottom: 10px;font-size: 14px;padding-left: 20px">
                                        红包占比:
                                        <span style="font-size: 16px;color: #ff797c "><span v-cloak>{{userinfo.share_red_total}}</span>/<span v-cloak>{{userinfo.red_total}}</span></span>
                                    </P>
                                    <div class="tub" id="e1"></div>
                                </el-col>
                                <el-col :span="11" class="fonx" style="padding-top: 100px" >
                                    <p style="margin-bottom: 5px;padding-left: 15px"> <span class="land" style="background: #ff797c" ></span>  红包：<span v-cloak>{{userinfo.share_red_total}}</span>元</p>
                                    <p style="padding-left: 15px">总红包：<span v-cloak>{{userinfo.red_total}}</span>元</p>
                                </el-col>

                            </el-row>
                        </div>
                    </el-col>
                </div>
            </div>
        </el-row>

        <el-row :gutter="10" style="padding-top: 10px;">
            <el-col :span="24" >

                <div style="background: #fff">
                    <table class="table table-bordered table-hover" style="margin-bottom: 0;" >
                        <thead style="background:#e8e5ff ">
                            <tr >
                                <th>序号</th>
                                <th>微信昵称</th>
                                <th>姓名</th>
                                <th>电话</th>
                                <th>领取红包金额</th>
                                <th>积分总数</th>
                                <th>分享总数</th>
                                <th>浏览总数</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(ta,index) in tabledata">
                                <td v-cloak >{{index+1}}</td>
                                <td v-cloak >{{ta.user_name ? ta.user_name : '--'}}</td>
                                <td v-cloak >{{ta.real_name ? ta.real_name : '--'}}</td>
                                <td v-cloak >{{ta.phone ? ta.phone : '--'}}</td>
                                <td v-cloak >{{ta.money ? ta.money : '--'}}</td>
                                <td v-cloak >{{ta.zjf}}</td>
                                <td v-cloak >{{ta.share_num}}</td>
                                <td v-cloak >{{ta.browse_num ? ta.browse_num : '--'}}</td>
                            </tr>
                        </tbody>
                    </table>

                    <p style="padding: 0 10px 0;margin-top: 25px;color: #999;font-size: 14px">共有<span v-cloak>{{map.counttotal}}</span>条数据，当前第<span v-cloak>{{map.page}}</span>页共<span v-cloak>{{map.pagetotal}}</span>页</p>

                    <div style="background: #fff;margin-top: 20px;padding-bottom:10px;display: flex;display: -webkit-flex;justify-content:center;align-items:center">
                        <el-pagination
                                background
                                :page-size="pageone"
                                @current-change="tan"
                                layout="prev, pager, next"
                                :total="counttotal">
                        </el-pagination>
                    </div>
                </div>

            </el-col>

            <!--<el-col :span="24">-->
            <!--<div style="background: #fff">-->
            <!--<el-pagination-->
            <!--background-->
            <!--@current-change="tan"-->
            <!--layout="prev, pager, next"-->
            <!--:total="1000">-->
            <!--</el-pagination>-->
            <!--</div>-->
            <!--</el-col>-->
        </el-row>
    </div>

    <div  style='height:32px'></div>
    <!-- <div style='background:#e8e5ff;position:fixed;left:0;bottom:0;width:100%' class='text-center'>
        <img src="/static/img/dibu.png" style="width:900px" alt="">
    </div> -->
</div>
</template>

<script>
    export default{
        data(){
            return{
                userinfo:'',
                pageone:0,
                map:'',
                tabledata:'',
                counttotal:0,
                iid:'',
                token:'',
            } 
        },
        methods:{
            tan:function (val) {
                var self=this;
                $.ajax({
                    url: "https://hdapi.codeclassroom.org/index/huodong/userinfo",
                    type: "post",
                    dataType: 'json',
                    data: {
                        uid: self.iid,
                        page:val,
                        token:self.token
                    },
                    success: function (data) {
                        self.tabledata=data.shareinfo
                    }
                })

            }

        },
        mounted:function () {
            var id=this.$route.query.id;

            var self=this;
            self.token = self.$cookies.get("token");
            self.iid=id;
            $.ajax({
                url:"https://hdapi.codeclassroom.org/index/huodong/userinfo",
                type:"post",
                dataType:'json',
                data:{
                    uid:self.iid,
                    token:self.token
                },
                success:function(data){
                    self.userinfo=data.userinfo
                    self.tabledata=data.shareinfo
                    self.map=data.map
                    self.counttotal=Number(data.map.counttotal)
                    self.pageone=Number(data.map.pageone)

                    var echarts1=self.userinfo.share_total
                    var echarts2=self.userinfo.people_total
                    var echarts3=self.userinfo.share_red_total
                    var echarts4=self.userinfo.red_total
                    var myChart2 = echarts.init(document.getElementById('e2'));
                    option = {
                        tooltip : {
                            formatter: "{a} <br/>{c} {b}"
                        },
                        toolbox: {
                            show: false
                        },
                        legend: {
                            show:false
                        },
                        series : [
                            {
                                name: '转速',
                                type: 'gauge',
                                center: ['50%', '85%'],
                                radius: '130%',
                                min:0,
                                max:1000,
                                startAngle:180,
                                endAngle:0,
                                splitNumber:7,
                                axisLine: {            // 坐标轴线
                                    lineStyle: {       // 属性lineStyle控制线条样式
                                        width: 8,
                                        color: [[echarts1/echarts2, '#7c72c1'],[1, '#f1f1f1']]
                                    }
                                },
                                axisLabel:{
                                    show:false
                                },
                                axisTick: {            // 坐标轴小标记
                                    show:false
                                },
                                splitLine: {
                                    show:false// 分隔线
                                },
                                pointer: {
                                    show:false
                                },
                                title: {
                                    show:false       // x, y，单位px
                                },
                                detail: {
                                    offsetCenter: [0, '-25%'],
                                    formatter:echarts1+'/'+echarts2,
                                    textStyle: {
                                        fontSize: 14,
                                        color: '#7c72c1'          // 主标题文字颜色
                                    }
                                },
                                data:[{value:echarts1,  name: '50%'}]
                            },


                        ]
                    };
                    myChart2.setOption(option);
                    window.onresize = myChart2.resize;
                    var myChart = echarts.init(document.getElementById('e1'));
                    var option = {
                        tooltip : {
                            formatter: "{a} <br/>{c} {b}"
                        },
                        toolbox: {
                            show: false
                        },
                        legend: {
                            show:false
                        },
                        series : [
                            {
                                name: '转速',
                                type: 'gauge',
                                center: ['50%', '85%'],
                                radius: '130%',
                                min:0,
                                max:1000,
                                startAngle:180,
                                endAngle:0,
                                splitNumber:7,
                                axisLine: {            // 坐标轴线
                                    lineStyle: {       // 属性lineStyle控制线条样式
                                        width: 8,
                                        color: [[echarts3/echarts4, '#ff797c'],[1, '#f1f1f1']]
                                    }
                                },
                                axisLabel:{
                                    show:false
                                },
                                axisTick: {            // 坐标轴小标记
                                    show:false
                                },
                                splitLine: {
                                    show:false// 分隔线
                                },
                                pointer: {
                                    show:false
                                },
                                title: {
                                    show:false       // x, y，单位px
                                },
                                detail: {
                                    offsetCenter: [0, '-25%'],
                                    formatter:echarts3+'/'+echarts4,
                                    textStyle: {
                                        fontSize: 14,
                                        color: '#ff797c'          // 主标题文字颜色
                                    }
                                },
                                data:[{value:echarts3,  name: '50%'}]
                            },


                        ]
                    };
                    myChart.setOption(option);
                    window.onresize = myChart.resize;


                } //ajax_e
            });
        },
        beforeCreate:function(){
            var self=this;


        }

    };
</script>


<style scoped>
        @import url('../../../static/css/zhuanqianb.css');
        [v-cloak] {
            display: none;
        }
        .btnzu li{
            display: inline-block;
            padding:5px 10px;
            cursor: pointer;
        }
        .btnzu li.ative11{
            background:#7c72c1 ;
            color: #fff;
        }
        .btnzu{
            border: 1px solid #7c72c1;
            border-radius: 20px;
            overflow: hidden;
            display: inline-block;
        }
        .jiansuo .el-input__inner{
            width: 267px;
            height: 30px;line-height: 30px;border: 1px solid #948ae0;border-radius: 15px;
        }
        .jiansuo .el-input__icon{
            line-height: 30px;
        }
        .touxiang{
            width: 100px;
            height: 100px;
            border-radius: 65px;
            background: #cccccc;
            margin: 20px auto 0;
            position: relative;
            overflow: hidden;
        }
        .touxiangp{
            width: 100%;
            height: 100%;
        }
        .toustutas{
            position: absolute;
            left: 0;
            right: 0;
            bottom:20px;
            text-align: center;
            color: #fff;
        }
</style>
<style>
    .icon {
        width: 1em; height: 1em;
        vertical-align: -0.15em;
        fill: currentColor;
        overflow: hidden;
    }
    .el-pagination.is-background .el-pager li:not(.disabled).active {
        background-color: #7c72c1;
        color: #fff;
    }
</style>
