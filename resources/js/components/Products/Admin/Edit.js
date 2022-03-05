import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  data () {
    return {
      edit_id: '',
      nmi_api_plan_id: '',
      nmiapiplanid_error: false,
      product_name: '',
      productname_error: false,
      product_price: '',
      productprice_error: false,
      sales_tax: '',
      salestax_error: false,
      isReset: false
    }
  },
  mounted() {
    this.getProductByID ()
  },
  components: {
    Loading,
  },
  watch: {
    nmi_api_plan_id () {
      if (!this.isReset) {
        this.nmiapiplanid_error = this.nmi_api_plan_id ? false : true
      } else {
        this.nmiapiplanid_error = false
      }
    },
    product_name () {
      if (!this.isReset) {
        this.productname_error = this.product_name ? false : true
      } else {
        this.productname_error = false
      }
    },
    product_price () {
      if (!this.isReset) {
        this.productprice_error = this.product_price ? false : true
      } else {
        this.productprice_error = false
      }
    },
    sales_tax () {
      if (!this.isReset) {
        this.salestax_error = this.sales_tax ? false : true
      } else {
        this.salestax_error = false
      }
    },

  },
  methods: {
    getProductByID () {
      var id = $('#edit_id').val()
      this.edit_id = id;


      this.isLoadingProductList = true
      axios({
          method: 'get',
          url: '/products/'+id,
          params: '',
          headers: {
              'Content-Type': 'application/x-www-form-urlencode',
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
              'Accept': 'application/json'
          }
      })
      .then(res => {
        this.isLoadingProductList = false
        this.nmi_api_plan_id = res.data.nmi_api_plan_id
        this.product_name = res.data.product_name
        this.product_price = res.data.product_price
        this.sales_tax = res.data.sales_tax
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

    saveProduct () {
      if (this.validateAddProducts()) {
        const payload = {
          nmi_api_plan_id: this.nmi_api_plan_id,
          product_name: this.product_name,
          product_price: this.product_price,
          sales_tax : this.sales_tax,
        }
        axios.put('/products/'+this.edit_id, payload)
        .then(response => {
          this.reset()
          this.submitting = false

          Swal.fire({
            title: 'Success!',
            text: 'Product successfully updated',
            type: 'success'
          })

          location.href="/products"
        })
        .catch(error => {
          Swal.fire({
            title: 'Error!',
            text: 'Something went wrong',
            type: 'error'
          })
          this.submitting = false
        })
      }
    },
    validateAddProducts () {
      this.nmiapiplanid_error = this.nmi_api_plan_id ? false : true
      this.productname_error = this.product_name ? false : true
      this.productprice_error = this.product_price ? false : true
      this.salestax_error = this.sales_tax ? false : true

      if (!this.productname_error && !this.productprice_error && !this.salestax_error && !this.nmiapiplanid_error) {
        return true
      } else {
        return false
      }
    },
    reset () {
      this.isReset = true
      this.product_name = ''
      this.product_price = ''
      this.sales_tax = ''
      this.nmi_api_plan_id = ''
    }
  }
}
