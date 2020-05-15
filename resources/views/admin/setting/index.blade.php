@extends("layouts.master")

@section("content")
<div class="container-fluid">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Setting</h3>
          </div>
          <!-- /.card-header -->
          @if ($errors->has("msg"))
          <div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
            <strong>{{ $errors->first("msg") }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          @endif

          @if(Session::has('message'))
          <div class="alert alert-info alert-dismissible fade show mt-1" role="alert">
            <strong>{{ Session::get('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          @endif

          <!-- form start -->
        <form role="form" method="POST" action="{{ route("admin.setting.update", $data["id"])}}">
          {{ csrf_field() }}
            <div class="card-body">
                <div class="form-group">
                    <label>Start_time:</label>

                    <div class="input-group date col-md-3" id="timepicker" data-target-input="nearest">
                    <input type="text" required class="form-control datetimepicker-input" data-target="#timepicker" name="start_time" value="{{ $data["start_time"]}}"/>
                      <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="far fa-clock"></i></div>
                      </div>
                      </div>
                    <!-- /.input group -->
                </div>
              @if ($errors->has("start_time"))
                <span class="text-danger">{{ $errors->first("start_time") }}</span>
                @endif
                <div class="form-group">
                    <label>End_time:</label>

                    <div class="input-group date col-md-3" id="timepicker2" data-target-input="nearest">
                      <input type="text" required class="form-control datetimepicker-input" data-target="#timepicker2" name="end_time" value="{{ $data["end_time"]}}"/>
                      <div class="input-group-append" data-target="#timepicker2" data-toggle="datetimepicker">
                          <div class="input-group-text"><i class="far fa-clock"></i></div>
                      </div>
                      </div>
                    <!-- /.input group -->
                </div>
              @if ($errors->has("end_time"))
              <span class="text-danger">{{ $errors->first("end_time") }}</span>
              @endif
            <!-- /.card-body -->
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Edit</button>
            </div>
            </div>
          </form>
        </div>
        <!-- /.card -->
      </div>
      <!--/.col (left) -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
@endsection
@section("js")
<script src="/plugins/daterangepicker/daterangepicker.js"></script>
<script>
    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'LT'
    })
    $('#timepicker2').datetimepicker({
      format: 'LT'
    })
</script>
@endsection