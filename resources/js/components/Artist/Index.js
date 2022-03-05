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
    deleteArtist (id) {
      const action = '/artists/' + id
      this.$refs.delete_form.setAttribute('action', action)
    }
  }
}
