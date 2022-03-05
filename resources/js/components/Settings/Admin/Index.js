import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {

  data () {
      return {
        days_trial: '',
        isLoadingDetails: false,
      }
  },
  mounted() {
    this.daysTrial ()
  },
  watch: {

  },
  components: {
    Loading
  },
  methods: {

    daysTrial () {
        this.isLoading = true
        axios.get('/adminsettings/days_trial')
        .then(res => {
            this.isLoading = false
            this.days_trial = res.data.value
        })
        .catch(error => {
          Swal.fire({
            title: 'Error!',
            text: 'Something went wrong',
            type: 'error'
          })
        })
    },

    saveSettings () {
        this.isLoadingDetails = true
        const data = {
            days_trial: this.days_trial
        }
        axios.post('/adminsettings', data)
        .then(res => {
            this.isLoadingDetails = false
            Swal.fire({
              title: 'Success!',
              text: 'Settings successfully saved',
              type: 'Success'
            })
        })
        .catch(error => {
          Swal.fire({
            title: 'Error!',
            text: 'Something went wrong',
            type: 'error'
          })
        })
    }

  }
}
