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
        <div class="row">
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">User records </h4>
                <p class="text-muted float-end">Total: {{ $data->total() }}</p>
                <p class="card-description"> per: {{ \Carbon\Carbon::now()->format('d M Y') }}</p>

                <form class="form-inline">
                  <input type="text" class="form-control mb-2 mr-sm-2" id="searchInput" placeholder="Search here..." />
                </form>

                <table class="table table-striped table-hover">
                  <thead>
                    <tr>
                      <th> No. </th>
                      <th> User </th>
                      <th> Name </th>
                      <th> Age </th>
                      <th> Gender </th>
                      <th> Created At </th>
                      <th> Action </th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($data as $index => $val)
                    @php
                    $avatar = rand(1, 4);
                    $path = 'assets/images/faces-clipart/pic-' . $avatar . '.png';
                    $name = json_decode($val['name']);
                    @endphp
                    <tr>
                      <td>{{ $index + 1 }}.</td>
                      <td class="py-1">
                        <img src="{{ asset($path) }}" alt="image" />
                      </td>
                      <td> {{ $name->title . '. ' . $name->first . ' ' . $name->last }} </td>
                      <td> {{ $val->age }} </td>
                      <td> {{ ucfirst($val->gender) }} </td>
                      <td> {{ \Carbon\Carbon::parse($val->created_at)->format('d M Y, H:m:s') }} </td>
                      <td>
                        <form action="{{ url('view/delete/' . $val->id) }}" method="post">
                          @csrf
                          @method('DELETE')
                          
                          <button type="submit" class="btn btn-sm btn-gradient-danger">Delete!</button>
                        </form>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>

                <div class="py-3">
                  <p class="text-muted float-start">Total: {{ $data->total() }}</p>
                  <div class="d-flex flex-row-reverse float-none float-sm-end">
                    <!-- pagination -->
                    {{$data->links('partials._pagination')}}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
      <!-- partial:../../partials/_footer.html -->
      @include('partials._footer')

      <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
          const searchValue = this.value.toLowerCase();
          const tableRows = document.querySelectorAll('.table tbody tr');

          tableRows.forEach(function(row) {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.indexOf(searchValue) !== -1 ? '' : 'none';
          });
        });
      </script>