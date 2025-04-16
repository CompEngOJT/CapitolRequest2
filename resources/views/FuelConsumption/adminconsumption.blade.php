@extends('FuelConsumption.layout')

@section('title', 'AdminConsumption')

@push('styles')
    <!-- Link to your dashboard.css -->
    <link href="{{ asset('assets/css/Adminfixed/header.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/AdminConsumption/adminconsumption.css') }}" rel="stylesheet">
    <!-- Add Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endpush

@section('content')
<div id="content">
    <div id="rectangle">
        <!-- Summary Stats -->
        <div class="summary-stats">
            <div class="stat-box">
                <h3>Total Products</h3>
                <div class="stat-value">{{ $totalProducts }}</div>
            </div>
            <div class="stat-box">
                <h3>Most Requested</h3>
                <div class="stat-value">{{ $mostRequested->product_name ?? 'N/A' }}</div>
            </div>
            <div class="stat-box">
                <h3>Least Requested</h3>
                <div class="stat-value">{{ $leastRequested->product_name ?? 'N/A' }}</div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="products-grid">
            @foreach($products as $product)
            <div class="product-card">
                <div class="product-icon">
                    @php
                        // Assign icons based on product name
                        $iconClass = 'bi-droplet-fill'; // Default icon
                        
                        $productNameLower = strtolower($product->product_name);
                        
                        if (strpos($productNameLower, 'engine') !== false || strpos($productNameLower, 'oil') !== false) {
                            $iconClass = 'bi-fuel-pump-fill';
                        } elseif (strpos($productNameLower, 'fuel') !== false || strpos($productNameLower, 'gas') !== false) {
                            $iconClass = 'bi-lightning-fill';
                        } elseif (strpos($productNameLower, 'hydraulic') !== false) {
                            $iconClass = 'bi-gear-fill';
                        } elseif (strpos($productNameLower, 'brake') !== false) {
                            $iconClass = 'bi-disc-fill';
                        } elseif (strpos($productNameLower, 'atf') !== false || strpos($productNameLower, 'transmission') !== false) {
                            $iconClass = 'bi-arrow-repeat';
                        }
                    @endphp
                    <i class="bi {{ $iconClass }}"></i>
                </div>
                <h4>{{ $product->product_name }}</h4>
                <div class="product-info">
                    <p>Balance: {{ $product->stock_remaining }} Liters</p>
                    <p>Total Stock: {{ $product->total_stocks }} Liters</p>
                </div>
                <div class="progress-bar">
                    @php
                        $percentage = $product->total_stocks > 0 ? ($product->stock_remaining / $product->total_stocks) * 100 : 0;
                    @endphp
                    <div class="progress" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="pagination-container">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/darkmode.js') }}"></script>
<script src="{{ asset('assets/js/AdminConsumption/pagination.js') }}"></script>
@endpush