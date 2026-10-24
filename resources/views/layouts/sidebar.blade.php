<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <!-- Sidebar Brand -->
  <div class="sidebar-brand">
    <a href="{{ route('dashboard') }}" class="brand-link">
      <img
        src="{{ asset('dist/assets/img/AdminLTELogo.png') }}"
        alt="Coaching Center Logo"
        class="brand-image opacity-75 shadow"
      />
      <span class="brand-text fw-light">Coaching Center</span>
    </a>
  </div>

  <!-- Sidebar Wrapper -->
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <ul
        class="nav sidebar-menu flex-column"
        data-lte-toggle="treeview"
        role="menu"
        data-accordion="false"
      >
        <!-- Dashboard -->
        <li class="nav-item">
          <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- User Management -->
        <li class="nav-header">USER MANAGEMENT</li>
        
        <!-- Roles -->
        <li class="nav-item {{ request()->routeIs('roles.*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-shield-lock"></i>
            <p>
              User Roles
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>All Roles</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('roles.create') }}" class="nav-link {{ request()->routeIs('roles.create') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Create Role</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Users Directory -->
        <li class="nav-item {{ request()->routeIs('users.*') || request()->routeIs('teachers.*') || request()->routeIs('students.*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->routeIs('users.*') || request()->routeIs('teachers.*') || request()->routeIs('students.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-people"></i>
            <p>
              Users
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>All Users</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('users.create') }}" class="nav-link {{ request()->routeIs('users.create') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Create User</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('teachers.index') }}" class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Teachers</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Students</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Academic Module -->
        <li class="nav-header">ACADEMICS</li>
        <li class="nav-item {{ request()->routeIs('classes.*') || request()->routeIs('subjects.*') || request()->routeIs('batches.*') || request()->routeIs('routines.*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->routeIs('classes.*') || request()->routeIs('subjects.*') || request()->routeIs('batches.*') || request()->routeIs('routines.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-journal-bookmark"></i>
            <p>
              Academic Setup
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('classes.index') }}" class="nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Classes</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('subjects.index') }}" class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Subjects</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('batches.index') }}" class="nav-link {{ request()->routeIs('batches.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Batches</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('routines.index') }}" class="nav-link {{ request()->routeIs('routines.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Class Routines</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Examination Module -->
        <li class="nav-header">EXAMINATIONS</li>
        <li class="nav-item {{ request()->routeIs('exams.*') || request()->routeIs('exam-schedules.*') || request()->routeIs('mark-entries.*') || request()->routeIs('result-sheets.*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->routeIs('exams.*') || request()->routeIs('exam-schedules.*') || request()->routeIs('mark-entries.*') || request()->routeIs('result-sheets.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-award"></i>
            <p>
              Exams & Results
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('exams.index') }}" class="nav-link {{ request()->routeIs('exams.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Exams</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('exam-schedules.index') }}" class="nav-link {{ request()->routeIs('exam-schedules.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Exam Schedules</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('mark-entries.index') }}" class="nav-link {{ request()->routeIs('mark-entries.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Mark Entries</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('result-sheets.index') }}" class="nav-link {{ request()->routeIs('result-sheets.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Result Sheets</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Financials Module -->
        <li class="nav-header">FINANCIALS</li>
        <li class="nav-item {{ request()->routeIs('fee-types.*') || request()->routeIs('student-fees.*') || request()->routeIs('payment-collections.*') || request()->routeIs('account-transactions.*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->routeIs('fee-types.*') || request()->routeIs('student-fees.*') || request()->routeIs('payment-collections.*') || request()->routeIs('account-transactions.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-cash-stack"></i>
            <p>
              Fees & Billing
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('fee-types.index') }}" class="nav-link {{ request()->routeIs('fee-types.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Fee Types</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('student-fees.index') }}" class="nav-link {{ request()->routeIs('student-fees.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Student Invoices</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('payment-collections.index') }}" class="nav-link {{ request()->routeIs('payment-collections.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Payment Collections</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('account-transactions.index') }}" class="nav-link {{ request()->routeIs('account-transactions.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-circle"></i>
                <p>Accounts Ledger</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Notice & Communication -->
        <li class="nav-header">COMMUNICATION</li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-bell"></i>
            <p>Notices</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-chat-text"></i>
            <p>SMS Logs</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>