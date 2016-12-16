@extends('laravel_dashboard.layout')

@section('title')
    Inventory Item: {{ $item->name }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Panel title</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <th>Name: {{ $item->item_name }}</th>
                            <th>Cost Price: {{ $item->cost_price }}</th>
                            <th>Selling Price: {{$item->selling_price}}</th>
                            <th>Quantity: {{ $item->quantity }}</th>

                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ $item->item_name }}</h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-hover">
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
                    @foreach($item->inventory as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->created_at->format("D, jS M Y h:i a") }}</td>
                        <td>{{ $row->user->name }}</td>
                        <td>{{ $row->adjustment }}</td>
                        <td>{!! $row->comments !!}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@stop


