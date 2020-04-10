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
            <h3 class="card-title">Create User</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
        <form role="form" method="post" enctype="multipart/form-data" action="{{ route("users.store") }}">
          {{ csrf_field() }}
            <div class="card-body">
              <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
              <input type="email" class="form-control" name="email" placeholder="Enter email" value="{{ old("email") }}">
                @if ($errors->has("email"))
                <span class="text-danger">{{ $errors->first("email") }}</span>
                @endif
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Username</label>
                <input type="text" class="form-control" placeholder="Username" name="username" value="{{ old("username") }}">
                @if ($errors->has("username"))
                <span class="text-danger">{{ $errors->first("username") }}</span>
                @endif
              </div>
              <div class="form-group">
                <label>Role</label>
                <select class="form-control select2" name="role_id">
                    @if (!empty($role))
                        @foreach ($role as $item)
                <option selected="selected" value="{{ $item["id"] }}">{{ $item["name"] or "" }}</option>
                        @endforeach
                        
                    @endif
                </select>
              </div>
              <div class="form-group">
                <label>Leader</label>
                <select class="form-control select2" name="leader">
                  <option value=""></option>
                  @if (!empty($listUser))
                      @foreach ($listUser as $item)
                <option value="{{ $item["id"]}}">{{ $item["username"] or ""}}</option>
                      @endforeach
                  @endif
                </select>
              </div>
              <div class="form-group">
                <label>List notification</label>
              <select class="select2" multiple="multiple" data-placeholder="Select a State" style="width: 100%;" name="listUser[]">
                  @if (!empty($listUser))
                      @foreach ($listUser as $item)
                <option value="{{ $item["id"] }}">{{ $item["username"] or ""}}</option>
                      @endforeach
                  @endif
                </select>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Description</label>
              <textarea type="text" class="form-control" name="description" rows="5"> {{old("description")}}</textarea>
                @if ($errors->has("description"))
                <span class="text-danger">{{ $errors->first("description") }}</span>
                @endif
              </div>
              <div class="form-group">
                <label for="exampleInputFile">Avatar</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" id="selectImg" class="custom-file-input" name="image">
                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <img id="showImage" src="/img/avatar.png" alt="" height="60" width="60">
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
<script src="{{ asset('plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
<script src="{{ asset('js/user.js') }}"></script>
<script>
  $('.select2').select2()
</script>
@endsection