@extends('AdminFuelRequest/layout')
@section('title', 'Account')


@push('styles')
  <!-- Account CSS files -->
  <link href="{{ asset('assets/css/fixed/dashboard-left.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/header.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/darkmode.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/help.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/help-contactus-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/account/account-middle.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/account/account-middle-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/account/account-resetpass-popup.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/account/account-success-popup.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/sidebar.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Middle Content -->
<div id= "middle">
<div id="middle-container">
  <div id="middle-content">
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




@push('scripts')
<!-- Dashboard JavaScript -->
<script src="{{ asset('assets/js/slidebar.js') }}"></script>
<script src="{{ asset('assets/js/account.js') }}"></script>
<script src="{{ asset('assets/js/fixed/darkmode.js') }}"></script>
@endpush
@endsection