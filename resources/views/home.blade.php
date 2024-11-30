@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')

<div class="row mb-4">
    <div class="col-lg-3 col-md-3 col-sm-12">
        <x-adminlte-info-box title="Total Gudang" text="{{$totalWarehouses}}" icon="fas fa-lg fa-warehouse" icon-theme="purple"/>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-12">
        <x-adminlte-info-box title="Total SPM" text="{{$totalMaterialRequest}}" icon="fas fa-lg fa-file-signature" icon-theme="primary"/>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-12">
        <x-adminlte-info-box title="Total Surat Jalan" text="{{$totalDeliveryNote}}" icon="fas fa-lg fa-truck" icon-theme="warning"/>
    </div>
</div>
<div class="row mb-4">
    <div class="col-lg-6 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-warehouse mr-1"></i>
                    Top 10 Barang Masuk
                </h3>
                <div class="card-tools">
                    <select class="form-control" id="top_10_in_warehouse_id">
                        @foreach ($warehouses as $value => $label)
                            <option value="{{ $value }}">
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover table-striped table-top-10-in">
                    <thead class="bg-info">
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Qty Keluar</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-dolly mr-1"></i>
                    Top 10 Barang Keluar
                </h3>
                <div class="card-tools">
                    <select class="form-control" id="top_10_out_warehouse_id">
                        @foreach ($warehouses as $value => $label)
                            <option value="{{ $value }}">
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover table-striped table-top-10-out">
                    <thead class="bg-info">
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Qty Keluar</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@if($announcements->isNotEmpty())
    <h3 class="text-primary font-weight-bold">Pengumuman</h3>
    <div class="row">
        @foreach($announcements as $announcement)
            <div class="col-sm-12 col-md-12 col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ $announcement->title }}</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">{{ $announcement->date }}</p>
                        <p>{{ $announcement->description }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@stop

@section('css')
@stop

@section('js')
<script type="text/javascript">
    $(document).ready(function() {
        top_10_in_warehouse_id = $('#top_10_in_warehouse_id').val()
        top_10_out_warehouse_id = $('#top_10_out_warehouse_id').val()
        var tableTop10In = $('.table-top-10-in').DataTable({
            processing: true,
            serverSide: true,     
            searching: false,
            lengthChange: false,
            info: false,
            paging: false,
            sort:false,       
            ajax: {
                url: "/arrival/top10",
                data: function (d) {
                    d.warehouse_id = $('#top_10_in_warehouse_id').val();  
                }
            },
            columns: [
                {data: 'code', name: 'code'},
                {data: 'name', name: 'name'},
                {data: 'arrived_qty', name: 'arrived_qty'},
            ]
        });
        var tableTop10Out = $('.table-top-10-out').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            sort:false,       
            info: false,
            paging: false,
            ajax: {
                url: "/out/top10",
                data: function (d) {
                    d.warehouse_id = $('#top_10_out_warehouse_id').val();  
                }
            },
            columns: [
                {data: 'code', name: 'code'},
                {data: 'name', name: 'name'},
                {data: 'qty', name: 'qty'},
            ]
        });
    
        $('#top_10_in_warehouse_id').change(function() {
            tableTop10In.ajax.reload();
        });

        $('#top_10_out_warehouse_id').change(function() {
            tableTop10Out.ajax.reload();
        });
    });

</script>
@stop