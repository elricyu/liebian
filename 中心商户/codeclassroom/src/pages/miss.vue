<template>
  <div id="app">
        <img class="p404" src="/static/img/404.gif" @click="back" alt="">
  </div>
</template>

<script>

export default {
    data() {
        return {
        };
    },
    methods:{
        back:function () {
//          this.$router.push({path:"home"})
//            this.$router.push('home')
            this.$router.replace('../home')
        }
    }
}
</script>

<style scoped>
    #app{
        height: 100vh;
        overflow: hidden;
        cursor: pointer;
    }
    .p404{
        margin-top: -9.5vh;
        width: 100%;
    }

</style>
