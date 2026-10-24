<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Dashboard') - Coaching Center</title>

  <!-- Google Fonts & Bootstrap Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
  <!-- OverlayScrollbars -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css">

  <!-- AdminLTE 4 CSS -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}">

  @stack('styles')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  
  <!-- Native AdminLTE v4 App Wrapper -->
  <div class="app-wrapper">

    <!-- Navbar / Header -->
    <nav class="app-header navbar navbar-expand bg-body">
      <div class="container-fluid">
        <!-- Sidebar Toggle & Left Navigation -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
              <i class="bi bi-list fs-4"></i>
            </a>
          </li>
          <li class="nav-item d-none d-md-block">
            <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
          </li>
        </ul>

        <!-- Right User Navigation -->
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle fs-5 me-1"></i>
              <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'User' }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
              <li class="user-header text-bg-primary">
                <p>
                  {{ Auth::user()->name ?? 'User' }}
                  <small>{{ Auth::user()->email ?? '' }}</small>
                </p>
              </li>
              <li class="user-footer d-flex justify-content-between">
                <a href="#" class="btn btn-default btn-flat">Profile</a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                  @csrf
                  <button type="submit" class="btn btn-default btn-flat text-danger">Sign out</button>
                </form>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
    <!-- /.navbar -->

    <!-- Sidebar Partial -->
    @include('layouts.sidebar')

    <!-- Main Content Container -->
    <main class="app-main">
      <!-- App Content Header -->
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row align-items-center">
            <div class="col-sm-6">
              <h3 class="mb-0">@yield('page_title', 'Dashboard')</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">@yield('page_title', 'Dashboard')</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <!-- /.app-content-header -->

      <!-- App Content Body -->
      <div class="app-content">
        <div class="container-fluid">
          @yield('content')
        </div>
      </div>
      <!-- /.app-content -->
    </main>

    <!-- Footer -->
    <footer class="app-footer">
      <div class="float-end d-none d-sm-inline">Coaching Management System</div>
      <strong>Copyright &copy; {{ date('Y') }} <a href="#">Coaching Center</a>.</strong> All rights reserved.
    </footer>

  </div>
  <!-- ./app-wrapper -->

  <!-- Required Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"></script>
  <script src="{{ asset('dist/js/adminlte.js') }}"></script>

  <!-- Sidebar OverlayScrollbars Initialization -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const sidebarWrapper = document.querySelector(".sidebar-wrapper");
      if (sidebarWrapper && typeof OverlayScrollbarsGlobal !== "undefined") {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
          scrollbars: {
            theme: "os-theme-light",
            autoHide: "leave",
            clickScroll: true,
          },
        });
      }
    });
  </script>

  @stack('scripts')
</body>
</html>