import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'

export default {
  data () {
      return {
        submitting: false,
        isReset: false,
        artist_id: '',
        artist_name: '',
        isLoadingSongList: false,
        isLoadingArtistList: false,
        isLoadingRemoveSong: false,
        allSongs: [],
        searchsong: '',
        sort: 'ASC',
        music_detail_id: '',
        song_title: '',
        task: 'Add',


        isLoadingArtistList: false,
        allArtist: [],
        search: {
            'text': ''
        },
        pagination: {
            'current_page': 1
        },
        sort_field: 'name',
        sort_order: 'asc',
      }
  },
  mounted () {
    this.getArtistList()
  },
  props: ['songs'],
  watch: {

  },
  components: {
    Loading
  },
  methods: {
    sortArtistList (sortBy) {
      if(this.sort_field != sortBy) {
          this.sort_order = 'asc'
      } else {
        if(this.sort_order == 'asc')
          this.sort_order = 'desc'
        else
          this.sort_order = 'asc'
      }
      this.sort_field = sortBy
      this.getArtistList()
    },
    getArtistList (param='') {
        if(param == 'search')
            var pageno = 1
        else
            var pageno = this.pagination.current_page

        this.isLoadingArtistList = true
        const data = {
            search: this.search.text,
            page: pageno,
            sort_order: this.sort_order,
            sort_field: this.sort_field,
        }
        axios({
            method: 'get',
            url: '/artists',
            params: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencode',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => {
          this.isLoadingArtistList = false
          this.allArtist = res.data.data.data
          this.pagination = res.data.pagination
          this.aws_url = res.data.aws_url
        })
        .catch(error => {
          console.log(error)
          Swal.fire({
            title: 'Error!',
            text: 'Something went wrong',
            type: 'error'
          })
        })
    },
    deleteArtist (id) {
      const action = '/artists/' + id
      console.log(action)
      this.$refs.delete_form.setAttribute('action', action)
    },
    showDeleteSongForArtist (artist_id, artist_name, music_detail_id, music_title) {
      this.music_detail_id = music_detail_id
      this.song_title = music_title
      this.artist_id = artist_id
      this.artist_name = artist_name

      $('#deleteSongForArtist').modal('show');
    },
    removeSong () {
      $('#deleteSongForArtist').modal('hide');
      this.isLoadingRemoveSong = true;

      const data = {
          music_detail_id: this.music_detail_id,
          artist_id: this.artist_id
      }
      axios.post('/remove-artist-to-song', data)
      .then(res => {
          this.isLoadingRemoveSong = false
          this.submitting = false
          //this.reset()
          //window.location.reload()
          this.getArtistList()
      })
      .catch(error => {
          this.isLoadingRemoveSong = false
          this.submitting = false
      })
    },
    getSongList (artist_id, task) {
        if(!artist_id || artist_id < 1) {
          Swal.fire({ title: 'Error!', text: 'Unknown artist.', type: 'error' })
          return false
        }

        $('#selectSongs').modal('show');

        this.isLoadingSongList = true
        this.artist_id = artist_id
        this.task = task

        const data = {
            id: this.artist_id,
            task: this.task
        }
        axios.post('/get-music-list-for-artist', data)
        .then(res => {
          this.isLoadingSongList = false
          this.artist_name = res.data.artist_info.name
          this.allSongs = res.data.all_songs
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
    processMultipleSong () {
        var songArray = [];
        $("input[name='songsel']:checkbox:checked").each(function () {
            songArray.push($(this).val());
        });
        if( songArray.length > 0 ) {
          if ( this.task == 'Add' || (this.task == 'Remove' && confirm('Are you sure you want to remove those songs?')) ) {
              $('#selectSongs').modal('hide');
              this.isLoadingArtistList = true

              const data = {
                  songs: songArray,
                  artist_id: this.artist_id,
                  task: this.task,
              }

              axios.post('/process-multiple-songs-to-artist', data)
              .then(res => {
                  this.isLoadingArtistList = false
                  this.submitting = false
                  this.reset()
                  //window.location.reload()
                  this.getArtistList()
              })
              .catch(error => {
                  this.isLoadingArtistList = false
                  this.submitting = false
              })
          }
        }
        else {
            Swal.fire({
              title: 'Error!',
              text: 'Please select a song.',
              type: 'error'
            })
        }
    },
    reset () {
        this.isReset = true
        this.allSongs = []
        this.artist_id = ''
        this.artist_name = ''
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

          if (this.searchsong) {
              songs = this.allSongs.filter((song)=>{
                  return song.title.toLowerCase().includes(this.searchsong);
              });
          } else {
              songs = this.allSongs;
          }

          return songs.sort(compare);
      }
  }
}
