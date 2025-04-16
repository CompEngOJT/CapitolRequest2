@extends('AdminFuelRequest/layout')
@section('title', 'History')


@push('styles')
  <!-- Document CSS files -->
  <link href="{{ asset('assets/css/fixed/dashboard-left.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/sidebar-mobile-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/header.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/darkmode.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/help.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/help-contactus-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/history/history-middle.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/history/history-middle-media.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/history/history-filter-popup.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/fixed/sidebar.css') }}" rel="stylesheet">
@endpush

@section('content')
<div id="middle">
<div id="middle-container">
  <div id="content">
      <h3>Request History</h3>
      <div id="horizontal"></div>
      <p>You can view all the document request transactions you made on this section.</p>

      <!-- Filter Button -->
      <div class="table-header">
          <h2>All Requests</h2>
          <div class="filter-controls">
              <button id="filter-button" class="filter-button">Filter</button>
          </div>
      </div>

      <!-- Table Container -->
      <div class="table-container">
          <!-- Table to display driver_request_table data -->
          <table class="request-table">
              <thead>
                  <tr class="even-row">
                      <th>Date</th>
                      <th>Plate No.</th>
                      <th>Driver</th>
                      <th>Type</th>
                      <th>Division</th>
                      <th>Quantity</th>
                      <th>Status</th>
                  </tr>
              </thead>
              <tbody id="requestTableBody">
                  @php
                      // Ensure there are always 10 rows
                      $emptyRows = 10 - count($requests);
                  @endphp

                  @forelse ($requests as $index => $request)
                      <tr class="{{ $index % 2 === 0 ? 'odd-row' : 'even-row' }}">
                          <td>{{ $request->date }}</td>
                          <td>{{ $request->government_car_number }}</td>
                          <td>{{ $request->driver_name }}</td>
                          <td>{{ $request->type }}</td>
                          <td>{{ $request->division }}</td>
                          <td>{{ $request->quantity }}</td>
                          <td>{{ $request->status }}</td>
                      </tr>
                  @empty
                      @for ($i = 0; $i < 10; $i++)
                          <tr class="{{ $i % 2 === 0 ? 'odd-row' : 'even-row' }}">
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                          </tr>
                      @endfor
                  @endforelse

                  @for ($i = 0; $i < $emptyRows; $i++)
                      <tr class="{{ (count($requests) + $i) % 2 === 0 ? 'odd-row' : 'even-row' }}">
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                          <td>&nbsp;</td>
                      </tr>
                  @endfor
              </tbody>
          </table>
      </div>

      <!-- Pagination -->
      <div class="pagination-container">
          <div class="pagination">
              {{ $requests->onEachSide(1)->links('pagination::bootstrap-4') }} <!-- Laravel pagination links -->
          </div>
      </div>
  </div>
</div>
<!-- Filter Modal -->
<div id="filter-modal" class="popup">
  <div class="popup-content">
    <div class="popup-header">
      <h2>Filter Requests</h2>
      <span class="close">&times;</span>
    </div>
    <form id="filter-form">
      <div class="form-row">
        <div class="form-group">
          <label for="driver-name">Driver's Name</label>
          <select id="driver-name" name="driver_name">
            <option value="" disabled selected>Select Driver</option>
            @foreach ($drivers as $driver)
              <option value="{{ $driver->full_name }}">
                {{ $driver->full_name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label for="type">Type</label>
          <select id="type" name="type">
            <option value="" disabled selected>Select Type</option>
            @foreach ($productTypes as $product)
              <option value="{{ $product->name }}">{{ $product->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="division">Division</label>
          <select id="division" name="division">
            <option value="" disabled selected>Select Division</option>
            @foreach ($divisions as $division)
              <option value="{{ $division->name }}">{{ $division->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label for="date-range">Date Range</label>
          <select id="date-range" name="date_range">
            <option value="">All</option>
            <option value="day">Day</option>
            <option value="week">Week</option>
            <option value="month">Month</option>
            <option value="6months">6 Months</option>
            <option value="year">Year</option>
          </select>
        </div>
      </div>
      <div class="form-buttons">
        <button type="submit" class="filter-submit">Filter</button>
        <button type="button" id="revert-button" class="revert-button">Revert</button>
      </div>
    </form>
  </div>
</div>
</div>


@push('scripts')
<!-- Dashboard JavaScript -->
<script src="{{ asset('assets/js/slidebar.js') }}"></script>
<script src="{{ asset('assets/js/history.js') }}"></script>
<script src="{{ asset('assets/js/fixed/darkmode.js') }}"></script>
@endpush
@endsection