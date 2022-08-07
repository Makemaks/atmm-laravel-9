import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import moment from 'moment';

Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('YYYY-MM-DD hh:mm')
    }
});

export default {

  data () {
      return {
        isLoadingSubscriberList: false,
        isLoadingAllTransaction: false,
        allTransactions: [],
        selected_email: '',

        allSubscribers: [],
        search: {
            'text': ''
        },
        pagination: {
            'current_page': 1
        },
        sort_field: 'id',
        sort_order: 'desc',
        all_subscription_status: [],
        select_subscription_status: '',
        total_rows: 0,
      }
  },
  mounted() {
    this.getSubscriberList();
  },
  watch: {

  },
  components: {
    Loading,
  },
  methods: {
    sortSubscriberList (sortBy) {
      if(this.sort_field != sortBy) {
          this.sort_order = 'asc'
      } else {
        if(this.sort_order == 'asc')
          this.sort_order = 'desc'
        else
          this.sort_order = 'asc'
      }
      this.sort_field = sortBy
      this.getSubscriberList()
    },
      deleteSubscriber (id) {
        $('#exampleModalCenter').modal('show');
        const action = '/subscriber-metrics/' + id
        this.$refs.delete_form.setAttribute('action', action)
      },
      getAllNMITransactionByUser (email) {
          console.log(email)
          $('#showAllNMITransactionByUser').modal('show');

          this.isLoadingAllTransaction = true
          const data = {
              email: email,
          }
          axios({
              method: 'get',
              url: '/get-nmitransactions-by-user',
              params: data,
              headers: {
                  'Content-Type': 'application/x-www-form-urlencode',
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                  'Accept': 'application/json'
              }
          })
          .then(res => {
              this.isLoadingAllTransaction = false
              //this.allTransactions = res.data
              this.allTransactions = res.data.items
              this.selected_email = res.data.email
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

      filterBySubscriberList () {
        this.getSubscriberList('search')
      },
      getSubscriberList (param='') {
          if(param == 'search')
              var pageno = 1
          else
              var pageno = this.pagination.current_page

          this.isLoadingSubscriberList = true
          const data = {
              search: this.search.text,
              page: pageno,
              sort_order: this.sort_order,
              sort_field: this.sort_field,
              select_subscription_status: this.select_subscription_status,
          }
          axios({
              method: 'get',
              url: '/get-all-subscribers',
              params: data,
              headers: {
                  'Content-Type': 'application/x-www-form-urlencode',
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                  'Accept': 'application/json'
              }
          })
          .then(res => {
            this.isLoadingSubscriberList = false
            this.allSubscribers = res.data.data.data
            this.pagination = res.data.pagination
            this.all_subscription_status = res.data.all_subscription_status

            this.total_rows = res.data.pagination.total
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
