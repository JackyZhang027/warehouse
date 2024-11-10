@extends('adminlte::page')

@section('title', 'Barang Keluar - Baru')

@section('content_header')
<h1>Barang Keluar - Baru</h1>
@stop

@section('content')
<form action="{{ route('out.store') }}" method="POST">
    @csrf
    <!-- Step 1 -->
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="io_number">No. Barang Keluar</label>
                        <input type="text" class="form-control" id="io_number" name="io_number" value="Nomor Otomatis" disabled>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date">Tanggal Barang Keluar</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                </div>
                <div class="col-md-3">
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
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="remark">Keterangan</label>
                        <textarea class="form-control" id="remark" name="remark"></textarea>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
        </div>
    </div>
</form>
@stop

@section('css')
<style>
    .step {
        display: none;
    }
    .step.active {
        display: block;
    }
    .delete-row{
        color: red;
        cursor: pointer;
    }
    .qty{
        text-align: right;
    }
</style>
@stop

@section('js')
<script>

</script>
@include('layouts.errors.swal-alert')
@stop
