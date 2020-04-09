@extends('layouts.master')
@section('content')
    <!-- Default box -->
    <div class="card">
        <div class="card-header">
          <h3 class="card-title">Detail</h3>

        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-md-12 col-lg-12 order-1 order-md-2">
              <h3 class="text-primary"><i class="fas fa-paint-brush"></i> Issue</h3>
              <p class="text-muted">{{ $timesheet["issue"] }}</p>
              <br>
              <h3 class="text-primary"><i class="fas fa-paint-brush"></i> Next day</h3>
              <p class="text-muted">{{ $timesheet["next_day"] }}</p>
              <br>
              
              <table class="table table-striped">
                  <thead >
                    <th>ID task</th>
                    <th>Content</th>
                    <th>Time</th>
                  </thead>
                  <tbody>
                      @foreach ($detail as $item)
                      <td>{{ $item["task_id"] }}</td>
                      <td>{{ $item["content"] }}</td>
                      <td>{{ $item["time"] }}</td>
                      @endforeach
                  </tbody>
              </table>
              <div class="text-muted">
                <p class="text-sm">Work date
                  <b class="d-block">{{ $timesheet["work_day"] }}</b>
                </p>
                <p class="text-sm">Create date
                  <b class="d-block">{{ $timesheet["created_at"] }}</b>
                </p>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
@endsection