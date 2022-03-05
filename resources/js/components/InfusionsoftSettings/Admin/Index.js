import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {

  data () {
      return {
        isLoadingInfusionsoftProductList: false,
        allInfusionsoftProducts: [],
        id: '',
        salestax:'',
        salestax_error: false,
      }
  },
  mounted() {
    this.getInfusionsoftProducts ()
  },
  watch: {

  },
  components: {
    Loading
  },
  watch: {
    salestax () {
      if (!this.isReset) {
        this.salestax_error = this.salestax ? false : true
      } else {
        this.salestax_error = false
      }
    }
  },
  methods: {

    showHideProduct (selected_id) {
        this.id = selected_id
        if( this.id < 1 ) {
          Swal.fire({ title: 'Error!', text: 'Unknow product.', type: 'error' })
          return false;
        }

        this.isLoadingInfusionsoftProductList = true
        const data = {
            id: this.id,
        }
        axios.post('/hide-product', data)
        .then(res => {
            //this.all_results = res.data
            this.isLoadingInfusionsoftProductList = false
            this.allInfusionsoftProducts = res.data.data
        })
        .catch(error => {
          Swal.fire({
            title: 'Error!',
            text: 'Something went wrong',
            type: 'error'
          })
        })
    },

    getInfusionsoftProducts () {
        this.isLoadingInfusionsoftProductList = true
        const data = {
        }
        axios({
            method: 'get',
            url: '/infusionsoft-settings',
            params: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencode',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => {
          this.isLoadingInfusionsoftProductList = false
          this.allInfusionsoftProducts = res.data.data
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

    editSalesTax(selected_id, sales_tax) {
      this.id = selected_id
      this.salestax = sales_tax
      if( this.id < 1 ) {
        Swal.fire({ title: 'Error!', text: 'Unknow product.', type: 'error' })
        return false;
      }
    },

    saveSalesTax () {
        if (this.validateSalesTax()) {

            this.isLoadingInfusionsoftProductList = true
            const data = {
                id: this.id,
                salestax: this.salestax,
            }
            axios({
                method: 'post',
                url: '/save-sales-tax',
                params: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencode',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => {
              this.isLoadingInfusionsoftProductList = false
              this.allInfusionsoftProducts = res.data.data
              $('#editTaxModal').modal('hide');
            })
            .catch(error => {
              console.log(error)
              Swal.fire({
                title: 'Error!',
                text: 'Something went wrong',
                type: 'error'
              })
            })

        }
    },
    validateSalesTax () {
      this.salestax_error = this.salestax ? false : true
      if (!this.salestax_error) {
        return true
      } else {
        return false
      }
    }


  }
}
