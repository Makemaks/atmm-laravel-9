import 'aplayer/dist/APlayer.min.css';
import RawSongAPlayer from 'aplayer';

export default {
    data: () => ({
        isPodcastsActive: true,
        play_id: '',
        podcast_page: 1,
        audiobook_page: 1,
        search_podcast: '',
        search_audiobook: '',
        //sort_podcast: 'title',
        //sort_audiobook: 'title',
        sort_podcast: 'newest',
        sort_audiobook: 'newest',
        isPlaying: false,
        podcasts: {
            podcast: {},
            audiobook: {},
        },
        podcasts_next_page_url: null,
        loadingMorePodcasts: false,
        scrolledToBottom: false,
        podcastList: [],
        audiobookList: [],
        repeat_song_index: -1, // for the fork aplyer (emiecsviphp/APlayer)
    }),
    mounted () {
        this.getPodcasts('podcast');
        //this.getPodcasts('audiobook');
        this.scroll()
    },
    methods: {
        playPodcast (id) {
            var prev_playing = document.getElementById('podcast' + this.play_id)
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
                var podcast = document.getElementById('podcast' + id)
                podcast.play()
            }
        },
        doSearchPodcast () {
            const type = this.isPodcastsActive ? 'podcast' : 'audiobook';
            this[`${type}_page`] = 1;
            this.getPodcasts(type);
        },
        scroll () {
            window.onscroll = () => {
                if(document.documentElement.scrollTop + document.documentElement.offsetHeight === document.documentElement.scrollHeight) {
                    this.scrolledToBottom = true
                    const type = this.isPodcastsActive ? 'podcast' : 'audiobook';
                    if( this.podcasts[type].next_page_url ) {
                        this.isPodcastsActive ? this.getPodcasts('podcast') : this.getPodcasts('audiobook')
                    }
                }
            }
        },
        getPodcasts (type) {
            //$('.musicsong_popover').popover('hide'); // for the fork aplyer (emiecsviphp/APlayer)

            const data = {
                page: this[`${type}_page`],
                type,
            }

            if(this.isPodcastsActive) {
                if(this.search_podcast) {
                    this.$set(data, 'search', this.search_podcast)
                }

                switch(this.sort_podcast) {
                    case 'title':
                        this.$set(data, 'sortBy', 'title')
                        this.$set(data, 'sortOrder', 'ASC')
                        break;
                    case 'oldest':
                        this.$set(data, 'sortBy', 'date')
                        this.$set(data, 'sortOrder', 'ASC')
                        break;
                    case 'newest':
                        this.$set(data, 'sortBy', 'date')
                        this.$set(data, 'sortOrder', 'DESC')
                        break;
                }

            } else if(!this.isPodcastsActive) {
                if(this.search_audiobook) {
                    this.$set(data, 'search', this.search_audiobook)
                }

                switch(this.sort_audiobook) {
                    case 'title':
                        this.$set(data, 'sortBy', 'title')
                        this.$set(data, 'sortOrder', 'ASC')
                        break;
                    case 'oldest':
                        this.$set(data, 'sortBy', 'date')
                        this.$set(data, 'sortOrder', 'ASC')
                        break;
                    case 'newest':
                        this.$set(data, 'sortBy', 'date')
                        this.$set(data, 'sortOrder', 'DESC')
                        break;
                }
            } else {
                this.$set(data, 'sortBy', 'title');
                this.$set(data, 'sortOrder', 'ASC');
            }

            axios({
                method: 'get',
                url: '/get-podcasts',
                params: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencode',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => {

                const responseData = res.data;

                if(responseData.current_page > 1) {
                    responseData.data = this.podcasts[type].data.concat(responseData.data);
                }
                this.podcasts[type] = responseData;

                if (this.podcasts[type].next_page_url) {
                    this[`${type}_page`]++;
                }

                if(type == 'podcast')
                  var imageThumbnail = '/img/podcasts.png';
                else
                  var imageThumbnail = '/img/audiobook.jpg';

                //var podcast_data = this.podcasts['podcast'].data
                var podcast_data = this.podcasts[`${type}`].data
                this.podcastList = []
                for (let i=0; i < podcast_data.length; i+=1) {
                   this.podcastList.push({
                      music_id: podcast_data[i].id,
                      title: podcast_data[i].title + ' / ' +  podcast_data[i].date,
                      artist: type.capitalize() + 's',
                      src: res.data.aws_url + podcast_data[i].audio,
                      pic: res.data.aws_url + imageThumbnail,

                      url: res.data.aws_url + podcast_data[i].audio,
                      cover: res.data.base_url + imageThumbnail,
                      theme: '#0000'
                    })
                }

                const song_list_aplayer = new RawSongAPlayer({
                    container: document.getElementById('podcast_aplayer'),
                    loop: 'all',
                    order: 'list',
                    autoplay: true,
                    listMaxHeight:'-',
                    audio: this.podcastList,
                })

                song_list_aplayer.list.switch(first_current_song_index)

                for (let i=0; i < this.podcastList.length; i+=1) {
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


    }
}
