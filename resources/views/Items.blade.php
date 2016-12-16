@extends('laravel_dashboard.layout')

@section('title')
    Inventory  <span style="font-size: 16px;margin-left: 30%;">@{{ today }}</span>
@stop

@section('content')

<div class="container-fluid">
	<stocks></stocks>
</div>
	

@stop


