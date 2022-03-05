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
        track_sequence_for_update_error: false,
        selected_music_ids: [],
        album_music_id: '',
        initial_load_album_music: true,
        allSongs: [],
        search: '',
        sort: 'ASC',
    }),
    mounted() {
        this.allSongs = JSON.parse(this.songs);
    },
    props: ['songs'],
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
        },
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
        updateSongTrackSequenceNew () {
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
                    window.location.reload()
                    /*
                    var old_album_music_id = this.album_music_id;
                    var old_track_sequence_for_update = this.track_sequence_for_update;
                    if( res.status == 200 && res.data == 'success') {
                        alert("$('#trackseq_"+old_album_music_id+"').text("+old_track_sequence_for_update+");")
                        $('#trackseq_'+old_album_music_id+'').text(old_track_sequence_for_update)
                    }
                    this.cancelTrackSequence()
                    */
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
        selectTrackSequence (id, music_id, band_album_id, track_sequence) {
            this.album_music_id = id
            this.initial_load_album_music = false
            this.music_id_for_update = music_id
            this.band_album_id_for_update = band_album_id
            this.track_sequence_for_update = track_sequence
        },
        cancelTrackSequence () {
            this.album_music_id = ''
            this.initial_load_album_music = true
            this.music_id_for_update = ''
            this.band_album_id_for_update = ''
            this.track_sequence_for_update = ''
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
                    window.location.reload()
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
        },
        checkAll () {
            this.allSongs.forEach((song) => {
                song.checked = true;
            });
            this.$forceUpdate();
        },
        unCheckAll () {
            this.allSongs.forEach((song) => {
                song.checked = false;
            });
            this.$forceUpdate();
        },
        toggleSort() {
            this.sort = (this.sort === 'ASC' ? 'DESC' : 'ASC');
        },
        addMultipleMusic () {

            var musicArray = [];
            $("input[name='musicsel']:checkbox:checked").each(function () {
                musicArray.push($(this).val());
            });
            if( musicArray.length > 0 ) {
                const data = {
                    musics: musicArray,
                    band_album_id: this.band_album_id
                }

                axios.post('/add-multiple-music-to-band-album', data)
                .then(res => {
                    this.submitting = false
                    this.reset()
                    window.location.reload()
                })
                .catch(error => {
                    this.submitting = false
                })
            }
            else {
                alert('Please select a music to be added on this album.');
            }

        },
    },
    computed: {
        filteredSongs (){

            const compare = (a, b) => {
                if(this.sort === 'ASC') {
                    if (a.title < b.title)
                        return -1;
                    if (a.title > b.title)
                        return 1;
                } else if(this.sort === 'DESC') {
                    if (b.title < a.title)
                        return -1;
                    if (b.title > a.title)
                        return 1;
                }
                return 0;
            }

            let songs;

            if (this.search) {
                songs = this.allSongs.filter((song)=>{
                    return song.title.toLowerCase().includes(this.search);
                });
            } else {
                songs = this.allSongs;
            }

            return songs.sort(compare);
        }
    }
}
