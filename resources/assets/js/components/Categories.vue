<style scoped>
    #categories tr {
        font-style: "Fira Code";
        
    }

    #bold {
        
    }
</style>

<template>

    <div class="col-md-10 col-md-offset-1">
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
            <h2 class="box-title">Stock Categories</h2> <button class="btn btn-success pull-right" @click="showCreateCategoryModal"><i class="fa fa-plus"></i> Add Category</button></div>

            <div class="panel-body">
                <div class="well" v-if="categories.length === 0">
                    No category created yet. <a href="" @click.prevent="showCreateCategoryModal">Create a category?</a>
                </div>

                <table id="categories" class="table table-hover table-condensed" v-if="categories.length > 0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Last Update</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(category, index) in categories">
                            <td>{{ index + 1 }}</td>
                            <td id="bold">{{ category.name }}</td>
                            <td>{{ category.description }}</td>
                            <td>{{ category.updated_at }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" @click="edit(category)" title="Edit Category" data-toggle="tooltip" data-placement="top"><i class="fa fa-pencil-square"></i></button>
                                <button class="btn btn-sm btn-danger" @click="deleteConfirm(category)" title="Delete Category" data-toggle="tooltip" data-placement="top"><i class="fa fa-close"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create New Category Modal -->
        <div class="modal fade" id="create-category-modal">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header modal-header-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Create Category</h4>
                <span class="" style="">Fields with the asterisk(*) are required!</span>
              </div>
              <div class="modal-body">
                <!-- Form Errors -->
                <div class="alert alert-danger" v-if="createForm.errors.length > 0">
                    <p><strong>Whoops!</strong> Something went wrong!</p>
                    <br>
                    <ul>
                        <li v-for="error in createForm.errors">
                            {{ error }}
                        </li>
                    </ul>
                </div>
                <form class="form-horizontal" role="form" onsubmit="return false">
                    <div class="form-group">
                        <label for="name" class="control-label col-sm-2">Name *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" @keyup.enter="store" v-model="createForm.name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="control-label col-sm-2">Description</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="4" v-model="createForm.description"></textarea>
                        </div>                     
                    </div>   
                </form>
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="store">
                    <i class="fa fa-plus" v-show="create_icon"></i>
                    <i class="fa fa-spinner fa-spin" v-show="button_spinner"></i> Add Category
                </button>
               
                
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


        <!-- Edit Category Modal -->
        <div class="modal fade" id="edit-category-modal">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header modal-header-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Edit Category</h4>
                <span class="" style="">Fields with the asterisk(*) are required!</span>
              </div>
              <div class="modal-body">
                <!-- Form Errors -->
                <div class="alert alert-danger" v-if="editForm.errors.length > 0">
                    <p><strong>Whoops!</strong> Something went wrong!</p>
                    <br>
                    <ul>
                        <li v-for="error in editForm.errors">
                            {{ error }}
                        </li>
                    </ul>
                </div>
                <form class="form-horizontal" role="form" onsubmit="return false">
                    <div class="form-group">
                        <label for="name" class="control-label col-sm-2">Name *</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" @keyup.enter="update" v-model="editForm.name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="control-label col-sm-2">Description</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="4" v-model="editForm.description"></textarea>
                        </div>                     
                    </div>   
                </form>
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="update">
                    <i class="fa fa-pencil-square-o" v-show="create_icon"></i>
                    <i class="fa fa-spinner fa-spin" v-show="button_spinner"></i> Update Category
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
                        <h4 class="modal-title">Category Delete Confirmation</h4>
                    </div>
                    <div class="modal-body">
                        <h5>{{ confirm_text }}</h5>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" @click="destroy(category)">
                            <i class="fa fa-trash" v-show="delete_icon"></i>
                            <i class="fa fa-spin fa-spinner"v-show="delete_spinner"></i>
                            Delete Category
                        </button>
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
                categories: [],
                alert: false,
                alert_message: false,
                alert_success: false,
                create_icon: true,
                button_spinner: false,
                delete_icon: true,
                delete_spinner: false,
                confirm_text: '',
                category: {},

                createForm: {
                    errors: [],
                    name: '',
                    description: ''
                },

                editForm: {
                    errors: [],
                    id: '',
                    name: '',
                    description: ''
                }
            };
        },

        mounted() {
            this.prepareComponent();
        },

        computed: {
            formatedTime: function(time) {
                return moment(time).format("ddd,MMM Do YYYY");
            }
        },

        methods: {
            prepareComponent() {
                this.getCategories();

                $('#create-category-modal').on('shown.bs.modal', () => {
                    $('#name').focus();
                });

                $('#edit-category-modal').on('shown.bs.modal', () => {
                    $('#name').focus();
                });

                // $('#categories').DataTable();
            },

            /**
             * Get all the categories.
             */
            getCategories() {
                this.$http.get('/category')
                    .then(response => {
                        this.categories = response.data;
                        this.modDate();
                    });
            },

            modDate() {
                if (this.categories.length > 0) {
                    for (var i = 0; i < this.categories.length; i++) {
                        this.categories[i].updated_at =  moment(this.categories[i].updated_at).fromNow();
                    }
                }
                
            },

            /**
             * Show the Create category Modal
             */
            showCreateCategoryModal() {
                this.createForm.errors = [];
                this.createForm.name = '';
                this.createForm.description = '';

                $('#create-category-modal').modal('show');
            },

            clear() {
                
            },

            /**
             * Save the category
             */
            store() {
                this.persistCategory(
                    '/category', 'post', 
                    this.createForm, '#create-category-modal'
                );
            },

            edit(category) {
                this.editForm.id = category.id;
                this.editForm.name = category.name;
                this.editForm.description = category.description;
                this.editForm.errors = [];

                $('#edit-category-modal').modal('show');
            },

            update() {
                this.persistCategory(
                    '/category/' + this.editForm.id, 'put',
                    this.editForm, '#edit-category-modal'
                );
            },

            deleteConfirm(category) {
                this.confirm_text = 'Are you sure you want to delete (' + category.name + ') category ?';
                this.category = category;

                $('#delete-confirmation-modal').modal('show');
            },

            destroy(category) {
                this.clear();
                this.$http.delete('/category/' + category.id)
                    .then(response => {
                        this.getCategories();
                        this.alert_message = '[' + category.name + '] Deleted';
                        this.alert = true;
                    })

                    $('#delete-confirmation-modal').modal('hide');
            },

            persistCategory(uri, method, form, modal) {
                form.errors = [];

                // Display Spinner icon and Hide the Add Icon
                this.create_icon = false;
                this.button_spinner = true;

                this.$http[method](uri, form)
                    .then(response => {
                        this.getCategories();
                        var categoryName = form.name;

                        form.name = '';
                        form.description = '';
                        form.errors = [];

                        // Display the add icon and hide the spinner
                        this.create_icon = true;
                        this.button_spinner = false;

                        // Set The alert
                        if (method == 'post') {
                            this.alert_message = 'New Category created';
                            this.alert_success = true;
                        } else {
                            this.alert_message = 'Category [' + categoryName + '] created';
                            this.alert = true;
                        }
                        

                        $(modal).modal('hide');
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
            }
        }

    }
</script>
