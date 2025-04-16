@extends('FuelConsumption.layout')

@section('title', 'AdminDriverList')

@push('styles')
    <!-- Link to your dashboard.css -->
    <link href="{{ asset('assets/css/Adminfixed/header.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/AdminDriver/admindriver.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/AdminDriver/adddriver-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/AdminDriver/consumption-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/AdminDriver/edit-popup.css') }}" rel="stylesheet">
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="content">
    <div id="rectangle">
        <div class="drivers-header">
            <h2>ALL DRIVERS</h2>
            <div class="drivers-actions">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="driverSearch" class= "driver-search" placeholder="Search Driver">
                </div>
                <button class="new-driver-btn">
                    <i class="fas fa-plus"></i> New Driver
                </button>
            </div>
        </div>

        <div class="table-container">
            <table class="drivers-table">
                <thead>
                    <tr>
                        <th>Driver's Name</th>
                        <th>Position</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($drivers as $driver)
                    <tr data-driver-id="{{ $driver->id }}">
                        <td>{{ $driver->first_name }} {{ $driver->last_name }}</td>
                        <td>{{ $driver->position }}</td>
                        <td class="action-buttons">
                            <button class="action-btn view-btn" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn edit-btn" title="Edit Driver">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn delete-btn-small" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Add pagination if needed -->
            <div class="pagination-container">
                <div class="pagination">
                    {{ $drivers->onEachSide(1)->links('pagination::bootstrap-4') }}
                </div>
        </div>
    </div>
</div>

<!-- Add Driver Modal -->
<div id="addDriverModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Add New Driver</h3>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body">
            <form id="addDriverForm" method="POST" action="{{ route('admin.driver.store') }}">
                @csrf
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="position">Position</label>
                    <input type="text" id="position" name="position" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="cancel-btn">Cancel</button>
                    <button type="submit" class="save-btn">Save Driver</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Driver Consumption Report Modal -->
<div id="driverConsumptionModal" class="modal">
    <div class="consumption-modal-wrapper">
        <div class="consumption-header">
            <h3>Driver's Consumption Report</h3>
            <span class="close">&times;</span>
        </div>
        <div class="consumption-body">
            <div id="consumptionDriverInfo" class="consumption-driver-details">
                <h4>Driver: <span id="driverNameDisplay"></span></h4>
            </div>
            
            <div class="consumption-products-section">
                <!-- Main products view -->
                <div id="productsMainSection">
                    <h4>PRODUCTS</h4>
                    <div id="consumptionProductList">
                        <!-- Product consumption items will be populated here dynamically -->
                    </div>
                </div>
                
                <!-- Product details view (initially hidden) -->
                <div id="productDetailsSection" class="product-details-section" style="display: none;">
                    <div class="product-details-header">
                        <button id="backToProductsBtn" class="back-btn">&larr; Back to Products</button>
                        <h4>Product: <span id="productNameDisplay"></span></h4>
                        <button id="exportDriverConsumptionBtn" class="apply-filter-btn" style="margin-left: auto;">Export to PDF</button>
                        <div id="pdfLoadingIndicator" class="loading-state" style="display: none;">
                            <div class="loading-spinner"></div>
                            <div>Generating PDF...</div>
                        </div>
                    </div>
                    <div class="consumption-date-filters">
                        <div class="consumption-date-range-start">
                            <label for="consumptionStartDate">Start Date</label>
                            <input type="date" id="consumptionStartDate" class="consumption-date-input">
                        </div>
                        <div class="consumption-date-range-end">
                            <label for="consumptionEndDate">End Date</label>
                            <input type="date" id="consumptionEndDate" class="consumption-date-input">
                        </div>
                        <button id="applyDateFiltersBtn" class="apply-filter-btn">Apply Filters</button>
                    </div>
                    <div id="productRequestsList" class="product-requests-list">
                        <!-- Product request details will be populated here -->
                    </div>
                    <div id="requestPagination" class="pagination-controls">
                        <!-- Pagination controls will be added here -->
                    </div>
                </div>
            </div>
            
            <div class="consumption-actions">
                <button class="consumption-close-btn">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Edit Driver Modal -->
<div id="editDriverModal" class="modal">
    <div class="edit-modal-content">
        <div class="edit-modal-header">
            <h3>Edit Driver</h3>
            <span class="edit-close">&times;</span>
        </div>
        <div class="edit-modal-body">
            <form id="editDriverForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_driver_id" name="driver_id">
                <div class="form-group">
                    <label for="edit_first_name">First Name</label>
                    <input type="text" id="edit_first_name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="edit_last_name">Last Name</label>
                    <input type="text" id="edit_last_name" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="edit_position">Position</label>
                    <input type="text" id="edit_position" name="position" required>
                </div>
                <div class="form-group">
                    <label for="edit_status">Status</label>
                    <select id="edit_status" name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="edit-form-actions">
                    <button type="button" class="edit-cancel-btn">Cancel</button>
                    <button type="submit" class="edit-save-btn">Update Driver</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/darkmode.js') }}"></script>
<script src="{{ asset('assets/js/admindriver/admindriver.js') }}"></script>
<script src="{{ asset('assets/js/admindriver/pagination.js') }}"></script>
<script src="{{ asset('assets/js/admindriver/search.js') }}"></script>
<script src="{{ asset('assets/js/admindriver/adddriver.js') }}"></script>
<script src="{{ asset('assets/js/admindriver/delete.js') }}"></script>
<script src="{{ asset('assets/js/admindriver/view.js') }}"></script>
<script src="{{ asset('assets/js/admindriver/download.js') }}"></script>
<script src="{{ asset('assets/js/admindriver/edit.js') }}"></script>
@endpush