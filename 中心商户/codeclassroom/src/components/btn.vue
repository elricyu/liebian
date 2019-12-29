<template>
    <el-button type="primary" size="middle" @click="fun" style="padding: 7px 20px;border-radius: 15px;box-shadow: 0 2px 12px 0 rgba(0,0,0,.3) " round>{{msg}}</el-button>
</template>

<script>
export default {
    // components:{
    //     purBtn:'el-button'
    // },
  props: ['msg'],
    methods:{
        fun:function () {
            this.$emit('fn')
        }
    }
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    .el-button--primary:hover {
         background: #7c71bf;
         border-color: #7c71bf;
         color: #fff;
        opacity: 0.9;
    }
    .el-button--primary {
        color: #fff;
        background-color: #7c71bf;
        border-color: #7c71bf;
    }
</style>
