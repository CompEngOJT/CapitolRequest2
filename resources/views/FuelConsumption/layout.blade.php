<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laravel AUTH')</title>
    <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @stack('styles')
  </head>
  <body>

        <div id="header">
            <div id="profile-container">
                <!-- Logo and system title moved here -->
                <img src="{{ asset('images/logo.png') }}" alt="Logo" id="header-logo" style="width: 40px; height: auto; margin-right: 10px;">
                <p id="header-system-title" style="margin: 0;">
                    <span class="blue-text">Online</span> Request System
                </p>
            </div>
            <!-- Theme toggle moved to right side of header -->
            <div id="theme-toggle" style="margin-left: auto;">
                <i class="fas fa-lightbulb"></i>
            </div>
        </div>
    
    <div id="sidebar">
        <div id="sidebar-content">
            <!-- Admin profile moved to top of sidebar and styled as column -->
            <div id="sidebar-profile">
                <i class="fas fa-user-circle profile-icon"></i>
                <span class="admin-badge">ADMIN</span>
            </div>
            
            <div class="sidebar-menu">
                <a href="{{ route('admin-overview') }}" class="menu-item {{ $activePage === 'overview' ? 'active' : '' }}" id="overview-link">
                    <i class="fas fa-clock menu-icon"></i>
                    Overview
                </a>
                <a href="{{ route('admin-consumption') }}" class="menu-item {{ $activePage === 'consumption' ? 'active' : '' }}" id="consumption-link">
                    <i class="fas fa-chart-line menu-icon"></i>
                    Consumption
                </a>
                <a href="{{ route('admin-driver') }}" class="menu-item {{ $activePage === 'drivers-list' ? 'active' : '' }}" id="drivers-list-link">
                    <i class="fas fa-list menu-icon"></i>
                    Drivers List
                </a>
                <a href="{{ route('admin-request') }}" class="menu-item {{ $activePage === 'admin-request' ? 'active' : '' }}" id="admin-request-link">
                    <i class="fas fa-file-alt menu-icon"></i>
                    Request Records
                </a>
                <a href="{{ route('admin-inventory') }}" class="menu-item {{ $activePage === 'inventory' ? 'active' : '' }}" id="inventory-link">
                    <i class="fas fa-box menu-icon"></i>
                    Inventory
                </a>
                <a href="{{ route('admin-team') }}" class="menu-item {{ $activePage === 'team' ? 'active' : '' }}" id="team-link">
                    <i class="fas fa-users menu-icon"></i>
                    Team
                </a>
            </div>
        </div>
    </div>
   
    <!-- Main Content -->
    @yield('content')
    <!-- Scripts -->
    <script src="{{ asset('assets/js/bootstrap.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous"></script>
    @stack('scripts')
  </body>
</html>