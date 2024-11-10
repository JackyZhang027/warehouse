@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="float-left">
            <h2>Informasi Perusahaan</h2>
        </div>
    </div>
</div>
@stop

@section('content')

@session('success')
    <div class="alert alert-success" role="alert"> 
        {{ $value }}
    </div>
@endsession

<form action="{{ isset($site) ? route('sites.update', $site->id) : route('sites.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($site))
        @method('PUT')
    @endif

    <div class="row mb-3">
        <div class="col-8">
            <label for="name" class="form-label">Nama Perusahaan</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $site->name ?? '') }}" required>
        </div>
        <div class="col-4">
            <label for="code" class="form-label">Inisial Perusahaan</label>
            <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $site->code ?? '') }}" required>
        </div>
    </div>
    <div class="row mn-3">
        <div class="col-4">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $site->email ?? '') }}" required>
        </div>
        <div class="col-4">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $site->phone ?? '') }}" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address" required>{{ old('address', $site->address ?? '') }}</textarea>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-6">
            <label for="logo_path" class="form-label">Logo</label>
            <input type="file" class="form-control" id="logo_path" name="logo_path" accept="image/*">
            @if(isset($site) && $site->logo_path)
                <img src="{{ asset('storage/' . $site->logo_path) }}" alt="Logo" width="100">
            @endif
        </div>
        <div class="col-6">
            <label for="favicon_path" class="form-label">Favicon</label>
            <input type="file" class="form-control" id="favicon_path" name="favicon_path" accept="image/*">
            @if(isset($site) && $site->favicon_path)
                <img src="{{ asset($site->favicon_path) }}" alt="Favicon" width="50">
            @endif
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>


@stop

@section('css')
@stop

@section('js')
<script>
    var table;
    $(function () {
          
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('roles.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
          
    });
</script>
@include("layouts.helper.delete")
@stop