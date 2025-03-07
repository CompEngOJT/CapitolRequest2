@extends('layout')
@section('title', 'Registry')
@section('content')

@push('styles')
  <!-- Document CSS files -->
  <link href="{{ asset('assets/css/dashboard/dashboard-left.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/document/document-right.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/registry/registry-middle.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/registry/registry-mobile.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/dashboard/dashboard-left-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/document/document-right-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/registry/registry-middle-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/registry/registry-mobile-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/dashboard/mobile-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/registry/registry-driver-popup.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/registry/registry-vehicle-popup.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/registry/registry-success-popup.css') }}" rel="stylesheet">
@endpush

<!-- Left Sidebar -->
<div id="container">
  <div id="logo">
    <img src="{{ asset('images/logo.png') }}" alt="Logo">
  </div>
  <div id="profile">
    <img src="{{ asset('images/profile.png') }}" alt="Profile">
  </div>
  <div class="admin-badge">ADMIN</div>
  <div id="sidebar">
    <a span href="{{route('dashboard')}}" class="sidebar-link" >Dashboard</a></span>
    <a href="{{ route('document') }}" class="sidebar-link">Document</a>
    <a class="sidebar-link active" style="cursor: default;">Registry</a>
    <a href="#" class="sidebar-link">Inventory</a>   
    <a href="#" class="sidebar-link">Request History</a>
    <a href="{{ route('account') }}" class="sidebar-link">Account</a>
  </div>
  <div id="logout">
    <img src="{{ asset('images/logout.png') }}" alt="Logout">
  </div>    
</div>

<!-- Middle Content -->
<div id="middle-container">
  <div id="content">
      <h1>Online Request System</h1>
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
<div id="driver-popup" class="popup">
  <div class="popup-content">
    <!-- Header for the popup -->
    <div class="popup-header">
      <h2>Add new driver</h2>
    </div>

    <!-- Form fields -->
    <form method="POST" action="{{ route('drivers.store') }}">
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
      <!-- Success Popup for Driver -->
      <div id="driver-success-popup" class="popup">
        <div class="popup-content">
          <!-- Header for the popup -->
          <div class="popup-header">
            <h2>SUCCESS</h2>
          </div>
          
          <!-- Success Icon -->
          <img src="{{ asset('images/success.png') }}" alt="Success">
      
          <!-- Success Message -->
          <p>Driver's information has been saved!</p>
      
          <!-- Dashboard Button -->
          <div class="form-buttons">
            <button type="button" id="driver-dashboard-button">DASHBOARD</button>
          </div>
        </div>
      </div>
      
    </form>
  </div>
</div>
<!-- Vehicle Popup -->
<div id="vehicle-popup" class="popup">
  <div class="popup-content">
    <!-- Header for the popup -->
    <div class="popup-header">
      <h2>Add new vehicle</h2>
    </div>

    <!-- Form fields -->
    <form method="POST" action="{{ route('vehicle.store') }}">
      @csrf
      <!-- Equipment Type and Unit in a row -->
      <div class="form-row">
        <div class="form-group">
          <label for="equipment-type">Equipment Type</label>
          <input type="text" id="equipment-type" name="equipment-type">
        </div>
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
<!-- Vehicle Success Popup -->
<div id="vehicle-success-popup" class="popup">
  <div class="popup-content">
    <div class="popup-header">SUCCESS</div>
    <img src="{{asset('images/success.png') }}" alt="Success">
    <p>Vehicle's information has been saved!</p>
    <div class="form-buttons">
      <button type="button" id="vehicle-dashboard-button">Dashboard</button>
    </div>
  </div>
</div>
    </form>
  </div>
</div>



<!-- Right Sidebar -->
<div id="right-container">
  <div id="rectangle-wrapper">
    <div class="rectangle">
      <img src="{{ asset('images/3.png') }}" alt="Step 3">
      <p><strong> STEPS TO CREATE A REQUEST</strong></p>
    </div>
    <div class="rectangle">
      <div class="step-header">
        <h3>Step #1</h3>
      </div>
      <div class="step-content">
        <img src="{{ asset('images/request.png') }}" alt="Request Now">
        <p>Click the <strong>“Request Now”</strong> button</p>
      </div>
    </div>
    <div class="rectangle">
      <div class="step2-header">
        <h3>Step #2</h3>
      </div>
      <div class="step2-content">
        <img src="{{ asset('images/click.png') }}" alt="Click">
        <p>Click the <strong>“Request Slip”</strong></p>
      </div>
    </div>
    <div class="rectangle">
      <div class="step3-header">
        <h3>Step #3</h3>
      </div>
      <div class="step3-content">
        <img src="{{ asset('images/submit.png') }}" alt="Submit">
        <p>Fill out the form and hit the <strong>“Submit”</strong> button</p>
      </div>
    </div>
  </div>
</div>

<!-- Background Content (Hidden by default) -->
<div id="background-content">
  <div id="background-header">
    <h3>MANAGE DRIVER AND VEHICLE RECORDS</h3>
    <div id="horizontal2"></div>
    <p>Register and manage vehicle information</p>
    <div id="squares-container2">
      <div id="square3">
        <div id="driver2">
          <img src="{{asset('images/driver.png') }}" alt="Driver">
          <p>Add new driver</p>
        </div>
      </div>
      <div id="square4">
        <div id="vehicle2">
          <img src="{{asset('images/vehicle.png') }}" alt="Vehicle">
          <p>Add new vehicle</p>
        </div>
      </div>
    </div>
</div>
</div>

<!-- Top Rectangle Container (Header) -->
<div id="rectangle-container">
  <!-- Toggle Button for Mobile Sidebar -->
  <div id="sidebar-toggle">
    <img src="{{ asset('images/menu.png') }}" alt="Menu" id="menu-icon">
  </div>
  
  <!-- Title -->
  <h1>Online Request System</h1>
</div>

<!-- Sliding Sidebar Menu (Mobile) -->
<div id="sliding-sidebar">
  <div id="sidebar-logo">
    <img src="{{ asset('images/logo.png') }}" alt="Logo">
  </div>
  <div id="sidebar-profile">
    <img src="{{ asset('images/profile.png') }}" alt="Profile">
  </div>
  <div id="sidebar-badge">ADMIN</div>
  <div id="menu-content">
    <a span href="{{route('dashboard')}}" class="sidebarlink" >Dashboard</a></span>
    <a href="{{ route('document') }}" class="sidebarlink">Documents</a>
    <a class="sidebarlink active" style="cursor: default;">Registry</a>
    <a href="#" class="sidebarlink">Inventory</a>
    <a href="#" class="sidebarlink">Request History</a>
    <a href="{{ route('account') }}" class="sidebarlink">Account</a>
  </div>
  <div id="sidebar-logout">
    <img src="{{ asset('images/logout.png') }}" alt="Logout">
  </div>
</div>

@push('scripts')
<!-- Dashboard JavaScript -->
<script src="{{ asset('assets/js/registry.js') }}"></script>
@endpush
@endsection