@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="float-left">
            <h2>Role Management</h2>
        </div>
        <div class="float-right">
        @can('role-create')
            <a class="btn btn-success btn-sm mb-2" href="{{ route('roles.create') }}"><i class="fa fa-plus"></i> Create New Role</a>
            @endcan
        </div>
    </div>
</div>
@stop

@section('content')

@session('success')
    <div class="alert alert-success" role="alert"> 
        {{ $value }}
    </div>
@endsession
<table id="tblRoles" class="table table-bordered data-table" style="width: 100%">
    <thead class="bg-primary text-white">
        <tr>
            <th style="width: 20px;">No</th>
            <th>Name</th>
            <th width="100px">Action</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
@stop

@section('css')
@stop

@section('js')
<script>
    var table;
    $(function () {
          
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('roles.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
          
    });
</script>
@include("layouts.helper.delete")
@stop