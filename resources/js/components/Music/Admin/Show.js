export default {
  data () {
      return {
        music_detail_id: '',
        artist_id: '',
        author_id: '',
        artist_id_error: false,
        author_id_error: false,
        isReset: false,
        submitting: false
      }
  },
  created () {

  },
  watch: {
    artist_id () {
      if (!this.isReset) {
        this.artist_id_error = this.artist_id ? false : true
      } else {
        this.artist_id_error = false
      }
    },
    author_id () {
      if (!this.isReset) {
        this.author_id_error = this.author_id ? false : true
      } else {
        this.author_id_error = false
      }
    }
  },
  methods: {
    showAddArtist (id) {
      this.music_detail_id = id
    },
    showRemoveArtist (music_detail_id, artist_id) {
      this.music_detail_id = music_detail_id
      this.artist_id = artist_id
    },
    showRemoveAuthor (music_detail_id, author_id) {
      this.music_detail_id = music_detail_id
      this.author_id = author_id
    },
    addArtist () {
      if (this.validateAddArtist()) {
          const data = {
              music_detail_id: this.music_detail_id,
              artist_id: this.artist_id
          }
          axios.post('/add-artist-to-song', data)
          .then(res => {
              this.submitting = false
              this.reset()
              window.location.reload()
          })
          .catch(error => {
              this.submitting = false
          })
      }
    },
    removeArtist () {
      const data = {
          music_detail_id: this.music_detail_id,
          artist_id: this.artist_id
      }
      axios.post('/remove-artist-to-song', data)
      .then(res => {
          this.submitting = false
          this.reset()
          window.location.reload()
      })
      .catch(error => {
          this.submitting = false
      })
    },
    addAuthor () {
      if (this.validateAddAuthor()) {
          const data = {
              music_detail_id: this.music_detail_id,
              author_id: this.author_id
          }
          axios.post('/add-author-to-song', data)
          .then(res => {
              this.submitting = false
              this.reset()
              window.location.reload()
          })
          .catch(error => {
              this.submitting = false
          })
      }
    },
    removeAuthor () {
      const data = {
          music_detail_id: this.music_detail_id,
          author_id: this.author_id
      }
      axios.post('/remove-author-to-song', data)
      .then(res => {
          this.submitting = false
          this.reset()
          window.location.reload()
      })
      .catch(error => {
          this.submitting = false
      })
    },
    validateAddArtist () {
      this.artist_id_error = this.artist_id ? false : true
      if (!this.artist_id_error) {
        return true
      } else {
        return false
      }
    },
    validateAddAuthor () {
      this.author_id_error = this.author_id ? false : true
      if (!this.author_id_error) {
        return true
      } else {
        return false
      }
    },
    reset () {
      this.isReset = true
      this.music_detail_id = ''
      this.artist_id = ''
      this.author_id = ''
    }
  }
}
