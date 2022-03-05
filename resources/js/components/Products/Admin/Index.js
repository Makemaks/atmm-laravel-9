import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  data () {
      return {
        isLoadingProductList: false,
        allProducts: [],
        search: {
            'text': ''
        },
        pagination: {
            'current_page': 1
        },
        sort_field: 'product_name',
        sort_order: 'asc',
      }
  },
  mounted() {
    this.getProductList ()
  },
  watch: {

  },
  components: {
    Loading,
  },
  methods: {
    deleteProduct (id, payment_count) {
      if(payment_count > 0) {
        Swal.fire({
          title: 'Error!',
          text: 'There are payments made on this Plan. Please remove first all the payments under this Plan',
          type: 'error'
        })
      } else {
        $('#exampleModalCenter').modal({
          keyboard: false,
          backdrop: 'static'
        });
        const action = '/products/' + id
        this.$refs.delete_form.setAttribute('action', action)
      }
    },
    sortProductList (sortBy) {
      if(this.sort_field != sortBy) {
          this.sort_order = 'asc'
      } else {
        if(this.sort_order == 'asc')
          this.sort_order = 'desc'
        else
          this.sort_order = 'asc'
      }
      this.sort_field = sortBy
      this.getProductList()
    },
    getProductList (param='') {
        if(param == 'search')
            var pageno = 1
        else
            var pageno = this.pagination.current_page

        this.isLoadingProductList = true
        const data = {
            search: this.search.text,
            page: pageno,
            sort_order: this.sort_order,
            sort_field: this.sort_field,
        }
        axios({
            method: 'get',
            url: '/products',
            params: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencode',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => {
          this.isLoadingProductList = false
          this.allProducts = res.data.data.data
          this.pagination = res.data.pagination;
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

    checkAll () {
        this.allProducts.forEach((song) => {
            prod.checked = true;
        });
        this.$forceUpdate();
    },
    unCheckAll () {
        this.allProducts.forEach((song) => {
            prod.checked = false;
        });
        this.$forceUpdate();
    },

  }
}
