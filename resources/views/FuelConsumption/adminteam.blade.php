@extends('FuelConsumption.layout')

@section('title', 'AdminConsumption')

@push('styles')
    <!-- Link to your dashboard.css -->
    <link href="{{ asset('assets/css/Adminfixed/header.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/AdminTeam/adminteam.css') }}" rel="stylesheet">
@endpush

@section('content')
<div id="content">
    <div id ="rectangle">
    </div>
</div>
@endsection



@push('scripts')
<script src="{{ asset('assets/js/darkmode.js') }}"></script>

@endpush