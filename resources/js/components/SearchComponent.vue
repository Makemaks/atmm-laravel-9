<template>
  <form method="get" class="form-inline" @submit.prevent="getList()">
      <div class="input-group">
          <input type="text" class="form-control" name="search" v-model="searchtxt" placeholder="Search">
          <div class="input-group-btn">
              <button class="btn btn-default" type="submit" style="padding:9px 15px">
              <i class="fas fa-search"></i>
              </button>
          </div>
          <input type="hidden" name="page" value="1">
          <input type="hidden" name="sortBy" value="">
          <input type="hidden" name="sortOrder" value="">
      </div>
</form>
</template>
<style>

</style>

<script>
    export default {
        data () {
          return {
            searchtxt: '',
          }
        },
        props: ['searched', 'search'],
        methods: {
          getList () {
            this.search.text = this.searchtxt;
            this.$emit('gosearch')
          },
        },
    }
</script>
