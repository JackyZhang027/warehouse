@extends('adminlte::page')

@section('title', 'Warehouses')

@section('content_header')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="float-left">
            <h1>Warehouses</h1>
        </div>
        <div class="float-right">
            <a class="btn btn-success mb-2" href="{{ route('warehouse.create') }}"><i class="fa fa-plus"></i> Create New Warehouse</a>
        </div>
    </div>
</div>
@stop

@section('content')
<table id="tblWarehouse" class="table table-bordered data-table" style="width: 100%">
    <thead class="bg-primary text-white">
        <tr>
            <th style="width: 20px;">No</th>
            <th>Owner</th>
            <th>Project</th>
            <th>SPK Number</th>
            <th>Location</th>
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
            ajax: "{{ route('warehouse.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'owner', name: 'owner'},
                {data: 'project', name: 'project'},
                {data: 'spk_number', name: 'spk_number'},
                {data: 'location', name: 'location'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
          
    });

</script>
@include("layouts.helper.delete")
@stop