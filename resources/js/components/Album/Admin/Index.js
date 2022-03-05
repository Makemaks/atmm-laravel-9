import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import VueExpandableImage from 'vue-expandable-image'
import draggable from 'vuedraggable'

export default {
  data () {
      return {
        submitting: false,
        isReset: false,
        album_id: '',
        album_name: '',
        isLoadingSongList: false,
        isLoadingAlbumList: false,
        isLoadingRemoveSong: false,
        allSongs: [],
        searchsong: '',
        sort: 'ASC',
        music_detail_id: '',
        song_title: '',
        task: 'Add',

        enabled: true,
        draggablesongLists: [],
        dragging: false,
        songArray:[],


        isLoadingAlbumList: false,
        allAlbum: [],
        search: {
            'text': ''
        },
        pagination: {
            'current_page': 1
        },
        sort_field: 'album',
        sort_order: 'asc',

      }
  },
  mounted() {
    this.getAlbumList()

    // for Expandable Image
    const viewportMeta = document.createElement('meta')
    viewportMeta.name = 'viewport'
    viewportMeta.content = 'width=device-width, initial-scale=1'
    document.head.appendChild(viewportMeta)
  },
  watch: {

  },
  components: {
    Loading,
    draggable,
  },
  order: 0,
  methods: {
    sortAlbumList (sortBy) {
      if(this.sort_field != sortBy) {
          this.sort_order = 'asc'
      } else {
        if(this.sort_order == 'asc')
          this.sort_order = 'desc'
        else
          this.sort_order = 'asc'
      }
      this.sort_field = sortBy
      this.getAlbumList()
    },
    getAlbumList (param='') {
        if(param == 'search')
            var pageno = 1
        else
            var pageno = this.pagination.current_page

        this.isLoadingAlbumList = true
        const data = {
            search: this.search.text,
            page: pageno,
            sort_order: this.sort_order,
            sort_field: this.sort_field,
        }
        axios({
            method: 'get',
            url: '/albums',
            params: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencode',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => {
          this.isLoadingAlbumList = false
          this.allAlbum = res.data.data.data
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

    deleteAlbum (id) {
      const action = '/albums/' + id
      this.$refs.delete_form.setAttribute('action', action)
    },
    showDeleteSongForAlbum (album_id, album_name, music_detail_id, music_title) {
      this.music_detail_id = music_detail_id
      this.song_title = music_title
      this.album_id = album_id
      this.album_name = album_name

      $('#deleteSongForAlbum').modal('show');
    },
    removeSong () {
      $('#deleteSongForAlbum').modal('hide');
      this.isLoadingRemoveSong = true;

      const data = {
          music_detail_id: this.music_detail_id,
          band_album_id: this.album_id
      }
      axios.post('/remove-music-to-band-album', data)
      .then(res => {
          this.isLoadingRemoveSong = false
          this.submitting = false
          this.getAlbumList()

          //this.getSongList(this.album_id,'Remove')
          //this.reset()
          //window.location.reload()
      })
      .catch(error => {
          this.isLoadingRemoveSong = false
          this.submitting = false
      })
    },
    getSongList (album_id, task) {
        if(!album_id || album_id < 1) {
          Swal.fire({ title: 'Error!', text: 'Unknown artist.', type: 'error' })
          return false
        }

        $('#selectSongs').modal('show');

        this.isLoadingSongList = true
        this.album_id = album_id
        this.task = task

        const data = {
            id: this.album_id,
            task: this.task
        }
        axios.post('/get-music-list-for-album', data)
        .then(res => {
          this.isLoadingSongList = false
          this.album_name = res.data.band_album_info.album
          this.allSongs = res.data.all_songs

          this.draggablesongLists = res.data.all_songs
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
    draggableSortTrackSequence (e) {
      this.songArray = this.draggablesongLists
      this.task = 'Sort'
      this.processMultipleSong()
    },
    processMultipleSong () {
        var songArray = [];
        $("input[name='songsel']:checkbox:checked").each(function () {
            songArray.push($(this).val());
        });

        if(this.task != 'Sort')
          this.songArray = songArray
        else
          this.songArray = this.draggablesongLists

        if( this.songArray.length > 0 ) {
          if ( this.task == 'Sort' || this.task == 'Add' || (this.task == 'Remove' && confirm('Are you sure you want to remove those songs?')) ) {

              if(this.task != 'Sort')
                $('#selectSongs').modal('hide')

              this.isLoadingAlbumList = true
              this.isLoadingSongList = true
              const data = {
                  songs: this.songArray,
                  album_id: this.album_id,
                  task: this.task,
              }

              axios.post('/process-multiple-songs-to-album', data)
              .then(res => {
                  this.isLoadingAlbumList = false
                  this.submitting = false
                  this.isLoadingSongList = false

                  if(this.task != 'Sort') {
                    this.reset()
                    //window.location.reload()
                    //this.getAlbumList()
                  }
                  this.getAlbumList()
              })
              .catch(error => {
                  this.isLoadingAlbumList = false
                  this.submitting = false

                  Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong',
                    type: 'error'
                  })
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
        this.album_id = ''
        this.album_name = ''
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
