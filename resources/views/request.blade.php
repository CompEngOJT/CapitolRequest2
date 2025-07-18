@extends('layout')
@section('title', 'Request')
@section('content')

@push('styles')
  <!-- Request CSS files -->
  <link href="{{ asset('assets/css/dashboard/dashboard-left.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/request/request-right.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/request/request-middle.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/request/request-mobile.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/dashboard/dashboard-left-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/request/request-right-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/request/request-middle-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/request/request-mobile-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/request/request-mobile-popup.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/dashboard/mobile-media.css') }}" rel="stylesheet">
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
    <a class="sidebar-link active" style="cursor: default;">Documents</a>
    <a href="{{ route('registry') }}" class="sidebar-link">Registry</a>
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
      <!-- Add the request-rectangle here -->
      <div id="request-rectangle">
          <h2 id="form-header">Document Request Form</h2>
          
          <!-- Form starts here -->
          <form class="document-request-form web-form" action="{{ route('storeRequest') }}" method="POST">
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

              <!-- Container for Type and Quantity/Measurement fields -->
              <div class="form-row">
                  <!-- Dropdown field for Type -->
                  <div class="form-group half-width">
                    <select class="form-control" id="type" name="type" required>
                      <option value="" disabled selected></option>
                      <option value="Automatic Brake Fluid">Automatic Transmission Fluid</option>
                      <option value="Brake Fluid">Brake Fluid</option>
                      <option value="Engine Oil">Engine Oil</option>
                      <option value="Fuel">Fuel</option>
                      <option value="Gear Oil">Gear Oil</option>
                      <option value="Grease">Grease</option>
                      <option value="Hydrolic Oil">Hydrolic Oil</option>
                      <option value="Hydrolic Oil">Coolant</option>
                      <option value="Hydrolic Oil">Brake Cleaner</option>
                  </select>
                      <label for="type">Type</label>
                  </div>

                  <!-- Joint Quantity and Measurement fields -->
                  <div class="form-group half-width joint-field">
                      <!-- Quantity Input Field -->
                      <div class="joint-input">
                          <input type="number" class="form-control" id="quantity" name="quantity" placeholder=" " required>
                          <label for="quantity">Quantity</label>
                      </div>
                      <!-- Measurement Dropdown -->
                      <div class="joint-dropdown">
                        <select class="form-control" id="measurement" name="measurement" required>
                          <option value="" disabled selected></option>
                          <!-- Combined Units (Biggest to Smallest) -->
                          <option value="Gallons">Gallons</option>
                          <option value="Liters">Liters</option>
                          <option value="Quarts">Quarts</option>
                          <option value="Pints">Pints</option>
                          <option value="Cups">Cups</option>
                          <option value="Deciliters">Tubs</option>
                          <option value="Fluid Ounces">Bottles</option>
                          <option value="Centiliters">Centiliters</option>
                          <option value="Tablespoons">Tablespoons</option>
                          <option value="Teaspoons">Teaspoons</option>
                          <option value="Milliliters">Milliliters</option>
                      </select>
                          <label for="measurement">Measurement</label>
                      </div>
                  </div>
              </div>
<!-- Government Car Used and Government Car Number -->
<div class="form-row">
  <!-- Government Car Used -->
  <div class="form-group half-width">
      <select class="form-control" id="government-car-used" name="government_car_used" required>
          <option value="" disabled selected></option>
          @foreach ($vehicles as $vehicle)
              <option value="{{ $vehicle->equipment_type }}">{{ $vehicle->equipment_type }}</option>
          @endforeach
      </select>
      <label for="government-car-used">Government Car Used</label>
  </div>

  <!-- Government Car Number -->
  <div class="form-group half-width">
      <select class="form-control" id="government-car-number" name="government_car_number" required>
          <option value="" disabled selected></option>
          @foreach ($vehicles as $vehicle)
              <option value="{{ $vehicle->unit }}">{{ $vehicle->unit }}</option>
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
                      <option value="" disabled selected></option>
                      <option value="ADMIN">ADMIN</option>
                      <option value="CONSTRUCTION">CONSTRUCTION</option>
                      <option value="MAINTENANCE">MAINTENANCE</option>
                      <option value="MOTORPOOL">MOTORPOOL</option>
                      <option value="MTQC">MTQC</option>
                      <option value="PLANNING">PLANNING</option>
                      <option value="SUPPLY">SUPPLY</option>
                  </select>
                      <label for="division">Division</label>
                  </div>
              </div>

   <!-- Submit Button -->
   <div class="form-row" style="justify-content: center;">
    <button type="button" class="show-popup submit-button">Submit</button>
  </div>

  <!-- Popup Overlay -->
  <div class="popup-overlay hidden">
    <div class="popup">
      <h2>SUCCESS</h2>
      <p>Your request has been submitted!</p>
      <button type="submit" class="back-to-dashboard">BACK TO DASHBOARD</button>
    </div>
          </form>
        </div>
          <!-- Form ends here -->
      </div>
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
    <!-- Add your request-rectangle here -->
    <div id="request-rectangle">
      <h2 id="form-header">Document Request Form</h2>

      <!-- Form starts here -->
      <form class="document-request-form mobile-form" action="{{ route('storeRequest') }}" method="POST">
        @csrf <!-- Add CSRF token for security -->
        <!-- Container for the dropdown and date input -->
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

        <!-- Container for Type and Quantity/Measurement fields -->
        <div class="form-row">
          <!-- Dropdown field for Type -->
          <div class="form-group half-width">
            <select class="form-control" id="type" name="type" required>
              <option value="" disabled selected></option>
              <option value="Automatic Brake Fluid">Automatic Brake Fluid</option>
              <option value="Brake Fluid">Brake Fluid</option>
              <option value="Engine Oil">Engine Oil</option>
              <option value="Fuel">Fuel</option>
              <option value="Gear Oil">Gear Oil</option>
              <option value="Grease">Grease</option>
              <option value="Hydrolic Oil">Hydrolic Oil</option>
          </select>
            <label for="type">Type</label>
          </div>

          <!-- Joint Quantity and Measurement fields -->
          <div class="form-group half-width joint-field">
            <!-- Quantity Input Field -->
            <div class="joint-input">
              <input type="number" class="form-control" id="quantity" name="quantity" placeholder=" " required>
              <label for="quantity">Quantity</label>
            </div>
            <!-- Measurement Dropdown -->
            <div class="joint-dropdown">
              <select class="form-control" id="measurement" name="measurement" required>
                <option value="" disabled selected></option>
                <!-- Combined Units (Biggest to Smallest) -->
                <option value="Gallons">Gallons</option>
                <option value="Liters">Liters</option>
                <option value="Quarts">Quarts</option>
                <option value="Pints">Pints</option>
                <option value="Pints">Tubs</option>
                <option value="Cups">Cups</option>
                <option value="Pints">Bottles</option>
                <option value="Deciliters">Deciliters</option>
                <option value="Fluid Ounces">Fluid Ounces</option>
                <option value="Centiliters">Centiliters</option>
                <option value="Tablespoons">Tablespoons</option>
                <option value="Teaspoons">Teaspoons</option>
                <option value="Milliliters">Milliliters</option>
            </select>
              <label for="measurement">Measurement</label>
            </div>
          </div>
        </div>

        <!-- Container for Government Car Used and Government Car Number -->
        <div class="form-row">
          <!-- Government Car Used -->
          <div class="form-group half-width">
              <select class="form-control" id="government-car-used" name="government_car_used" required>
                  <option value="" disabled selected></option>
                  @foreach ($vehicles as $vehicle)
                      <option value="{{ $vehicle->equipment_type }}">{{ $vehicle->equipment_type }}</option>
                  @endforeach
              </select>
              <label for="government-car-used">Government Car Used</label>
          </div>
        
          <!-- Government Car Number -->
          <div class="form-group half-width">
              <select class="form-control" id="government-car-number" name="government_car_number" required>
                  <option value="" disabled selected></option>
                  @foreach ($vehicles as $vehicle)
                      <option value="{{ $vehicle->unit }}">{{ $vehicle->unit }}</option>
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
              <option value="" disabled selected></option>
              <option value="ADMIN">ADMIN</option>
              <option value="CONSTRUCTION">CONSTRUCTION</option>
              <option value="MAINTENANCE">MAINTENANCE</option>
              <option value="MOTORPOOL">MOTORPOOL</option>
              <option value="MTQC">MTQC</option>
              <option value="PLANNING">PLANNING</option>
              <option value="SUPPLY">SUPPLY</option>
          </select>
            <label for="division">Division</label>
          </div>
        </div>

  <!-- Submit Button -->
  <div class="form-row" style="justify-content: center;">
    <button type="button" class="show-popup submit-button">Submit</button>
  </div>

  <!-- Popup Overlay -->
  <div class="popup-overlay hidden">
    <div class="popup">
      <h2>SUCCESS</h2>
      <p>Your request has been submitted!</p>
      <button type="submit" class="back-to-dashboard">BACK TO DASHBOARD</button>
    </div>
        </div>
      </div>
      </form>
      <!-- Form ends here -->
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
    <a class="sidebarlink active" style="cursor: default;">Documents</a>
    <a href="{{ route('registry') }}" class="sidebarlink">Registry</a>
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
<script src="{{ asset('assets/js/request.js') }}"></script>
@endpush
@endsection