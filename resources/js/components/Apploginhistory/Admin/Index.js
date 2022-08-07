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
        isLoadingAppLoginHistoryList: false,
        sort: 'ASC',
        allAppLoginHistory: [],
        search: {
            'text': ''
        },
        pagination: {
            'current_page': 1
        },
        sort_field: 'id',
        sort_order: 'desc',
        all_device_os: [],
        all_device_version: [],
        all_appLogIn_user: [],
        select_device_os: '',
        select_device_version: '',
        select_appLogIn_user: '',

      }
  },
  mounted() {
    this.getAppLoginHistoryList();
  },
  watch: {

  },
  components: {
    Loading,
  },
  methods: {
    viewDeviceFullInfo(id) {
      $("#deviceFullInfo_"+id).modal('show');
    },

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
      this.getAppLoginHistoryList()
    },

    filterByAppLoginHistoryList () {
      this.getAppLoginHistoryList('search')
    },

    getAppLoginHistoryList (param='') {
        if(param == 'search')
            var pageno = 1
        else
            var pageno = this.pagination.current_page

        this.isLoadingAppLoginHistoryList = true
        const data = {
            search: this.search.text,
            page: pageno,
            sort_order: this.sort_order,
            sort_field: this.sort_field,
            select_device_os: this.select_device_os,
            select_device_version: this.select_device_version,
            select_appLogIn_user: this.select_appLogIn_user,
        }
        axios({
            method: 'get',
            url: '/loginhistory',
            params: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencode',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => {
          this.isLoadingAppLoginHistoryList = false
          this.allAppLoginHistory = res.data.data.data
          this.pagination = res.data.pagination
          this.all_device_os = res.data.all_device_os
          this.all_device_version = res.data.all_device_version
          this.all_appLogIn_user = res.data.all_appLogIn_user
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
