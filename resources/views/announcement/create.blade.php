@extends('adminlte::page')

@section('title', 'Buat Pengumuman')

@section('content_header')
<h1>Pengumuman Baru</h1>
@stop

@section('content')
<div>  
    <form action="{{ route('announcement.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="title">Judul</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="expired">Tanggal</label>
                            <input type="date" class="form-control" name="date" value="{{ old('date') }}" required/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="published">Published</label>
                            <select class="form-control" name="published" required>
                                <option value="0" {{ old('published') == 0 ? 'selected' : '' }}>Draft</option>
                                <option value="1" {{ old('published') == 1 ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="expired">Expired Date</label>
                            <input type="date" class="form-control" name="expire_date" value="{{ old('expire_date') }}" required/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="6" required>{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
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
