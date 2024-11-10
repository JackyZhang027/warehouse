@extends('adminlte::page')

@section('title', 'List Barang Keluar')

@section('content_header')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="float-left">
            <h1>List Barang Keluar</h1>
        </div>
        <div class="float-right">
            @can('item-create')
                <a class="btn btn-success mb-2" href="{{ route('out.create') }}"><i class="fa fa-plus"></i> Buat Barang Keluar</a>
            @endcan
        </div>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-3">
        <div class="form-group">
            <label for="warehouse_id">Gudang</label>
            <x-adminlte-select2 name="warehouse_id" required>
                @if(auth()->user()->isAdmin())
                    <option value="">Semua Gudang</option>
                @endif
                @foreach ($warehouses as $value => $label)
                    <option value="{{ $value }}">
                        {{ $label }}
                    </option>
                @endforeach
            </x-adminlte-select2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="warehouse_id">&nbsp;</label><br/>
            <button class="btn btn-info" onclick="searchData()"><i class="fas fa-search"></i> Cari</button>
        </div>
    </div>
</div>
<table id="tblIssued" class="table table-bordered data-table" style="width: 100%">
    <thead class="bg-primary text-white">
        <tr>
            <th style="width: 20px;">No</th>
            <th>No. Barang Keluar</th>
            <th>Tanggal Keluar</th>
            <th>Proyek</th>
            <th>Keterangan</th>
            <th>Dibuat Oleh</th>
            <th width="100px"></th>
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
        searchData()      
    });
    function searchData(){
        // Check if the DataTable is already initialized and destroy it
        if ($.fn.DataTable.isDataTable('.data-table')) {
            $('.data-table').DataTable().clear().destroy();
        }

        var warehouse_id = $('#warehouse_id').val()
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('out.index') }}",
                data: function (d) {
                    d.warehouse_id = warehouse_id;  
                }
            },

            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'io_number', name: 'io_number'},
                {data: 'date', name: 'date'},
                {data: 'warehouse_id', name: 'warehouse_id'},
                {data: 'remark', name: 'remark'},
                {data: 'created_by', name: 'created_by'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    }
</script>
@include("layouts.helper.delete")
@include('layouts.errors.swal-alert')
@stop