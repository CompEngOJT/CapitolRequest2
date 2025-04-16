@extends('AdminFuelRequest/layout')
@section('title', 'Registry')


@push('styles')
  <!-- Document CSS files -->
  <link href="{{ asset('assets/css/fixed/dashboard-left.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/header.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/darkmode.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/help.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/help-contactus-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/registry/registry-middle.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/registry/registry-middle-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/registry/registry-driver-popup.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/registry/registry-vehicle-popup.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/registry/registry-success-popup.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/sidebar.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Middle Content -->
<div id="middle">
  <div id="middle-container">
    <div id="content">
        <h3>MANAGE DRIVER AND VEHICLE RECORDS</h3>
        <div id="horizontal"></div>
        <p>Register and manage vehicle information</p>
        <div id="squares-container">
          <button id="square" class="square-button">
            <div id="driver">
              <img src="{{asset('images/driver.png') }}" alt="Driver">
              <p>Add new driver</p>
            </div>
          </button>
          <button id="square2" class="square-button">
            <div id="vehicle">
              <img src="{{asset('images/vehicle.png') }}" alt="Vehicle">
              <p>Add new vehicle</p>
            </div>
          </button>
        </div>
    </div>
  </div>
  
  <!-- Driver Popup -->
  <div id="driver-popup" class="popup">
    <div class="popup-content">
      <!-- Header for the popup -->
      <div class="popup-header">
        <h2>Add new driver</h2>
      </div>
  
      <!-- Form fields -->
      <form method="POST" action="{{ route('drivers.store') }}" id="driver-form">
        @csrf
        <!-- First Name and Last Name in a row -->
        <div class="form-row">
          <div class="form-group">
            <label for="first-name">First Name</label>
            <input type="text" id="first-name" name="first-name">
          </div>
          <div class="form-group">
            <label for="last-name">Last Name</label>
            <input type="text" id="last-name" name="last-name">
          </div>
        </div>
  
        <!-- Position and Status in a row -->
        <div class="form-row">
          <div class="form-group">
            <label for="position">Position</label>
            <input type="text" id="position" name="position">
          </div>
          <div class="form-group">
            <label for="status">Status</label>
            <div class="custom-dropdown">
              <select id="status" name="status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
          </div>
        </div>
  
        <!-- Cancel and Add buttons -->
        <div class="form-buttons">
          <button type="button" id="cancel-button">Cancel</button>
          <button type="button" id="add-driver-button">Add</button>
        </div>
      </form>
    </div>
  </div>
  
  <!-- Vehicle Popup -->
  <div id="vehicle-popup" class="popup">
    <div class="popup-content">
      <!-- Header for the popup -->
      <div class="popup-header">
        <h2>Add new equipment/unit</h2>
      </div>
  
      <!-- Form fields -->
      <form method="POST" action="{{ route('vehicle.store') }}" id="vehicle-form">
        @csrf
        <!-- Equipment Type -->
        <div class="form-row">
          <div class="form-group">
            <label for="equipment-type">Equipment Type</label>
            <input type="text" id="equipment-type" name="equipment-type">
          </div>
        </div>
  
        <!-- Unit -->
        <div class="form-row">
          <div class="form-group">
            <label for="unit">Unit</label>
            <input type="text" id="unit" name="unit">
          </div>
        </div>
  
        <!-- Cancel and Add buttons -->
        <div class="form-buttons">
          <button type="button" id="cancel-vehicle-button">Cancel</button>
          <button type="button" id="add-vehicle-button">Add</button>
        </div>
      </form>
    </div>
  </div>
  
  <div id="driver-success-popup" class="popup">
    <div class="popup-content success-popup">
        <div class="popup-header">
            SUCCESS
        </div>
        <div class="popup-body">
            <div class="success-icon">
                <img src="{{ asset('images/success.png') }}" alt="Success">
            </div>
            <p> Driver has been added to your records.</p>
            <button id="driver-dashboard-button" class="dashboard-button">DASHBOARD</button>        
        </div>
    </div>
  </div>
  
  <div id="vehicle-success-popup" class="popup">
    <div class="popup-content success-popup">
        <div class="popup-header">
            SUCCESS
        </div>
        <div class="popup-body">
            <div class="success-icon">
                <img src="{{ asset('images/success.png') }}" alt="Success">
            </div>
            <p> Vehicle has been added to your records.</p>
            <button id="vehicle-dashboard-button" class="dashboard-button">DASHBOARD</button>
        </div>
    </div>
  </div>


@push('scripts')
<!-- Dashboard JavaScript -->
<script src="{{ asset('assets/js/slidebar.js') }}"></script>
<script src="{{ asset('assets/js/registry.js') }}"></script>
<script src="{{ asset('assets/js/fixed/darkmode.js') }}"></script>
@endpush
@endsection