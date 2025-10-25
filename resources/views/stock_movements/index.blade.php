@extends('adminlte::page')

@section('title', 'Mutasi')

@section('content_header')
<h1>Mutasi Barang</h1>
@stop

@section('content')
<a href="{{ route('stock_movements.create') }}" class="btn btn-primary">Buat Mutasi Baru</a>

<table class="table table-bordered table-striped mt-3">
    <thead class="thead-dark">
        <tr>
            <th>Tanggal</th>
            <th>Tipe Mutasi</th>
            <th>Gudang Asal</th>
            <th>Gudang Tujuan</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($movements as $m)
        <tr>
            <td>{{ $m->date }}</td>
            <td>{{ strtoupper($m->movement_type) }}</td>
            <td>{{ $m->warehouseFrom->name ?? '-' }}</td>
            <td>{{ $m->warehouseTo->name ?? '-' }}</td>
            <td>
                <a href="{{ route('stock_movements.show', $m->id) }}" class="btn btn-sm btn-info">View</a>
                <a href="{{ route('stock_movements.edit', $m->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('stock_movements.destroy', $m->id) }}" method="POST" style="display:inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus Mutasi?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $movements->links() }}
@endsection