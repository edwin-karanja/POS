@if (count($creditSales) === 0)
	<div class="callout callout-info">
        <h4>Customer Callout!</h4>
        <p>This customer does not have any sales history within our records.</p>
    </div>
@else
<div class="container-fluid">
    <div class="box box-primary">
    	<div class="box-header with-border">
    		<h3 class="box-title">Credit Sales</h3>
    	</div>
    	<table class="table table-striped table-condensed table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Receipt No</th>
                    <th>Date</th>
                    <th>User</th>
                    <th>Transaction</th>
                    <th>SubTotal</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($creditSales as $key=>$sale)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $sale->id }}</td>
                        <td>{{ $sale->created_at->diffForHumans() }}</td>
                        <td>{{ $sale->user->name }}</td>
                        <td>{{ $sale->payment_type }}</td>
                        <td>{{ $sale->total }}</td>
                        <td>{{ $sale->amount_credited }}</td>
                        <td>{{ $sale->balance }}</td>
                        <td><button class="btn btn-default btn-sm" @click="showReceipt({{ $sale->id }})"><i class="fa fa-eye"></i></button></td>
                    </tr>
               @endforeach
            </tbody>
        </table>	
        {{ $creditSales->links() }}
    </div>	
</div>	   
@endif

