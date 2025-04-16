@extends('AdminFuelRequest/layout')
@section('title', 'Request')

@push('styles')
  <!-- Request CSS files -->
  <link href="{{ asset('assets/css/fixed/dashboard-left.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/sidebar-mobile-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/header.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/darkmode.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/help.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/help-contactus-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/request/request-middle.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/request/request-middle-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/request/request-mobile-popup.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/sidebar.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Middle Content -->
<div id="middle">
<div id="middle-container">
  <div id="content">
      <div id="request-rectangle">
          <h2 id="form-header">Document Request Form</h2>
          
<!-- Form starts here -->
<form class="document-request-form web-form" action="{{ route('request.store') }}" method="POST">
    @csrf <!-- Add CSRF token for security -->
    <div class="form-row">
        <!-- Dropdown field for Driver's Full Name -->
        <div class="form-group half-width">
            <select class="form-control" id="driver-name" name="driver_name" required>
                <option value="" disabled selected>Select a driver</option>
                @foreach ($drivers as $driver)
                    <option value="{{ $driver->first_name }} {{ $driver->last_name }}">{{ $driver->first_name }} {{ $driver->last_name }}</option>
                @endforeach
            </select>
            <label for="driver-name">Driver's Full Name</label>
        </div>

        <!-- Date input field -->
        <div class="form-group half-width">
            <input type="date" class="form-control" id="date-input" name="date" required>
            <label for="date-input">Date</label>
        </div>
    </div>

    <!-- Container for Type and Quantity fields -->
    <div class="form-row">
        <!-- Dropdown field for Type -->
        <div class="form-group half-width">
            <select class="form-control" id="type" name="type" required>
                <option value="" disabled selected>Select a type</option>
                @foreach ($inventoryProducts as $product)
                    <option value="{{ $product->product_name }}">{{ $product->product_name }}</option>
                @endforeach
            </select>
            <label for="type">Type</label>
        </div>

        <!-- Quantity Input Field -->
        <div class="form-group half-width">
            <input type="number" class="form-control" id="quantity" name="quantity" placeholder=" " required>
            <label for="quantity">Quantity</label>
        </div>
    </div>

    <!-- Government Car Used and Government Car Number -->
    <div class="form-row">
        <!-- Government Car Used -->
        <div class="form-group half-width">
            <select class="form-control" id="government-car-used" name="government_car_used" required>
                <option value="" disabled selected>Select a car</option>
                @foreach ($equipments as $equipment)
                    <option value="{{ $equipment->name }}">{{ $equipment->name }}</option>
                @endforeach
            </select>
            <label for="government-car-used">Government Car Used</label>
        </div>

        <!-- Government Car Number -->
        <div class="form-group half-width">
            <select class="form-control" id="government-car-number" name="government_car_number" required>
                <option value="" disabled selected>Select a car number</option>
                @foreach ($units as $unit)
                    <option value="{{ $unit->name }}">{{ $unit->name }}</option>
                @endforeach
            </select>
            <label for="government-car-number">Government Car Number</label>
        </div>
    </div>

    <!-- Container for Place to Visit and Purpose -->
    <div class="form-row">
        <!-- Input field for Place to Visit -->
        <div class="form-group half-width">
            <input type="text" class="form-control" id="place-to-visit" name="place_to_visit" placeholder=" " required>
            <label for="place-to-visit">Place to Visit</label>
        </div>

        <!-- Input field for Purpose -->
        <div class="form-group half-width">
            <input type="text" class="form-control" id="purpose" name="purpose" placeholder=" " required>
            <label for="purpose">Purpose</label>
        </div>
    </div>

    <!-- Container for Requested By and Division -->
    <div class="form-row">
        <!-- Input field for Requested By -->
        <div class="form-group half-width">
            <input type="text" class="form-control" id="requested-by" name="requested_by" placeholder=" " required>
            <label for="requested-by">Requested By</label>
        </div>

        <!-- Dropdown field for Division -->
        <div class="form-group half-width">
            <select class="form-control" id="division" name="division" required>
                <option value="" disabled selected>Select a division</option>
                @foreach ($sections as $section)
                    <option value="{{ $section->name }}">{{ $section->name }}</option>
                @endforeach
            </select>
            <label for="division">Division</label>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="form-row" style="justify-content: center;">
        <button type="submit" class="submit-button">Submit</button>
    </div>

    <!-- Popup Overlay -->
    <div class="popup-overlay hidden">
        <div class="popup">
            <h2>SUCCESS</h2>
            <p>Your request has been submitted!</p>
            <button type="buttton" class="back-to-dashboard">BACK TO DASHBOARD</button>
        </div>
    </div>
</form>
      </div>


@push('scripts')
<!-- Dashboard JavaScript -->
<script src="{{ asset('assets/js/slidebar.js') }}"></script>
<script src="{{ asset('assets/js/request.js') }}"></script>
<script src="{{ asset('assets/js/fixed/darkmode.js') }}"></script>
@endpush
@endsection