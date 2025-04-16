@extends('AdminFuelRequest/layout')
@section('title', 'Inventory')


@push('styles')
  <!-- Document CSS files -->
  <link href="{{ asset('assets/css/fixed/dashboard-left.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/header.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/darkmode.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/help.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/help-contactus-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/inventory/inventory-middle.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/inventory/inventory-middle-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/inventory/inventory-view-popup.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/inventory/inventory-add-stock.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/inventory/inventory-add-product.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/sidebar.css') }}" rel="stylesheet">
@endpush
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Middle Content -->
<div id="middle">
    <div id="middle-container">
      <div id="content">
        <h3>Inventory</h3>
        <p>Check the stock update in this section</p>
  
        <!-- Header for the table -->
        <div class="table-header">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>All Stocks</h2>
            <div class="action-buttons">
              <button id="add-stock-btn" class="add-stock-btn">
                <i class="fas fa-plus"></i> Add Stock
              </button>
              <button id="add-product-btn" class="add-product-btn">
                <i class="fas fa-plus"></i> Add Product
              </button>
            </div>
          </div>
        </div>
             
        <!-- Table Container -->
        <div id="table-container">
          <!-- Inventory Table -->
          <table class="request-table">
            <thead>
              <tr class="even-row">
                <th>Product</th>
                <th>Total Stocks</th>
                <th>Last Withdrawal</th>
                <th>Stock Remaining</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="table-body">
              @forelse ($stocks as $index => $stock)
                <tr class="{{ $loop->index % 2 === 0 ? 'odd-row' : 'even-row' }}">
                  <td>{{ $stock->product }}</td>
                  <td>{{ $stock->totalStocks }}</td>
                  <td>{{ $stock->lastWithdrawal }}</td>
                  <td>{{ $stock->stockRemaining }}</td>
                  <td>
                    <div class="action-icons">
                      <button class="action-icon view-icon view-btn">
                        <i class="fas fa-eye"></i>
                      </button>
                      <div class="action-icon delete-icon">
                        <i class="fas fa-trash"></i>
                      </div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center">No inventory data found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
  
          <!-- Pagination -->
          <div class="pagination-container">
            <div class="pagination">
              {{ $stocks->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
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
      <div class="product-details">
        <div class="detail-item">
          <span class="detail-label">Total Stocks:</span>
          <span id="modalTotalStocks"></span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Last Withdrawal:</span>
          <span id="modalLastWithdrawal"></span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Stock Remaining:</span>
          <span id="modalStockRemaining"></span>
        </div>
      </div>
      
      <div class="withdrawal-history">
        <h3>Withdrawal History</h3>
        
        <div class="date-filter">
          <div class="filter-controls">
            <div class="filter-group">
              <label for="startDate">Start Date:</label>
              <input type="date" id="startDate" name="startDate">
            </div>
            <div class="filter-group">
              <label for="endDate">End Date:</label>
              <input type="date" id="endDate" name="endDate">
            </div>
            <div class="filter-buttons">
              <button id="applyDateFilter" class="filter-btn">Apply Filter</button>
              <button id="resetDateFilter" class="filter-btn">Reset</button>
            </div>
          </div>
          <div class="export-controls">
            <button id="exportPdfBtn" class="export-btn">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
              </svg>
              Export to PDF
            </button>
          </div>
        </div>
        
        <div id="loadingRequests" class="loading-indicator">Loading...</div>
        
        
        <div class="table-container">
          <table class="withdrawal-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Gov. Car #</th>
                <th>Driver Name</th>
                <th>Stock Before</th>
                <th>Quantity</th>
                <th>Stock After</th>
              </tr>
            </thead>
            <tbody id="withdrawalRequestsBody">
              <!-- Withdrawal requests will be populated here -->
            </tbody>
          </table>
          <div id="noRequestsMessage" class="no-data-message hidden">No withdrawal requests found.</div>
        </div>
        
        <div class="pagination-controls">
          <button id="prevPageBtn" class="page-btn" disabled>Previous</button>
          <span class="page-info">Page <span id="currentPage">1</span> of <span id="totalPages">1</span></span>
          <button id="nextPageBtn" class="page-btn" disabled>Next</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Add Stock Dialog (new naming convention) -->
<div id="addStockDialog" class="stock-dialog">
  <div class="stock-dialog-content">
    <div class="stock-dialog-header">
      <h3>Add Stock</h3>
      <span class="close-stock-dialog">&times;</span>
    </div>
    <div class="stock-dialog-body">
      <!-- Success/Error alert will be inserted here -->
      <form id="add-stock-form">
        <div class="form-group">
          <label for="stockProduct">Select Product:</label>
          <select id="stockProduct" name="stockProduct" required>
            <option value="">-- Select Product --</option>
            @foreach ($allProducts as $product)
              <option value="{{ $product->product }}">{{ $product->product }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label for="stockQuantity">Quantity to Add:</label>
          <input type="number" id="stockQuantity" name="stockQuantity" min="1" required>
        </div>
        <div class="form-actions">
          <button type="button" class="stock-cancel-btn">Cancel</button>
          <button type="submit" class="stock-submit-btn">Add Stock</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Add Product Dialog -->
<div id="addProductDialog" class="stock-dialog">
  <div class="stock-dialog-content">
    <div class="stock-dialog-header">
      <h3>Add New Product</h3>
      <span class="close-product-dialog">&times;</span>
    </div>
    <div class="stock-dialog-body">
      <div id="productDialogAlert" class="dialog-alert hidden"></div>
      <form id="add-product-form">
        <div class="form-group">
          <label for="productName">Product Name:</label>
          <input type="text" id="productName" name="productName" required>
        </div>
        <div class="form-group">
          <label for="initialStock">Initial Stock Quantity:</label>
          <input type="number" id="initialStock" name="initialStock" min="0" required>
        </div>
        <div class="form-actions">
          <button type="button" class="product-cancel-btn">Cancel</button>
          <button type="submit" class="product-submit-btn">Add Product</button>
        </div>
      </form>
    </div>
  </div>
</div>


@push('scripts')
<!-- Dashboard JavaScript -->
<script src="{{ asset('assets/js/slidebar.js') }}"></script>
<script src="{{ asset('assets/js/inventory/viewdetailsmodal.js') }}"></script>
<script src="{{ asset('assets/js/inventory/addstock.js') }}"></script>
<script src="{{ asset('assets/js/inventory/addproduct.js') }}"></script>
<script src="{{ asset('assets/js/inventory/inventory.js') }}"></script>
<script src="{{ asset('assets/js/inventory/download.js') }}"></script>
<script src="{{ asset('assets/js/inventory/delete.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/fixed/darkmode.js') }}"></script>
@endpush
@endsection