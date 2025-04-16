<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laravel AUTH')</title>
    <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/fixed/dashboard-left.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/fixed/contactus.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/fixed/sidebar.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @stack('styles')
  </head>
  <body>
        <!-- Back button prevention script -->
        <script>
          (function() {
              // Check authentication status on page load and back navigation
              if (!{{ Auth::guard('admin')->check() ? 'true' : 'false' }} && 
                  document.referrer.includes(window.location.hostname)) {
                  // User is not authenticated and coming from our site (back button)
                  window.location.replace("{{ route('login') }}");
              }
          })();
      </script>
  
    <!-- Header -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div id="main-header">
      <div id="header-logo">
        <button class="sidebar-toggle" id="sidebarToggle">
          <i class="fas fa-bars"></i>
        </button>
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
        <span class="header-title">Online Request System</span>
      </div>
      <div id="header-controls">
        <div class="header-icon" id="header-help" title="Help">
          <i class="fas fa-question-circle"></i>
        </div>
        <div class="header-icon" id="header-darkmode" title="Dark Mode">
          <i class="fas fa-moon"></i>
        </div>
        <div class="header-icon" id="header-contact" title="Contact Us">
          <i class="fas fa-envelope"></i>
        </div>
        <div class="header-icon" id="header-logout" title="Logout">
          <i class="fas fa-sign-out-alt"></i>
        </div>
      </div>
    </div>

    
    <!-- Left Sidebar -->
    <div id="container">
    <div id="profile">
      <div class="profile-icon">
        <i class="fas fa-user-circle"></i>
      </div>
    </div>
      <div class="admin-badge">ADMIN</div>
      
      <!-- Left Sidebar -->
      <div id="sidebar">
        <a href="{{ route('dashboard') }}" class="sidebar-link {{ $activePage === 'dashboard' ? 'active' : '' }}" style="cursor: pointer;">Dashboard</a>
        <a href="{{ route('document') }}" class="sidebar-link {{ $activePage === 'document' ? 'active' : '' }}">Documents</a>
        <a href="{{ route('registry') }}" class="sidebar-link {{ $activePage === 'registry' ? 'active' : '' }}">Registry</a>
        <a href="{{ route('inventory') }}" class="sidebar-link {{ $activePage === 'inventory' ? 'active' : '' }}">Inventory</a>
        <a href="{{ route('history') }}" class="sidebar-link {{ $activePage === 'history' ? 'active' : '' }}">Request History</a>
        <a href="{{ route('account') }}" class="sidebar-link {{ $activePage === 'account' ? 'active' : '' }}">Account</a>
      </div>
    </div>


<!-- Help Popup Overlay -->
<div id="help-overlay" class="overlay">
  <div class="help-popup-content">
    <div class="popup-header">
      <h3>How to Create a Request</h3>
      <span class="close-btn" id="close-help">&times;</span>
    </div>
    <div class="popup-body">
      <h4>3 EASY STEPS TO CREATE A REQUEST</h4>
      
      <div class="step-container">
        <div class="step">
          <div class="step-number">Step #1</div>
          <div class="step-content">
            <button class="request-now-btn">Request Now</button>
            <div class="step-text">Click the "Request Now" button</div>
          </div>
        </div>
        
        <div class="step">
          <div class="step-number">Step #2</div>
          <div class="step-content">
            <div class="step-image">
              <img src="{{ asset('images/fuel.png') }}" alt="Request Slip">
            </div>
            <div class="step-text">Click the "Request Slip"</div>
          </div>
        </div>
        
        <div class="step">
          <div class="step-number">Step #3</div>
          <div class="step-content">
            <button class="submit-btn">Submit</button>
            <div class="step-text">Fill out the form and hit the "Submit" button</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Contact Us Popup Overlay -->
<div id="contactus-overlay" class="contactus-overlay">
  <div class="contactus-popup-content">
    <div class="contactus-popup-header">
      <h3>Contact Our Team</h3>
      <span class="contactus-close-btn" id="close-contactus">&times;</span>
    </div>
    <div class="contactus-popup-body">
      <div class="contactus-subtitle">Need help? Reach out to our development team.</div>
      
      <div class="contactus-grid">
        <div class="contactus-card">
          <div class="contactus-image">
            <img src="{{ asset('images/clark.jpg') }}" alt="Clark Joross Lim" onerror="this.src='{{ asset('images/default-profile.png') }}'">
          </div>
          <div class="contactus-info">
            <h4>Clark Joross Lim</h4>
            <p>Backend Developer</p>
            <div class="contactus-buttons">
              <a href="https://mail.google.com/mail/?view=cm&fs=1&to=lim.clark@gmail.com&su=Question about Online Request System" target="_blank" class="contactus-btn email-btn">
                <i class="fas fa-envelope"></i> Email
              </a>
              <a href="https://www.facebook.com/clark.lim.7739" target="_blank" class="contactus-btn facebook-btn">
                <i class="fab fa-facebook-f"></i> Facebook
              </a>
            </div>
          </div>
        </div>

        <div class="contactus-card">
          <div class="contactus-image">
            <img src="{{ asset('images/jea.png') }}" alt="Jea Maica D. Repuno" onerror="this.src='{{ asset('images/default-profile.png') }}'">
          </div>
          <div class="contactus-info">
            <h4>Jea Maica D. Repuno</h4>
            <p>Backend Developer</p>
            <div class="contactus-buttons">
              <a href="https://mail.google.com/mail/?view=cm&fs=1&to=repuno.jeamaica18@gmail.com&su=Question about Online Request System" class="contactus-btn email-btn">
                <i class="fas fa-envelope"></i> Email
              </a>
              <a href="https://www.facebook.com/profile.php?id=61569626549715&rdid=PQsEJhDuOO0XqgoP&share_url=https%3A%2F%2Fwww.facebook.com%2Fshare%2F15y7sqWZqe%2F#" target="_blank" class="contactus-btn facebook-btn">
                <i class="fab fa-facebook-f"></i> Facebook
              </a>
            </div>
          </div>
        </div>

        <div class="contactus-card">
          <div class="contactus-image">
            <img src="{{ asset('images/jio.png') }}" alt="Jio Q. Daging" onerror="this.src='{{ asset('images/default-profile.png') }}'">
          </div>
          <div class="contactus-info">
            <h4>Jio Q. Daging</h4>
            <p>UI/UX Developer</p>
            <div class="contactus-buttons">
              <a href="https://mail.google.com/mail/?view=cm&fs=1&to=dagingjio@gmail.com&su=Question about Online Request System" class="contactus-btn email-btn">
                <i class="fas fa-envelope"></i> Email
              </a>
              <a href="https://www.facebook.com/jdaging09" target="_blank" class="contactus-btn facebook-btn">
                <i class="fab fa-facebook-f"></i> Facebook
              </a>
            </div>
          </div>
        </div>

        <div class="contactus-card">
          <div class="contactus-image">
            <img src="{{ asset('images/john.jpg') }}" alt="John Kristofer A. Go" onerror="this.src='{{ asset('images/default-profile.png') }}'">
          </div>
          <div class="contactus-info">
            <h4>John Kristofer A. Go</h4>
            <p>UI/UX Developer</p>
            <div class="contactus-buttons">
              <a href="https://mail.google.com/mail/?view=cm&fs=1&to=go.johnkristofer32@gmail.com&su=Question about Online Request System" class="contactus-btn email-btn">
                <i class="fas fa-envelope"></i> Email
              </a>
              <a href="https://www.facebook.com/tofffy32" target="_blank" class="contactus-btn facebook-btn">
                <i class="fab fa-facebook-f"></i> Facebook
              </a>
            </div>
          </div>
        </div>
      </div>
      
      <div class="contactus-footer">
        <p>For urgent matters, please contact our support line: <strong>(123) 456-7890</strong></p>
      </div>
    </div>
  </div>
</div>
    <!-- Main Content -->
    @yield('content')

    <!-- Scripts -->
    
    <script src="{{ asset('assets/js/fixed/help.js') }}"></script>
    <script src="{{ asset('assets/js/fixed/contactus.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/logout.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    @stack('scripts')
  </body>
</html>