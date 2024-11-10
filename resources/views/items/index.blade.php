@extends('adminlte::page')

@section('title', 'Items')

@section('content_header')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="float-left">
            <h1>Items</h1>
        </div>
        <div class="float-right">
            @can('item-create')
                <a class="btn btn-success mb-2" href="{{ route('items.create') }}"><i class="fa fa-plus"></i> Create New Item</a>
            @endcan
        </div>
    </div>
</div>
@stop

@section('content')
<table id="tblItem" class="table table-bordered data-table" style="width: 100%">
    <thead class="bg-primary text-white">
        <tr>
            <th style="width: 20px;">No</th>
            <th>Code</th>
            <th>Name</th>
            <th>Description</th>
            <th>Category</th>
            <th>UOM</th>
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
            ajax: "{{ route('items.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'code', name: 'code'},
                {data: 'name', name: 'name'},
                {data: 'description', name: 'description'},
                {data: 'category_id', name: 'category_id'},
                {data: 'uom_id', name: 'uom_id'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
          
    });

</script>
@include("layouts.helper.delete")
@stop