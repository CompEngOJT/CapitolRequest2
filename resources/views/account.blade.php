@extends('layout')
@section('title', 'Account')
@section('content')

@push('styles')
  <!-- Account CSS files -->
  <link href="{{ asset('assets/css/dashboard/dashboard-left.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/document/document-right.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/account/account-middle.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/account/account-mobile.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/dashboard/dashboard-left-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/document/document-right-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/account/account-middle-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/account/account-mobile-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/account/account-resetpass-popup.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/account/account-success-popup.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/account/account-container-media.css') }}" rel="stylesheet">
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
    <a href="{{ route('registry') }}" class="sidebar-link">Documents</a>
    <a href="{{ route('registry') }}" class="sidebar-link">Registry</a>
    <a href="#" class="sidebar-link">Inventory</a>
    <a href="#" class="sidebar-link">Request History</a>
    <a class="sidebar-link active" style="cursor: default;">Account</a>

  </div>
  <div id="logout">
    <img src="{{ asset('images/logout.png') }}" alt="Logout">
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

<!-- Middle Content -->
<div id="middle-container">
  <div id="middle-content">
      <h1>Online Request System</h1>
      <h3>ACCOUNT</h3>
      <div id="middle-horizontal-line"></div> <!-- Horizontal Line Added -->
      <p>Manage your account in this section</p>

      <div id="middle-account-container"> <!-- Centering Wrapper -->
          <div id="middle-account-card">
              <div id="middle-account-header">
                  <div id="middle-profile-section">
                      <img src="{{ asset('images/profile2.png') }}" alt="Profile" id="middle-profile-image">
                  </div>
              </div>
              <p id="middle-admin-text">Admin</p> <!-- Moved below the header -->
              <div id="middle-account-body">
                  <div class="middle-info-row">
                      <label>Username</label>
                      <span class="middle-info-box">{{ $adminUser->username }}</span> <!-- Display username -->
                  </div>
                  <div class="middle-info-row">
                      <label>Password</label>
                      <span class="password-info-box">************</span> <!-- Masked password -->
                  </div>
                  <!-- Replace the <a> tag with a <button> or <span> -->
                  <button id="middle-reset-password">Reset Password</button>
              </div>
          </div>
      </div>
  </div>
</div>


<!-- Reset Password Popup Overlay -->
<div id="popup-overlay" class="popup-overlay">
  <div id="popup-content" class="popup-content">
      <!-- Header -->
      <div class="popup-header">
          <h2>Reset Password</h2>
      </div>
      <form id="reset-password-form" method="POST" action="{{ route('reset.password') }}">
          <meta name="csrf-token" content="{{ csrf_token() }}">
          <input type="hidden" name="username" value="{{ $adminUser->username }}"> <!-- Include username -->
<label for="new-password">New Password:</label>
<input type="password" id="new-password" name="new_password" required minlength="8">
<div class="error-message">Password must be at least 8 characters long.</div>

<label for="confirm-password">Confirm Password:</label>
<input type="password" id="confirm-password" name="new_password_confirmation" required>
<div class="error-message">Passwords do not match.</div>
          <div class="popup-buttons">
              <button type="button" id="cancel-reset">Cancel</button>
              <button type="submit" id="confirm-reset">Reset</button>
          </div>
      </form>
  </div>
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
      <p>Password Reset Successfully!</p>
  
      <!-- Dashboard Button -->
      <div class="form-buttons">
          <button type="button" id="driver-dashboard-button">DASHBOARD</button>
      </div>
  </div>
</div>



<div id="background-container">
  <div id="background-content">
      <h3>ACCOUNT</h3>
      <div id="background-horizontal-line"></div> <!-- Horizontal Line Added -->
      <p>Manage your account in this section</p>

      <div id="background-account-container"> <!-- Centering Wrapper -->
          <div id="background-account-card">
              <div id="background-account-header">
                  <div id="background-profile-section">
                      <img src="{{ asset('images/profile2.png') }}" alt="Profile" id="background-profile-image">
                  </div>
              </div>
              <p id="background-admin-text">Admin</p> <!-- Moved below the header -->
              <div id="background-account-body">
                  <div class="background-info-row">
                      <label>Username</label>
                      <span class="background-info-box">{{ $adminUser->username }}</span> <!-- Display username -->
                  </div>
                  <div class="background-info-row">
                      <label>Password</label>
                      <span class="password-info-box">************</span> <!-- Masked password -->
                  </div>
                  <!-- Replace the <a> tag with a <button> or <span> -->
                  <button id="background-reset-password">Reset Password</button>
              </div>
          </div>
      </div>
  </div>
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
      <p>Password Reset Successfully!</p>
  
      <!-- Dashboard Button -->
      <div class="form-buttons">
          <button type="button" id="driver-dashboard-button">DASHBOARD</button>
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
    <a href="#" class="sidebarlink">Documents</a>
    <a href="{{ route('registry') }}" class="sidebarlink">Registry</a>
    <a href="#" class="sidebarlink">Request History</a>
    <a class="sidebarlink active" style="cursor: default;">Account</a>
  </div>
  <div id="sidebar-logout">
    <img src="{{ asset('images/logout.png') }}" alt="Logout">
  </div>
</div>

@push('scripts')
<!-- Dashboard JavaScript -->
<script src="{{ asset('assets/js/account.js') }}"></script>
@endpush
@endsection