@extends("layouts.master")

@section("content")
<div class="container-fluid">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Create Timesheet</h3>
        </div>
          <!-- /.card-header -->
        @if ($errors->has("msg"))
            <div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                {{ $errors->first("msg") }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
          <!-- form start -->
        <form role="form" method="post" action="{{ route("timesheets.store") }}">
          {{ csrf_field() }}
            <div class="card-body">
                <div class="form-group">
                    <label>Date</label>
                   <div class="input-group" id="datetimepicker7" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datetimepicker7" data-toggle="datetimepicker">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>
                    <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker7" name="work_day" value="{{ old("work_day")}}" required>
                  </div>
                </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Issue</label>
                <textarea rows="5" class="form-control" placeholder="Issue" name="issue" value="{{ old("issue") }}"></textarea>
                @if ($errors->has("issue"))
                  <span class="text-danger">{{ $errors->first("issue") }}</span>
                @endif
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Next day</label>
              <textarea type="text" class="form-control" name="next_day" rows="5"> {{old("next_day")}}</textarea>
                @if ($errors->has("next_day"))
                  <span class="text-danger">{{ $errors->first("next_day") }}</span>
                @endif
              </div>
              <div id="listTask">
                <div class="row">
                    <div class="col-3">
                      <input type="text" class="form-control" placeholder="task id" name="task[0][taskId]">
                    </div>
                    <div class="col-5">
                      <textarea rows="2" class="form-control" placeholder="content" name="task[0][content]" required></textarea>
                    </div>
                    <div class="col-2">
                      <input type="number" class="form-control" placeholder="time ( minutes)" name="task[0][time]" required>
                    </div>
                  </div>
              </div>
              <div class="row" style="color:green">
                <i class="fas fa-plus-circle" id="add"></i>
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Create</button>
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

@section('js')
<script src="{{ asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}""></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}""></script>
<script src="{{ asset('js/user.js') }}"></script>
<script>
  //Money Euro
  $('[data-mask]').inputmask()
</script>
@endsection