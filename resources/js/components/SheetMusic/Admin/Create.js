export default {
  data () {
    return {
      title: '',
      title_error: false,
      image_error: false,
      image_validation_error: false,
      file_error: false,
      file_validation_error: false,
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

    this.$refs.file_file.addEventListener('change', (event) => {
      this.file_error = this.$refs.file_file.value ? false : true;
        if (!this.file_error) {
          const x = (this.$refs.file_file.value).split('.')
          const y = x[x.length - 1]
          const extensions = ['pdf', 'docx', 'dotx', 'dotm', 'docb']
          var found = 0
          for (var i = 0; i < extensions.length; i++) {
            if (y === extensions[i]) {
              found++
              break
            }
          }
          this.file_validation_error = found < 1 ? true : false
        }
    });
  },
  watch: {
    title () {
      if (!this.isReset) {
        this.title_error = this.title ? false : true
      } else {
        this.title_error = false
      }
    },
  },
  methods: {
    submit () {
      if (this.validateAddSheetMusicForm()) {
        this.submitting = true
        var payload = new FormData()
        payload.append('title', this.title)
        payload.append('image', this.$refs.image_file.value)
        payload.append('file', this.$refs.file_file.value)
        payload.append('show_in_explore', this.show_in_explore ? 'yes' : 'no')
        payload.append('is_public', this.is_public ? 'yes' : 'no')

        const x = this

        axios({
          method: 'post',
          url: '/sheet_musics',
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
          x.$refs.file.value = ''
          x.percentage = 0
          x.uploadStatus = ''
        })
      }
    },
    validateAddSheetMusicForm () {
      this.title_error = this.title ? false : true
      this.image_error = this.$refs.image_file.value ? false : true
      if (!this.image_error) {
        const x = (this.$refs.image_file.value).split('.')
        const y = x[x.length - 1]
        const extensions = ['jpg', 'png', 'jpeg', 'gif'];
        var found = 0
        for (var i = 0; i < extensions.length; i++) {
          if (y === extensions[i]) {
            found++
            break
          }
        }
        this.image_validation_error = found < 1 ? true : false
      }
      this.file_error = this.$refs.file_file.value ? false : true
      if (!this.file_error) {
        const x = (this.$refs.file_file.value).split('.')
        const y = x[x.length - 1]
        const extensions = ['pdf', 'docx', 'dotx', 'dotm', 'docb'];
        var found = 0
        for (var i = 0; i < extensions.length; i++) {
          if (y === extensions[i]) {
            found++
            break
          }
        }
        this.file_validation_error = found < 1 ? true : false
      }
      const a = !this.title_error
      const c = !this.date_error
      const d = !this.file_error && !this.file_validation_error
      if (a && c && d) {
        return true
      } else {
        return false
      }
    },
    reset () {
      this.isReset = true
      this.title = '';
      this.file = '';
      this.show_in_explore = false
      this.is_public = false
      this.percentage = 0
      this.uploadStatus = ''
      this.$refs.image_file.value = ''
      this.$refs.file_file.value = ''
    }
  }
}
