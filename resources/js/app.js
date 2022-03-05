/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

Vue.component('admin-create-video-component', require('./components/Video/Admin/Create.js').default);
Vue.component('admin-index-video-component', require('./components/Video/Admin/Index.js').default);
Vue.component('admin-edit-video-component', require('./components/Video/Admin/Edit.js').default);

Vue.component('admin-create-video-category-component', require('./components/VideoCategory/Admin/Create.js').default);
Vue.component('admin-index-video-category-component', require('./components/VideoCategory/Admin/Index.js').default);
Vue.component('admin-edit-video-category-component', require('./components/VideoCategory/Admin/Edit.js').default);

Vue.component('admin-create-album-component', require('./components/Album/Admin/Create.js').default);
Vue.component('admin-index-album-component', require('./components/Album/Admin/Index.js').default);
Vue.component('admin-edit-album-component', require('./components/Album/Admin/Edit.js').default);
Vue.component('admin-show-album-component', require('./components/Album/Admin/Show.js').default);
Vue.component('admin-newshow-album-component', require('./components/Album/Admin/Newshow.js').default);

Vue.component('admin-create-music-component', require('./components/Music/Admin/Create.js').default);
Vue.component('admin-index-music-component', require('./components/Music/Admin/Index.js').default);
Vue.component('admin-edit-music-component', require('./components/Music/Admin/Edit.js').default);
Vue.component('admin-show-music-component', require('./components/Music/Admin/Show.js').default);

Vue.component('admin-create-instrumental-component', require('./components/Instrumental/Admin/Create.js').default);
Vue.component('admin-index-instrumental-component', require('./components/Instrumental/Admin/Index.js').default);
Vue.component('admin-edit-instrumental-component', require('./components/Instrumental/Admin/Edit.js').default);

Vue.component('admin-create-podcast-component', require('./components/Podcast/Admin/Create.js').default);
Vue.component('admin-index-podcast-component', require('./components/Podcast/Admin/Index.js').default);
Vue.component('admin-edit-podcast-component', require('./components/Podcast/Admin/Edit.js').default);

Vue.component('admin-index-artist-component', require('./components/Artist/Admin/Index.js').default);
Vue.component('admin-create-artist-component', require('./components/Artist/Admin/Create.js').default);
Vue.component('admin-edit-artist-component', require('./components/Artist/Admin/Edit.js').default);

Vue.component('admin-index-author-component', require('./components/Author/Admin/Index.js').default);
Vue.component('admin-create-author-component', require('./components/Author/Admin/Create.js').default);
Vue.component('admin-edit-author-component', require('./components/Author/Admin/Edit.js').default);

Vue.component('admin-index-sheet-music-component', require('./components/SheetMusic/Admin/Index.js').default);
Vue.component('admin-create-sheet-music-component', require('./components/SheetMusic/Admin/Create.js').default);
Vue.component('admin-edit-sheet-music-component', require('./components/SheetMusic/Admin/Edit.js').default);

Vue.component('admin-index-subscriber-metrics-component', require('./components/SubscriberMetrics/Admin/Index.js').default);
Vue.component('admin-subscriber-list-component', require('./components/SubscriberMetrics/Admin/Subscriberlist.js').default);
Vue.component('admin-index-infusionsoft-settings-products-component', require('./components/InfusionsoftSettings/Admin/Index.js').default);
Vue.component('admin-index-infusionsoft-settings-promotions-component', require('./components/InfusionsoftSettings/Admin/Promotion.js').default);
Vue.component('admin-index-settings-component', require('./components/Settings/Admin/Index.js').default);

Vue.component('admin-index-products-component', require('./components/Products/Admin/Index.js').default);
Vue.component('admin-create-product-component', require('./components/Products/Admin/Create.js').default);
Vue.component('admin-edit-product-component', require('./components/Products/Admin/Edit.js').default);

Vue.component('admin-index-nmipayment-component', require('./components/Nmipayments/Admin/Index.js').default);
Vue.component('admin-index-tansaction-component', require('./components/Nmitransactions/Admin/Index.js').default);

Vue.component('admin-index-apploginhistory-component', require('./components/Apploginhistory/Admin/Index.js').default);
Vue.component('admin-index-appactivitylog-component', require('./components/Appactivitylog/Admin/Index.js').default);
Vue.component('admin-index-userscancelled-component', require('./components/Userscancelled/Admin/Index.js').default);

// agent-side

Vue.component('index-video-component', require('./components/Video/Index.js').default);
Vue.component('index-song-component', require('./components/Music/Index.js').default);
Vue.component('index-sheet-music-component', require('./components/SheetMusic/Index.js').default);
Vue.component('index-instrumental-component', require('./components/Instrumental/Index.js').default);
Vue.component('index-podcast-component', require('./components/Podcast/Index.js').default);

Vue.component('nmi-user-settings-component', require('./components/Nmiusersettings/Index.js').default);

Vue.component('pagination', require('./components/PaginationComponent.vue').default);
Vue.component('searched', require('./components/SearchComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
