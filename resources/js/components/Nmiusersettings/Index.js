import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  data () {
      return {
        email: '',
        password: '',
        new_password: '',
        confirm_password: '',
      }
  },
  mounted() {

  },
  watch: {

  },
  components: {
    Loading,
  },
  methods: {


    changePassword () {
        this.isLoading = true
        const data = {
            email: this.email,
            date_end: this.date_end
        }
        axios.post('/update_credential', data)
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


  }
}
