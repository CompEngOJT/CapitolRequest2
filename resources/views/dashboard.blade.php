@extends('layout')
@section('title', 'Dashboard')
@section('content')

@push('styles')
  <!-- Dashboard CSS files -->
  <link href="{{ asset('assets/css/dashboard/dashboard-left.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/dashboard/dashboard-right.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/dashboard/dashboard-middle.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/dashboard/dashboard-mobile.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/dashboard/dashboard-left-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/dashboard/dashboard-right-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/dashboard/dashboard-middle-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/dashboard/dashboard-mobile-media.css') }}" rel="stylesheet">
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
    <span class="sidebar-link active" style="cursor: default;">Dashboard</span>
    <a href="{{route('document')}}" class="sidebar-link">Documents</a>
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
    <h3>REQUEST DOCUMENT FORMS WITH EASE</h3>
    <p>Request your forms <strong>Online</strong>.</p>
    <a href="{{ route('request')}}" id="request-now">Request Now</a>
    <div id="paglaum">
      <img src="{{ asset('images/paglaum.png') }}" alt="Paglaum">
    </div>
    <div id="copyright">
      <img src="{{ asset('images/copyright.png') }}" alt="Copyright">
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
  <!-- Header -->
  <div id="background-header">
    <h1>REQUEST DOCUMENT FORMS WITH EASE</h1>
    <p>Request your forms <strong>Online</strong></p>
    <!-- Request Now Button -->
    <a href="{{ route('request')}}" id="request-now1">Request Now</a>
  </div>

  <!-- Paglaum Image -->
  <div id="paglaum-image">
    <img src="{{ asset('images/paglaum.png') }}" alt="Paglaum">
  </div>

  <!-- Copyright Image -->
  <div id="copyright-image">
    <img src="{{ asset('images/copyright.png') }}" alt="Copyright">
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
    <span class="sidebarlink active" style="cursor: default;">Dashboard</span>
    <a href="{{ route('document') }}" class="sidebarlink">Documents</a>
    <a href="{{ route('document') }}" class="sidebarlink">Registry</a>
    <a href="#" class="sidebarlink">Inventory</a>
    <a href="#" class="sidebarlink">Request History</a>
    <a href="#" class="sidebarlink">Account</a>
  </div>
  <div id="sidebar-logout">
    <img src="{{ asset('images/logout.png') }}" alt="Logout">
  </div>
</div>

@push('scripts')
<!-- Dashboard JavaScript -->
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
@endpush
@endsection