<template>
    <nav role="navigation" aria-label="pagination">
        <ul class="pagination">
            <li><a @click.prevent="changePage(1)" :disabled="pagination.current_page <= 1" alt="First" title="First">&laquo</a></li>
            <li><a @click.prevent="changePage(pagination.current_page - 1)" :disabled="pagination.current_page <= 1" alt="Previous" title="Previous">&lsaquo;</a></li>
            <li v-for="page in pages" :class="isCurrentPage(page) ? 'page-item active is-current' : 'page-item'" >
                <a class="pagination-link" :class="isCurrentPage(page) ? 'is-current' : ''" @click.prevent="changePage(page)">{{ page }}</a>
            </li>
            <li><a @click.prevent="changePage(pagination.current_page + 1)" :disabled="pagination.current_page >= pagination.last_page" alt="Next" title="Next">&rsaquo;</a></li>
            <li><a @click.prevent="changePage(pagination.last_page)" :disabled="pagination.current_page >= pagination.last_page" alt="Last" title="Last">&raquo;</a></li>
        </ul>
    </nav>
</template>
<style>
    .pagination {
        cursor: pointer;
    }
</style>

<script>
    export default {
        props: ['pagination', 'offset'],
        methods: {
            isCurrentPage(page) {
                return this.pagination.current_page === page;
            },
            changePage(page) {
                if (page > this.pagination.last_page) {
                    page = this.pagination.last_page;
                }
                this.pagination.current_page = page;
                this.$emit('paginate');
            }
        },
        computed: {
            pages() {
                let pages = [];
                let from = this.pagination.current_page - Math.floor(this.offset / 2);
                if (from < 1) {
                    from = 1;
                }
                let to = from + this.offset - 1;
                if (to > this.pagination.last_page) {
                    to = this.pagination.last_page;
                }
                while (from <= to) {
                    pages.push(from);
                    from++;
                }
                return pages;
            }
        }
    }
</script>
