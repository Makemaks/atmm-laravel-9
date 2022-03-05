export default {
  data () {
      return {
        title: '',
        type: '',
        date: '',
        title_error: false,
        type_error: false,
        audio_error: false,
        audio_validation_error: false,
        date_error: false,
        isReset: false,
        submitting: false,
        percentage: 0,
        uploadStatus: '',
        show_in_explore: false,
        is_public: false,
        image_error: false,
        image_validation_error: false,
      }
  },
  mounted() {
    this.$refs.image_file.addEventListener('change', (event) => {
      this.image_error = this.$refs.image_file.value ? false : true;
        if (!this.image_error) {
          const x = (this.$refs.image_file.value).split('.')
          const y = x[x.length - 1].toLowerCase()
          const extensions = ['png', 'jpeg', 'jpg', 'gif']
          var found = 0
          for (var i = 0; i < extensions.length; i++) {
            if (y === extensions[i]) {
              found++
              break
            }
          }
          this.image_validation_error = found < 1 ? true : false
      } else {
        this.image_validation_error = false;
      }
    });

    this.$refs.audio_file.addEventListener('change', (event) => {
      this.audio_error = this.$refs.audio_file.value ? false : true;
        if (!this.audio_error) {
          const x = (this.$refs.audio_file.value).split('.')
          const y = x[x.length - 1]
          const extensions = ['mpga', 'mp3']
          var found = 0
          for (var i = 0; i < extensions.length; i++) {
            if (y === extensions[i]) {
              found++
              break
            }
          }
          this.audio_validation_error = found < 1 ? true : false
        }
    });
  },
  created () {
    this.setDefaultDate()
  },
  watch: {
    title () {
      if (!this.isReset) {
        this.title_error = this.title ? false : true
      } else {
        this.title_error = false
      }
    },
    type () {
      if (!this.isReset) {
        this.type_error = this.type ? false : true
      } else {
        this.type_error = false
      }
    },
    date () {
      if (!this.isReset) {
        this.date_error = this.date ? false : true
      } else {
        this.date_error = false
      }
    }
  },
  methods: {
    setDefaultDate () {
      var today = new Date()
      var dd = String(today.getDate()).padStart(2, '0')
      var mm = String(today.getMonth() + 1).padStart(2, '0')
      var yyyy = today.getFullYear()

      today = yyyy + '-' + mm + '-' + dd
      this.date = today
    },
    submit () {
      if (this.validateAddPodcast()) {
        this.submitting = true
        var payload = new FormData()
        payload.append('title', this.title)
        payload.append('type', this.type)
        payload.append('date', this.date)
        payload.append('audio', this.$refs.audio_file.value)
        payload.append('show_in_explore', this.show_in_explore ? 'yes' : 'no')
        payload.append('is_public', this.is_public ? 'yes' : 'no')
        payload.append('image', this.$refs.image_file.value)

        const x = this

        axios({
          method: 'post',
          url: '/podcasts',
          data: payload,
          config: {
              headers: {
                  'Content-Type': 'multipart/form-data',
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                  'Accept': 'application/json'
              }
          },
            onUploadProgress: progressEvent => {
              this.percentage = ((progressEvent.loaded / progressEvent.total) * 100)
              if (this.percentage == 100) {
                this.uploadStatus = 'finalizing'
              } else {
                this.uploadStatus = 'uploading...'
              }
            }
        })
        .then (function (response) {
          x.reset()
          x.submitting = false
          Swal.fire({
            title: 'Success!',
            text: 'Success',
            type: 'success'
          }).then(() => {
            location.reload();
          })
        })
        .catch(function (error) {
          Swal.fire({
            title: 'Error!',
            text: 'Something went wrong',
            type: 'error'
          })
          x.submitting = false
          x.$refs.audio_file.value = ''
          x.percentage = 0
          x.uploadStatus = ''
        })
      }
    },
    validateAddPodcast () {
      this.title_error = this.title ? false : true

      this.image_error = this.$refs.image_file.value ? false : true
      if (!this.image_error) {
        const x = (this.$refs.image_file.value).split('.')
        const y = x[x.length - 1]
        const extensions = ['png', 'jpeg', 'jpg', 'gif']
        var found = 0
        for (var i = 0; i < extensions.length; i++) {
          if (y === extensions[i]) {
            found++
            break
          }
        }
        this.image_validation_error = found < 1 ? true : false
      }

      this.type_error = this.type ? false : true
      this.audio_error = this.$refs.audio_file.value ? false : true

      if (!this.audio_error) {
        const x = (this.$refs.audio_file.value).split('.')
        const y = x[x.length - 1]
        const extensions = ['mpga', 'mp3']
        var found = 0
        for (var i = 0; i < extensions.length; i++) {
          if (y === extensions[i]) {
            found++
            break
          }
        }
        this.audio_validation_error = found < 1 ? true : false
      }

      this.date_error = this.date ? false : true
      const a = !this.title_error
      const b = !this.audio_error && !this.audio_validation_error
      const c = !this.date_error
      const f = !this.image_error && !this.image_validation_error
      if (a && b && c && f) {
        return true
      } else {
        return false
      }
    },
    reset () {
      this.isReset = true
      this.title = ''
      this.type = ''
      this.audio = null
      this.show_in_explore = false
      this.is_public = false
      this.setDefaultDate()
      this.$refs.audio_file.value = ''
      this.percentage = 0
      this.uploadStatus = ''
    },
    OnFileChange (e) {
      this.audio = e.target.files[0]
    }
  }
}
