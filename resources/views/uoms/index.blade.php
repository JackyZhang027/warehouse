@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')
@can('uom-create')
<button class="btn btn-primary mb-3" data-toggle="modal" data-target="#createUomModal">Add New UOM</button>    
@endcan
<table id="tblUom" class="table table-bordered data-table" style="width: 100%">
    <thead class="bg-primary text-white">
        <tr>
            <th style="width: 25px">#</th>
            <th>Name</th>
            <th style="width: 100px">Action</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>


<!-- Create UOM Modal -->
<div class="modal fade" id="createUomModal" tabindex="-1" role="dialog" aria-labelledby="createUomModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="createUomForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createUomModalLabel">Create UOM</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit UOM Modal -->
<div class="modal fade" id="editUomModal" tabindex="-1" role="dialog" aria-labelledby="editUomModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editUomForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editUomModalLabel">Edit UOM</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editName">Name</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
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
            ajax: "{{ route('uoms.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
          
    });

    $('#createUomForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: '{{ route('uoms.store') }}',
            data: $(this).serialize(),
            success: function (response) {
                $('#createUomModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.msg,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000
                });
                $('#tblUom').DataTable().ajax.reload();
            },
            error: function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "An error occurred while creating the warehouse",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000
                });
            }
        });
    });


    $(document).on('click', '.edit-btn', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');
        $('#editUomModal').modal('show');
        $('#editName').val(name);
        $('#editUomForm').attr('action', '/uoms/' + id);
    });
    $('#editUomForm').on('submit', function (e) {
        e.preventDefault();

        var id = $(this).attr('action').split('/').pop();

        $.ajax({
            type: 'PUT',
            url: '/uoms/' + id,
            data: $(this).serialize(),
            success: function (response) {
                $('#editUomModal').modal('hide');
                $('#editUomForm')[0].reset();
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.msg,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000
                });
                $('#tblUom').DataTable().ajax.reload();
            },
            error: function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "An error occurred while creating the warehouse",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000
                });
            }
        });
    });  
</script>
@include("layouts.helper.delete")
@stop