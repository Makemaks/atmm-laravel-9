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
    deleteInstrumental (id) {
      const action = '/instrumentals/' + id
      this.$refs.delete_form.setAttribute('action', action)
    }
  }
}
