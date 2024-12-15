@extends('adminlte::page')

@section('title', 'Edit Permission')

@section('content_header')
<h1>Edit Permission</h1>
@stop

@section('content')
<div>  
    <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Include PUT method for updates -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-sm-12 mb-2">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $permission->name) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 mb-2">
                        <div class="form-group">
                            <label for="guard_name">Guard Name</label>
                            <input type="text" class="form-control" id="guard_name" name="guard_name" value="{{ old('guard_name', $permission->guard_name) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 mb-2">
                        <div class="form-group">
                            <label for="display_name">Display Name</label>
                            <input type="text" class="form-control" id="display_name" name="display_name" value="{{ old('display_name', $permission->display_name) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 mb-2">
                        <div class="form-group">
                            <label for="model_type">Model Name</label>
                            <input type="text" class="form-control" id="model_type" name="model_type" value="{{ old('model_type', $permission->model_type) }}" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Update</button>
            </div>
        </div>
    </form>
</div>

@stop

@section('css')
@stop

@section('js')
<script>
</script>
@include('layouts.errors.swal-alert')
@stop
