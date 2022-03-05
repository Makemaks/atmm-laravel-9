export default {
    data: () => ({
        music_id: '',
        track_sequence: '',
        band_album_id: '',
        submitting: false,
        music_id_error: false,
        track_sequence_error: false,
        isReset: false,
        music_id_for_update: '',
        band_album_id_for_update: '',
        track_sequence_for_update: '',
        track_sequence_for_update_error: false
    }),
    watch: {
        music_id () {
          if (!this.isReset) {
            this.music_id_error = this.music_id ? false : true
          } else {
            this.music_id_error = false
          }
        },
        track_sequence () {
            if (!this.isReset) {
                this.track_sequence_error = this.track_sequence ? false : true
            } else {
                this.track_sequence_error = false
            }
        },
        track_sequence_for_update () {
            if (!this.isReset) {
                this.track_sequence_for_update_error = this.track_sequence_for_update ? false : true
            } else {
                this.track_sequence_for_update_error = false
            }
        }
    },
    methods: {
        showAddMusicModal (id) {
            this.band_album_id = id
        },
        updateSongTrackSequence () {
            this.track_sequence_for_update_error = this.track_sequence_for_update ? false : true
            if (!this.track_sequence_for_update_error) {
                const data = {
                    music_detail_id: this.music_id_for_update,
                    band_album_id: this.band_album_id_for_update,
                    track_sequence: this.track_sequence_for_update
                }
                axios.post('/update-music-track-sequence', data)
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
        changeTrackSequence (music_id, band_album_id, ts) {
            this.music_id_for_update = music_id
            this.band_album_id_for_update = band_album_id
            this.track_sequence_for_update = ts
        },
        addMusic () {
            if (this.validateAddMusic()) {
                const data = {
                    music_detail_id: this.music_id,
                    band_album_id: this.band_album_id,
                    track_sequence: this.track_sequence
                }
                axios.post('/add-music-to-band-album', data)
                .then(res => {
                    this.submitting = false
                    this.reset()
                    //window.location.reload()
                })
                .catch(error => {
                    this.submitting = false
                })
            }
        },
        validateAddMusic () {
            this.music_id_error = this.music_id ? false : true
            this.track_sequence_error = this.track_sequence ? false : true
            if (!this.music_id_error && !this.track_sequence_error) {
                return true
            } else {
                return false
            }
        },
        reset () {
            this.isReset = true
            this.music_id = ''
            this.track_sequence = ''
            this.track_sequence_error = false
            this.track_sequence_for_update = ''
            this.track_sequence_for_update_error = false
            this.music_id_error = false
        },
        showRemoveMusicModal (music_id, band_album_id) {
            this.music_id = music_id
            this.band_album_id = band_album_id
        },
        removeMusic () {
            const data = {
                music_detail_id: this.music_id,
                band_album_id: this.band_album_id
            }
            axios.post('/remove-music-to-band-album', data)
            .then(res => {
                this.submitting
                this.reset()
                window.location.reload()
            })
            .catch(error => {
                this.submitting = false
            })
        }
    }
}