@extends('adminlte::page')

@section('title', 'Laporan')

@section('content_header')
<h1>Laporan</h1>
@stop

@section('content')
<form method="POST" action="{{ route('report.generate') }}">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="report_type">Jenis Laporan</label>
                        <x-adminlte-select2 id="report_type" name="report_type" required onchange="checkReport(this)">
                            <option value="incoming">Laporan Barang Masuk</option>
                            <option value="outgoing">Laporan Barang Keluar</option>
                            <option value="stock">Laporan Stock</option>
                            <option value="spm">Laporan SPM</option>
                        </x-adminlte-select2>
                    </div>
                </div>
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
                <div class="col-3" id="category-group">
                    <div class="form-group">
                        <label for="category_id">Kategori Barang</label>
                        <x-adminlte-select2 id="category_id" name="category_id" required>
                            @foreach ($categories as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </x-adminlte-select2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 date-group-start">
                    <div class="form-group">
                        <label for="start_date">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                </div>
                <div class="col-md-3 date-group-end">
                    <div class="form-group">
                        <label for="end_date">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-info"><i class="fas fa-download"></i> Cetak Laporan</button>
        </div>
    </div>
</form>
@stop

@section('css')
@stop

@section('js')
<script>
    function checkReport(obj){
        const reportType = document.getElementById('report_type');
        const categoryGroup = document.getElementById('category-group');
        const dateGroupStart = document.querySelector('.date-group-start');
        const dateGroupEnd = document.querySelector('.date-group-end');
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        const category = document.getElementById('category_id');   
        if (obj.value == 'spm') {
            categoryGroup.style.display = 'none';
            dateGroupStart.style.display = 'none';
            dateGroupEnd.style.display = 'none';
            category.removeAttribute('required');
            startDate.removeAttribute('required');
            endDate.removeAttribute('required');
        } else {
            categoryGroup.style.display = 'block';
            dateGroupStart.style.display = 'block';
            dateGroupEnd.style.display = 'block';
            category.setAttribute('required', true);
            startDate.setAttribute('required', true);
            endDate.setAttribute('required', true);
        }
    }
    
</script>
@stop