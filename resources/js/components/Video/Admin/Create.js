export default {
  data () {
      return {
          title: '',
          video_category_id: '',
          date_release: '',
          image_error: false,
          title_error: false,
          video_category_id_error: false,
          date_release_error: false,
          video_error: false,
          image_validation_error: false,
          video_validation_error: false,
          uploadStatus: '',
          percentage: 0,
          submitting: false,
          isReset: false,
          show_in_explore: false,
          is_public: false
      }
  },
  created () {
    this.setDefaultDateRelease();

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

    this.$refs.video_file.addEventListener('change', (event) => {
      this.video_error = this.$refs.video_file.value ? false : true;
        if (!this.video_error) {
          const x = (this.$refs.video_file.value).split('.')
          const y = x[x.length - 1].toLowerCase()
          const extensions = ['mp4','x-flv','x-mpegURL','MP2T','3gpp','quicktime','x-msvideo','x-ms-wmv','mkv','avi']
          var found = 0
          for (var i = 0; i < extensions.length; i++) {
            if (y === extensions[i]) {
              found++
              break
            }
          }
          this.video_validation_error = found < 1 ? true : false
      } else {
        this.video_validation_error = false;
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
    video_category_id () {
      if (!this.isReset) {
        this.video_category_id_error = this.video_category_id ? false : true
      } else {
        this.video_category_id_error = false
      }
    },
    date_release () {
      if (!this.isReset) {
        this.date_release_error = this.date_release ? false : true
      } else {
        this.date_release_error = false
      }
    },
  },
  methods: {
    setDefaultDateRelease () {
      var today = new Date()
      var dd = String(today.getDate()).padStart(2, '0')
      var mm = String(today.getMonth() + 1).padStart(2, '0')
      var yyyy = today.getFullYear();

      today = yyyy + '-' + mm + '-' + dd
      this.date_release = today
    },
    submit () {
      if (this.validateAddVideoForm()) {
        this.submitting = true
        var payload = new FormData()
        payload.append('title', this.title)
        payload.append('video_category_id', this.video_category_id)
        payload.append('date_release', this.date_release)
        payload.append('video', this.$refs.video_file.value)
        payload.append('image', this.$refs.image_file.value)
        payload.append('show_in_explore', this.show_in_explore ? 'yes' : 'no')
        payload.append('is_public', this.is_public ? 'yes' : 'no')

        const x = this

        axios({
          method: 'post',
          url: '/videos',
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
          x.$refs.image_file.value = ''
          x.$refs.video_file.value = ''
          x.percentage = 0
          x.uploadStatus = ''
        })
      }
    },
    validateAddVideoForm () {
      this.title_error = this.title ? false : true
      this.video_category_id_error = this.video_category_id ? false : true
      this.date_release_error = this.date_release ? false : true

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

      this.video_error = this.$refs.video_file.value ? false : true
      if (!this.video_error) {
        const x = (this.$refs.video_file.value).split('.')
        const y = x[x.length - 1].toLowerCase()
        const extensions = ['mp4','x-flv','x-mpegURL','MP2T','3gpp','quicktime','x-msvideo','x-ms-wmv','mkv', 'avi']
        var found = 0
        for (var i = 0; i < extensions.length; i++) {
          if (y === extensions[i]) {
            found++
            break
          }
        }
        this.video_validation_error = found < 1 ? true : false
      }
      const a = !this.title_error
      const b = !this.video_category_id_error
      const d = !this.date_release_error
      const e = !this.video_error
      if (a && b && d && e) {
        return true
      } else {
        return false
      }
    },
    reset () {
      this.isReset = true
      this.title = ''
      this.show_in_explore = false
      this.is_public = false
      this.video_category_id = ''
      this.setDefaultDateRelease()
      this.video = null
      this.percentage = 0
      this.uploadStatus = ''
      this.$refs.image_file.value = ''
      this.$refs.video_file.value = ''
    },
    OnFileChange (e) {
      this.video = e.target.files[0]
    },
    OnImageChange (e) {
        this.image = e.target.files[0]
    },
  }
}
