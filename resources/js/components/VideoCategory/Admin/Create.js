export default {
  data () {
    return {
      description: '',
      description_error: false,
      isReset: false
    }
  },
  created () {

  },
  watch: {
    description () {
      if (!this.isReset) {
        this.description_error = this.description ? false : true
      } else {
        this.description_error = false
      }
    }
  },
  methods: {
    submit () {
      if (this.validateAddVideoCategory()) {
        const payload = {
          description: this.description
        }
        axios.post('/video-categories', payload)
        .then(response => {
          this.reset()
          this.submitting = false
          Swal.fire({
            title: 'Success!',
            text: 'Success',
            type: 'success'
          })
        })
        .catch(error => {
          Swal.fire({
            title: 'Error!',
            text: 'Something went wrong',
            type: 'error'
          })
          this.submitting = false
        })
      }
    },
    validateAddVideoCategory () {
      this.description_error = this.description ? false : true

      if (!this.description_error) {
        return true
      } else {
        return false
      }
    },
    reset () {
      this.isReset = true
      this.description = ''
    }
  }
}
