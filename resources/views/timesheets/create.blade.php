@extends("layouts.master")

@section('css')
    <link href="{{ asset('plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}" rel="stylesheet">
@endsection

@section("content")
@if(Session::has('flash_message'))
    <div class="alert alert-success">
        {{ Session::get('flash_message') }}
    </div>
@endif
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
          <!-- form start -->
        <form role="form" method="post" action="{{ route("timesheets.store") }}">
          {{ csrf_field() }}
            <div class="card-body">
                <div class="form-group">
                    <label>Date</label>
  
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                      </div>
                      <input type="text" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask name="work_day">
                    </div>
                    <!-- /.input group -->
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
                      <input type="text" class="form-control" placeholder="task id" name="task_id1" >
                    </div>
                    <div class="col-5">
                      <textarea rows="2" class="form-control" placeholder="content" name="content1" required></textarea>
                    </div>
                    <div class="col-2">
                      <input type="number" class="form-control" placeholder="time ( minutes)" name="time1" required>
                    </div>
                    <div class="col-2">
                        <i class="fas fa-minus-circle" style="color:red"></i>
                      </div>
                  </div>
              </div>
              <div class="row" style="color:green">
                <i class="fas fa-plus-circle"></i>
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
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}""></script>
<script src="{{ asset('plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}""></script>
<script>
    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy', 'setDate': new Date() })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()
</script>
@endsection