@extends('layouts.master')
@section('js')
<script type="text/javascript" src="{{asset('js/admin.js')}}"></script>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Users</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-toggle="tooltip" title="Create">
                <a href="{{ route("admin.users.create") }}">
                    <i class="fas fa-plus"></i>
                </a>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                title="Collapse">
                <i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                <i class="fas fa-times"></i></button>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped projects">
            <thead>
                <tr>
                    <th style="width: 1%">
                        #
                    </th>
                    <th style="width: 20%">
                        username
                    </th>
                    <th style="width: 14%">
                        avatar
                    </th>
                    <th>
                        email
                    </th>
                    <th style="width: 8%" class="text-center">
                        Status
                    </th>
                    <th style="width: 8%" class="text-center">
                        Role
                    </th>
                    <th style="width: 39%">
                    </th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($listUser))
                @foreach ($listUser as $item)
                <tr id="{{ $item["id"]}}">
                    <td>
                        #
                    </td>
                    <td>
                        <a>
                            {{ $item["username"] }}
                        </a>
                        <br />
                        <small>
                            Created {{ $item["created_at"] }}
                        </small>
                    </td>
                    <td>
                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <img alt="Avatar" class="table-avatar" src="{{ $item["avatar"] or "/img/avatar.png"}}">
                            </li>
                        </ul>
                    </td>
                    <td class="project_progress">
                        <small>
                            {{ $item["email"] }}
                        </small>
                    </td>
                    <td class="project-state">
                        @if (isset($item["is_active"]) && $item["is_active"] == 1)
                        <span class="badge badge-success">Active</span>
                        @else
                        <span class="badge badge-secondary">Suspended</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <small>
                            {{ $item["role_id"] == 1 ? "admin" : "user"}}
                        </small>
                    </td>
                    <td class="project-actions text-right">
                        @if (isset($item["is_active"]) && $item["is_active"] != 0)
                        <a class="btn btn-info btn-sm" href="{{ route('admin.users.edit', $item["id"]) }}">
                            <i class="fas fa-pencil-alt">
                            </i>
                            Edit
                        </a>
                        <form method="POST" class="d-inline" action="{{ route('admin.users.delete', $item['id']) }}">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>Delete
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
        {{ $listUser->count() != 0 ? $listUser->links('components.pagination', ["page" => $listUser]) : ''}}
    </div>
</div>
@endsection