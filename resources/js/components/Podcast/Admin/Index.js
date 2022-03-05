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
    deletePodcast (id) {
      const action = '/podcasts/' + id
      this.$refs.delete_form.setAttribute('action', action)
    }
  }
}
