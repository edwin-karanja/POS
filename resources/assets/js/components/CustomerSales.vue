<template>
    <div class="container-fluid">
        <div class="row">
            <div class="callout callout-info" v-if="sales.length === 0">
                <h4>Customer Callout!</h4>
                <p>This customer does not have any sales history within our records.</p>
            </div>

            <div class="col-md-4">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label class="control-label col-sm-4">Filter By Date</label>
                        <div class="col-sm-8">
                            <input type="text" id="cost_price" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            
            <table class="table table-bordered table-hover" v-if="sales.length > 0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Receipt No</th>
                        <th>Date</th>
                        <th>Comments</th>
                        <th>SubTotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(sale, index) in sales">
                        <td>{{ index + 1 }}</td>
                        <td>{{ sale.id }}</td>
                        <td>{{ sale.created_at }}</td>
                        <td>{{ sale.comments }}</td>
                        <td>{{ sale.total }}</td>
                        <td><button class="btn btn-default btn-sm" @click="showReceipt(sale.id)"><i class="fa fa-eye"></i></button></td>
                    </tr>
                </tbody>
            </table>

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
                                    <p>Date: <strong>{{ sale_dt.sale.created_at }}</strong></p>
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
    </div>
</template>

<script>
    export default {
        data () {
            return {
                sales: [], // Hold all the sales for a customer
                id: customerId,
                customer: {},

                sale_dt: {
                    sale: {},
                    customer: {},
                    user: {},
                    items: []
                },
            };
        },       

        mounted() {
            this.prepareData();
        },

        methods: {
            prepareData() {
                this.getSales();
            },

            getSales() {
                this.$http.get('/customer/' + this.id + '/history')
                    .then(response => {
                        this.customer = response.data;
                        this.sales = response.data.sales;
                    });
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
    }
</script>
