@include('partials._header')

<div class="container-scroller">
  <!-- partial:../../partials/_navbar.html -->
  @include('partials._navbar')

  <!-- partial -->
  <div class="container-fluid page-body-wrapper">
    <!-- partial:../../partials/_sidebar.html -->
    @include('partials._sidebar')

    <!-- partial -->
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="page-header">
          <h3 class="page-title"> Daily Records </h3>

          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="#">Reports</a></li>
              <li class="breadcrumb-item active" aria-current="page">Daily</li>
            </ol>
          </nav>
        </div>

        <h5 class="page-description">
          Today Compilation report
          <small class="text-muted">per: {{ \Carbon\Carbon::now()->format('d M Y') }}</small>
        </h5>

        <div class="py-3">
          <a href="{{ url('/view/daily_reports?today=true') }}">
            <button type="button" class="btn btn-sm btn-gradient-success">Fetch Today Data!</button>
          </a>
          <a href="{{ url('/view/daily_reports?latest=true') }}">
            <button type="button" class="btn btn-sm btn-gradient-primary">Fetch Latest Data!</button>
          </a>
        </div>

        <div class="row">
          <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Age average per gender</h4>
                <canvas id="barChart" style="height:230px"></canvas>
              </div>
            </div>
          </div>

          <div class="col-lg-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Gender count</h4>
                <canvas id="doughnutChart" style="height:250px"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->

      <!-- partial:../../partials/_footer.html -->
      @include('partials._footer')