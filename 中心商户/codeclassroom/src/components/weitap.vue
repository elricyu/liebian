<template>

      <ul class="weitap" style="width: 300px">
            <li  v-for="(da,index) in shuju" :class="{active:index==Tapchecked}" @click="tapjump(da.lian)"> {{da.neir}}</li>
      </ul>
</template>

<script>
export default {
//     components:{
//         weitap:'weitap'
//     },
    data() {
        return {

        };
    },
  props: ['shuju','Tapchecked'],
    methods:{
        tapjump:function (d) {
            this.$router.push(d)
        },
    }

}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

      .weitap{
            display: flex;
            display:-webkit-flex;
            list-style: none;
            justify-content: space-between;
      }
      .weitap li {

            width: 86px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            float: left;
            color: #fff;
            border-radius: 5px 5px 0 0;
            background: rgb(124, 113, 191);
            border: 1px solid rgb(124, 113, 191);
            border-bottom: none;
            cursor: pointer;
      }
      .weitap li.active{
            background: #ecebfd;
            color: rgb(124, 113, 191);
      }
</style>
