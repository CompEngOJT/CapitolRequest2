@extends('AdminFuelRequest/layout')
@section('title', 'Document')


@push('styles')
  <!-- Document CSS files -->
  <link href="{{ asset('assets/css/fixed/dashboard-left.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/sidebar-mobile-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/header.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/darkmode.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/help.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/help-contactus-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/document/document-middle.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/sidebar.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Middle Content -->
<div id="middle">
<div id="middle-container">
  <div id="content">
      <h3>AVAILABLE DOCUMENT</h3>
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
</div>





@push('scripts')
<!-- Dashboard JavaScript -->
<script src="{{ asset('assets/js/slidebar.js') }}"></script>
<script src="{{ asset('assets/js/document.js') }}"></script>
<script src="{{ asset('assets/js/dashboard/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/fixed/darkmode.js') }}"></script>
@endpush
@endsection