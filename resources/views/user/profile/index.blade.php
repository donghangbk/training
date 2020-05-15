@extends('layouts.master')
@section("content")
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="{{ $user["avatar"] ?? '/img/avatar.png' }}" alt="User profile picture">
                    </div>

                    <h3 class="profile-username text-center">{{ $user["username"] ?? '' }}</h3>

                    <p class="text-muted text-center">Software Engineer</p>

                    <div class="card-body">
                        <strong><i class="far fa-file-alt mr-1"></i> Description</strong>

                        <p class="text-muted">{{ $user["description"] ?? ""}}</p>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    @if ($errors->has("msg"))
                    <div class="alert alert-danger" role="alert">
                        {{ $errors->first("msg") }}
                    </div>
                    @endif
                    @if(Session::has('message'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ Session::get('message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <div class="tab-content">
                        <div class="active tab-pane" id="settings">
                            <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{ route("profile.update", $user["id"]) }}">
                                {{ csrf_field() }}
                                <div class="form-group row">
                                    <label for="inputExperience" class="col-sm-2 col-form-label">Description</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="inputExperience" placeholder="Description" name="description">{{ old("description", $user["description"]) }}</textarea>
                                    </div>
                                    @if ($errors->has("description"))
                                    <span class="col-sm-12 offset-2 text-danger">{{ $errors->first("description") }}</span>
                                    @endif
                                </div>
                                <div class="form-group row">
                                    <label for="inputSkills" class="col-sm-2 col-form-label">Avatar</label>
                                    <div class="col-sm-10">
                                        <div class="form-control custom-file">
                                            <input type="file" id="selectImg" class="custom-file-input" name="image">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-10 offset-2" style="margin-top:10px;">
                                        <img id="showImage" src="{{ $user["avatar"] ?? '/img/avatar.png' }}" alt="" height="60" width="60">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="exampleInputEmail1" class="col-sm-2">Current Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" placeholder="current password" name="current_password">
                                    </div>
                                    @if ($errors->has("current_password"))
                                    <span class="col-sm-12 offset-2 text-danger">{{ $errors->first("current_password") }}</span>
                                    @endif
                                </div>
                                <div class="form-group row">
                                    <label for="exampleInputEmail1" class="col-sm-2">New Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" placeholder="password" name="password">
                                    </div>
                                    @if ($errors->has("password"))
                                    <span class="col-sm-12 offset-2 text-danger">{{ $errors->first("password") }}</span>
                                    @endif
                                </div>
                                <div class="form-group row">
                                    <label for="exampleInputEmail1" class="col-sm-2">Confirm New Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" placeholder="confirm password" name="password_confirmation">
                                    </div>
                                    @if ($errors->has("password_confirmation"))
                                    <span class="col-sm-12 offset-2 text-danger">{{ $errors->first("password_confirmation") }}</span>
                                    @endif
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <button type="submit" class="btn btn-danger">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div><!-- /.container-fluid -->
@endsection
@section('js')
<script src="{{ asset('js/user.js') }}"></script>
@endsection