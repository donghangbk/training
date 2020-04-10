@extends('layouts.master')
@section('js')
<script type="text/javascript" src="{{asset('js/user.js')}}"></script>   
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Timesheet member</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                <tr>
                    <th style="width: 1%">
                        #
                    </th>
                    <th style="width: 20%">
                        Date
                    </th>
                    <th style="width: 5%">
                        Total task
                    </th>
                    <th style="width: 20%" class="text-center">
                        Issue
                    </th>
                    <th style="width: 20%" class="text-center">
                        Next day
                    </th>
                    <th style="width: 8%" class="text-center">
                        Approve
                    </th>
                    <th>
                    </th>
                </tr>
                </thead>
                <tbody>
                    @if (!empty($timesheets))
                    @foreach ($timesheets as $item)
                <tr id="{{ $item["id"]}}">
                        <td>
                            #
                        </td>
                        <td>
                            <a>
                                {{ $item["work_day"] }}
                            </a>
                            <br/>
                            <small>
                                Created {{ $item["created_at"] }}
                            </small>
                        </td>
                        <td>
                            {{ $item["total"] }}
                        </td>
                        <td class="project_progress text-center">
                            <small>
                                {{ $item["issue"] }}
                            </small>
                        </td>
                        <td class="project-state">
                            <small>
                                {{ $item["next_day"] }}
                            </small>
                        </td>
                        <td class="project_progress">
                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                            @if (isset($item["status"]) && $item["status"] == 1)
                            <input type="checkbox" checked disabled readonly>
                            @else
                        <input type="checkbox" class="approve" data-id="{{ $item["id"] }}">
                            @endif
                        </td>
                        <td class="project-actions">
                            <a class="btn btn-primary btn-sm" href="{{ route('timesheets.show', $item["id"]) }}">
                                <i class="fas fa-folder">
                                </i>
                                View
                            </a>
                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
@endsection