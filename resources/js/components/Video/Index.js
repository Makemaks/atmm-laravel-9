import { Carousel, Slide } from 'vue-carousel'

export default {
    data: () => ({
        search: '',
        fireSides: {},
        memoryLane: {},
        songwriterSundaySchool: {},
        miscellaneousMichael: {},
    }),
    mounted () {
        this.getVideos();

        $('#videoModal').on('hide.bs.modal', () => {
            const videoPlayer = $('#videoModal video')[0];
            videoPlayer.pause();
        });
    },
    methods: {
        doSearch () {
            this.getVideos()
        },
        getVideos () {
            const data = {}
            if (this.search) {
                this.$set(data, 'search', this.search)
            }

            axios({
                method: 'get',
                url: '/get-videos',
                params: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencode',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                this.fireSides = res.data.firesides;
                this.memoryLane = res.data.memory_lane;
                this.songwriterSundaySchool = res.data.songwriter_sunday_school;
                this.miscellaneousMichael = res.data.miscellaneous_michael;

                this.setDuration();

                if ($('.carousel').hasClass('slick-initialized')) {
                    $('.carousel').slick('unslick');
                    setTimeout(() => {
                        $('.carousel').slick({
                            slidesPerRow: 3,
                            prevArrow: '<a class="slick-prev"><i class="fas fa-angle-left"></i></a>',
                            nextArrow: '<a class="slick-next"><i class="fas fa-angle-right"></i></a>',
                            responsive: [
                                {
                                    breakpoint: 767,
                                    settings: {
                                        slidesPerRow: 1,
                                    }
                                }
                            ]
                        });

                        $(".carousel .slick-slide > div" ).addClass('row');
                    }, 100);

                } else {
                    $((document) => {
                        $('.carousel').slick({
                            slidesPerRow: 3,
                            prevArrow: '<a class="slick-prev"><i class="fas fa-angle-left"></i></a>',
                            nextArrow: '<a class="slick-next"><i class="fas fa-angle-right"></i></a>',
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
                    });
                }

            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong',
                    type: 'error'
                })
            })
        },
        nextPage(url, category) {

            const data = {}
            if (this.search) {
                this.$set(data, 'search', this.search)
            }

            axios({
                method: 'get',
                url,
                params: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencode',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                const newCategoryData = res.data;
                // concat the new data to the current data
                newCategoryData.data = this[category].data.concat(newCategoryData.data);

                this[category] = newCategoryData;
                this.setDuration();

            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Something went wrong',
                    type: 'error'
                })
            })
        },
        setDuration() {
            setTimeout(() => {

                $('.video-container .video:not(.timeset)').each((idx, video) => {
                    // Assume "video" is the video node
                    var i = setInterval(function() {
                        if(video.readyState > 0) {
                            var minutes = parseInt(video.duration / 60, 10);
                            var seconds = parseInt(video.duration % 60);

                            // (Put the minutes and seconds in the display)
                            $(video).next().find('span').text(`${minutes}:${seconds}`);
                            $(video).addClass('timeset')
                            clearInterval(i);
                        }
                    }, 200);
                });
            }, 500);
        },
        showVideoInModal(title, video, poster) {

            const videoPlayer = $('#videoModal video')[0];

            $('#videoModal').modal({
                keyboard: false,
                backdrop: 'static'
            });

            $('#videoModal .modal-title').text(title);

            videoPlayer.src = video;
            videoPlayer.poster = poster;
        }
    },
    components: {
        Carousel,
        Slide
    }
}