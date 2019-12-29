<template>


      <el-row style="width: 100%;">
            <el-col :span="24"  class="search">
                  <el-input :placeholder="placeholder" v-model="zhi" class="input-with-select" size="small">
                        <el-button slot="append" type="primary"  size="small" style="
                              background:#7c71bf;color: #fff;border: 1px solid #7c71bf;border-bottom:0;border-radius: 0  4px 4px 0"
                                   icon="el-icon-search"></el-button>
                  </el-input>
            </el-col>
      </el-row>

</template>

<script>
export default {
    components:{
        searchbtn:'search'
    },
    data() {
        return {
            zhi:'',
        }
    },
    methods:{

    },
  props: ['val1','placeholder']
}
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style >
      .search .el-input-group__append{
            border-radius: 0 15px 15px 0;
            overflow: hidden;
            border: 1px solid #7c71bf;

      }
       .search .el-input-group--append .el-input__inner, .el-input-group__prepend {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
             border-radius: 15px;
             border-top-right-radius: 0;
             border-bottom-right-radius: 0;
      }

</style>
