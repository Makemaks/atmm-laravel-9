//import moment from 'moment'
export default {
    data: () => ({
        instrumentals: [],
        instrumentals_next_page_url: null,
        page: 1,
        search: '',
        loadingMoreInstrumentals: false,
        selectedInstrumental: '',
        isPlayVideo: false,
        isPlayAudio: false,
        playType: '',
        expectedLastSlideCount: null,
        perPage: 30,
        perSlide: 6,
        totalRecord: null,
        aws_url: '',
    }),
    mounted () {
        if(this.exploreInstrumentals) {
            this.instrumentals = JSON.parse(this.exploreInstrumentals);
        } else {
            // only fetch instrumentals when we're not on explore page
            this.getInstrumentals()
        }
    },
    props: ['exploreInstrumentals'],
    watch: {
        low_key_video () {
            console.log(this.low_key_video)
        }
    },
    methods: {
        formatDate (date) {
          return date;
            //return moment(date).format('YYYY-MM-DD')
        },
        loadMoreInstrumentals () {
            this.loadingMoreInstrumentals = true;
            this.page++;
            this.getInstrumentals()
        },
        doSearch () {
            this.intrumentals_next_page_url = null
            this.intrumentals_current_page = 1
            this.loadingMoreInstrumentals = false
            this.getInstrumentals()
        },
        showModal (instrumental, type) {
            if($('#welcome-video').length) {
                $('#welcome-video')[0].pause();
            }
            this.selectedInstrumental = instrumental
            this.playType = type
        },
        closeModal () {
            this.selectedInstrumental = ''
            this.isPlayAudio = false
            this.isPlayVideo = false
            this.playType = ''
        },
        playVideo () {
            this.isPlayAudio = false
            this.isPlayVideo = true
            setTimeout(() => {
                if (this.playType === 'high') {
                    var vid = document.getElementById('high_key_vid')
                    vid.play()
                } else {
                    var vid = document.getElementById('low_key_vid')
                    vid.play()
                }
            },500)
        },
        playAudio () {
            this.isPlayVideo = false
            this.isPlayAudio = true
            setTimeout(() => {
                if (this.playType === 'high') {
                    var audio = document.getElementById('high_key_audio')
                    audio.play()
                } else {
                    var audio = document.getElementById('low_key_audio')
                    audio.play()
                }
            },500)
        },
        getInstrumentals () {

            const data = {
                page: this.page,
                perPage: this.perPage,
            }
            if (this.search) {
                this.$set(data, 'search', this.search)
            }
            axios({
                method: 'get',
                url: '/get-instrumentals',
                params: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencode',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => {

                this.aws_url = res.data.aws_url
                this.expectedLastSlideCount = res.data.orig.last_page;
                this.instrumentals_next_page_url = res.data.orig.next_page_url;
                this.totalRecord = res.data.orig.total;

                if (!this.loadingMoreInstrumentals) {
                    this.instrumentals = res.data.orig.data
                } else {
                    $('.carousel').css({
                        visibility: 'hidden',
                    })
                    var loaded = res.data.orig.data
                    this.instrumentals = this.instrumentals.concat(loaded)
                }

                if ($('.carousel').hasClass('slick-initialized')) {
                    $('.carousel').slick('unslick');
                    setTimeout(() => {
                        $('.carousel').slick({
                            slidesPerRow: this.perSlide,
                            arrows: false,
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

                        this.afterSlickInit();

                    }, 100);

                } else {
                    $((document) => {
                        $('.carousel').slick({
                            slidesPerRow: this.perSlide,
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

                        this.afterSlickInit();
                    })
                }

                if(this.loadingMoreInstrumentals) {
                    this.loadingMoreInstrumentals = false;

                    setTimeout(() => {
                        $('.carousel').slick('slickGoTo', ((this.page - 1) * Math.ceil(this.perPage / this.perSlide)) - 1);
                        setTimeout(() => {
                            $('.carousel').css({
                                visibility: 'visible',
                            });
                            $('.carousel').slick('slickNext');
                        }, 500);
                    }, 100);
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
        afterSlickInit() {
            $(".carousel .slick-slide > div" ).addClass('row');

            const carousel = $('.carousel');

            carousel.append(`<a class="slick-prev slick-arrow" style=""> <div style="margin-top: -90px;"><i class="fas fa-angle-left"></i></div> <div style="margin-top: 150px;"><i class="fas fa-angle-left"></i></div> </a>
                             <a class="slick-next slick-arrow" style=""> <div style="margin-top: -90px;"><i class="fas fa-angle-right"></i></div> <div style="margin-top: 150px;"><i class="fas fa-angle-right"></i></div> </a>`);

            //carousel.append(`<a class="slick-prev slick-arrow" style=""><i class="fas fa-angle-left"></i></a>
            //            <a class="slick-next slick-arrow" style=""><i class="fas fa-angle-right"></i></a>`);

            let currentSlide = carousel.slick('slickCurrentSlide');
            let slickObj = carousel.slick('getSlick');
            let slideCount = slickObj.slideCount;

            $('.slick-prev').on('click', () => {
                // get the current slide number while the slick prev has not yet been called.
                currentSlide = carousel.slick('slickCurrentSlide');
                console.log(slideCount);
                console.log(Math.ceil(this.totalRecord / this.perSlide));
                if (currentSlide !== 0 || (slideCount === Math.ceil(this.totalRecord / this.perSlide) )) {
                    carousel.slick('slickPrev');
                }
            })

            $('.slick-next').on('click', () => {
                // get the current slide number while the slick next has not yet been called.
                currentSlide = carousel.slick('slickCurrentSlide');
                slickObj = carousel.slick('getSlick');
                slideCount = slickObj.slideCount;

                if ((currentSlide === (slideCount - 1)) && (this.page < this.expectedLastSlideCount)) {
                    this.loadMoreInstrumentals();
                } else {
                    carousel.slick('slickNext');
                }
            })
        }
    }
}
