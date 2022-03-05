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
        sources: [],
        conditions: [],
        select_source: '',
        select_condition: '',
      }
  },
  mounted() {
    this.getNMITransactionList ()
  },
  watch: {

  },
  components: {
    Loading,
  },
  methods: {
    getNMIFullTransactionInfo (id) {
        console.log(id)
        $('#showFullTransaction').modal('show');
        this.isLoadingTransactionInfo = true
        axios({
            method: 'get',
            url: '/nmitransactions/' + id,
            params: '',
            headers: {
                'Content-Type': 'application/x-www-form-urlencode',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => {
            this.isLoadingTransactionInfo = false
            this.transactionInfo = res.data
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
      this.getNMITransactionList()
    },
    filterByNMITransactionList () {
      this.getNMITransactionList('search')
    },
    getNMITransactionList (param='') {
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
            select_source: this.select_source,
            select_condition: this.select_condition,
        }
        axios({
            method: 'get',
            url: '/nmitransactions',
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
          this.pagination = res.data.pagination
          this.sources = res.data.sources
          this.conditions = res.data.conditions
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
    capitalize(s) {
        return s[0].toUpperCase() + s.slice(1);
    },

  }
}
