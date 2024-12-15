@extends('adminlte::page')

@section('title', 'Permission')

@section('content_header')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="float-left">
            <h1>Permission List</h1>
        </div>
        <div class="float-right">
            @can('announcement-create')
            @endcan
            <a class="btn btn-success mb-2" href="{{ route('permissions.create') }}"><i class="fa fa-plus"></i> Buat Permission</a>
        </div>
    </div>
</div>
@stop

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<table id="tblPermissions" class="table table-bordered data-table" style="width: 100%">
    <thead class="bg-primary text-white">
        <tr>
            <th style="width: 20px;">No</th>
            <th>Name</th>
            <th>Display Name</th>
            <th>Guard Name</th>
            <th>Model Type</th>
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
<script type="text/javascript">
    var table;
    $(function () {
          
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('permissions.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'display_name', name: 'display_name'},
                {data: 'guard_name', name: 'guard_name'},
                {data: 'model_type', name: 'model_type'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
          
    });

</script>
@include("layouts.helper.delete")
@stop