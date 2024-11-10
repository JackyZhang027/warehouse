@extends('adminlte::page')

@section('title', 'List Surat Jalan')

@section('content_header')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="float-left">
            <h1>List Surat Jalan</h1>
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
                @foreach ($warehouses as $value => $label)
                    <option value="{{ $value }}">
                        {{ $label }}
                    </option>
                @endforeach
            </x-adminlte-select2>
            
        </div>
    </div>
    <div class="col-3">
        <div class="form-group">
            <label for="status">Status</label>
            <x-adminlte-select2 name="status" required>
                <option value="">Semua Status</option>
                <option value="1">Telah Terima</option>
                <option value="2">Terima Sebagian</option>
                <option value="3" selected>Belum Terima</option>
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
<table id="tblDeliveryOrder" class="table table-bordered data-table" style="width: 100%">
    <thead class="bg-primary text-white">
        <tr>
            <th style="width: 20px;">No</th>
            <th>No. Surat Jalan</th>
            <th>Tanggal</th>
            <th>Items</th>
            <th>Qty Surat Jalan</th>
            <th>Qty Diterima</th>
            <th>Sisa Qty</th>
            <th>Status</th>
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
        var status = $('#status').val()
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('delivery.list') }}",
                data: function (d) {
                    d.warehouse_id = warehouse_id,
                    d.status = status;  
                }
            },

            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'do_number', name: 'do_number'},
                {data: 'do_date', name: 'date'},
                {data: 'item', name: 'item'},
                {data: 'do_qty', name: 'do_qty'},
                {data: 'received_qty', name: 'received_qty'},
                {data: 'balance', name: 'balance'},
                {data: 'status', name: 'status'},
            ]
        });
    }
</script>
@include("layouts.helper.delete")
@include('layouts.errors.swal-alert')
@stop