<style scoped>
    .action-link {
        cursor: pointer;
    }

    .m-b-none {
        margin-bottom: 0;
    }

    li {
        list-style: none;
    }

    #inventory {
        margin: 0px;

    }

    #inventory tr {
        font-style: "Fira Code"; 
        font-size: 14px;
    }

    #bold {
        font-weight: normal;
    }

    #null-results {
        margin-top: 10px;
    }


</style>


<template>
    <div class="">
        <div class="row">
            <div class="">
            <!-- Bootstrap alert -->
            <div class="alert alert-info" v-if="alert">
              <a href="#" class="close" data-dismiss="alert" aria-label="close" @click="clear">&times;</a>
              <strong>Info!</strong> {{ alert_message }}.
            </div>

            <div class="alert alert-success" v-if="alert_success">
              <a href="#" class="close" data-dismiss="alert" aria-label="close" @click="clear">&times;</a>
              <strong>Success!</strong> {{ alert_message }}.
            </div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h1 class="box-title">Stock Items</h1>  <button class="btn btn-success pull-right" @click="showCreateItemModal"><i class="fa fa-plus"></i> Add Item</button></div>

                    <div class="panel-body">
                        <div class="row" v-if="items_count > 10">
                            <div class="col-md-9">
                                <form role="form" onsubmit="return false" class="form-horizontal">
                                    <label class="control-label col-sm-3">Search Inventory</label>
                                    <div class="input-group col-sm-9">
                                        <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                        <input type="text" id="search" v-model="searchForm.search" class="form-control" @keyup.enter="searchItems" placeholder="Enter a search item" autofocus="autofocus">
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-default btn-flat" @click="searchItems">
                                <i class="fa fa-search" v-if="search_icon"></i>
                                <i class="fa fa-spin fa-spinner" v-if="search_spinner"></i>
                                Search</button>

                                <button type="button" class="btn btn-default btn-flat" @click="refreshPage">
                                <i class="fa fa-refresh" v-if="refresh_icon"></i>
                                <i class="fa fa-spin fa-refresh" v-if="refresh_spinner"></i>
                                Refresh</button>
                            </div>
                        </div>

                        <div id="null-results" class="well" v-if="items.length === 0 && items_count > 0">
                            No results for your query. Enter a new search parameter.
                        </div>

                        <div class="well" v-if="items_count === 0 && items.length === 0">
                            No items here yet! <a href="#" @click.prevent="showCreateItemModal">Create Item?</a>
                        </div>
                        <div class="row">
                            <table id="inventory" class="table table-responsive table-hover table-condensed" v-if="items.length > 0">
                                <thead>
                                    <tr>
                                        <th>##</th>
                                        <th>Item Name</th>
                                        <th>Size</th>
                                        <th>Category</th>
                                        <th>Cost Price</th>
                                        <th>Selling Price</th>
                                        <th>Quantity</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in items">
                                        <td>{{ index + 1 }}</td>
                                        <td id="bold">{{ item.item_name }}</td>
                                        <td>{{ item.packaging }}</td>
                                        <td v-if="item.category != null">{{ item.category.name }}</td>
                                        <td v-if="item.category == null"></td>
                                        <td id="bold"><a href="#" @click.prevent="showEditCostModal(item)">{{ item.cost_price }}</a></td>
                                        <td id="bold"><a href="#" @click.prevent="showEditCostModal(item)">{{ item.selling_price }}</a></td>
                                        <td>{{ item.quantity }}</td>
                                        <td>
                                            <button class="btn btn-xs btn-flat btn-default" @click="showInventory(item)"><i class="fa fa-server"></i> Stocks</button>
                                            <button class="btn btn-xs btn-flat btn-primary" @click="edit(item)" title="Edit Inventory Item" data-toggle="tooltip" data-placement="top"><i class="fa fa-pencil"></i></button>
                                            <button class="btn btn-xs btn-flat btn-danger" @click="deleteConfirm(item)" title="Delete Inventory Item" data-toggle="tooltip" data-placement="top"><i class="fa fa-close"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>



                        <!-- Create New Item Modal -->
                        <div class="modal fade" id="create-item-modal">
                          <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                              <div class="modal-header modal-header-info">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title">Create Item</h4>
                                <span class="text-danger">Fields with the asterisk(*) are required!</span>
                              </div>
                              <div class="modal-body">
                                <!-- Form Errors -->
                                <div class="alert alert-danger" v-if="createForm.errors.length > 0">
                                    <ul>
                                        <li v-for="error in createForm.errors">
                                            {{ error }}
                                        </li>
                                    </ul>
                                </div>
                                <form class="" role="form">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="task">UPC/EAN/ISBN</label>
                                                <input type="text" class="form-control" id="upc_ean_isbn" @keyup.enter="store" v-model="createForm.upc_ean_isbn" placeholder="">
                                            </div>

                                            <div class="form-group">
                                                <label for="task">Item Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="item_name" @keyup.enter="store" v-model="createForm.item_name" placeholder="Item name">
                                            </div>

                                            <div class="form-group">
                                                <label for="task">Packaging </label>
                                                <input type="text" class="form-control" id="packaging" @keyup.enter="store" v-model="createForm.packaging" placeholder="Describe the packaging">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="task">Cost Price <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">Kshs</div>
                                                    <input type="number" class="form-control" id="cost_price" @keyup.enter="store" v-model="createForm.cost_price">
                                                    <div class="input-group-addon">.00</div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="task">Selling Price <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">Kshs</div>
                                                    <input type="number" class="form-control" id="selling_price" @keyup.enter="store" v-model="createForm.selling_price">
                                                    <div class="input-group-addon">.00</div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="task">Quantity </label>
                                                <input type="number" class="form-control" id="quantity" @keyup.enter="store" v-model="createForm.quantity"  placeholder="Enter the initial quantity">
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea rows="5" class="form-control" name="description" v-model="createForm.description" placeholder="Describe the task"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="task">Category <span class="text-danger">*</span></label>
                                                <select v-model="createForm.category" class="form-control" >
                                                    <option value="">Select a category</option>
                                                    <option v-for="category in categories" v-bind:value="category.id">{{ category.name }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </form>

                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" @click="store">
                                    <i class="fa fa-plus" v-show="create_icon"></i>
                                    <i class="fa fa-spinner fa-spin" v-show="button_spinner"></i> Add Item
                                </button>


                              </div>
                            </div><!-- /.modal-content -->
                          </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->


                        <!-- Edit Item Modal -->
                        <div class="modal fade" id="edit-item-modal">
                          <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                              <div class="modal-header modal-header-info">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                                <div class="modal-title">
                                    <div class="row"></div>
                                </div>
                                <h4 class="modal-title">Edit Item: <span class="text-center"><strong>{{ editForm.item_name }}</strong></span></h4>
                                <span class="" style="">Fields with the asterisk(*) are required!</span>
                              </div>
                              <div class="modal-body">
                                <!-- Form Errors -->
                                <div class="alert alert-danger" v-if="editForm.errors.length > 0">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <p><strong>Whoops!</strong> Something went wrong!</p>
                                    <br>
                                    <ul>
                                        <li v-for="error in editForm.errors">
                                            {{ error }}
                                        </li>
                                    </ul>
                                </div>
                                <form class="" role="form">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="task">UPC/EAN/ISBN</label>
                                                <input type="text" class="form-control" id="upc_ean_isbn" @keyup.enter="update" v-model="editForm.upc_ean_isbn" placeholder="">
                                            </div>

                                            <div class="form-group">
                                                <label for="task">Item Name *</label>
                                                <input type="text" class="form-control" id="edit_item_name" @keyup.enter="update" v-model="editForm.item_name" placeholder="Item name">
                                            </div>

                                            <div class="form-group">
                                                <label for="task">Packaging </label>
                                                <input type="text" class="form-control" id="packaging" @keyup.enter="update" v-model="editForm.packaging" placeholder="Describe the packaging">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="task">Cost Price *</label>
                                                <input type="number" class="form-control" id="cost_price" @keyup.enter="update" v-model="editForm.cost_price" readonly>
                                            </div>

                                            <div class="form-group">
                                                <label for="task">Selling Price *</label>
                                                <input type="number" class="form-control" id="selling_price" @keyup.enter="update" v-model="editForm.selling_price" readonly>
                                            </div>

                                            <div class="form-group">
                                                <label for="task">Quantity *</label>
                                                <input type="number" class="form-control" id="quantity" @keyup.enter="update" v-model="editForm.quantity"  placeholder="Enter the initial quantity" readonly>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea rows="5" class="form-control" name="description" v-model="editForm.description" placeholder="Describe the task"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="task">Category </label>
                                                <select v-model="editForm.category" class="form-control" >
                                                    <option value="">Select a category</option>
                                                    <option v-for="category in categories" v-bind:value="category.id">{{ category.name }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </form>

                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" @click="update">
                                    <i class="fa fa-wrench" v-show="create_icon"></i>
                                    <i class="fa fa-spinner fa-spin" v-show="button_spinner"></i> Update Item
                                </button>


                              </div>
                            </div><!-- /.modal-content -->
                          </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->

                        <!-- Create Inventory Modal -->
                        <div class="modal fade" id="create-inventory-modal">
                          <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                              <div class="modal-header modal-header-info">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                                <div class="modal-title">
                                    <div class="row"></div>
                                </div>
                                <h4 class="modal-title">Stock History: <span class="text-center"><strong>{{ item.item_name }}</strong></span></h4>
                              </div>
                              <div class="modal-body">
                                <!-- Form Success -->
                                <div class="alert alert-success" v-if="adjustment_alert">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close" @click="clear">&times;</a>
                                    <strong>Success!</strong> {{ adjustment_notice }}.
                                </div>
                                <!-- Form Errors -->
                                <div class="alert alert-danger" v-if="inventoryForm.errors.length > 0">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <p><strong>Whoops!</strong> Something went wrong!</p>
                                    <br>
                                    <ul>
                                        <li v-for="error in inventoryForm.errors">
                                            {{ error }}
                                        </li>
                                    </ul>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="well">
                                            <table class="table table-hover table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th>Item Name</th>
                                                        <td>{{ item.item_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>UPC/EAN/ISBN</th>
                                                        <td>{{ item.upc_ean_isbn }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Quantity</th>
                                                        <td>{{ item.quantity }}</td>
                                                    </tr>
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="well well-sm">Inventory Adjustment</div>
                                        <form role="form" class="form-horizontal">
                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="adjustment">Adjustment (+ve/-ve) *</label>
                                                <div class="col-sm-9">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">+ve</div>
                                                        <input type="text" class="form-control" id="adjustment" @keyup.enter="saveAdjustment" v-model="inventoryForm.adjustment" placeholder="+ve or -ve adjustment">
                                                        <div class="input-group-addon">-ve</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-3" for="comment">Comments *</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="comments" @keyup.enter="saveAdjustment" v-model="inventoryForm.comments" placeholder="Transaction comments">
                                                </div>
                                            </div>
                                        </form>
                                            <button class="btn btn-primary btn-block" @click="saveAdjustment">
                                                <i class="fa fa-spinner fa-spin" v-show="inventory_spinner"></i>
                                                <i class="fa fa-thumbs-up" v-show="inventory_icon"></i> Adjust Inventory
                                            </button>


                                    </div>
                                </div>

                                <hr>

                                <div class="container-fluid">
                                    <div class="well" v-if="inventory_history.length === 0">
                                        No History for this Inventory Item.
                                    </div>

                                    <table class="table table-hover table-condensed table-responsive" v-if="inventory_history.length > 0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Adjustment Date/Time</th>
                                                <th>Owner</th>
                                                <th>In/Out Qty</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="row in inventory_history" >
                                                <td>{{ row.id }}</td>
                                                <td>{{ row.created_at }}</td>
                                                <td>{{ row.user.name }}</td>
                                                <td>{{ row.adjustment }}</td>
                                                <td v-html="row.comments"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>




                              </div>
                              <div class="modal-footer" style="text-align: center">
                               <div class="well well-sm" v-if="inventory_history.length >= 5">
                                   <span><a href="#" @click.prevent="viewHistory(item)">View full history...</a></span>
                               </div>

                              </div>
                            </div><!-- /.modal-content -->
                          </div><!-- /.modal-dialog -->
                        </div><!-- /.Inventory modal -->


                        <!-- Edit Cost Price Modal -->
                        <div class="modal fade" id="edit-cost-modal">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header modal-header-info">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>

                                <h4 class="modal-title">Modify Prices: <span class="text-center"><strong>{{ costForm.item_name }}</strong></span></h4>
                              </div>
                              <div class="modal-body">



                                    <div class="row">
                                        <div class="col-md-8">
                                            <form class="form-horizontal" role="form">
                                                <div class="form-group">
                                                    <label class="control-label col-sm-4">Cost Price</label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">Kshs</div>
                                                        <input type="number" id="cost_price" class="form-control" @keyup.enter="updateCost" v-model="costForm.cost">
                                                        <div class="input-group-addon">.00</div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-sm-4">Selling Price</label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">Kshs</div>
                                                        <input type="number" id="selling_price" class="form-control" @keyup.enter="updateCost" v-model="costForm.selling_price">
                                                        <div class="input-group-addon">.00</div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-md-4">
                                            <br>
                                        </div>
                                        <div class="col-md-4">
                                            <button class="btn btn-default" @click="updateCost">
                                                <i class="fa fa-pencil-square-o" v-if="update_cost_icon"></i>
                                                <i class="fa fa-spin fa-spinner" v-if="update_cost_spinner"></i>
                                                Update Prices</button>
                                        </div>
                                    </div>

                              </div>
                              <div class="modal-footer" style="text-align: center">


                              </div>
                            </div><!-- /.modal-content -->
                          </div><!-- /.modal-dialog -->
                        </div><!-- /.Edit Prices modal -->

                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="delete-confirmation-modal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header modal-header-danger">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title">Inventory Delete Confirmation</h4>
                                    </div>
                                    <div class="modal-body">
                                        <h4>{{ confirm_text }}</h4>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-danger" @click="destroy(item)">
                                            <i class="fa fa-trash" v-show="delete_icon"></i>
                                            <i class="fa fa-spin fa-spinner"v-show="delete_spinner"></i>
                                            Delete Item
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
        data() {
            return {
                create_icon: true,
                button_spinner: false,
                items: [],
                items_count: '',
                categories: [],
                item: {},
                alert: false,
                alert_success: false,
                alert_message: '',
                adjustment_alert: false,
                adjustment_notice: '',
                dtHandle: null,
                confirm_text: '',
                delete_icon: true,
                delete_spinner: false,
                


                // Inventory variables
                item_id: null,
                inventory_history: [],
                inventory_name: '',
                inventory_quantity: '',
                inventory_upc_ean_isbn: '',
                inventory_spinner: false,
                inventory_icon: true,

                // Cost Variables
                update_cost_icon: true,
                update_cost_spinner: false,
                costForm: {
                    errors: [],
                    cost: '',
                    selling_price: '',
                    item_id: '',
                    item_name: ''
                },

                // Search form
                search_icon: true,
                search_spinner: false,
                searchForm: {
                    search: ''
                },

                // Refresh
                refresh_icon: true,
                refresh_spinner: false,

                inventoryForm: {
                    errors: [],
                    adjustment: '',
                    comments: '',
                    id: ''
                },

                createForm: {
                    upc_ean_isbn: '',
                    item_name: '',
                    description: '',
                    packaging: '',
                    cost_price: '',
                    selling_price: '',
                    quantity:'',
                    category: 0,
                    errors: []
                },

                editForm: {
                    upc_ean_isbn: '',
                    item_name: '',
                    description: '',
                    packaging: '',
                    cost_price: '',
                    selling_price: '',
                    quantity:'',
                    category: '',
                    errors: []
                }
            };
        },

        mounted() {
            this.prepareComponent();

        },

        methods: {
            showCreateItemModal() {
                this.createForm.errors = [];
                $('#create-item-modal').modal('show');
            },

            prepareComponent() {
                this.getItems();
                this.getCategories();
                // this.itemCount();

                $('#create-item-modal').on('shown.bs.modal', () => {
                    $('#item_name').focus();
                });

                $('#edit-item-modal').on('shown.bs.modal', () => {
                    $('#edit_item_name').focus();
                });

                $('#create-inventory-modal').on('shown.bs.modal', () => {
                    $('#adjustment').focus();
                });

                $('#edit-cost-modal').on('shown.bs.modal', () => {
                    $('#cost_price').focus();
                });
                

                // $('#inventory').DataTable();

            },

            /**
             * Get all the Stock Items From The Database
             */
            getItems() {
                this.$http.get('/item')
                    .then(response => {
                        this.items = response.data;
                        this.items_count = response.data.length;
                    });
            },

            /**
             * Get all theCategories
             */
            getCategories() {
                this.$http.get('/category')
                    .then(response => {
                        this.categories = response.data;
                    });
            },

            getItem(id) {
                this.$http.get('/item/' + id)
                    .then(response => {
                        this.item = response.data;
                    });
            },

            searchItems() {

                this.search_icon = false;
                this.search_spinner = true;
                this.$http.post('/item/search', this.searchForm)
                    .then(response => {
                        this.items = response.data;

                        this.search_icon = true;
                        this.search_spinner = false;
                    });
            },

            search() {
                this.$http.post('/item/search', this.searchForm)
                    .then(response => {
                        this.items = response.data;
                    })
            },

            refreshPage() {
                //Show Spinner
                this.refresh_icon = false;
                this.refresh_spinner = true;

                this.alert = false;
                this.alert_success = false;
                this.alert_message = false;
                this.searchForm.search = '';

                this.getItems();

                // Hide Spinner
                this.refresh_icon = true;
                this.refresh_spinner = false;
            },

            clear() {
                this.alert = false;
                this.alert_success = false;
                this.alert_message = false;
            },

            store() {
                this.persistItem(
                    'post', '/item',
                    this.createForm, '#create-item-modal'
                );
            },

            edit(item) {
                this.editForm.id = item.id;
                this.editForm.upc_ean_isbn = item.upc_ean_isbn;
                this.editForm.item_name = item.item_name;
                this.editForm.description = item.description;
                this.editForm.packaging = item.packaging;
                this.editForm.cost_price = item.cost_price;
                this.editForm.selling_price = item.selling_price;
                this.editForm.quantity = item.quantity;
                this.editForm.category = item.category_id;
                this.editForm.errors = [];

                $('#edit-item-modal').modal('show');
            },

            update() {
                this.persistItem(
                    'put', '/item/' + this.editForm.id,
                    this.editForm, '#edit-item-modal'
                );
            },

            deleteConfirm(item) {
                this.confirm_text = 'Delete ' + item.item_name + '?';
                this.item = item;

                $('#delete-confirmation-modal').modal('show');
            },


            /**
             * Delete an Item from the Inventory
             */
            destroy(item) {
                this.clear();
                this.delete_icon = false;
                this.delete_spinner = true;

                this.$http.delete('/item/' + item.id)
                    .then(response => {
                        this.notify('information', response.data.msg);
                        this.getItems();
                    });

                this.delete_icon = true;
                this.delete_spinner = false;

                $('#delete-confirmation-modal').modal('hide');
            },

            viewHistory(item) {
                // this.$route.router.go('/item/' + item.id);
                location.href = '/item/' + item.id;
            },

            notify(type, text) {
                noty({
                    layout: 'topCenter',
                    theme: 'relax',
                    type: type,
                    text: '<p>' + type[0].toUpperCase() + type.substring(1) + '!</p>' + '<p>' + text + '</p>',
                    timeout: 5000,
                    progressBar: true
                });
            },

            persistItem(method, uri, form, modal) {
                form.errors = [];

                // Hide the Add icon and display the Spinner Icon
                this.create_icon = false;
                this.button_spinner = true;

                this.$http[method](uri, form)
                    .then(response => {
                        if (response.data.success == true) {
                            form.upc_ean_isbn = '';
                            form.item_name = '';
                            form.description = '';
                            form.packaging = '';
                            form.cost_price = '';
                            form.selling_price = '';
                            form.quantity = '';
                            form.errors = [];

                            // Display the Button icon and Hide the Spinner
                            this.create_icon = true;
                            this.button_spinner= false;
                            this.notify('success', response.data.msg);
                        } else {
                            this.create_icon = true;
                            this.button_spinner= false;
                            this.notify('error', response.data.msg);
                        }
                        if (method === 'put') {
                            this.search();
                        } else {
                            this.getItems();
                        }
                        $(modal).modal('hide');
                        
                        

                        

                        
                        // if (method == 'post') {
                        //     this.alert_message = 'New Item Successfully added!';
                        //     this.alert_success = true;
                        // } else {
                        //     this.alert_message = 'Item updated!';
                        //     this.alert = true;
                        // }

                    })
                    .catch(response => {
                        // Display Add icon and Hide the Spinner
                        this.create_icon = true;
                        this.button_spinner = false;

                        if (typeof response.data === 'object') {
                            form.errors = _.flatten(_.toArray(response.data));
                        } else {
                            form.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },

            saveAdjustment() {
                this.inventoryForm.errors = [];

                // Display the Spinner and Hide the Icon.
                this.inventory_spinner = true;
                this.inventory_icon = false;

                this.$http.post('/inventory', this.inventoryForm)
                    .then(response => {
                        this.getItemInventory(this.item_id);
                        this.getItems();
                        this.getItem(this.item_id);

                        //Reset the form fields.
                        this.inventoryForm.comments = '';
                        this.inventoryForm.adjustment = '';

                        // Display the Icon and Hide the Spinner.
                        this.inventory_spinner = false;
                        this.inventory_icon = true;

                        // Show the Success Notice
                        this.adjustment_alert = true;
                        this.adjustment_notice = 'Inventory adjustment added!';
                    })
                    .catch(response => {
                        // Display Add icon and Hide the Spinner
                        this.inventory_spinner = false;
                        this.inventory_icon = true;

                        if (typeof response.data === 'object') {
                            this.inventoryForm.errors = _.flatten(_.toArray(response.data));
                        } else {
                            this.inventoryForm.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },

            showInventory(item) {
                this.item_id = item.id;
                this.getItem(item.id);

                this.inventoryForm.id = item.id;
                this.inventoryForm.comments = '';
                this.inventoryForm.adjustment = '';
                this.inventoryForm.errors = [];

                this.adjustment_alert = false;
                this.adjustment_notice = '';


                this.getItemInventory(this.item_id);
                $('#create-inventory-modal').modal('show');
            },

            getItemInventory(id) {
                this.$http.get('/inventory/' + id)
                    .then(response => {
                        this.inventory_history = response.data;
                        this.modDate();
                    });
            },

            modDate() {
                if (this.inventory_history.length > 0) {
                    for (var i = 0; i < this.inventory_history.length; i++) {
                        this.inventory_history[i].created_at =  moment(this.inventory_history[i].created_at).format("ddd, MMM Do YYYY");
                    }
                }
                
            },

            /**
             * Show The #edit-cost-modal
             */
            showEditCostModal(item) {
                this.costForm.errors = [];
                this.costForm.item_id = item.id;
                this.costForm.cost = item.cost_price;
                this.costForm.selling_price = item.selling_price;
                this.costForm.item_name = item.item_name;



                $('#edit-cost-modal').modal('show');
            },

            /**
             * Update the cost of an Item
             */
            updateCost() {
                // Display Spinner icon and Hide the Update
                this.update_cost_spinner = true;
                this.update_cost_icon = false;

                this.$http.put('/cost/' + this.costForm.item_id, this.costForm)
                    .then(response => {
                        this.search();

                        // Display Add icon and Hide the Spinner
                        this.update_cost_spinner = false;
                        this.update_cost_icon = true;

                        // Show the Alert Message
                        this.alert_message = "[" + this.costForm.item_name + "] Prices Updated!";
                        this.alert = true;

                        // Clear the form field
                        this.costForm.item_id = '';
                        this.costForm.errors = [];
                        this.costForm.cost = '';
                        this.costForm.item_name = '';

                        // Close the Modal.
                        $('#edit-cost-modal').modal('hide');

                    })
                    .catch(response => {
                        // Display Add icon and Hide the Spinner
                        this.update_cost_spinner = false;
                        this.update_cost_icon = true;

                        if (typeof response.data === 'object') {
                            this.costForm.errors = _.flatten(_.toArray(response.data));
                        } else {
                            this.costForm.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            }


        }
    }
</script>
