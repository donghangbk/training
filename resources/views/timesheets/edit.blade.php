@extends("layouts.master")

@section("content")

<div class="container-fluid">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Edit Timesheet</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
        <form role="form" method="post" action="{{ route("editTimesheet", $timesheet["id"]) }}">
          {{ csrf_field() }}
            <div class="card-body">
                <div class="form-group">
                    <label>Date</label>
  
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                      </div>
                    <input type="text" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy-mm-dd" data-mask name="work_day" value="{{ old('work_day', $timesheet["work_day"])}}" required>
                    </div>
                    <!-- /.input group -->
                  </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Issue</label>
                <textarea rows="5" class="form-control" placeholder="Issue" name="issue" value="{{ old("issue", $timesheet["issue"]) }}"></textarea>
                @if ($errors->has("issue"))
                <span class="text-danger">{{ $errors->first("issue") }}</span>
                @endif
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Next day</label>
              <textarea type="text" class="form-control" name="next_day" rows="5"> {{old("next_day", $timesheet["next_day"])}}</textarea>
                @if ($errors->has("next_day"))
                <span class="text-danger">{{ $errors->first("next_day") }}</span>
                @endif
              </div>
              <div id="listTask">
                  <?php $i = 1;?>
                @foreach ($listTask as $item)
                <div class="row" style="margin-top:10px;">
                    <div class="col-3">
                    <input type="text" class="form-control" placeholder="task id" name="task_id{{ $i}}" value="{{old("task_id", $item["task_id"])}}">
                    </div>
                    <div class="col-5">
                      <textarea rows="2" class="form-control" placeholder="content" name="content{{ $i}}" required>{{old("content", $item["content"])}}</textarea>
                    </div>
                    <div class="col-2">
                      <input type="number" class="form-control" placeholder="time ( minutes)" name="time{{ $i}}" required value="{{old("time", $item["time"])}}">
                    </div>
                    <div class="col-2">
                        <i class="fas fa-minus-circle" style="color:red"></i>
                      </div>
                  </div>
                  <?php $i++;?>
                @endforeach
              </div>
              <div class="row" style="color:green">
                <i class="fas fa-plus-circle" id="add"></i>
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
              <button type="submit" class="btn btn-primary">Edit</button>
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
    var i = {{ $i++}};
   //Money Euro
    $('[data-mask]').inputmask()
</script>
@endsection