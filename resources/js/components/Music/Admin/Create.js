export default {
  data () {
    return {
      title: '',
      title_error: false,
      image_error: false,
      audio_error: false,
      image_validation_error: false,
      audio_validation_error: false,
      uploadStatus: '',
      percentage: 0,
      submitting: false,
      isReset: false,
      show_in_explore: false,
      is_public: false
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

  },
  watch: {
    title () {
      if (!this.isReset) {
        this.title_error = this.title ? false : true
      } else {
        this.title_error = false
      }
    }
  },
  methods: {
    submit () {
      if (this.validateAddMusicForm()) {
        this.submitting = true
        var payload = new FormData()
        payload.append('title', this.title)
        payload.append('image', this.$refs.image_file.value)
        payload.append('audio', this.$refs.audio_file.value)
        payload.append('show_in_explore', this.show_in_explore ? 'yes' : 'no')
        payload.append('is_public', this.is_public ? 'yes' : 'no')

        const x = this

        axios({
          method: 'post',
          url: '/songs',
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
          x.$refs.image.value = ''
          x.$refs.audio.value = ''
          x.percentage = 0
          x.uploadStatus = ''
        })
      }
    },
    validateAddMusicForm () {
      this.title_error = this.title ? false : true

      this.image_error = this.$refs.image_file.value ? false : true;
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
      const a = !this.title_error
      const b = !this.audio_error && !this.audio_validation_error
      const c = !this.image_error && !this.image_validation_error
      if (a && b) {
        return true
      } else {
        return false
      }
    },
    OnFileChange (e) {
      this.audio = e.target.files[0]
    },
    OnImageChange (e) {
      this.image = e.target.files[0]
    },
    reset () {
      this.isReset = true
      this.title = ''
      this.show_in_explore = false
      this.is_public = false
      this.image = ''
      this.percentage = 0
      this.uploadStatus = ''
      this.$refs.image_file.value = ''
      this.$refs.audio_file.value = ''
    }
  }
}
