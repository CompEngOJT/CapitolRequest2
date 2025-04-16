@extends('AdminFuelRequest/layout')

@section('title', 'Dashboard')

@push('styles')
  <!-- Dashboard CSS files -->
  <link href="{{ asset('assets/css/fixed/header.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/darkmode.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/help.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/help-contactus-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/dashboard/dashboard-middle.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/dashboard/dashboard-middle-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/dashboard/dashboard-image-popup.css') }}" rel="stylesheet">
  

@endpush

@section('content')
<!-- New parent div for overflow handling -->
<div id="middle">
  <!-- Middle Content -->
  <div id="middle-container">
    <div id="content">
      <!-- Header Section -->
      <h3>REQUEST DOCUMENT FORMS WITH EASE</h3>
      <p>Request your forms <strong>Online</strong>.</p>
      <a href="{{ route('request') }}" id="request-now">Request Now</a>
  
      <!-- Image Container -->
      <div class="image-container">
        @php
          $latestImage = \App\Models\Image::latest()->first();
        @endphp
        @if($latestImage)
        <div class="image-wrapper">
          <img src="{{ asset($latestImage->image_path) }}" alt="Uploaded Image" class="uploaded-image">
          <img src="{{ asset('images/pen2.png') }}" alt="Edit" class="edit-icon trigger-popup">
        </div>
        @else
          <p class="no-image-text">No image uploaded yet.</p>
        @endif
      </div>
      
      <!-- Footer -->
      <div id="footer">
        © Copyright 2025 Provincial Government of Misamis Oriental | Online Request System
      </div>
    </div>
  </div>
</div>

      <!-- Popup Modal -->
      <div id="imagePopup" class="popup">
        <div class="popup-content">
          <span class="close-btn">&times;</span>
          <h2>Upload New Image</h2>
          <form id="imageUploadForm" enctype="multipart/form-data">
            @csrf
            <input type="file" name="image" id="image" class="custom-file-input" required>
            <div class="preview-container" style="display: none; margin-top: 10px;">
              <h4>Preview:</h4>
              <img id="imagePreview" style="max-width: 100%; margin-top: 10px;">
            </div>
            <button type="submit" class="upload-button">Upload Image</button>
          </form>
        </div>
      </div>



@endsection

@push('scripts')
  <!-- Dashboard JavaScript -->
  <script src="{{ asset('assets/js/slidebar.js') }}"></script>
  <script src="{{ asset('assets/js/dashboard/dashboard.js') }}"></script>
  <script src="{{ asset('assets/js/fixed/darkmode.js') }}"></script>
@endpush