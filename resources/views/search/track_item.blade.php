@extends('adminlte::page')

@section('title', 'Cari Barang')

@section('content_header')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="float-left">
            <h1>Cari Barang</h1>
        </div>
    </div>
</div>
@stop

@section('content')
<form action="{{ route('search.item') }}" method="GET">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="warehouse_id">Gudang</label>
                <x-adminlte-select2 name="warehouse_id">
                    @if(auth()->user()->isAdmin())
                        <option value="">Semua Gudang</option>
                    @endif
                    @foreach ($warehouses as $value => $label)
                        <option value="{{ $value }}" {{ old('warehouse_id', request('warehouse_id')) == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </x-adminlte-select2>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                <label for="search_category">Berdasarkan</label>
                <x-adminlte-select2 name="search_category" required>
                    <option value="code" {{ old('search_category', request('search_category')) == 'code' ? 'selected' : '' }}>Kode Barang</option>
                    <option value="name" {{ old('search_category', request('search_category')) == 'name' ? 'selected' : '' }}>Nama Barang</option>
                </x-adminlte-select2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="filter">Filter</label>
                <input type="text" class="form-control" id="filter" name="filter" value="{{ old('filter', request('filter')) }}" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="filter">&nbsp;<br/></label><br/>
                <button type="submit" class="btn btn-info">Search</button>
            </div>
        </div>
    </div>
</form>

<div class="card">
    <div class="card-header">
        <h3>SPM</h3>
    </div>
    <div class="card-body">
        <table id="tblSPM" class="table table-bordered data-table" style="width: 100%">
            <thead class="bg-primary text-white">
                <tr>
                    <th>Nomor SPM</th>
                    <th>Tanggal</th>
                    <th>Gudang</th>
                    <th>Requestor</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($datas as $data)
                    <tr>
                        <td>
                            <a href="{{ route('material.edit', $data->id) }}" target="_blank">{{ $data->mr_number }}</a>
                        </td>
                        <td>{{ $data->date }}</td>
                        <td>{{ optional($data->warehouse)->spk_number ?? 'N/A' }}</td>
                        <td>{{ $data->requestor->name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h3>Stock Barang</h3>
    </div>
    <div class="card-body">
        <table id="tblSPM" class="table table-bordered data-table" style="width: 100%">
            <thead class="bg-primary text-white">
                <tr>
                    <th>Gudang</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Stock Qty</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stocks as $data)
                    <tr>
                        <td>{{ $data->warehouse->spk_number }}</td>
                        <td>{{ $data->item->code }}</td>
                        <td>{{ $data->item->name }}</td>
                        <td>{{ $data->total_qty }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop

@section('css')

@stop

@section('js')
@stop