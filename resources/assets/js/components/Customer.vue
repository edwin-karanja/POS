<style scoped>
    
</style>

<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title">Customer Management</h2> <button class="btn btn-success pull-right" @click="addCustomer"><i class="fa fa-plus"></i> Add Customer</button></div>

                    <div class="panel-body">
                        <div class="alert alert-info" v-if="alert">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" @click="clear">&times;</a>
                            <strong>Info!</strong> {{ alert_message }}.
                        </div>

                        <div class="alert alert-success" v-if="alert_success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close" @click="clear">&times;</a>
                            <strong>Success!</strong> {{ alert_message }}.
                        </div>

                        <div class="well" v-if="customers.length === 0">
                            No Customers created. <a href="#" @click.prevent="addCustomer">Create One?</a>
                        </div>
                        <div class="" v-if="customers.length > 0">
                            <table id="customers" class="table table-condensed table-hover">
                                <thead>
                                    <tr>
                                        <th>##</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(customer, index) in customers">
                                        <td>{{ index+ 1 }}</td>
                                        <td id="bold">{{ customer.name }}</td>
                                        <td>{{ customer.email }}</td>
                                        <td>{{ customer.phone_number }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-default" @click="showCustomer(customer)"><i class="fa fa-history"></i> History</button>
                                            <!-- <a href="/sales/.{{ customer.id }}"><i class="fa fa-history"></i> History</a> -->
                                            <button class="btn btn-sm btn-info" @click="edit(customer)" title="Edit Customer" data-toggle="tooltip" data-placement="top"><i class="fa fa-pencil-square-o"></i></button>
                                            <button class="btn btn-sm btn-danger" @click="deleteConfirm(customer)" title="Delete Customer" data-toggle="tooltip" data-placement="top"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
                            <div class="alert alert-danger" v-if="addForm.errors.length > 0">
                                <p><strong>Whoops!</strong> Something went wrong!</p>
                                <br>
                                <ul>
                                    <li v-for="error in addForm.errors">
                                        {{ error }}
                                    </li>
                                </ul>
                            </div>
                            <form class="form-horizontal" role="form" onsubmit="return false">
                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-2">Name *</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" @keyup.enter="store" v-model="addForm.name">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-2">Email </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" @keyup.enter="store" v-model="addForm.email">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-2">Phone </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" @keyup.enter="store" v-model="addForm.phone_number">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-2">Address </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" @keyup.enter="store" v-model="addForm.address">
                                    </div>
                                </div>

                            </form>
                            
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" @click="store">
                                <i class="fa fa-plus" v-show="add_icon"></i>
                                <i class="fa fa-spinner fa-spin" v-show="button_spinner"></i> Add Customer
                            </button>
                           
                            
                          </div>
                        </div><!-- /.modal-content -->
                      </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->

                    <!-- Edit Customer Modal -->
                    <div class="modal fade" id="edit-customer-modal">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header modal-header-info">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title">Edit Customer <strong>{{ editCust.name }}</strong></h4>
                            <span class="" style="">Fields with the asterisk(*) are required!</span>
                          </div>
                          <div class="modal-body">
                            <!-- Form Errors -->
                            <div class="alert alert-danger" v-if="editCust.errors.length > 0">
                                <p><strong>Whoops!</strong> Something went wrong!</p>
                                <br>
                                <ul>
                                    <li v-for="error in editCust.errors">
                                        {{ error }}
                                    </li>
                                </ul>
                            </div>
                            <form class="form-horizontal" role="form" onsubmit="return false">
                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-2">Name *</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="edit_name" @keyup.enter="update" v-model="editCust.name">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-2">Email </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" @keyup.enter="update" v-model="editCust.email">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-2">Phone </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" @keyup.enter="update" v-model="editCust.phone_number">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="control-label col-sm-2">Address </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" @keyup.enter="update" v-model="editCust.address">
                                    </div>
                                </div>

                            </form>
                            
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" @click="update">
                                <i class="fa fa-pencil-square-o" v-show="add_icon"></i>
                                <i class="fa fa-spinner fa-spin" v-show="button_spinner"></i> Update Customer
                            </button>
                           
                            
                          </div>
                        </div><!-- /.modal-content -->
                      </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->


                    <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="delete-confirmation-modal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header modal-header-danger">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title">Customer Delete Confirmation</h4>
                                    </div>
                                    <div class="modal-body">
                                        <h4>{{ confirm_text }}</h4>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-danger" @click="destroy(customer)">
                                            <i class="fa fa-trash" v-show="delete_icon"></i>
                                            <i class="fa fa-spin fa-spinner"v-show="delete_spinner"></i>
                                            Delete Customer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                customers: [],
                customer: {},
                add_icon: true,
                button_spinner: false,
                alert: false,
                alert_success: false,
                alert_message: '',
                confirm_text: '',
                delete_icon: true,
                delete_spinner: false,

                addForm: {
                    errors: [],
                    name: '',
                    email: '',
                    phone_number: '',
                    address: ''
                },

                editCust: {
                    id: '',
                    errors: [],
                    name: '',
                    email: '',
                    phone_number: '',
                    address: ''
                }
            };
        },

        mounted() {
            this.prepareComponent();
        },

        methods: {
            prepareComponent() {
                this.getCustomers();

                $('#create-customer-modal').on('shown.bs.modal', () => {
                    $('#name').focus();
                });

                $('#edit-customer-modal').on('shown.bs.modal', () => {
                    $('#edit_name').focus();
                });
                
            },

            /**
             * Get all te customers in the datatbase.
             */
            getCustomers() {
                this.$http.get('/customers')
                    .then(response => {
                        this.customers = response.data;
                    });
            },

            /**
             * Show the add customer modal.
             */
            addCustomer() {
                this.addForm.errors = [];

                $('#create-customer-modal').modal('show');
            },

            /**
             * Show the customer information with recent transactions
             */
            showCustomer(customer) {
                location.href = ('/customer/' + customer.id + '/history');
            },

            /**
             * Show edit form
             */
            edit(customer) {
                this.editCust.id = customer.id;
                this.editCust.name = customer.name;
                this.editCust.address = customer.address;
                this.editCust.phone_number = customer.phone_number;
                this.editCust.email = customer.email;
                this.editCust.errors = [];

                $('#edit-customer-modal').modal('show');
            },

            /**
             * Update customer details.
             */
            update() {
                this.persistCustomer('/customer/' + this.editCust.id, 'put',
                                     this.editCust, '#edit-customer-modal'
                );
            },

            /**
             * Delete Confirmation modal
             */
            deleteConfirm(customer) {
                this.confirm_text = 'Delete ' + customer.name + '?';
                this.customer = customer;

                $('#delete-confirmation-modal').modal('show');
            },

            /**
             * Save a customer
             */
            store() {
                this.persistCustomer('/customer', 'post',
                                    this.addForm, '#create-customer-modal'
                );
            },

            clear() {
                this.alert = false;
                this.alert_success = false;
                this.alert_message = '';
            },

            /**
             * Persist the save and update actions to the database.
             */
            persistCustomer(uri, method, form, modal) {
                form.errors = [];
                this.add_icon = false;
                this.button_spinner = true;

                this.$http[method](uri, form)
                    .then(response => {
                        this.getCustomers();

                        
                        if (method === 'post') {
                            this.alert_success = true;
                            this.alert_message = 'Customer ' +form.name+ ' created!';
                        } else {
                            this.alert = true;
                            this.alert_message = 'Customer ' +form.name+ ' updated!';
                        }
                        

                        form.name = '';
                        form.email = '';
                        form.address = '';
                        form.phone_number = '';

                        this.add_icon = true;
                        this.button_spinner = false;

                        $(modal).modal('hide');
                    })
                    .catch(response => {
                        // Display Add icon and Hide the Spinner
                        this.add_icon = true;
                        this.button_spinner = false;

                        if (typeof response.data === 'object') {
                            form.errors = _.flatten(_.toArray(response.data));
                        } else {
                            form.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },

            /**
             * Delete an Item from the Inventory
             */
            destroy(customer) {
                this.delete_icon = false;
                this.delete_spinner = true;

                this.$http.delete('/customer/' + customer.id)
                    .then(response => {
                        this.alert = true;
                        this.alert_message = '[' + customer.name + '] deleted successfully!';
                        this.getCustomers();
                    });

                this.delete_icon = true;
                this.delete_spinner = false;

                $('#delete-confirmation-modal').modal('hide');
            },
        }
    }
</script>
