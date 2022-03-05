export default {
  data () {
    return {
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
  created () {

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
    saveProduct () {
      if (this.validateAddProducts()) {
        const payload = {
          nmi_api_plan_id: this.nmi_api_plan_id,
          product_name: this.product_name,
          product_price: this.product_price,
          sales_tax : this.sales_tax,
        }
        axios.post('/products', payload)
        .then(response => {
          this.reset()
          this.submitting = false

          Swal.fire({
            title: 'Success!',
            text: 'Product successfully added',
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
