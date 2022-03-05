import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

export default {
  data () {
      return {
        isLoadingTransactionList: false,
        allTransactions: [],
        search: {
            'text': ''
        },
        pagination: {
            'current_page': 1
        },
        sort_field: 'created_at',
        sort_order: 'desc',
        isLoadingTransactionInfo: false,
        transactionInfo: '',
        transactionInfoAction: '',
      }
  },
  mounted() {
    this.getTransactionList ()
  },
  watch: {

  },
  components: {
    Loading,
  },
  methods: {
    getFullTransactionInfo (transactionid) {
        console.log(transactionid)
        $('#showFullTransaction').modal({
          keyboard: false,
          backdrop: 'static'
        });
        this.isLoadingTransactionInfo = true
        axios({
            method: 'get',
            url: '/nmipayments/' + transactionid,
            params: '',
            headers: {
                'Content-Type': 'application/x-www-form-urlencode',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => {
            this.isLoadingTransactionInfo = false
            this.transactionInfo = res.data.transaction
            this.transactionInfoAction = this.transactionInfo.action
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
    sortTransactionList (sortBy) {
      if(this.sort_field != sortBy) {
          this.sort_order = 'asc'
      } else {
        if(this.sort_order == 'asc')
          this.sort_order = 'desc'
        else
          this.sort_order = 'asc'
      }
      this.sort_field = sortBy
      this.getTransactionList()
    },
    getTransactionList (param='') {
        if(param == 'search')
            var pageno = 1
        else
            var pageno = this.pagination.current_page

        this.isLoadingTransactionList = true
        const data = {
            search: this.search.text,
            page: pageno,
            sort_order: this.sort_order,
            sort_field: this.sort_field,
        }
        axios({
            method: 'get',
            url: '/nmipayments',
            params: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencode',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => {
          this.isLoadingTransactionList = false
          this.allTransactions = res.data.data.data
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
        this.allTransactions.forEach((song) => {
            tran.checked = true;
        });
        this.$forceUpdate();
    },
    unCheckAll () {
        this.allTransactions.forEach((song) => {
            tran.checked = false;
        });
        this.$forceUpdate();
    },

  }
}
