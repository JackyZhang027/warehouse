@extends('adminlte::page')

@section('title', 'Create Item')

@section('content_header')
<h1>New Item</h1>
@stop

@section('content')
<div>
    <form id="itemForm">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="code">Item Code</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="name">Item Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <x-adminlte-select2 name="category_id" required>
                                @foreach($categories as $data)
                                    <option value="{{ $data->id }}" {{ old('category_id') == $data->id ? 'selected' : '' }}>
                                        {{ $data->name }}
                                    </option>
                                @endforeach
                            </x-adminlte-select2>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="category_id">Unit of Measure</label>
                            <x-adminlte-select2 name="uom_id" required>
                                @foreach($uoms as $data)
                                    <option value="{{ $data->id }}" {{ old('uom_id') == $data->id ? 'selected' : '' }}>
                                        {{ $data->name }}
                                    </option>
                                @endforeach
                            </x-adminlte-select2>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">Item Description</label>
                            <textarea class="form-control" id="description" name="description" rows="6" required></textarea>
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
<style>
    .step {
        display: none;
    }
    .step.active {
        display: block;
    }
</style>
@stop

@section('js')
<script>
    // Get CSRF token from meta tag
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Add CSRF token to AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });
    $('#itemForm').submit(function(event) {
        event.preventDefault();
        
        // Perform form validation and submission logic here
        var formData = $(this).serialize();
        // Here you can make an AJAX call to submit the form data to the server
        $.ajax({
            url: '{{ route('items.store') }}', // Replace with your store route
            type: 'POST',
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.msg,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1000
                }).then(function() {
                    window.location.href = "{{route('items.index')}}";
                });;
            },
            error: function(response) {
                console.log(response)
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "An error occurred while creating the item",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000
                });
            }
        });
    });
</script>
@stop
