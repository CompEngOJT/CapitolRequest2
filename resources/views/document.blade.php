@extends('layout')
@section('title', 'Document')
@section('content')

@push('styles')
  <!-- Document CSS files -->
  <link href="{{ asset('assets/css/dashboard/dashboard-left.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/document/document-right.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/document/document-middle.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/document/document-mobile.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/dashboard/dashboard-left-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/document/document-right-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/document/document-middle-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/document/document-mobile-media.css') }}" rel="stylesheet">
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
    <a href="{{ route('registry') }}" class="sidebar-link">Request History</a>
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
      <h3>AVAILABLE DOCUMENT</h3>
      <div id="horizontal"></div>
      <p>Request document for maintenance in this section</p>
      <div id="square">
          <div id="fuel">
              <a href="{{route('request')}}" id="fuel-link">
                  <img src="{{asset('images/fuel.png') }}" alt="Fuel">
              </a>
          </div>
      </div>
      <h2>Request Slip</h2>
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
    <h3>AVAILABLE DOCUMENT</h3>
    <div id="horizontal"></div>
    <p>Request document for maintenance in this section</p>
    <div id="square">
        <div id="fuel">
            <a href="{{route('request')}}" id="fuel-link">
                <img src="{{asset('images/fuel.png') }}" alt="Fuel">
            </a>
        </div>
    </div>
    <h2>Request Slip</h2>
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
    <a href="{{ route('account') }}"class="sidebarlink">Account</a>
  </div>
  <div id="sidebar-logout">
    <img src="{{ asset('images/logout.png') }}" alt="Logout">
  </div>
</div>

@push('scripts')
<!-- Dashboard JavaScript -->
<script src="{{ asset('assets/js/document.js') }}"></script>
@endpush
@endsection