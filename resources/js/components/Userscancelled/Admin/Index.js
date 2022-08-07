import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import JsonTree from 'vue-json-tree'
import moment from 'moment';

Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('YYYY-MM-DD hh:mm')
    }
});
Vue.component('json-tree', JsonTree)

export default {
  data () {
      return {
        isLoadingCancelledUserList: false,
        sort: 'ASC',
        allCancelledUsers: [],
        search: {
            'text': ''
        },
        pagination: {
            'current_page': 1
        },
        sort_field: 'id',
        sort_order: 'desc',

      }
  },
  mounted() {
    this.getCancelledUserList();
  },
  watch: {

  },
  components: {
    Loading,
  },
  methods: {

    getCancelledUserList (param='') {
        if(param == 'search')
            var pageno = 1
        else
            var pageno = this.pagination.current_page

        this.isLoadingCancelledUserList = true
        const data = {
            search: this.search.text,
            page: pageno,
            sort_order: this.sort_order,
            sort_field: this.sort_field,
        }
        axios({
            method: 'get',
            url: '/users-cancelled',
            params: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencode',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => {
          this.isLoadingCancelledUserList = false
          this.allCancelledUsers = res.data.data.data
          this.pagination = res.data.pagination
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
