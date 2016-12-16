@extends('laravel_dashboard.layout')

@section('title')
    Customer Information
@stop

@section('content')


	<!-- Custom Tabs -->
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
        	<li class="active"><a href="#info" data-toggle="tab">Customer Info</a></li>
          	<li><a href="#sales" data-toggle="tab">Cash Sales</a></li>
          	<li><a href="#tab_2" data-toggle="tab">Credit Sales</a></li>
          	<li class="dropdown">
	            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
	              	Dropdown <span class="caret"></span>
	            </a>

	            <ul class="dropdown-menu">
	              	<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Action</a></li>
	              	<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Another action</a></li>
	              	<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Something else here</a></li>
	              	<li role="presentation" class="divider"></li>
	              	<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li>
	            </ul>
        	</li>
          	<li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
        </ul>
        <div class="tab-content">
        	<div class="tab-pane active" id="info">
	          	@include('tabs.customer-info')
          	</div>
          	<div class="tab-pane" id="sales">
	          	@include('tabs.sales')
          	</div>
          	<!-- /.tab-pane -->
          	<div class="tab-pane" id="tab_2">
	            @include('tabs.credit')
          	</div>
        </div>
	</div>

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
                        <p>Receipt No: <strong>@{{ sale_dt.sale.id }}</strong></p>
                        <p>Customer: <strong>@{{ sale_dt.customer.name }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <p>Date: <strong>@{{ sale_dt.sale.created_at }}</strong></p>
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
                                <td>@{{ index + 1 }}</td>
                                <td>@{{ row.item.item_name }}</td>
                                <td>@{{ row.quantity }}</td>
                                <td id="ttal">@{{ row.item_total }}</td>
                            </tr>
                            <tr id="stot">
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                                <td class="" id="tot">@{{ sale_dt.sale.total }}</td>
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

@stop

