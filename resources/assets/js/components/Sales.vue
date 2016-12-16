<style scoped>
    #items-list {
        font-size: 14px;
        min-height: 150px;
        max-height: 70vh;
        overflow-y: auto;
    }

    #receipt {
        font-size: 14px;
    }

    #qtty {
        width: 60px;
    }

    #sprice {
        width: 120px;
    }

    ::-webkit-scrollbar {
        width: 9px;
    }
 
    ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
        border-radius: 7px;
    }
 
    ::-webkit-scrollbar-thumb {
        border-radius: 7px;
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5); 
    }

    #bullets {
        margin-right: 30px;
    }

    #subtotals {
        font-size: 14px;
    }

    hr {

    }

    #pment {
        font-size: 14px;
    }

    #ttal {
        border-left: 2px solid grey;
    }

    #stot {
        border-top: 2px solid grey;
    }

    #tot {
       
    }

    #list {
        font-weight: regular;

    }


</style>

<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                    <h2 class="box-title" v-if="items.length === 0">Items Listing</h2>
                        <form role="form" onsubmit="return false" v-if="items.length > 0">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-search" v-show="search_icon"></i> <i class="fa fa-spinner fa-spin" v-show="search_spinner"></i> </div>
                                <input type="text" name="" class="form-control" v-model="searchForm.search" @keyup.enter="searchItem">
                                <div class="input-group-addon"><i class="fa fa-search" v-show="search_icon"></i> <i class="fa fa-spinner fa-spin" v-show="search_spinner"></i> </div>
                            </div>
                        </form>
                    </div>

                    <div class="panel-body" id="items-list">
                        <div class="well" v-if="items.length === 0">
                            No Items within your database.
                        </div>
                        <table class="table table-condensed" v-if="items.length > 0">
                            <tbody>
                                <tr v-for="(item, index) in items">
                                    <td>{{ item.item_name }}</td>
                                    <td id="list">{{ item.quantity }}</td>
                                    <td><button class="btn btn-primary btn-sm" @click="push(item)"><i class="fa fa-share"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title">Sales Register</h2>
                        <span class="pull-right" id="">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    &#149;&#149;&#149;
                                </button>
                                <ul class="dropdown-menu">
                                    <li v-if="sales_items.length > 0"><a href="#" @click.prevent="addCustomer"><i class="fa fa-plus"></i> Add Customer</a></li>
                                    <li v-if="sales_items.length > 0"><a href="#" @click.prevent="suspendSale"><i class="fa fa-coffee"></i> Suspend Sale</a></li>
                                    <li v-if="suspended_sales > 0"><a href="#"> <i class="fa fa-mail-reply"></i> Recall Sale</a></li>
                                    <li><a href="#" @click.prevent="showRecentSales"><i class="fa fa-window-restore"></i> Show Recent Sales</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#"><i class="fa fa-location-arrow"></i> LookUp Receipt</a></li>
                                </ul>
                            </div>
                            <button type="button" class="btn btn-danger disabled" v-if="sales_items.length === 0"><i class="fa fa-close"></i> Cancel Sale</button>
                            <button type="button" class="btn btn-danger" @click="cancelSale" v-if="sales_items.length > 0"><i class="fa fa-close"></i> Cancel Sale</button>
                        </span>
                        
                    </div>
                    <div class="panel-body" id="receipt">

                    <div class="alert alert-success" v-if="alert">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" @click="clear">&times;</a>
                        <strong>Success!</strong> {{ alert_message }}.
                    </div>
                    
                        <form role="form" v-if="sales_items.length > 0">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Customer</label>
                                        <div class="col-sm-9">
                                            <select v-model="salesForm.customer_id" class="form-control">
                                                <option v-for="(customer, index) in customers" v-bind:value="customer.id">{{ index + 1 }} {{ customer.name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Form Errors -->
                                    <div class="alert alert-danger" v-if="salesForm.errors.length > 0">
                                        <ul>
                                            <li v-for="error in salesForm.errors">
                                                {{ error }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <table id="receipt" class="table table-condensed" v-if="sales_items.length > 0">
                            <thead>
                                <tr>
                                    <th>##</th>
                                    <th>Item</th>
                                    <th>Selling Price</th>
                                    <th>Quantity</th>
                                    <th>Totals(Kshs)</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(temp, index) in sales_items">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ temp.item_name }}</td>
                                    <td><input id="sprice" type="text" class="form-control" v-model="temp.sprice" @change="updateItem(temp)" pattern="[0-9]"></td>
                                    <td><input id="qtty" type="text" class="form-control" v-model="temp.qtty" @change="updateItem(temp)" pattern="[0-9]"></td>
                                    <td>{{ temp.total }}</td>
                                    <td><button class="btn btn-danger btn-sm" @click="pop(temp)"><i class="fa fa-close"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row" v-if="sales_items.length > 0">
                            <div class="col-md-6">
                                <div class="well" id="pment">
                                    <form class="form-horizontal" onsubmit="return false">
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label">Payment Options</label>
                                            <div class="col-sm-6">
                                                <select class="form-control" v-model="salesForm.payment_type">
                                                    <option>Cash</option>
                                                    <option>Credit</option>
                                                </select>
                            
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label">Amount Tendered</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" v-model="salesForm.amount_credited" @change="confirmTenderedAmount">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-6 control-label">Balance Due</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" v-model="salesForm.balance" readonly>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="well" id="subtotals">
                                    <table class="table table-hover">
                                        <tr>
                                            <th>Sub totals:</th>
                                            <td>{{ subtotal }}</td>
                                        </tr>
                                    </table>
                                    <button type="button" class="btn btn-primary btn-block" @click="completeSale">
                                        <i class="fa fa-shopping-cart" v-show="cart_icon"></i>
                                        <i class="fa fa-spin fa-spinner" v-show="cart_spinner"></i> Complete Sale
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Create New Customer Modal -->
        <div class="modal fade" id="create-customer-modal">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header modal-header-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Create Customer</h4>
                <span class="" style="">Fields with the asterisk(*) are required!</span>
              </div>
              <div class="modal-body">
                <!-- Form Errors -->
                <div class="alert alert-danger" v-if="addCust.errors.length > 0">
                    <p><strong>Whoops!</strong> Something went wrong!</p>
                    <br>
                    <ul>
                        <li v-for="error in addCust.errors">
                            {{ error }}
                        </li>
                    </ul>
                </div>
                <form class="form-horizontal" role="form" onsubmit="return false">
                    <div class="form-group">
                        <label for="name" class="control-label col-sm-2">Name *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="customer_name" @keyup.enter="storeCustomer" v-model="addCust.name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="control-label col-sm-2">Email </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" @keyup.enter="storeCustomer" v-model="addCust.email">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="control-label col-sm-2">Phone </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" @keyup.enter="storeCustomer" v-model="addCust.phone_number">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="control-label col-sm-2">Address </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" @keyup.enter="storeCustomer" v-model="addCust.address">
                        </div>
                    </div>

                </form>
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="storeCustomer">
                    <i class="fa fa-plus" v-show="add_icon"></i>
                    <i class="fa fa-spinner fa-spin" v-show="add_spinner"></i> Add Customer
                </button>
               
                
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->



        <!-- Show recent Sales -->
        <div class="modal fade" id="show-recent-sales">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header modal-header-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Recent Sales</h4>
              </div>
              <div class="modal-body">
                <div class="well" v-if="recent_sales.length === 0">
                    No Recent Sales.
                </div>
                <table class="table table-condensed table-hover" v-if="recent_sales.length > 0">
                    <thead>
                        <tr>
                            <th>Rcpt No:</th>
                            <th>Customer</th>
                            <th>Payment</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(sale, index) in recent_sales">
                            <td>{{ sale.id }}</td>
                            <td>{{ sale.customer.name }}</td>
                            <td>{{ sale.payment_type }}</td>
                            <td>Kshs {{ sale.total }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm " @click="receiptOptions(sale.id)"><i class="fa fa-print"></i> </button>
                                
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
               
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Print Receipt Modal -->
        <div class="modal fade" id="print-receipt-modal">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Print Receipt</h4>
              </div>
              <div class="modal-body">
                
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Receipt Options: <strong></strong></h3>
                            <div class="pull-right">
                                <button type="button" class="btn btn-secondary"><i class="fa fa-print"></i> Print</button>
                                <button type="button" class="btn btn-secondary"><i class="fa fa-file-pdf-o"></i> PDF</button>
                            </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p>Receipt No: <strong>{{ sale_dt.sale.id }}</strong></p>
                                <p>Customer: <strong>{{ sale_dt.customer.name }}</strong></p>
                            </div>
                            <div class="col-md-6">
                                <p>Date: <strong>{{ sale_date }}</strong></p>
                            </div>
                        </div>
                        <div class="">
                            <table class="table table-condensed">
                                <thead>
                                    <th>##</th>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </thead>
                                <tbody>
                                    <tr v-for="(row, index) in sale_dt.items">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ row.item.item_name }}</td>
                                        <td>{{ row.quantity }}</td>
                                        <td id="ttal">{{ row.item_total }}</td>
                                    </tr>
                                    <tr id="stot">
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                        <td class="" id="tot">{{ sale_dt.sale.total }}</td>
                                    </tr>
                                        
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
              </div>

            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


    </div>
</template>

<script>
    export default {
        data() {
            return {
                items: [],
                sitems: [],
                sales_items: [],
                suspended_sales: [],
                recent_sales: [],
                customers: [],
                item: {},
                search_icon: true,
                search_spinner: false,
                cart_icon: true,
                cart_spinner: false,
                subtotal: '',
                alert: false,
                alert_message: '',

                sale_dt: {
                    sale: {},
                    customer: {},
                    user: {},
                    items: []
                },

                searchForm: {
                    search:'',
                    errors: []
                },

                salesForm: {
                    errors: [],
                    items: [],
                    payment_type: 'Cash',
                    customer_id: '',
                    total: '', 
                    amount_credited: 0,
                    balance: ''
                },

                add_icon: true,
                add_spinner: false,
                addCust: {
                    errors: [],
                    name: '',
                    email: '',
                    phone_number: '',
                    address: ''

                }

            }
        },

        mounted() {
            this.prepareComponent();

        },

        computed: {
            sale_date () {
                var date = this.sale_dt.sale.created_at;
                return moment(date).format("ll");
            }
        },

        methods: {
            prepareComponent() {
                this.getStocks();
                this.getSuspendedSales();
                this.getCustomers();

                $('#create-customer-modal').on('shown.bs.modal', () => {
                    $('#customer_name').focus();
                });
            },

            sweetalert(title, message, type = 'success') {
                swal(
                  title,
                  message,
                  type
                )
            },

            /**
             * Get allthe stock items from the database.
             * Store the data in the items[] array.
             */
            getStocks() {
                this.$http.get('/item')
                    .then(response => {
                        this.items = response.data; 
                    })
            },

            /**
             * Get Suspended Sales If any.
             */
            getSuspendedSales() {

            },

            getCustomers() {
                this.$http.get('/customers')
                    .then(response => {
                        this.customers = response.data;
                    })
            },

            addCustomer() {
                this.addCust.errors = [];
                this.addCust.name = '';
                this.addCust.email = '';
                this.addCust.address = '';
                this.addCust.phone_number = '';

                $('#create-customer-modal').modal('show');
            },

            storeCustomer() {
                this.addCust.errors = [];

                // Set The Icons
                this.add_icon = false;
                this.add_spinner = true;

                this.$http.post('/customer', this.addCust)
                    .then(response => {
                        this.getCustomers();

                        this.addCust.name = '';
                        this.addCust.email = '';
                        this.addCust.address = '';
                        this.addCust.phone_number = '';

                        $('#create-customer-modal').modal('hide');
                    })
                    .catch(response => {
                        if (typeof response.data === 'object') {
                            this.addCust.errors = _.flatten(_.toArray(response.data));
                        } else {
                            this.addCust.errors = ['Something went wrong. Please try again.'];
                        }
                    });

                    // Set The Icons
                    this.add_icon = true;
                    this.add_spinner = false;
            },

            suspendSale() {
                this.salesForm.items = this.sales_items;

                this.$http.post('/sale/suspend', this.salesForm)
                    .then(response => {

                        // Clear the sale tab.
                        this.cancelSale();

                        // Trigger the alert
                        this.sweetalert('Sale Suspended', 'The Sale was Completed successfully!');
                    })
            },

            completeSale() {
                this.salesForm.items = this.sales_items;
                this.salesForm.total = this.subtotal;
                this.salesForm.errors = [];

                // Make sure tendered amount isn't more than subtotal
                if (this.salesForm.payment_type === 'Credit') {
                    var subT = parseInt(this.subtotal.replace(/\,/g,""));
                    var amtCredited = parseInt(this.salesForm.amount_credited);
                    if (amtCredited > subT) {
                        this.salesForm.errors = ['Sorry, Amount Tendered can\'t exceed the sale total'];
                        return;
                    }

                    this.confirmTenderedAmount();
                }

                // Set The Icons
                this.cart_icon = false;
                this.cart_spinner = true;

                this.$http.post('/sales', this.salesForm)
                    .then(response => {
                        // Update Items, Customers & Suspended sales.
                        this.searchItem();
                        this.getCustomers();
                        this.getSuspendedSales();

                        this.salesForm.total = '';
                        this.salesForm.items = [];
                        this.salesForm.customer_id = '';
                        this.salesForm.payment_type = 'Cash';
                        this.sales_items = [];
                        this.salesForm.amount_credited = '';
                        this.salesForm.balance = '';

                        // Set The Icons
                        this.cart_icon = true;
                        this.cart_spinner = false;

                        // Trigger the alert
                        this.sweetalert('Sale Completed', 'The Sale was Completed successfully!');

                        // Trigger The modal to allow for printing of the receipt
                        
                        
                    })
                    .catch(response => {
                        // Set The Icons
                        this.cart_icon = true;
                        this.cart_spinner = false;

                        if (response.status === 500) {
                            // Show an alert with info that the user's session has expired
                            // Pop up a modal that allows the user to login.
                            alert('session expired');
                            return;
                        }

                        if (typeof response.data === 'object') {
                            this.salesForm.errors = _.flatten(_.toArray(response.data));
                        } else {
                            this.salesForm.errors = ['Something went wrong. Please try again.'];
                        }
                    });

                
            },

            searchItem() {
                this.search_icon = false;
                this.search_spinner = true;

                this.$http.post('/sales/search', this.searchForm)
                    .then(response => {
                        this.items = response.data;

                        // Set Icons
                        this.search_icon = true;
                        this.search_spinner = false;
                    })
                    .catch(response => {
                        // Set Icons
                        this.search_icon = true;
                        this.search_spinner = false;

                        if (typeof response.data === 'object') {
                            this.searchForm.errors = _.flatten(_.toArray(response.data));
                        } else {
                            this.searchForm.errors = ['Something went wrong. Please try again.'];
                        }
                    });

                // this.search_icon = true;
                // this.icon_spinner = false;
            },

            push(item) {

                item.qtty = 1;
                item.sprice = item.selling_price;
                item.total = item.selling_price;

                // Check if the item is already in the array.
                var val = this.sales_items.indexOf(item);

                if (val != -1) {
                    return;
                }

                // Search the array to make sure the item isn't in there
                var check = this.searchArray(this.sales_items, item.item_name, 'item_name');

                if (check === 'true') {
                    return;
                }
                
                this.sales_items.push(item);
                this.calculateTotals();
            },

            searchArray(array, searchValue, property) {
                for (var i=0; i<array.length; i++) {
                    if (array[i][property] === searchValue) {
                        return 'true';
                    }  
                }
            },

            calculateTotals() {
                var totals = 0;
                for (var i = 0; i < this.sales_items.length; i++) {
                    var value = this.sales_items[i].total;
                    value = value.replace(/\,/g,"");

                    totals += parseInt(value);
                }
                var subt = totals.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") + '.00';
                this.subtotal = subt;

                // Update the balance
                this.calculateBalance();
            },

            updateItem(item) {
                this.salesForm.errors = [];

                var index = this.sales_items.indexOf(item);

                this.validQtty(item.qtty);
                this.validSprice(item.sprice);

                item.qtty = Math.abs(item.qtty);
                item.sprice = Math.abs(item.sprice);
                var total = item.sprice * item.qtty;
                item.total = total.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") + '.00';
                this.sales_items.splice(index, 1, item);

                this.calculateTotals();
            },

            validQtty(val) {
                if (this.checkInteger(val) === false) {
                    this.salesForm.errors = ['Value passed in Quantity is not an integer'];
                    return;
                }
            },

            validSprice(val) {
                if (this.checkInteger(val) === false) {
                    this.salesForm.errors = ['Value passed in Selling Price is not an integer'];
                    return;
                }
            },

            checkInteger(val) {
                var x = parseFloat(val);
                return !isNaN(val) && (x | 0) === x;
            },

            pop(item) {
                var index = this.sales_items.indexOf(item);
                this.sales_items.splice(index, 1);

                this.calculateTotals();
            },

            getRecentSales() {
                this.$http.get('/sales')
                    .then(response => {
                        this.recent_sales = response.data;
                    })
            },

            showRecentSales() {
                this.getRecentSales();

                $('#show-recent-sales').modal('show');
            },

            clear() {

            },

            cancelSale() {
                this.sales_items = [];
                this.alert = false;
                this.alert_message = '';
                this.salesForm.errors = []; 
            },

            receiptOptions(id) {
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
            },

            confirmTenderedAmount() {
                var subT = parseInt(this.subtotal.replace(/\,/g,""));
                var amtCredited = parseInt(this.salesForm.amount_credited);

                // Check if payment is credit
                if (this.salesForm.payment_type === 'Credit') {
                    if (amtCredited > subT) {
                        this.salesForm.errors = ['Sorry, Amount Tendered can\'t exceed the sale total'];
                        return;
                    }
                }
                this.calculateBalance();
            },

            calculateBalance() {
                var subT = parseInt(this.subtotal.replace(/\,/g,""));
                var bal = subT - this.salesForm.amount_credited;

                this.salesForm.balance = bal.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") + '.00';
            }
        }
    }
</script>
