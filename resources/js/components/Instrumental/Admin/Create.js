export default {
  data () {
    return {
      title: '',
      image_validation_error: false,
      title_error: false,
      image_error: false,
      high_key_video_error: false,
      high_key_video_validation_error: false,
      low_key_video_error: false,
      low_key_video_validation_error: false,
      high_key_audio_error: false,
      high_key_audio_validation_error: false,
      low_key_audio_error: false,
      low_key_audio_validation_error: false,
      isReset: false,
      percentage: 0,
      uploadStatus: '',
      submitting: false,
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

    this.$refs.high_key_video_file.addEventListener('change', (event) => {
      this.high_key_video_error = this.$refs.high_key_video_file.value ? false : true;
        if (!this.high_key_video_error) {
          const x = (this.$refs.high_key_video_file.value).split('.')
          const y = x[x.length - 1]
          const extensions = ['mp4','x-flv','x-mpegURL','MP2T','3gpp','quicktime','x-msvideo','x-ms-wmv']
          var found = 0
          for (var i = 0; i < extensions.length; i++) {
            if (y === extensions[i]) {
              found++
              break
            }
          }
          this.high_key_video_validation_error = found < 1 ? true : false
        }
    });

    this.$refs.low_key_video_file.addEventListener('change', (event) => {
      this.audio_error = this.$refs.low_key_video_file.value ? false : true;
        if (!this.audio_error) {
          const x = (this.$refs.low_key_video_file.value).split('.')
          const y = x[x.length - 1]
          const extensions = ['mp4','x-flv','x-mpegURL','MP2T','3gpp','quicktime','x-msvideo','x-ms-wmv']
          var found = 0
          for (var i = 0; i < extensions.length; i++) {
            if (y === extensions[i]) {
              found++
              break
            }
          }
          this.low_key_video_validation_error = found < 1 ? true : false
        }
    });

    this.$refs.high_key_audio_file.addEventListener('change', (event) => {
      this.audio_error = this.$refs.high_key_audio_file.value ? false : true;
        if (!this.audio_error) {
          const x = (this.$refs.high_key_audio_file.value).split('.')
          const y = x[x.length - 1]
          const extensions = ['mpga', 'mp3']
          var found = 0
          for (var i = 0; i < extensions.length; i++) {
            if (y === extensions[i]) {
              found++
              break
            }
          }
          this.high_key_audio_validation_error = found < 1 ? true : false
        }
    });

    this.$refs.low_key_audio_file.addEventListener('change', (event) => {
      this.audio_error = this.$refs.low_key_audio_file.value ? false : true;
        if (!this.audio_error) {
          const x = (this.$refs.low_key_audio_file.value).split('.')
          const y = x[x.length - 1]
          const extensions = ['mpga', 'mp3']
          var found = 0
          for (var i = 0; i < extensions.length; i++) {
            if (y === extensions[i]) {
              found++
              break
            }
          }
          this.low_key_audio_validation_error = found < 1 ? true : false
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
        this.music_detail_id_error = false
      }
    }
  },
  methods: {
    submit () {
      if (this.validateAddInstrumental()) {
        this.submitting = true
        var payload = new FormData()
        payload.append('title', this.title)
        payload.append('image', this.$refs.image_file.value)
        payload.append('show_in_explore', this.show_in_explore ? 'yes' : 'no')
        payload.append('is_public', this.is_public ? 'yes' : 'no')
        if (this.$refs.high_key_video_file.value) {
          payload.append('high_key_video', this.$refs.high_key_video_file.value)
        }
        if (this.$refs.low_key_video_file.value) {
          payload.append('low_key_video', this.$refs.low_key_video_file.value)
        }
        if (this.$refs.high_key_audio_file.value) {
          payload.append('high_key_audio', this.$refs.high_key_audio_file.value)
        }
        if (this.$refs.low_key_audio_file.value) {
          payload.append('low_key_audio', this.$refs.low_key_audio_file.value)
        }

        const x = this

        axios({
          method: 'post',
          url: '/instrumentals',
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
          })
        })
        .catch(function (error) {
          Swal.fire({
            title: 'Error!',
            text: 'Something went wrong',
            type: 'error'
          })
          x.submitting = false
          x.resetFiles()
          x.percentage = 0
          x.uploadStatus = ''
        })
      }
    },
    validateAddInstrumental () {
      this.title_error = this.title ? false : true;

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

      const a = !this.title_error
      const b = !this.high_key_video_validation_error
      const c = !this.high_key_audio_validation_error
      const d = !this.low_key_audio_validation_error
      const e = !this.low_key_video_validation_error
      const f = !this.image_error && !this.image_validation_error
      if (a && b && c && d && e && f) {
        return true
      } else {
        return false
      }
    },

    reset () {
      this.isReset = true
      this.title = '';
      this.show_in_explore = false
      this.is_public = false
      this.percentage = 0
      this.uploadStatus = ''
      this.resetFiles()
    },
    resetFiles () {
      this.$refs.high_key_video_file.value = ''
      this.$refs.low_key_video_file.value = ''
      this.$refs.high_key_audio_file.value = ''
      this.$refs.low_key_audio_file.value = ''
    }
  }
}
