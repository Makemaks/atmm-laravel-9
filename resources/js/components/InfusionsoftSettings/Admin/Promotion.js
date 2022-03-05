import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {

  data () {
      return {
        isLoadingInfusionsoftPromotionList: false,
        allInfusionsoftPromotions: [],
        id: '',
      }
  },
  mounted() {
    this.getInfusionsoftPromotions ()
  },
  watch: {

  },
  components: {
    Loading
  },
  methods: {

    getInfusionsoftPromotions () {
        this.isLoadingInfusionsoftPromotionList = true
        const data = {
        }
        axios({
            method: 'get',
            url: '/infusionsoft-settings-promotions',
            params: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencode',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => {
          this.isLoadingInfusionsoftPromotionList = false
          this.allInfusionsoftPromotions = res.data
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
