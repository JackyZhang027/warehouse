@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')
@include("layouts.errors.alert")
<form method="POST" action="{{ route('roles.update', $role->id) }}">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                <input type="text" name="name" placeholder="Name" class="form-control" value="{{ $role->name }}">
            </div>
        </div>
    </div>
    <div class="row">
        @foreach($permissions as $modelType => $groupedPermissions)
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ $modelType ?? 'No Model Type' }}</h4>
                    </div>
                    <div class="card-body">
                        @foreach($groupedPermissions as $permission)
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" name="permission[{{ $permission->id }}]" value="{{ $permission->id }}"
                                            class="form-check-input"
                                            {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                    {{ $permission->display_name ?? $permission->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary btn-sm mb-3"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
    </div>
</form>
@stop

@section('css')
@stop

@section('js')
@stop