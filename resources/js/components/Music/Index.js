import 'aplayer/dist/APlayer.min.css';
import RawAlbumAPlayer from 'aplayer';
import RawSongAPlayer from 'aplayer';


export default {
    data: () => ({
        //isSongsActive: false,
        isSongsActive: false,
        songs: {},
        searchSongs: '',
        searchAlbums: '',
        albums: [],
        play_id: '',
        isPlaying: false,
        sortAlbums: 1,
        //sortSongs: '',
        sortSongs: 3,
        songs_current_page: 1,
        albums_current_page: 1,
        songs_next_page_url: null,
        albums_next_page_url: null,
        loadingMoreSongs: false,
        loadingMoreAlbums: false,
        selectedAlbum: '',
        albumSongs: [],
        isAlbumSongPlaying: false,
        album_play_id: '',
        carouselCurrentSlide: 0,
        song_id: '',
        scrolledToBottom: false,
        musicList: [],
        init_music: {},
        aplayer_autoplay:true,
        aplayer_showLrc:true,
        aplayer_shuffle: false,
        aplayer_mutex:true,
        aplayer_loop:'all',
        aplayer_order:'list',
        albumMusicList: [],
        albumInitMusic: {},
        song_per_page: 20,
        PlayIndex: 0,
    }),
    mounted () {
        this.getSongs()
        this.getAlbums()
        this.scroll()
        //this.hideAPlayeButtonsPopover()
    },
    components: {
      //Aplayer
    },
    watch: {
        sortSongs () {
            this.songs_next_page_url = null
            this.songs_current_page = 1
            this.loadingMoreSongs = false
            this.getSongs()
        },
        sortAlbums () {
            this.albums_next_page_url = null
            this.albums_current_page = 1
            this.loadingMoreAlbums = false
            this.getAlbums()
        }
    },
    methods: {
        loadMoreSongs () {
            this.loadingMoreSongs = true
            this.getSongs()
        },
        doSearchSongs () {
            this.songs_next_page_url = null
            this.songs_current_page = 1
            this.loadingMoreSongs = false
            this.getSongs()
        },
        scroll () {
            window.onscroll = () => {
                if(document.documentElement.scrollTop + document.documentElement.offsetHeight === document.documentElement.scrollHeight) {
                    this.scrolledToBottom = true
                    if(this.isSongsActive) {
                        //this.loadMoreSongs()
                    }
                }
            }
        },
        getSongsViaTab() {
            $('#currentaplayer_selected').val('songlist_aplayer');
            $('.musicsong_popover').popover('hide'); // for the fork aplyer (emiecsviphp/APlayer)
            this.songs_current_page = 0
            this.getSongs ('viatab')
        },
        getSongs (param='') {
            var first_current_song_index = 0
            var data = {
                s_fields: 'title'
            }
            if (this.loadingMoreSongs) {
                //if (param != 'viatab') {
                  this.songs_current_page++
                //}
            }
            //if (param == 'viatab') {
                //this.songs_current_page = 1
            //}

            this.$set(data, 'page', this.songs_current_page)
            if (this.searchSongs) {
                this.$set(data,'search', this.searchSongs)
            }
            if (this.sortSongs) {
                this.$set(data, 'sortBy', this.sortSongs)
            }
            axios({
                method: 'get',
                params: data,
                url: '/music-detail',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencode',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                this.song_per_page = res.data.paginate_data.per_page
                if (!this.loadingMoreSongs) {
                    //this.songs = res.data.data
                    //this.songs_next_page_url = res.data.next_page_url
                    this.songs = res.data.paginate_data.data
                    this.songs_next_page_url = res.data.paginate_data.next_page_url


                    this.musicList = []
                    for (let i=0; i<this.songs.length; i+=1) {
                       if( this.songs[i].band_albums.length > 0 ) {
                         var album_name = this.songs[i].band_albums[0].album
                       } else {
                         var album_name = ''
                       }
                       this.musicList.push({
                          music_id: this.songs[i].id,
                          title: this.songs[i].title,
                          artist: album_name,
                          src: res.data.aws_url + this.songs[i].audio,
                          pic: res.data.aws_url + this.songs[i].thumbnail,

                          url: res.data.aws_url + this.songs[i].audio,
                          cover: res.data.aws_url + this.songs[i].thumbnail
                        })
                    }

                } else {
                    //var loaded = res.data.data
                    //this.songs_next_page_url = res.data.next_page_url
                    var loaded = res.data.paginate_data.data
                    this.songs_next_page_url = res.data.paginate_data.next_page_url
                    this.songs = this.songs.concat(loaded)


                    for (let i=0; i<loaded.length; i+=1) {
                        if( loaded[i].band_albums.length > 0 ) {
                          var album_name = loaded[i].band_albums[0].album
                        } else {
                          var album_name = ''
                        }
                       this.musicList.push({
                          music_id: loaded[i].id,
                          title: loaded[i].title,
                          artist: album_name,
                          src: res.data.aws_url + loaded[i].audio,
                          pic: res.data.aws_url + loaded[i].thumbnail,

                          url: res.data.aws_url + loaded[i].audio,
                          cover: res.data.aws_url + loaded[i].thumbnail
                        })
                    }

                    first_current_song_index = (this.musicList.length - res.data.paginate_data.per_page)
                }

                this.loadingMoreSongs = false

                const song_list_aplayer = new RawSongAPlayer({
                    container: document.getElementById('songlist_aplayer'),
                    loop: 'all',
                    order: 'list',
                    autoplay: true,
                    listMaxHeight:'-',
                    audio: this.musicList,
                })

                console.log(this.musicList);

                song_list_aplayer.list.switch(first_current_song_index)

                for (let i=0; i < this.musicList.length; i+=1) {
                    $(document).on('click', '#btn_backward_musicsongid_'+i, function() {
                      $('.musicsong_popover').popover('hide');
                      var repeat_song_index = parseInt( $('#repeat_song_index').val() );
                      if( repeat_song_index > -1 ) {
                        var play_song_index = repeat_song_index - 1;
                        song_list_aplayer.list.switch(play_song_index)
                        $('#repeat_song_index').val(-1);
                      } else {
                        song_list_aplayer.skipBack();
                        song_list_aplayer.play();
                      }
                    });

                    $(document).on('click', '#btn_play_musicsongid_'+i, function() {
                      $('.musicsong_popover').popover('hide');
                      var current_musicsongid_n = $('#current_musicsongid_n').val();
                      if( current_musicsongid_n == i ) {
                        if(song_list_aplayer.audio.paused)
                          song_list_aplayer.play();
                        else
                          song_list_aplayer.pause();
                      } else {
                        song_list_aplayer.list.switch(i);
                        song_list_aplayer.play();
                        $('#current_musicsongid_n').val(i);
                      }
                      $('#repeat_song_index').val(-1);
                    });

                    $(document).on('click', '#btn_forward_musicsongid_'+i, function() {
                      $('.musicsong_popover').popover('hide');
                      var repeat_song_index = parseInt( $('#repeat_song_index').val() );
                      if( repeat_song_index > -1 ) {
                        var play_song_index = repeat_song_index + 1;
                        song_list_aplayer.list.switch(play_song_index);
                        $('#repeat_song_index').val(-1);
                      } else {
                        song_list_aplayer.skipForward();
                        song_list_aplayer.play();
                      }
                    });

                    $(document).on('click', '#btn_loop_musicsongid_'+i, function() {
                      $('.musicsong_popover').popover('hide')
                      song_list_aplayer.list.switch(i)
                      $('#repeat_song_index').val(i)
                    });

                    $(document).on('mouseover', '#btn_volume_musicsongid_'+i, function() {
                      var current_volume = $('#current_volume').val()
                      $('#for_volume_'+i).html('<input id="popover_volume_'+i+'" value="'+current_volume+'" class="popover_volume" type="range" min="0" max="1" step="0.1">')
                    });

                    $(document).on('click', '#popover_volume_'+i, function() {
                      var set_volume = $(this).val()
                      $('#current_volume').val(set_volume)
                      $('.popover_volume').val(set_volume)
                      song_list_aplayer.volume(set_volume, true)
                    });


                }

                song_list_aplayer.on('play', function () {
                  var current_index = song_list_aplayer.list.index
                  this.song_id = song_list_aplayer.list.audios[current_index].music_id
                  axios.post('/subscriber-metrics', {song_id: this.song_id})
                })

                song_list_aplayer.on('ended', function () {
                  var repeat_song_index = parseInt( $('#repeat_song_index').val() );
                  if( repeat_song_index > -1 ) {
                    song_list_aplayer.list.switch(repeat_song_index)
                    $('#repeat_song_index').val(repeat_song_index)
                  }
                })

                song_list_aplayer.on('canplay', function () {
                  //console.log('canplay')
                  //song_list_aplayer.play() // making sure twhen selecting a song it will auto play
                })

                //$(document).on('mouseleave', '#songlist_aplayer .aplayer-list', function() {
                  //$('.musicsong_popover').popover('hide');
                //});


            })
            .catch(error => {
                /*
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong',
                    type: 'error'
                })
                */
            })
        },
        hideAPlayeButtonsPopover() {
          /*
          $(document).on('mouseleave', '#album_aplayer .aplayer-list', function() {
            var current_musicsongid_n = $('#current_musicsongid_n').val();
            //$('#musicsongid_'+current_musicsongid_n).popover('hide');
          });

          $(document).on('mouseleave', '#songlist_aplayer .aplayer-list', function() {
            var current_musicsongid_n = $('#current_musicsongid_n').val();
            //$('#musicsongid_'+current_musicsongid_n).popover('hide');
          });
          */
          /*
          $(document).on('mouseleave', '#songlist_aplayer .aplayer-list', function() {
            $('.musicsong_popover').popover('hide');
          });

          $(document).on('mouseleave', '#album_aplayer .aplayer-list', function() {
            $('.musicsong_popover').popover('hide');
          });
          */

        },
        vueAPlayerPlay() {
          setTimeout(function(){
              document.getElementsByClassName("aplayer-play")[0].click()
          }, 1000)
        },
        loadMoreAlbums () {
            this.loadingMoreAlbums = true
            this.getAlbums()
        },
        doSearchAlbums () {
            this.albums_next_page_url = null
            this.albums_current_page = 1
            this.loadingMoreAlbums = false
            this.getAlbums()
        },
        getAlbums () {

            var data = {
                s_fields: 'band_name,album,description,release_date'
            }
            if (this.loadingMoreAlbums) {
                this.albums_current_page++
            }
            this.$set(data, 'page', this.albums_current_page)
            if (this.searchAlbums) {
                this.$set(data,'search', this.searchAlbums)
            }
            if (this.sortAlbums) {
                this.$set(data, 'sortBy', this.sortAlbums)
            }
            axios({
                method: 'get',
                url: '/get-albums',
                params: data,
                config: {
                    headers: {
                      'Content-Type': 'multipart/form-data',
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                      'Accept': 'application/json'
                  }
                }
            })
            .then(res => {
                if (!this.loadingMoreAlbums) {
                    this.albums = res.data.data
                    this.albums_next_page_url = res.data.next_page_url
                } else {
                    var loaded = res.data.data
                    this.albums_next_page_url = res.data.next_page_url
                    this.albums = this.albums.concat(loaded)
                }
                this.loadingMoreAlbums = false

                this.setCarouselSlide();

            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong',
                    type: 'error'
                })
            })
        },
        selectAlbum (album) {
            this.selectedAlbum = Object.assign({}, album)
            const data = {
                album_id: album.id
            }
            axios({
                method: 'get',
                url: '/get-album-songs',
                params: data
            })
            .then(res => {
                this.albumSongs = res.data.music_details

                if( res.data.album != '' ) {
                  var albumname = res.data.album
                } else {
                  var albumname = ''
                }
                this.albumMusicList = []
                for (let i=0; i<this.albumSongs.length; i+=1) {
                   this.albumMusicList.push({
                      music_id: this.albumSongs[i].id,
                      title: this.albumSongs[i].title,
                      artist: albumname,
                      src: res.data.aws_url + this.albumSongs[i].audio, // for vue-aplaye
                      pic: res.data.aws_url + this.albumSongs[i].thumbnail, // for vue-aplaye

                      url: res.data.aws_url + this.albumSongs[i].audio,
                      cover: res.data.aws_url + this.albumSongs[i].thumbnail
                    })
                }

                console.log(this.albumMusicList);
                this.albumInitMusic = this.albumMusicList[0];
                this.albumMusicList = this.albumMusicList;

                const album_aplayer = new RawAlbumAPlayer({
                    container: document.getElementById('album_aplayer'),
                    loop: 'all',
                    order: 'list',
                    autoplay: true,
                    listMaxHeight:'-',
                    audio: this.albumMusicList,
                })


                for (let i=0; i < this.albumMusicList.length; i+=1) {
                    $(document).on('click', '#btn_backward_musicsongid_'+i, function() {
                      $('.musicsong_popover').popover('hide');
                      var repeat_song_index = parseInt( $('#repeat_song_index').val() );
                      if( repeat_song_index > -1 ) {
                        var play_song_index = repeat_song_index - 1;
                        album_aplayer.list.switch(play_song_index)
                        $('#repeat_song_index').val(-1);
                      } else {
                        album_aplayer.skipBack();
                        album_aplayer.play();
                      }
                    });

                    $(document).on('click', '#btn_play_musicsongid_'+i, function() {
                      $('.musicsong_popover').popover('hide');

                      var current_musicsongid_n = $('#current_musicsongid_n').val();
                      if( current_musicsongid_n == i ) {
                        if(album_aplayer.audio.paused)
                          album_aplayer.play();
                        else
                          album_aplayer.pause();
                      } else {
                        album_aplayer.list.switch(i);
                        album_aplayer.play();
                        $('#current_musicsongid_n').val(i);
                      }
                      $('#repeat_song_index').val(-1);

                    });

                    $(document).on('click', '#btn_forward_musicsongid_'+i, function() {
                      $('.musicsong_popover').popover('hide');
                      var repeat_song_index = parseInt( $('#repeat_song_index').val() );
                      if( repeat_song_index > -1 ) {
                        var play_song_index = repeat_song_index + 1;
                        album_aplayer.list.switch(play_song_index);
                        $('#repeat_song_index').val(-1);
                      } else {
                        album_aplayer.skipForward();
                        album_aplayer.play();
                      }
                    });

                    $(document).on('click', '#btn_loop_musicsongid_'+i, function() {
                      $('.musicsong_popover').popover('hide')
                      this.repeat_song_index = i
                      album_aplayer.list.switch(i)
                      $('#repeat_song_index').val(i)
                    });

                    $(document).on('mouseover', '#btn_volume_musicsongid_'+i, function() {
                      var current_volume = $('#current_volume').val()
                      $('#for_volume_'+i).html('<input id="popover_volume_'+i+'" value="'+current_volume+'" class="popover_volume" type="range" min="0" max="1" step="0.1">')
                    });

                    $(document).on('click', '#popover_volume_'+i, function() {
                      var set_volume = $(this).val()
                      $('#current_volume').val(set_volume)
                      $('.popover_volume').val(set_volume)
                      album_aplayer.volume(set_volume, true)
                    });

                }

                album_aplayer.on('play', function () {
                  var current_index = album_aplayer.list.index
                  this.song_id = album_aplayer.list.audios[current_index].music_id
                  axios.post('/subscriber-metrics', {song_id: this.song_id})
                });

                album_aplayer.on('ended', function () {
                  var repeat_song_index = parseInt( $('#repeat_song_index').val() );
                  if( repeat_song_index > -1 ) {
                    album_aplayer.list.switch(repeat_song_index)
                    $('#repeat_song_index').val(repeat_song_index)
                  }
                })

                album_aplayer.on('canplay', function () {
                  //console.log('canplay')
                  //album_aplayer.play() // making sure twhen selecting a song it will auto play
                })


                //$(document).on('mouseleave', '#album_aplayer .aplayer-list', function() {
                  //$('.musicsong_popover').popover('hide');
                //});

                /*
                //initialization
                var cnt_musicsongpopover = $('.musicsong_popover').length;
                if( cnt_musicsongpopover > 0 ) {
                  for( var j=0; j<cnt_musicsongpopover; j++ ) {
                    $('#musicsongid_'+j).on('shown.bs.popover', function () {
                      //$('#btn_play_musicsongid_'+j).attr('class','btn btn_default btn-sm btncustomrawplayer fa fa-play');
                      console.log( $.now() + ' - current_musicsongid_n = ' +  $('#current_musicsongid_n').val() );
                      if( $('#current_musicsongid_n').val() == j ) {
                        //$('#btn_play_musicsongid_'+j).attr('class','btn btn_default btn-sm btncustomrawplayer fa fa-pause');
                      } else {
                        //$('#btn_play_musicsongid_'+j).attr('class','btn btn_default btn-sm btncustomrawplayer fa fa-play');
                      }
                    });
                  }
                }
                */


            })
            .catch(err => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong',
                    type: 'error'
                })
            })
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
                this.sendStreamInfo()
            }
        },
        playAlbumSong (id) {
            this.song_id = id
            var prev_playing = document.getElementById('albumSong' + this.album_play_id);
             if (this.album_play_id === id && this.isAlbumSongPlaying) {
                this.isAlbumSongPlaying = false
                prev_playing.pause()
            } else if (this.album_play_id === id && !this.isAlbumSongPlaying) {
                this.isAlbumSongPlaying = true
                prev_playing.play()
            } else {
                if (this.album_play_id) {
                    prev_playing.currentTime = 0
                    prev_playing.pause()
                }
                this.album_play_id = id
                this.isAlbumSongPlaying = true
                var song = document.getElementById('albumSong' + id)
                song.play()
                this.sendStreamInfo()
            }
        },
        getAlbumList() {
            $('#currentaplayer_selected').val('album_aplayer');

            this.selectedAlbum = '';
            this.album_play_id = '';

            this.setCarouselSlide();
            setTimeout(() => {
                console.log(this.carouselCurrentSlide);
                $('.carousel').slick('slickGoTo', this.carouselCurrentSlide );
            }, 100)

        },
        setCarouselSlide() {
            $('.musicsong_popover').popover('hide'); // for the fork aplyer (emiecsviphp/APlayer)

            const vueInstance = this;
            if ($('.carousel').hasClass('slick-initialized')) {
                $('.carousel').slick('unslick');
                setTimeout(() => {
                    $('.carousel').slick({
                        slidesPerRow: 4,
                        rows: 2,
                        //prevArrow: '<a class="slick-prev"><i class="fas fa-angle-left"></i></a>',
                        //nextArrow: '<a class="slick-next"><i class="fas fa-angle-right"></i></a>',
                        prevArrow: '<a class="slick-prev"> <div style="margin-top: -90px;"> <i class="fas fa-angle-left"></i></div> <div style="margin-top: 110px;"><i class="fas fa-angle-left"></i> </div> </a>',
                        nextArrow: '<a class="slick-next"> <div style="margin-top: -90px;"> <i class="fas fa-angle-right"></i></div> <div style="margin-top: 110px;"><i class="fas fa-angle-right"></i> </div> </a>',
                        responsive: [
                            {
                                breakpoint: 767,
                                settings: {
                                    slidesPerRow: 1,
                                    rows: 1,
                                }
                            }
                        ]
                    });

                    $(".carousel .slick-slide > div" ).addClass('row');

                    $('.carousel').on('afterChange', function(event, slick, currentSlide){
                        vueInstance.carouselCurrentSlide = currentSlide;
                      });
                }, 100);

            } else {
                $((document) => {
                    $('.carousel').slick({
                        slidesPerRow: 4,
                        rows: 2,
                        //prevArrow: '<a class="slick-prev"><i class="fas fa-angle-left"></i></a>',
                        //nextArrow: '<a class="slick-next"><i class="fas fa-angle-right"></i></a>',
                        prevArrow: '<a class="slick-prev"> <div style="margin-top: -90px;"> <i class="fas fa-angle-left"></i></div> <div style="margin-top: 110px;"><i class="fas fa-angle-left"></i> </div> </a>',
                        nextArrow: '<a class="slick-next"> <div style="margin-top: -90px;"> <i class="fas fa-angle-right"></i></div> <div style="margin-top: 110px;"><i class="fas fa-angle-right"></i> </div> </a>',
                        responsive: [
                            {
                                breakpoint: 767,
                                settings: {
                                    slidesPerRow: 1,
                                    rows: 1,
                                }
                            }
                        ]
                    });

                    $(".carousel .slick-slide > div" ).addClass('row');

                    $('.carousel').on('afterChange', function(event, slick, currentSlide){
                        vueInstance.carouselCurrentSlide = currentSlide;
                    });
                })
            }
        },
        shuffleSongs() {
            const shuffle = (array) => {
                var currentIndex = array.length, temporaryValue, randomIndex;

                // While there remain elements to shuffle...
                while (0 !== currentIndex) {

                    // Pick a remaining element...
                    randomIndex = Math.floor(Math.random() * currentIndex);
                    currentIndex -= 1;

                    // And swap it with the current element.
                    temporaryValue = array[currentIndex];
                    array[currentIndex] = array[randomIndex];
                    array[randomIndex] = temporaryValue;
                }

                return array;
            }

            this.songs = shuffle(this.songs);
            this.musicList = shuffle(this.musicList);
            this.init_music = this.musicList[0];
            this.$forceUpdate();
        },
        sendStreamInfo () {
            const data = {
                song_id: this.song_id,
            }
            axios.post('/subscriber-metrics', data)
            .then(res => {

            })
            .catch(err => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong',
                    type: 'error'
                })
            })
        }
    },
    computed: {

      currentAlbumSongs () {
        let currentAlbumSongs
        currentAlbumSongs = this.albumMusicList
        return currentAlbumSongs
      },

      currentSongs () {
        /*
        let currentSongs
        currentSongs = this.musicList

        let first_current_song_index = (currentSongs.length - this.song_per_page)
        this.init_music = this.musicList[first_current_song_index]
        this.$forceUpdate()

        return currentSongs
        */
      }

    }
}
