<template>
    <div id="demo">
        <!-- 遮罩层 -->
        <div style="background-color: #50a0ff;height: 60px;color: #fff;">
            <img src="/static/img/logoc.png" alt="logo" style="margin: 10px 30px 0 30px;float: left;">
            <div style="border-left: 1px solid #fff;width: 100px;height: 30px;float: left;font-size: 18px;margin-top: 15px;padding-left: 15px;padding-top: 2px;">
                重置密码
            </div>

        </div>
        <div style="background-color: #f5f5f5;padding-top: 30px;padding-bottom: 30px">
            <div style="width: 280px;margin: 0 auto;font-size: 14px">
                <img src="/static/img/jia.png" alt="logo" style="vertical-align: middle;margin-right: 10px">
                为了您的账户安全，请修改初始密码
            </div>
        </div>
        <div style="width: 300px;margin: 60px auto 0">
           <el-row>
               <div class="labalbox">
                   新密码：
               </div>

           </el-row>
            <el-row>
                <el-input
                        placeholder="请输入内容,最少6位"
                        v-model="mima1"
                        type="password"
                        clearable>
                </el-input>
            </el-row>

            <el-row>
                <div class="labalbox">
                    重新输入密码：
                </div>

            </el-row>
            <el-row>
                <el-input
                        placeholder="请输入内容,最少6位"
                        v-model="mima2"
                        type="password"
                        clearable>
                </el-input>
            </el-row>
            <el-row>
                <div class="qurenbox">
                    <el-button type="primary" @click="gaimima"> &nbsp; 确 &nbsp; 定 &nbsp; </el-button>
                </div>

            </el-row>
        </div>


    </div>
</template>

<script>

export default {
    data() {
        return {
            mima1:'',
            mima2:'',
        };
    },
    methods:{
        gaimima:function () {
            let self=this;
            if(self.mima1!=self.mima2){
                self.$message({
                    message: '两次输入密码不相同',
                    type: 'error'
                });
            }else if(self.mima1.length < 6){
                self.$message({
                    message: '密码最小为6位',
                    type: 'error'
                });
            }else{
                let self=this;
                $.ajax({
                    type: 'POST',
                    url: "https://hdapi.codeclassroom.org/index/Login/chong",
                    data: {
                        token:$.cookie('tokenre'),
                     pad:self.mima1,
                     pad1:self.mima2,
                    },
                    success: function (data) {
                    self.$router.push('/')
                    }
                });
            }

        }

    }

}
</script>

<style scoped>
.labalbox{
    line-height: 40px;
    font-size: 14px;
    color: #666;
}
    .qurenbox{
        display: flex;
        justify-content: center;
        padding-top: 30px;
    }

</style>
