@extends('FuelConsumption.layout')

@section('title', 'AdminOverview')

@push('styles')
<!-- Your core stylesheets -->
<link href="{{ asset('assets/css/Adminfixed/header.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/AdminOverview/adminoverview.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/AdminOverview/carousel.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/AdminOverview/calendar.css') }}" rel="stylesheet">

<!-- External libraries -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

@endpush

@section('content')
<div id="content">
<!-- Add this in your head section -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid">
    <!-- First Row: Carousel and Calendar side by side -->
    <div class="row">
        <!-- Carousel Column -->
        <div class="col-md-6">
            <div id="rectangle" class="mb-4">
                <div class="carousel-container">
                    <div id="imageCarousel" class="carousel">
                        @if(count($carouselImages) > 0)
                            @foreach($carouselImages as $image)
                                <div class="carousel-item">
                                    <img src="{{ asset('storage/carousel/'.$image->image_path) }}" alt="Carousel Image">
                                    <img src="{{ asset('images/pen2.png') }}" alt="Edit" class="edit-icon trigger-popup" data-image-id="{{ $image->id }}">
                                </div>
                            @endforeach
                        @else
                            <div class="carousel-item">
                                <div class="empty-carousel">
                                    <p>No images uploaded yet. Click + to add images.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    <!-- Navigation controls inside carousel -->
                    <div class="carousel-controls">
                        <button type="button" class="prev-slide"><i class="fas fa-chevron-left"></i></button>
                        <button type="button" class="next-slide"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Rest of your columns/content here -->


<!-- Image Management Popup Modal -->
<div id="imagePopup" class="popup">
    <div class="popup-content">
        <span class="close-btn">&times;</span>
        <h2>Manage Gallery Image</h2>
        
        <!-- Tab Navigation -->
        <div class="tab-navigation">
            <button class="tab-btn active" data-tab="edit-tab">Edit Image</button>
            <button class="tab-btn" data-tab="add-tab">Add New Image</button>
        </div>
        
        <!-- Edit Image Tab -->
        <div id="edit-tab" class="tab-content active">
            <form id="imageEditForm" action="{{ route('carousel.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="image_id" id="editImageId">
                <input type="file" name="image" id="editImageInput" class="custom-file-input">
                <div class="preview-container" style="display: none; margin-top: 10px;">
                    <h5>Preview:</h5>
                    <img id="editImagePreview" style="max-width: 100%; margin-top: 10px;">
                </div>
                <div class="button-group mt-3">
                    <button type="submit" class="btn btn-primary">Update Image</button>
                    <button type="button" id="deleteImageBtn" class="btn btn-danger">Delete Image</button>
                </div>
            </form>
        </div>
        
        <!-- Add New Image Tab -->
        <div id="add-tab" class="tab-content">
            <form id="carouselUploadForm" action="{{ route('carousel.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="carousel_image" id="carouselImageInput" class="custom-file-input" accept="image/*" required>
                <div class="preview-container" style="display: none; margin-top: 10px;">
                    <h5>Preview:</h5>
                    <img id="imagePreview" style="max-width: 100%; margin-top: 10px;">
                </div>
                <button type="submit" class="btn btn-primary mt-3">Upload Image</button>
                <div id="uploadStatus" class="mt-2"></div>
            </form>
        </div>
    </div>
</div>
            
            <!-- Calendar Column -->
            <div class="col-md-6">
                <div id="rectangle" class="mb-4">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
        

        
<!-- Second Row: Fuel Usage Chart -->
<div class="row">
    <div class="col-12">
        <div id="rectangle" class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="product-filter">
                    <select id="productTypeSelect" class="form-select form-select-sm">
                        <option value="">All Products</option>
                        @foreach($productTypes as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="date-filter">
                    <select id="dateRangeSelect" class="form-select form-select-sm">
                        <option value="week">This Week</option>
                        <option value="month" selected>This Month</option>
                        <option value="year">This Year</option>
                    </select>
                </div>
            </div>
            <div class="chart-container" style="position: relative; height:300px;">
                <canvas id="fuelUsageChart"></canvas>
            </div>
        </div>
    </div>
</div>
        
        <!-- Third Row: Recent Transactions -->
        <div class="row">
            <div class="col-12">
                <div id="rectangle">
                    <h5 class="section-title">Request Transactions</h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Plate No.</th>
                                    <th>Driver</th>
                                    <th>Type</th>
                                    <th>Division</th>
                                    <th>Requested By</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($recentTransactions) > 0)
                                    @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td>{{ date('m/d/Y', strtotime($transaction->created_at)) }}</td>
                                        <td>{{ $transaction->government_car_number }}</td>
                                        <td>{{ $transaction->driver_name }}</td>
                                        <td>{{ $transaction->type }}</td>
                                        <td>{{ $transaction->division }}</td>
                                        <td>{{ $transaction->requested_by }}</td>
                                        <td>{{ $transaction->status }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">No recent transactions found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection

@push('scripts')
<!-- Core jQuery first -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- FullCalendar core and all plugins in one file -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
<!-- All other scripts -->
<script src="{{ asset('assets/js/darkmode.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>


<!-- Your custom scripts -->
<script src="{{ asset('assets/js/AdminOverview/carousel.js') }}"></script>
<script src="{{ asset('assets/js/AdminOverview/chart.js') }}"></script>
<!-- Calendar script last -->
<script src="{{ asset('assets/js/AdminOverview/calendar.js') }}"></script>
@endpush