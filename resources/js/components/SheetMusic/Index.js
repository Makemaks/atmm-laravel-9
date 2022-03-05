export default {
    data: () => ({
        sheets: [],
        sheets_next_page_url: null,
        page: 1,
        loadingMoreSheets: false,
        searchSheets: '',
        expectedLastSlideCount: null,
        perPage: 30,
        perSlide: 6,
        totalRecord: null,
    }),
    mounted () {
        this.getSheetMusics()
    },
    methods: {
        loadMoreSheets () {
            this.loadingMoreSheets = true;
            this.page++;
            this.getSheetMusics()
        },
        doSearchSheets () {
            this.sheets_next_page_url = null
            this.page = 1
            this.loadingMoreSheets = false
            this.getSheetMusics()
        },
        getSheetMusics () {
            const data = {
                page: this.page,
                perPage: this.perPage,
            }
            if (this.searchSheets) {
                this.$set(data, 'search', this.searchSheets)
            }
            axios({
                method: 'get',
                url: '/get-sheet-musics',
                params: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencode',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => {

                this.expectedLastSlideCount = res.data.last_page;
                this.sheets_next_page_url = res.data.next_page_url;
                this.totalRecord = res.data.total;

                if (!this.loadingMoreSheets) {
                    this.sheets = res.data.data;
                } else {
                    $('.carousel').css({
                        visibility: 'hidden',
                    })
                    var loaded = res.data.data;
                    this.sheets = this.sheets.concat(loaded);
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

                    })
                }

                if(this.loadingMoreSheets) {
                    this.loadingMoreSheets = false;

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
        showSheetMusicModal(title, file) {

            $('#sheetMusicBlockModal .modal-body object').remove();

            const obj = document.createElement('object');
            const $obj = $(obj);
            $obj.css({
                width: '100%',
                height: '500px'
            })
            $obj.attr('type', 'application/pdf');
            $obj.attr('frameborder', '0');
            $obj.attr('data', `${file}#view=Fit&zoom=page-fit`);

            $('#sheetMusicBlockModal .modal-body').append($obj);

            $('#sheetMusicBlockModal .modal-title').text(title);

            $('#sheetMusicBlockModal').modal({
                keyboard: false,
                backdrop: 'static'
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
                    this.loadMoreSheets();
                } else {
                    carousel.slick('slickNext');
                }
            })
        }
    }
}
