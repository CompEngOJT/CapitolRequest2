@extends('FuelConsumption.layout')

@section('title', 'AdminInventory')

@push('styles')
    <link href="{{ asset('assets/css/Adminfixed/header.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admininventory/admininventory.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admininventory/admininventory-add-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admininventory/admininventory-view-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admininventory/admininventory-stock-popup.css') }}" rel="stylesheet">
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="content">
    <div id="rectangle">
        <div class="header-container">
            <h1 class="records-title">INVENTORY MANAGEMENT</h1>
            <div class="header-actions">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="searchInput" class="search-input" placeholder="Search products...">
                </div>
                <div class="action-buttons">
                    <button class="add-product-button"><i class="fas fa-plus"></i> Add Product</button>
                    <button class="add-stock-button"><i class="fas fa-warehouse"></i> Add Stock</button>
                    <button class="refresh-btn"><i class="fas fa-sync-alt"></i> Refresh</button>
                </div>
            </div>
        </div>
        
        <div class="table-container">
            <table class="request-table">
                <thead>
                    <tr>
                        <th>Products</th>
                        <th>Total Stocks</th>
                        <th>Last Withdrawal</th>
                        <th>Stock Remaining</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventoryProducts as $product)
                        <tr data-product-id="{{ $product->id }}">
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->total_stocks }}</td>
                            <td>{{ $product->last_withdrawal }}</td>
                            <td>{{ $product->stock_remaining }}</td>
                            <td class="action-column">
                                <button class="action-btn view-btn" title="View Details" data-product-name="{{ $product->product_name }}"><i class="fas fa-eye"></i></button>
                                <button class="action-btn delete-btn-small" title="Delete" data-product-id="{{ $product->id }}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No inventory products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="pagination-container">
                <div class="pagination">
                    {{ $inventoryProducts->onEachSide(1)->links('pagination::bootstrap-4') }}
                </div>
            </div>  
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div id="viewDetailsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalProductName">Product Details</h2>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <div class="product-summary">
                <div class="summary-item">
                    <span class="label">Total Stocks:</span>
                    <span id="modalTotalStocks" class="value"></span>
                </div>
                <div class="summary-item">
                    <span class="label">Last Withdrawal:</span>
                    <span id="modalLastWithdrawal" class="value"></span>
                </div>
                <div class="summary-item">
                    <span class="label">Stock Remaining:</span>
                    <span id="modalStockRemaining" class="value"></span>
                </div>
            </div>
            
            <!-- Date Range Filter - Reorganized on One Line -->
            <div class="date-filter-container">
                <div class="date-filter">
                    <div class="filter-left-section">
                        <div class="date-input-group">
                            <label for="startDate">From:</label>
                            <input type="date" id="startDate" name="startDate">
                        </div>
                        <div class="date-input-group">
                            <label for="endDate">To:</label>
                            <input type="date" id="endDate" name="endDate">
                        </div>
                    </div>
                    <div class="filter-right-section">
                        <button id="applyDateFilter" class="filter-btn">
                            <i class="fas fa-filter"></i> Apply
                        </button>
                        <button id="resetDateFilter" class="reset-btn">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                        <button id="downloadPdfBtn" class="download-btn">
                            <i class="fas fa-download"></i> Download
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="withdrawal-header">
                <h3>Withdrawal Requests</h3>
            </div>
            <div class="withdrawal-requests-container">
                <table class="withdrawal-requests-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Plate Number</th>
                            <th>Driver</th>
                            <th>Stock Before</th>
                            <th>Withdrawal</th>
                            <th>Stock After</th>
                        </tr>
                    </thead>
                    <tbody id="withdrawalRequestsBody">
                        <!-- Request data will be populated here -->
                    </tbody>
                </table>
                <div id="noRequestsMessage" class="hidden">No withdrawal requests found.</div>
                <div id="loadingRequests" class="text-center">
                    <i class="fas fa-spinner fa-spin"></i> Loading requests...
                </div>
                <!-- Added withdrawal requests pagination -->
                <div class="withdrawal-pagination-container">
                    <div class="withdrawal-pagination">
                        <button id="prevPageBtn" class="page-btn"><i class="fas fa-chevron-left"></i> Previous</button>
                        <div id="paginationInfo" class="pagination-info">Page <span id="currentPage">1</span> of <span id="totalPages">1</span></div>
                        <button id="nextPageBtn" class="page-btn">Next <i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Add Product Popup -->
<div id="addProductPopup" class="popup-overlay">
    <div class="popup-content">
        <div class="popup-header">
            <h2>Add New Product</h2>
            <span class="close-popup">&times;</span>
        </div>
        <form id="addProductForm" action="{{ route('admin.inventory.addProduct') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <input type="text" id="product_name" name="product_name" required>
            </div>
            <div class="form-group">
                <label for="total_stocks">Total Stocks:</label>
                <input type="number" id="total_stocks" name="total_stocks" min="0" required>
            </div>
            <div class="form-buttons">
                <button type="button" class="cancel-btn">Cancel</button>
                <button type="submit" class="submit-btn">Add Product</button>
            </div>
        </form>
    </div>
</div>
<!-- Add Stock Popup -->
<div id="addStockPopup" class="popup-overlay">
    <!-- Make sure ID matches what's in your JS -->
    <div class="popup-content">
        <div class="popup-header">
            <h2>Add Stock</h2>
            <span class="close-popup-stock">&times;</span>
        </div>
        <form id="addStockForm">
            <div class="form-group">
                <label for="stock_product_name">Product Name:</label>
                <select id="stock_product_name" name="product_name" required>
                    <option value="">Select Product</option>
                </select>
            </div>
            <div class="form-group">
                <label>Current Stock Remaining: <span id="current_stock_remaining">-</span></label>
            </div>
            <div class="form-group">
                <label for="stock_amount">Stock Amount to Add:</label>
                <input type="number" id="stock_amount" name="stock_amount" min="1" required>
            </div>
            <div class="form-buttons">
                <button type="button" class="cancel-btn-stock">Cancel</button>
                <button type="button" id="submitAddStock" class="submit-btn">Add Stock</button>
            </div>
        </form>
    </div>
</div>     
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/darkmode.js') }}"></script>
<script src="{{ asset('assets/js/AdminInventory/main.js') }}"></script>
<script src="{{ asset('assets/js/AdminInventory/pagination.js') }}"></script>
<script src="{{ asset('assets/js/AdminInventory/pagination2.js') }}"></script>
<script src="{{ asset('assets/js/AdminInventory/search.js') }}"></script>
<script src="{{ asset('assets/js/AdminInventory/addProduct.js') }}"></script>
<script src="{{ asset('assets/js/AdminInventory/refresh.js') }}"></script>
<script src="{{ asset('assets/js/AdminInventory/viewDetails.js') }}"></script>
<script src="{{ asset('assets/js/AdminInventory/delete.js') }}"></script>
<script src="{{ asset('assets/js/AdminInventory/addStock.js') }}"></script>
<script src="{{ asset('assets/js/AdminInventory/download.js') }}"></script>
@endpush