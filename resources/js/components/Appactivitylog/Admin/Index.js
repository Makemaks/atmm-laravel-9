import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import JsonTree from 'vue-json-tree'
Vue.component('json-tree', JsonTree)

export default {
  data () {
      return {
        isLoadingAppActivityLogList: false,
        sort: 'ASC',
        allAppActivityLog: [],
        search: {
            'text': ''
        },
        pagination: {
            'current_page': 1
        },
        sort_field: 'id',
        sort_order: 'desc',

        users: [],
        select_user: '',

        all_os: [],
        select_os: '',

        all_device_name: [],
        select_device_name: '',

        isLoadingIPAddressDetails: false,
        ip_address_details: '',
        ip_address: '',

      }
  },
  mounted() {
    this.getAppActivityLogList();
  },
  watch: {

  },
  components: {
    Loading,
  },
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
      this.getAppActivityLogList()
    },
    filterByAppActivityLogList () {
      this.getAppActivityLogList('search')
    },
    getAppActivityLogList (param='') {
        if(param == 'search')
            var pageno = 1
        else
            var pageno = this.pagination.current_page

        this.isLoadingAppActivityLogList = true
        const data = {
            search: this.search.text,
            page: pageno,
            sort_order: this.sort_order,
            sort_field: this.sort_field,
            select_user: this.select_user,
            select_os: this.select_os,
            select_device_name: this.select_device_name,
        }
        axios({
            method: 'get',
            url: '/appactivitylog',
            params: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencode',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => {
          this.isLoadingAppActivityLogList = false
          this.allAppActivityLog = res.data.data.data
          this.pagination = res.data.pagination
          this.users = res.data.users
          this.all_os = res.data.all_os
          this.all_device_name = res.data.all_device_name
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

    viewDeviceFullInfo(id) {
      $("#deviceFullInfo_"+id).modal('show')
    },

    capitalize(s) {
        return s[0].toUpperCase() + s.slice(1);
    },

    viewIPAdressDetails (ip) {
      if(!ip) {
        Swal.fire({ title: 'Error!', text: 'Unknown ip address.', type: 'error' })
        return false;
      }

      this.ip_address = ip
      this.isLoadingIPAddressDetails = true
      const data = {
          ip: ip,
      }
      axios.post('/get-ip-details', data)
      .then(res => {
          this.isLoadingIPAddressDetails = false
          this.ip_address_details = res.data
      })
      .catch(error => {
        Swal.fire({
          title: 'Error!',
          text: 'Something went wrong',
          type: 'error'
        })
      })
    },

  }

}
