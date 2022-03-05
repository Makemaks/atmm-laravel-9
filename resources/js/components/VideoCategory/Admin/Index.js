import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  data () {
      return {
        isLoadingVideoCategoryList: false,
        allVideoCategories: [],
        search: {
            'text': ''
        },
        pagination: {
            'current_page': 1
        },
        sort_field: 'description',
        sort_order: 'asc',
      }
  },
  mounted() {
    this.getVideoCategoryList ()
  },
  watch: {

  },
  components: {
    Loading,
  },
  methods: {
    deleteVideoCategory (id, videos_count) {
      if( videos_count > 0 ) {
          Swal.fire({
            title: 'Error!',
            text: 'Please remove first all the videos under this category.',
            type: 'error'
          })
      } else {
        $('#exampleModalCenter').modal({
          keyboard: false,
          backdrop: 'static'
        });

        const action = '/video-categories/' + id
        this.$refs.delete_form.setAttribute('action', action)
      }
    },
    sortVideoCategoryList (sortBy) {
      if(this.sort_field != sortBy) {
          this.sort_order = 'asc'
      } else {
        if(this.sort_order == 'asc')
          this.sort_order = 'desc'
        else
          this.sort_order = 'asc'
      }
      this.sort_field = sortBy
      this.getVideoCategoryList()
    },
    getVideoCategoryList (param='') {
        if(param == 'search')
            var pageno = 1
        else
            var pageno = this.pagination.current_page

        this.isLoadingVideoCategoryList = true
        const data = {
            search: this.search.text,
            page: pageno,
            sort_order: this.sort_order,
            sort_field: this.sort_field,
        }
        axios({
            method: 'get',
            url: '/video-categories',
            params: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencode',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => {
          //console.log(res.data);
          this.isLoadingVideoCategoryList = false
          this.allVideoCategories = res.data.data.data
          this.pagination = res.data.pagination;
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

    checkAll () {
        this.allVideoCategories.forEach((song) => {
            video_category.checked = true;
        });
        this.$forceUpdate();
    },
    unCheckAll () {
        this.allVideoCategories.forEach((song) => {
            video_category.checked = false;
        });
        this.$forceUpdate();
    },


  }
}
