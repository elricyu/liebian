<template>
    <div>
        <el-container>
        <el-header><my-header></my-header></el-header>
        <el-container>
            <el-aside width="100px"><my-aside></my-aside></el-aside>
            <el-container>
            <el-main><my-main></my-main></el-main>
            <el-footer><my-footer></my-footer></el-footer>
            </el-container>
        </el-container>
        </el-container>
    </div>
</template>

<script>
import myHeader from './base/header'
import myAside from './base/aside'
import myMain from './base/main'
import myFooter from './base/footer'
export default {
    components:{
        myHeader,
        myAside,
        myMain,
        myFooter,
    }
}
</script>

<style scoped>
.el-header, .el-footer {
    background-color: #B3C0D1;
    color: #333;
    text-align: center;
    /* line-height: 60px; */
  }
  
  .el-aside {
    background-color: #7c72c1;
    color: #333;
    overflow:hidden;
    /* text-align: center; */
    /* line-height: 50px; */
  }
  .el-main {
    background-color: #E9EEF3;
    color: #333;
    /* text-align: center;
    line-height: 160px; */
  }
  
  body > .el-container {
    margin-bottom: 40px;
  }
  
  .el-container:nth-child(5) .el-aside,
  .el-container:nth-child(6) .el-aside {
    line-height: 260px;
  }
  
  .el-container:nth-child(7) .el-aside {
    line-height: 320px;
  }

</style>
