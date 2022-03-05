import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {

  data () {
      return {
          submitting: false,
          date_start: '',
          date_end: '',
          all_results: [],
          all_details_results: [],
          total_royalty_due: '',
          isLoadingDetails: false,
          isLoading: false,
          fullPage: false,
          isLoadingIPAddressDetails: false,
          ip_address_details: '',
          ip_address: '',
      }
  },
  mounted() {
    this.setDefaultDate ()
    this.getRoyalyInfoList ()
  },
  watch: {

  },
  components: {
    Loading
  },
  methods: {

    setDefaultDate () {
      var today = new Date();
      today.setMonth(today.getMonth() - 1);
      var dd = String(today.getDate()).padStart(2, '0')
      var mm = String(today.getMonth() + 1).padStart(2, '0')
      var yyyy = today.getFullYear();
      today = yyyy + '-' + mm + '-' + dd
      this.date_start = today

      var today = new Date()
      today.setDate(today.getDate() + 1);
      var dd = String(today.getDate()).padStart(2, '0')
      var mm = String(today.getMonth() + 1).padStart(2, '0')
      var yyyy = today.getFullYear();

      today = yyyy + '-' + mm + '-' + dd
      this.date_end = today
    },
    getRoyalyInfoList () {
        if( this.date_start > this.date_end ) {
          Swal.fire({ title: 'Error!', text: 'Start date cant be greater than End date', type: 'error' })
          return false;
        }

        this.isLoading = true
        const data = {
            date_start: this.date_start,
            date_end: this.date_end
        }
        axios.post('/get-royalty-info-list', data)
        .then(res => {
            this.isLoading = false
            this.all_results = res.data
        })
        .catch(error => {
          Swal.fire({
            title: 'Error!',
            text: 'Something went wrong',
            type: 'error'
          })
        })
    },
    showSubscriberMetricsDetails (author_id) { 
        if(!author_id || author_id < 1) {
          Swal.fire({ title: 'Error!', text: 'Unknown author.', type: 'error' })
          return false;
        }

        this.isLoadingDetails = true
        const data = {
            author_id: author_id,
            date_start: this.date_start,
            date_end: this.date_end
        }
        axios.post('/get-royalty-details', data)
        .then(res => {
            this.isLoadingDetails = false 
            this.all_details_results = res.data.items
            this.total_royalty_due = res.data.total_royalty_due
        })
        .catch(error => {
          Swal.fire({
            title: 'Error!',
            text: 'Something went wrong',
            type: 'error'
          })
        })
    },
    showIPAdressDetails (ip) { 
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
