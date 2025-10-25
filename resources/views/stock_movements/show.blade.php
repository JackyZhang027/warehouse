@extends('adminlte::page')

@section('title', 'Mutasi')

@section('content_header')
<h1>Mutasi Barang</h1>
@stop

@section('content')
<div class="row">
    <div class="col-sm-6 col-md-2 font-weight-bold">
        Date
    </div>
    <div class="col-auto">
        {{ $stockMovement->date }}
    </div>
</div>
<div class="row">
    <div class="col-sm-6 col-md-2 font-weight-bold">
        Type
    </div>
    <div class="col-auto">
        {{ $stockMovement->movement_type_label  }}
    </div>
</div>
<div class="row">
    <div class="col-sm-6 col-md-2 font-weight-bold">
        From
    </div>
    <div class="col-auto">
        {{ $stockMovement->warehouseFrom->code ?? '-' }}
    </div>
</div>
<div class="row">
    <div class="col-sm-6 col-md-2 font-weight-bold">
        To
    </div>
    <div class="col-auto">
        {{ $stockMovement->warehouseTo->code ?? '-' }}
    </div>
</div>
<br/>
<h4>List Barang</h4>
<table class="table table-bordered table-striped">
    <thead class="bg-primary text-white">
        <tr>
            <th>Kode Barang</th>
            <th>Qty</th>
            <th>Satuan</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($stockMovement->lines as $line)
        <tr>
            <td>{{ $line->item->code }} - {{ $line->item->name }}</td>
            <td>{{ number_format($line->qty) }}</td>
            <td>{{ $line->item->uom->name }}</td>
            <td>{{ $line->remarks }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection