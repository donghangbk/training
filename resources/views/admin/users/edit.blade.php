@extends("layouts.master")

@section("content")
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit User</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                @if ($errors->has("msg"))
                <div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                    <strong>{{ $errors->first("msg") }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                @endif
                <form role="form" method="POST" action="{{ route("admin.users.update", $user->id)}}">
                    {{ csrf_field() }}
                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Username</label>
                            <input type="text" class="form-control" placeholder="Username" name="username"
                                value="{{ $user->username or "" }}">
                        </div>
                        @if ($errors->has("username"))
                        <span class="text-danger">{{ $errors->first("username") }}</span>
                        @endif
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email</label>
                            <input type="email" class="form-control" placeholder="Email" name="email"
                                value="{{ old("email", $user->email ) }}">
                        </div>
                        @if ($errors->has("email"))
                        <span class="text-danger">{{ $errors->first("email") }}</span>
                        @endif
                        <div class="form-group">
                            <label for="exampleInputEmail1">Password</label>
                            <input type="password" class="form-control" placeholder="password" name="password">
                        </div>
                        @if ($errors->has("password"))
                        <span class="text-danger">{{ $errors->first("password") }}</span>
                        @endif
                        <div class="form-group">
                            <label for="exampleInputEmail1">Confirm Password</label>
                            <input type="password" class="form-control" placeholder="confirm password"
                                name="password_confirmation">
                        </div>
                        @if ($errors->has("password_confirmation"))
                        <span class="text-danger">{{ $errors->first("password_confirmation") }}</span>
                        @endif
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
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