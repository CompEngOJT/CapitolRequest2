@extends('layout')
@section('title', 'Registry')
@section('content')

@push('styles')
  <!-- Document CSS files -->
  <link href="{{ asset('assets/css/dashboard/dashboard-left.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/document/document-right.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/inventory/inventory-middle.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/inventory/inventory-mobile.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/dashboard/dashboard-left-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/document/document-right-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/inventory/inventory-middle-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/inventory/inventory-mobile-media.css') }}" rel="stylesheet">
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
    <a href="{{route('document')}}" class="sidebar-link">Documents</a>
    <a href="{{ route('registry') }}" class="sidebar-link">Registry</a>
    <a class="sidebar-link active" style="cursor: default;">Inventory</a>
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
        <h3>Inventory</h3>
        <div id="horizontal"></div>
        <p>Check the stock update in this section</p>
        
        <!-- Table Container -->
        <div id="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Total Stocks</th>
                        <th>Last Withdrawal</th>
                        <th>Stock Remaining</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <!-- Rows will be populated dynamically -->
                </tbody>
            </table>
            <!-- Pagination Controls -->
            <div id="pagination-controls">
                <button id="prev-page">Previous</button>
                <span id="page-indicator">Page 1</span>
                <button id="next-page">Next</button>
            </div>
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
    <a class="sidebarlink active" style="cursor: default;">Inventory</a>
    <a href="#" class="sidebarlink">Request History</a>
    <a href="{{ route('account') }}" class="sidebarlink">Account</a>
  </div>
  <div id="sidebar-logout">
    <img src="{{ asset('images/logout.png') }}" alt="Logout">
  </div>
</div>

@push('scripts')
<!-- Dashboard JavaScript -->
<script src="{{ asset('assets/js/inventory.js') }}"></script>
@endpush
@endsection