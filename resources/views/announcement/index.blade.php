@extends('adminlte::page')

@section('title', 'Pengumuman')

@section('content_header')

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="float-left">
            <h1>Pengumuman</h1>
        </div>
        <div class="float-right">
            @can('announcement-create')
                <a class="btn btn-success mb-2" href="{{ route('announcement.create') }}"><i class="fa fa-plus"></i> Buat Pengumuman</a>
            @endcan
        </div>
    </div>
</div>
@stop

@section('content')
<table id="tblAnnouncement" class="table table-bordered data-table" style="width: 100%">
    <thead class="bg-primary text-white">
        <tr>
            <th style="width: 20px;">No</th>
            <th>Tanggal</th>
            <th>Judul</th>
            <th>Deskripsi</th>
            <th>Active Sampai</th>
            <th>Published</th>
            <th width="100px">Action</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
@stop

@section('css')

@stop

@section('js')
<script type="text/javascript">
    var table;
    $(function () {
          
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('announcement.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'date', name: 'date'},
                {data: 'title', name: 'title'},
                {data: 'description', name: 'description'},
                {data: 'expire_date', name: 'expire_date'},
                {data: 'published', name: 'published'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
          
    });

</script>
@include("layouts.helper.delete")
@stop