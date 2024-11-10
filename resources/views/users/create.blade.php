@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Create User</h1>

@stop

@section('content')
@include("layouts.errors.alert")
<form method="POST" action="{{ route('users.store') }}">
    @csrf
    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <strong>Name</strong>
                <input type="text" name="name" placeholder="Name" class="form-control" required>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <strong>Email</strong>
                <input type="email" name="email" placeholder="Email" class="form-control" required>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <strong>Password</strong>
                <input type="password" name="password" placeholder="Password" class="form-control" required>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <div class="form-group">
                <strong>Confirm Password</strong>
                <input type="password" name="confirm-password" placeholder="Confirm Password" class="form-control" required>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                @php
                    $config = [
                        "placeholder" => "Pilih Roles...",
                        "allowClear" => true,
                    ];
                @endphp
                <x-adminlte-select2 id="roles" name="roles[]" label="Roles"
                    label-class="text-dark" igroup-size="sm" :config="$config" multiple required>
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-gradient-info">
                            <i class="fas fa-user-tag"></i>
                        </div>
                    </x-slot>
                    @foreach ($roles as $value => $label)
                        <option value="{{ $value }}">
                            {{ $label }}
                        </option>
                     @endforeach
                </x-adminlte-select2>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                @php
                    $config = [
                        "placeholder" => "Pilih Gudang ...",
                        "allowClear" => true,
                    ];
                @endphp
                <x-adminlte-select2 id="warehouses" name="warehouses[]" label="Gudang"
                    label-class="text-dark" igroup-size="sm" :config="$config" multiple required>
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-gradient-primary">
                            <i class="fas fa-warehouse"></i>
                        </div>
                    </x-slot>
                    @foreach ($warehouses as $value => $label)
                        <option value="{{ $value }}">
                            {{ $label }}
                        </option>
                     @endforeach
                </x-adminlte-select2>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3"><i class="fas fa-save"></i> Submit</button>
        </div>
    </div>
</form>
@stop

@section('css')
@stop

@section('js')
@stop