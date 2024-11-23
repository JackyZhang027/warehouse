@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')

<div class="row">
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
@stop

@section('css')
@stop

@section('js')
@stop