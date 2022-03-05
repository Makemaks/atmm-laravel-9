export default {
  data () {
    return {
      album: '',
      album_error: false,
      image: '',
      image_error: false,
      image_validation_error: false,
      file: '',
      file_error: false,
      file_validation_error: false,
      release_date: '',
      release_date_error: false,
      uploadStatus: '',
      percentage: 0,
      submitting: false,
      isReset: false,
      is_public: false
    }
  },
  mounted() {

    $(() => {
      const vueInstance = this;
      $('.datepicker').datepicker({
        format: "yyyy",
        /* viewMode: "years", */
        minViewMode: "years",
        startDate : new Date("1900-01-01"),
        endDate: new Date(),
      }).on('changeDate', function (ev) {
        vueInstance.release_date = $(this).val();
      });
    })

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
  created () {

  },
  watch: {
    album () {
      if (!this.isReset) {
        this.album_error = this.album ? false : true
      } else {
        this.band_name_error = false
      }
    }
  },
  methods: {
    submit () {
      if (this.validateAddAlbumForm()) {
        this.submitting = true
        var payload = new FormData()
        payload.append('album', this.album)
        payload.append('image', this.$refs.image_file.value)
        if (this.$refs.file_file.value) {
          payload.append('liner', this.$refs.file_file.value)
        }
        payload.append('release_date', this.release_date)
        payload.append('is_public', this.is_public ? 'yes' : 'no')
        const x = this

        axios({
          method: 'post',
          url: '/albums',
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
          x.$refs.image_file.value = ''
          x.$refs.file_file.value = ''
          x.percentage = 0
          x.uploadStatus = ''
        })
      }
    },
    validateAddAlbumForm () {
      this.album_error = this.album ? false : true;
      this.image_error = this.$refs.image_file.value ? false : true;
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
      this.release_date_error = this.release_date ? false : true;
      const a = !this.album_error
      const b = !this.image_error && !this.image_validation_error
      const c = !this.file_validation_error
      const d = !this.release_date_error
      if (a && b && c && d) {
        return true
      } else {
        return false
      }
    },
    reset () {
      this.isReset = true
      this.is_public = false
      this.album = null
      this.image = ''
      this.$refs.image_file.value = ''
      this.$refs.file_file.value = ''
      this.release_date = null
      this.percentage = 0
      this.uploadStatus = ''
    }
  }
}
