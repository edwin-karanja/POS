
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('dashboard', require('./components/Dashboard.vue'));

Vue.component('stocks', require('./components/Items.vue'));

Vue.component('categories', require('./components/Categories.vue'));

Vue.component('upload-csv', require('./components/UploadCSV.vue'));

Vue.component('profile', require('./components/Profile.vue'));

Vue.component('password', require('./components/Password.vue'));

Vue.component('customer', require('./components/Customer.vue'));

Vue.component('sales', require('./components/Sales.vue'));

// Vue.component('customer-sales', require('./components/CustomerSales.vue'));

const app = new Vue({
    el: '#app',

    data: {
    	today: null,
        sale_dt: {
            sale: {},
            customer: {},
            user: {},
            items: []
        },
    },

    mounted() {
    	setInterval(function() {
                this.getDateTime();
            }.bind(this), 1000);
    },

    methods: {
    	getDateTime() {
    		this.today = moment().format('MMMM Do YYYY, h:mm:ss a');
    	},

        showReceipt(id) {
            this.sale_dt.sale = {};
            this.sale_dt.customer = {};
            this.sale_dt.user = {};
            this.sale_dt.items = [];

            this.$http.get('/sales/' + id)
                .then(response => {
                    this.sale_dt.sale = response.data;
                    this.sale_dt.user = response.data.user;
                    this.sale_dt.customer = response.data.customer;
                    this.sale_dt.items = response.data.items;
                })

            $('#print-receipt-modal').modal('show');
        }
    }
});
