@extends('layouts.master')

@section('title', 'Dashboard')
@section('page_title', 'Coaching Dashboard Overview')

@section('content')
<!-- Dynamic Metric Cards -->
<div class="row">
  <!-- Total Students -->
  <div class="col-lg-3 col-6">
    <div class="small-box text-bg-primary shadow-sm">
      <div class="inner">
        <h3>{{ number_format($totalStudents) }}</h3>
        <p>Total Students</p>
      </div>
      <i class="small-box-icon bi bi-people-fill"></i>
      <a href="{{ route('students.index') }}" class="small-box-footer link-light">
        View All <i class="bi bi-arrow-right-circle"></i>
      </a>
    </div>
  </div>

  <!-- Total Teachers -->
  <div class="col-lg-3 col-6">
    <div class="small-box text-bg-success shadow-sm">
      <div class="inner">
        <h3>{{ number_format($totalTeachers) }}</h3>
        <p>Active Teachers</p>
      </div>
      <i class="small-box-icon bi bi-person-badge"></i>
      <a href="{{ route('teachers.index') }}" class="small-box-footer link-light">
        View All <i class="bi bi-arrow-right-circle"></i>
      </a>
    </div>
  </div>

  <!-- Active Batches -->
  <div class="col-lg-3 col-6">
    <div class="small-box text-bg-warning shadow-sm">
      <div class="inner">
        <h3>{{ number_format($activeBatches) }}</h3>
        <p>Active Batches</p>
      </div>
      <i class="small-box-icon bi bi-journal-bookmark-fill"></i>
      <a href="{{ route('batches.index') }}" class="small-box-footer link-dark">
        View Batches <i class="bi bi-arrow-right-circle"></i>
      </a>
    </div>
  </div>

  <!-- Total Collections -->
  <div class="col-lg-3 col-6">
    <div class="small-box text-bg-danger shadow-sm">
      <div class="inner">
        <h3>${{ number_format($totalCollections, 2) }}</h3>
        <p>Fees Collected</p>
      </div>
      <i class="small-box-icon bi bi-cash-stack"></i>
      <a href="{{ route('payment-collections.index') }}" class="small-box-footer link-light">
        Accounts Ledger <i class="bi bi-arrow-right-circle"></i>
      </a>
    </div>
  </div>
</div>

<!-- Charts Section -->
<div class="row mt-4">
  <!-- Revenue Analytics Chart -->
  <div class="col-lg-8">
    <div class="card card-outline card-primary shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="bi bi-graph-up-arrow me-1"></i> Fee Collection Analytics ({{ date('Y') }})</h3>
      </div>
      <div class="card-body">
        <canvas id="revenueChart" style="min-height: 280px; height: 280px; max-height: 280px; max-width: 100%;"></canvas>
      </div>
    </div>
  </div>

  <!-- Student Distribution Chart -->
  <div class="col-lg-4">
    <div class="card card-outline card-info shadow-sm">
      <div class="card-header">
        <h3 class="card-title"><i class="bi bi-pie-chart-fill me-1"></i> Students per Class</h3>
      </div>
      <div class="card-body">
        <canvas id="classDistributionChart" style="min-height: 280px; height: 280px; max-height: 280px; max-width: 100%;"></canvas>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js Engine -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    // 1. Line Chart: Monthly Revenue
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
          label: 'Fee Revenue ($)',
          data: @json($chartRevenueData),
          borderColor: '#0d6efd',
          backgroundColor: 'rgba(13, 110, 253, 0.1)',
          fill: true,
          tension: 0.3,
          pointRadius: 4,
          pointBackgroundColor: '#0d6efd'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: { beginAtZero: true }
        }
      }
    });

    // 2. Doughnut Chart: Student Distribution by Class
    const classCtx = document.getElementById('classDistributionChart').getContext('2d');
    const classData = @json($enrollmentByClass);

    new Chart(classCtx, {
      type: 'doughnut',
      data: {
        labels: Object.keys(classData),
        datasets: [{
          data: Object.values(classData),
          backgroundColor: [
            '#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0', '#6c757d'
          ]
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'bottom' }
        }
      }
    });
  });
</script>
@endpush