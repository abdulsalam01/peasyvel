<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="nav-profile-image">
          <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="profile">
          <span class="login-status online"></span>
          <!--change to offline or busy as needed-->
        </div>
        <div class="nav-profile-text d-flex flex-column">
          <span class="font-weight-bold mb-2">Abdul Salam</span>
          <span class="text-secondary text-small">Laravel Developer</span>
        </div>
        <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ url('view') }}">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ url('view/users') }}">
        <span class="menu-title">Users</span>
        <i class="mdi mdi-book menu-icon"></i>
      </a>
    </li>    
    <li class="nav-item">
      <a class="nav-link" href="{{ url('view/daily_reports') }}">
        <span class="menu-title">Daily Reports</span>
        <i class="mdi mdi-chart-bar menu-icon"></i>
      </a>
    </li>
  </ul>
</nav>