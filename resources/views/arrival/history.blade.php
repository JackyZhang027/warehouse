@extends('adminlte::page')

@section('title', 'Laporan Penerimaan Barang')

@section('content_header')
<h1>Laporan Penerimaan Barang</h1>
@stop

@section('content')
<div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="warehouse_id">Gudang</label>
                        <x-adminlte-select2 id="warehouse_id" name="warehouse_id" required>
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
                        <label for="start_date">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="end_date">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}" required>
                    </div>
                </div>
                <div class="col-md-auto">
                    <div class="form-group">
                        <label for="search">&nbsp;</label><br/>
                        <button type="button" id="searchButton" class="btn btn-info">Cari</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table id="dataTable" class="table table-bordered table-hover table-striped" style="width: 100%">
                <thead class="bg-info text-white">
                    <tr>
                        <th style="width: 25px">#</th>
                        <th>Nomor Surat Jalan</th>
                        <th>Tanggal Terima</th>
                        <th>Barang</th>
                        <th>Qty</th>
                        <th>Nomor PO</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded here by DataTables -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('css')

@stop

@section('js')
<script>
    $(document).ready(function() {
        var table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/arrival/search',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token for Laravel
                },
                data: function(d) {
                    d.warehouse_id = $('#warehouse_id').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'do_number', name: 'do_number'},
                {data: 'arrival_date', name: 'arrival_date'},
                {data: 'item', name: 'item'},
                {data: 'arrived_qty', name: 'arrived_qty'},
                {data: 'po_number', name: 'po_number'},
                {data: 'remark', name: 'remark'},

            ]
        });

        $('#searchButton').on('click', function() {
            table.ajax.reload();
        });
    });
</script>
@include('layouts.errors.swal-alert')
@stop
