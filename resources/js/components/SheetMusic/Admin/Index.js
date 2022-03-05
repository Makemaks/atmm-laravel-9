import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  data () {
      return {
        isLoadingSheetMusicList: false,
        allSheetMusics: [],
        pagination: {
            'current_page': 1
        },
        search: {
            'text': ''
        },
        sort_field: 'title',
        sort_order: 'asc',
        aws_url: '',
      }
  },
  mounted() {
    this.getSheetMusicList ()
  },
  watch: {

  },
  components: {
    Loading,
  },
  methods: {
    deleteSheetMusic (id) {
      const action = '/sheet_musics/' + id
      this.$refs.delete_form.setAttribute('action', action)
    },
    sortSheetMusicList (sortBy) {
      if(this.sort_field != sortBy) {
          this.sort_order = 'asc'
      } else {
        if(this.sort_order == 'asc')
          this.sort_order = 'desc'
        else
          this.sort_order = 'asc'
      }
      this.sort_field = sortBy
      this.getSheetMusicList()
    },
    getSheetMusicList (param='') {
        if(param == 'search')
            var pageno = 1
        else
            var pageno = this.pagination.current_page

        this.isLoadingSheetMusicList = true
        const data = {
            search: this.search.text,
            page: pageno,
            sort_order: this.sort_order,
            sort_field: this.sort_field,
        }
        axios({
            method: 'get',
            url: '/sheet_musics',
            params: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencode',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => {
          //console.log(res.data);
          this.isLoadingSheetMusicList = false
          this.allSheetMusics = res.data.data.data
          this.pagination = res.data.pagination;
          this.aws_url = res.data.aws_url;
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

  }
}
