import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import VueExpandableImage from 'vue-expandable-image'

export default {
  data () {
      return {
        submitting: false,
        isReset: false,
        music_detail_id: '',
        author_artist_id: '',
        author_artist_name: '',
        song_title: '',
        allAuthorsArtists: [],
        search: '',
        sort: 'ASC',
        isLoadingRemoveAuthorArtist: false,
        isLoadingAuthorList: false,
        isLoading: false,
        fullPage: false,
        play_id: '',
        isPlaying: false,
        task: 'Add',
        objlist: 'Author',
      }
  },
  mounted() {
    // for Expandable Image
    const viewportMeta = document.createElement('meta');
    viewportMeta.name = 'viewport';
    viewportMeta.content = 'width=device-width, initial-scale=1';
    document.head.appendChild(viewportMeta);
  },
  props: ['authors'],
  watch: {

  },
  components: {
    Loading
  },
  methods: {
    deleteMusic (id) {
      const action = '/songs/' + id
      this.$refs.delete_form.setAttribute('action', action)
    },
    playSong (id) {
        this.song_id = id
        var prev_playing = document.getElementById('song' + this.play_id)
         if (this.play_id === id && this.isPlaying) {
            this.isPlaying = false
            prev_playing.pause()
        } else if (this.play_id === id && !this.isPlaying) {
            this.isPlaying = true
            prev_playing.play()
        } else {
            if (this.play_id) {
                prev_playing.currentTime = 0
                prev_playing.pause()
            }
            this.play_id = id
            this.isPlaying = true
            var song = document.getElementById('song' + id)
            song.play()
        }
    },
    showDeleteSongAuthorArtist (music_detail_id, music_title, author_artist_id, author_artist_name, objlist) {
      this.music_detail_id = music_detail_id
      this.song_title = music_title
      this.author_artist_id = author_artist_id
      this.author_artist_name = author_artist_name
      this.objlist = objlist.capitalize()

      $('#deleteSongAuthorArtist').modal('modal');
    },
    removeAuthorArtist () {
      $('#deleteSongAuthorArtist').modal('hide');
      this.isLoadingRemoveAuthorArtist = true;

      if(this.objlist == 'Artist') {
        var ajax_remove_url = '/remove-artist-to-song'
        var data = {
            music_detail_id: this.music_detail_id,
            artist_id: this.author_artist_id,
        }
      } else {
        var ajax_remove_url = '/remove-author-to-song'
        var data = {
            music_detail_id: this.music_detail_id,
            author_id: this.author_artist_id,
        }
      }

      axios.post(ajax_remove_url, data)
      .then(res => {
          this.submitting = false
          this.reset()
          window.location.reload()
          console.log(res)
      })
      .catch(error => {
          this.submitting = false
      })
    },
    getAuthorArtistList (music_detail_id, task, objlist) {
        if(!music_detail_id || music_detail_id < 1) {
          Swal.fire({ title: 'Error!', text: 'Unknown music.', type: 'error' })
          return false
        }

        $('#selectAuthorArtist').modal('show');

        this.isLoadingAuthorList = true
        this.music_detail_id = music_detail_id
        this.task = task
        this.objlist = objlist.capitalize()

        const data = {
            id: this.music_detail_id,
            task: this.task,
            objlist: this.objlist,
        }
        axios.post('/get-music-author-artist-list', data)
        .then(res => {
          this.isLoadingAuthorList = false
          this.song_title = res.data.music_info.title
          this.allAuthorsArtists = res.data.all_authors_artists
        })
        .catch(error => {
          Swal.fire({
            title: 'Error!',
            text: 'Something went wrong',
            type: 'error'
          })
        })
    },
    checkAll () {
        this.allAuthorsArtists.forEach((author_artist) => {
            author_artist.checked = true;
        });
        this.$forceUpdate();
    },
    unCheckAll () {
        this.allAuthorsArtists.forEach((author_artist) => {
            author_artist.checked = false;
        });
        this.$forceUpdate();
    },
    toggleSort() {
        this.sort = (this.sort === 'ASC' ? 'DESC' : 'ASC');
    },
    processMultipleAuthorArtist () {
        var authorArtistArray = [];
        $("input[name='author_artist_sel']:checkbox:checked").each(function () {
            authorArtistArray.push($(this).val());
        });
        if( authorArtistArray.length > 0 ) {
          if ( this.task == 'Add' || (this.task == 'Remove' && confirm('Are you sure you want to remove those '+this.objlist+'s?')) ) {
              $('#selectAuthorArtist').modal('hide');
              this.isLoading = true

              const data = {
                  authors_artists: authorArtistArray,
                  music_detail_id: this.music_detail_id,
                  task: this.task,
                  objlist: this.objlist,
              }

              axios.post('/process-multiple-authors-artists-to-song', data)
              .then(res => {
                  this.isLoading = false
                  this.submitting = true
                  this.reset()
                  window.location.reload()
              })
              .catch(error => {
                  this.isLoading = false
                  this.submitting = false
              })
          }
        }
        else {
            Swal.fire({
              title: 'Error!',
              text: 'Please select an '+this.objlist+'.',
              type: 'error'
            })
        }
    },
    reset () {
        this.isReset = true
        this.allAuthorsArtists = []
        this.music_detail_id = ''
        this.song_title = ''
    },

  },
  computed: {
      filteredAuthorsArtists (){

          const compare = (a, b) => {
              if(this.sort === 'ASC') {
                  if (a.name < b.name)
                      return -1;
                  if (a.name > b.name)
                      return 1;
              } else if(this.sort === 'DESC') {
                  if (b.name < a.name)
                      return -1;
                  if (b.name > a.name)
                      return 1;
              }
              return 0;
          }

          let authors;

          if (this.search) {
              authors = this.allAuthorsArtists.filter((author)=>{
                  return author.name.toLowerCase().includes(this.search);
              });
          } else {
              authors = this.allAuthorsArtists;
          }

          return authors.sort(compare);
      }
  }

}


String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}
