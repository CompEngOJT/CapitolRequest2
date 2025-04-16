@extends('FuelConsumption.layout')

@section('title', 'AdminRequest')

@push('styles')
    <!-- Link to your dashboard.css -->
    <link href="{{ asset('assets/css/Adminfixed/header.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/AdminRequest/adminrequest.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/AdminRequest/adminrequest-filter-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/AdminRequest/adminrequest-view-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/AdminRequest/admin-download-popup.css') }}" rel="stylesheet">
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="content">
    <div id="rectangle">
        <div id="rectangle">
            <div class="header-container">
                <h1 class="records-title">REQUEST RECORDS</h1>
                <div class="header-actions">
                    <div class="search-container">
                    </div>
                    <div class="action-buttons">
                        <button class="filter-button"><i class="fas fa-filter"></i>Filter </button>
                        <button class="refresh-btn"><i class="fas fa-sync-alt"></i> Refresh</button>
                        <button class="download-btn"><i class="fas fa-download"></i> Download</button>
                    </div>
                </div>
            </div>
            
            <div class="table-container">
                <table class="request-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Plate No.</th>
                            <th>Driver</th>
                            <th>Type</th>
                            <th>Division</th>
                            <th>Requested by</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($driverRequests as $request)
                        <tr data-request-id="{{ $request->id }}" 
                            data-government-car-used="{{ $request->government_car_used }}"
                            data-purpose="{{ $request->purpose }}"
                            data-quantity="{{ $request->quantity }}">
                            <td>{{ date('m/d/Y', strtotime($request->updated_at)) }}</td>
                            <td>{{ $request->government_car_number }}</td>
                            <td>{{ $request->driver_name }}</td>
                            <td>{{ $request->type }}</td>
                            <td>{{ $request->division }}</td>
                            <td>{{ $request->requested_by }}</td>
                            <td>{{ $request->status }}</td>
                            <td class="action-column">
                                <button class="action-btn view-btn" data-id="{{ $request->id }}" title="View Details"><i class="fas fa-eye"></i></button>
                                <button class="action-btn delete-btn-small" title="Delete"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="pagination-container">
                <div class="pagination">
                    {{ $driverRequests->onEachSide(1)->links('pagination::bootstrap-4') }}
                </div>
            </div>

            <!-- Request Details Modal -->
            <div id="request-details-modal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Request Details</h2>
                        <span class="close">&times;</span>
                    </div>
                    <div class="modal-body">
                        <div class="request-detail-container">
                            <!-- Driver Name and Date -->
                            <div class="detail-row">
                                <div class="detail-group">
                                    <label>Driver Name:</label>
                                    <p id="detail-driver-name"></p>
                                    <select id="edit-driver-name" class="edit-field" style="display: none;">
                                        <!-- Options will be populated dynamically -->
                                    </select>
                                </div>
                                <div class="detail-group">
                                    <label>Date:</label>
                                    <p id="detail-date"></p>
                                    <input type="date" id="edit-date" class="edit-field" style="display: none;">
                                </div>
                            </div>

                            <!-- Type and Quantity -->
                            <div class="detail-row">
                                <div class="detail-group">
                                    <label>Type</label>
                                    <p id="detail-type"></p>
                                    <select id="edit-type" class="edit-field" style="display: none;">
                                        <!-- Options will be populated dynamically -->
                                    </select>
                                </div>
                                <div class="detail-group">
                                    <label>Quantity:</label>
                                    <p id="detail-quantity"></p>
                                    <input type="number" id="edit-quantity" class="edit-field" style="display: none;">
                                </div>
                            </div>

                            <!-- Government Car -->
                            <div class="detail-row">
                                <div class="detail-group">
                                    <label>Government Car:</label>
                                    <p id="detail-government-car"></p>
                                    <select id="edit-government-car" class="edit-field" style="display: none;">
                                        <!-- Options will be populated dynamically -->
                                    </select>
                                </div>
                                <div class="detail-group">
                                    <label>Plate Number:</label>
                                    <p id="detail-plate-number"></p>
                                    <select id="edit-plate-number" class="edit-field" style="display: none;">
                                        <!-- Options will be populated dynamically -->
                                    </select>
                                </div>
                            </div>

                            <!-- Place to Visit -->
                            <div class="detail-row">
                                <div class="detail-group">
                                    <label>Place to Visit:</label>
                                    <p id="detail-place-to-visit"></p>
                                    <input type="text" id="edit-place-to-visit" class="edit-field" style="display: none;">
                                </div>
                                <div class="detail-group">
                                    <label>Division:</label>
                                    <p id="detail-division"></p>
                                    <select id="edit-division" class="edit-field" style="display: none;">
                                        <!-- Options will be populated dynamically -->
                                    </select>
                                </div>
                            </div>

                            <!-- Purpose -->
                            <div class="detail-row full-width">
                                <div class="detail-group">
                                    <label>Purpose:</label>
                                    <p id="detail-purpose"></p>
                                    <input type="text" id="edit-purpose" class="edit-field" style="display: none;">
                                </div>
                            </div>

                            <!-- Requested By and Division -->
                            <div class="detail-row">
                                <div class="detail-group">
                                    <label>Requested By:</label>
                                    <p id="detail-requested-by"></p>
                                    <input type="text" id="edit-requested-by" class="edit-field" style="display: none;">
                                </div>
                                <div class="detail-group">
                                    <label>Division:</label>
                                    <p id="detail-division"></p>
                                    <input type="text" id="edit-division" class="edit-field" style="display: none;">
                                </div>
                            </div>
                            <!-- Plate Number and Status -->
                            <div class="detail-row">
                                <div class="detail-group">
                                    <label>Status:</label>
                                    <p id="detail-status"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="edit-modal-btn" class="btn btn-edit">Edit</button>
                        <button id="save-modal-btn" class="btn btn-save" style="display: none;">Save</button>
                        <button id="cancel-modal-btn" class="btn btn-cancel" style="display: none;">Cancel</button>
                        <button id="approve-modal-btn" class="btn btn-approve" style="display: none;">Approve</button>
                        <button id="disapprove-modal-btn" class="btn btn-disapprove" style="display: none;">Disapprove</button>
                        <button id="close-modal-btn" class="btn">Close</button>
                    </div>
                </div>
            </div>

            <!-- Download Modal -->
            <div id="download-modal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Download Request Records</h2>
                        <span class="close">&times;</span>
                    </div>
                    <div class="modal-body">
                        <div class="filter-container">
                            <div class="date-range-filter">
                                <div class="date-input-group">
                                    <label for="start-date">From:</label>
                                    <input type="date" id="start-date" class="date-input">
                                </div>
                                <div class="date-input-group">
                                    <label for="end-date">To:</label>
                                    <input type="date" id="end-date" class="date-input">
                                </div>
                                <button id="apply-filter" class="btn btn-filter">Apply Filter</button>
                            </div>
                        </div>
                        <div class="download-options">
                            <label class="select-all-container">
                                <input type="checkbox" id="select-all-requests">
                                <span class="checkmark"></span>
                                Select All
                            </label>
                            <div class="request-list">
                                <!-- Request items will be populated dynamically -->
                            </div>
                        </div>
                        <div class="format-options">
                            <label>Format:</label>
                            <select id="download-format">
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="cancel-download" class="btn btn-cancel">Cancel</button>
                        <button id="confirm-download" class="btn btn-approve">Download</button>
                    </div>
                </div>
            </div>

            <!-- Filter Popup -->
            <div id="filter-popup" class="filter-popup">
                <div class="filter-popup-content">
                    <div class="filter-header">
                        <h3>Filter Records</h3>
                        <button id="close-filter" class="close-filter">&times;</button>
                    </div>
                    <div class="filter-body">
                        <div class="filter-group">
                            <label>Driver</label>
                            <select id="filter-driver" name="driver_id" class="filter-input">
                                <option value="">All Drivers</option>
                                @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ request('driver_id') == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->last_name }}, {{ $driver->first_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Date Range</label>
                            <div class="date-range">
                                <input type="date" id="filter-date-from" name="date_from" class="filter-input" value="{{ request('date_from') }}">
                                <span>to</span>
                                <input type="date" id="filter-date-to" name="date_to" class="filter-input" value="{{ request('date_to') }}">
                            </div>
                        </div>
                        <div class="filter-group">
                            <label>Type</label>
                            <select id="filter-type" name="type" class="filter-input">
                                <option value="">All Types</option>
                                @foreach($types as $type)
                                <option value="{{ $type->product_name }}" {{ request('type') == $type->product_name ? 'selected' : '' }}>
                                    {{ $type->product_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label>Division</label>
                            <select id="filter-division" name="division" class="filter-input">
                                <option value="">All Divisions</option>
                                @foreach($divisions as $division)
                                <option value="{{ $division->name }}" {{ request('division') == $division->name ? 'selected' : '' }}>
                                    {{ $division->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="filter-footer">
                        <button id="reset-filter" class="reset-filter">Reset</button>
                        <button id="apply-filter" class="apply-filter">Apply Filters</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/AdminRequest/AdminRequest.js') }}"></script>
<script src="{{ asset('assets/js/AdminRequest/view.js') }}"></script>
<script src="{{ asset('assets/js/AdminRequest/filter.js') }}"></script>
<script src="{{ asset('assets/js/AdminRequest/delete.js') }}"></script>
<script src="{{ asset('assets/js/AdminRequest/pagination.js') }}"></script>
<script src="{{ asset('assets/js/AdminRequest/refresh.js') }}"></script>
<script src="{{ asset('assets/js/AdminRequest/download.js') }}"></script>
<script src="{{ asset('assets/js/darkmode.js') }}"></script>
@endpush