@extends('laravel_dashboard.layout')

@section('title')
    Categories
@stop

@section('content')

	<categories></categories>

@stop

@section('scripts')
    <script>
        noty({
            layout: 'topCenter',
            theme: 'defaultTheme',
            type: 'success',
            text: 'Success in loading',
            timeout: 3000,
            progressBar:true
        });
    </script>
@stop