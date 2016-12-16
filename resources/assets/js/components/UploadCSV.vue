
<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

            <div class="alert alert-success" v-if="alert_success">
	            <a href="#" class="close" data-dismiss="alert" aria-label="close" @click="clear">&times;</a>
	            <strong>Success!</strong> {{ alert_message }}.
	        </div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title">Upload Inventory Data</h2>
                    </div>

                    <div class="panel-body">
	                    <div class="" v-if="inventory.length === 0">
		                    <!-- Form Errors -->
	                        <div class="alert alert-danger" v-if="uploadCsv.errors.length > 0">
	                            <p><strong>Whoops!</strong> Something went wrong!</p>
	                            <br>
	                            <ul>
	                                <li v-for="error in uploadCsv.errors">
	                                    {{ error }}
	                                </li>
	                            </ul>
	                        </div>
	                    	<form role="form" class="form-horizontal">	
								<div class="form-group">
									<label for="file" class="col-sm-3">Upload a CSV or Excel file</label>
									<div class="col-sm-9">
										<input type="file" class="form-control" @change="sync" placeholder="Choose CSV file">
									</div>
								</div> 
							</form>
							<button type="button" class="btn btn-default" @click.prevent="uploadFile">
							<i class="fa fa-upload" v-show="upload_icon"></i>
							<i class="fa fa-spin fa-spinner" v-show="icon_spinner"></i>
							Upload CSV</button>

							<button type="button" class="btn btn-default pull-right" @click.prevent="persist">
							<i class="fa fa-spin fa-spinner" v-show="persist_spinner"></i>
							<i class="fa fa-database"></i>
							Persist Data</button>
	                    </div>
                        

						<div class="well well-lg" v-if="inventory.length > 0">
							You already have stocks, please reset your table before importing data.
						</div>

						<table class="table table-hover table-condensed" v-if="items.length > 0">
							<thead>
								<tr>
									<th>##</th>
									<th>upc_ean_isbn</th>
									<th>item_name</th>
									<th>Size</th>
									<th>cost_price</th>
									<th>selling_price</th>
								</tr>
							</thead>
								<tbody>
									<tr v-for="(item, index) in items">
										<td>{{ index }}</td>
										<td>{{ item.upc_ean_isbn }}</td>
										<td>{{ item.item_name }}</td>
										<td>{{ item.packaging }}</td>
										<td>{{ item.cost_price }}</td>
										<td>{{ item.selling_price }}</td>
									</tr>
								</tbody>
						</table>
						
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
    			items: [],
    			inventory: [],
    			upload_icon: true,
    			icon_spinner: false,
    			persist_spinner: false,
    			alert_success: false,
    			alert_message: '',

    			uploadCsv: {
    				csv_file: null,
    				errors: []
    			}
    		};
    	},

        mounted() {
            this.getInventory();
        },

        methods: {
        	sync(e) {
        		this.csv_file = e.target.files[0];
        	},

        	getInventory() {
        		this.$http.get('/item')
        			.then(response => {
        				console.log('function hit');
        				this.inventory = response.data;
        				console.log(this.inventory.length);
        			});
        	},

        	uploadFile() {
        		this.uploadCsv.errors = [];

        		// Show spinner and hide icon.
        		this.upload_icon = false;
        		this.icon_spinner = true;

        		var data = new FormData();
        		data.append('csv_file', this.csv_file);
        		this.$http.post('/upload', data)
        			.then(response => {
        				this.items = response.data;

        				// Hide spinner and show icon.
        				this.upload_icon = true;
        				this.icon_spinner = false;
        			})
        			.catch(response => {
                        // Hide the Spinner
                        this.upload_icon = true;
                        this.icon_spinner = false;

                        if (typeof response.data === 'object') {
                            this.uploadCsv.errors = _.flatten(_.toArray(response.data));
                        } else {
                            this.uploadCsv.errors = ['Something went wrong. Please try again.'];
                        }
                    });
        	},

        	clear() {
        		this.alert_success = '';
        		this.alert_message = false;
        	},

        	persist() {
        		this.uploadCsv.errors = [];

        		if (this.items.length === 0) {
        			this.uploadFile();
        			return;
        		}

        		// Show spinner.
        		this.persist_spinner = true;

        		var data = new FormData();
        		data.append('csv_file', this.csv_file);
        		this.$http.post('/persist', data)
        			.then(response => {
        				this.alert_message = 'Data successfully uploaded';
        				this.alert_success = true;

        				// Clear the items array.
        				this.items = [];

        				// Get the inventory
        				this.getInventory();

        				// Hide spinner.
        				this.persist_spinner = false;
        			})
        			.catch(response => {
                        // Hide the Spinner
                        this.persist_spinner = false;

                        if (typeof response.data === 'object') {
                            this.uploadCsv.errors = _.flatten(_.toArray(response.data));
                        } else {
                            this.uploadCsv.errors = ['Something went wrong. Please try again.'];
                        }
                    });
        	}
        }
    }
</script>
