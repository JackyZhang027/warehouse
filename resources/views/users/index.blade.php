@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="float-left">
            <h1>Users Management</h1>
        </div>
        <div class="float-right">
            <a class="btn btn-success mb-2" href="{{ route('users.create') }}"><i class="fa fa-plus"></i> Create New User</a>
        </div>
    </div>
</div>
@stop

@section('content')
<table id="tblUser" class="table table-bordered data-table" style="width: 100%">
    <thead class="bg-primary text-white">
        <tr>
            <th style="width: 20px;">No</th>
            <th>Name</th>
            <th>Email</th>
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
            ajax: "{{ route('users.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
          
    });

</script>
@include("layouts.helper.delete")
@stop