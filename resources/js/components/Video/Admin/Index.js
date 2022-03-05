export default {
  data () {
    return {
      
    }
  },
  created () {

  },
  watch: {

  },
  methods: {
    deleteVideo (id) {
      const action = '/videos/' + id
      this.$refs.delete_form.setAttribute('action', action)
    }
  }
}
