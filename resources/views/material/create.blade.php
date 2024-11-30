@extends('adminlte::page')

@section('title', 'Surat Pengajuan Material - Baru')

@section('content_header')
<h1>Surat Pengajuan Material - Baru</h1>
@stop

@section('content')
<form action="{{ route('material.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label for="mr_number">No. SPM</label>
                <input type="text" class="form-control" id="mr_number" name="mr_number" value="Nomor Otomatis" disabled>
            </div>
        </div>
        <div class="col-3">
            <div class="form-group">
                <label for="date">Tanggal SPM</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
        </div>
        <div class="col-3">
            <div class="form-group">
                <label for="warehouse_id">Gudang</label>
                <x-adminlte-select2 name="warehouse_id" required>
                    @foreach ($warehouses as $value => $label)
                        <option value="{{ $value }}">
                            {{ $label }}
                        </option>
                    @endforeach
                </x-adminlte-select2>
            </div>
        </div>
    </div>
    <h3>List Material</h3>
    <div class="row">
        <div class="col-12">
            <table id="tblItem" class="table table-bordered data-table table-striped" style="width: 100%">
                <thead class="bg-primary text-white">
                    <tr>
                        <th rowspan="2" style="min-width: 350px">Material</th>
                        <th rowspan="2">Qty</th>
                        <th rowspan="2">Satuan</th>
                        <th rowspan="2">Tanggal dibutuhkan</th>
                        <th rowspan="2">No. BOQ</th>
                        <th colspan="5" class="text-center">Need For</th>
                        <th rowspan="2"></th>
                    </tr>
                    <tr>
                        <th>M</th>
                        <th>T</th>
                        <th>HE</th>
                        <th>C</th>
                        <th>O</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <select class="form-control form-control-lg item-select" name="items[]" required>
                                <option value="">Select Item</option>
                                <!-- Options will be dynamically populated -->
                            </select>
                        </td>
                        <td><input type="text" class="form-control qty" name="qty[]"  placeholder="0" required></td>
                        <td><input type="text" class="form-control uom" name="uom[]" disabled></td>
                        <td><input type="date" class="form-control" name="date_needed[]" required></td>
                        <td><input type="text" class="form-control" name="boq[]"></td>
                        <td><input type="hidden" name="check_m[]" value="0"> <input type="checkbox" name="check_m[]" value="1" onclick="this.previousElementSibling.disabled = this.checked;"></td>
                        <td><input type="hidden" name="check_t[]" value="0"> <input type="checkbox" name="check_t[]" value="1" onclick="this.previousElementSibling.disabled = this.checked;"></td>
                        <td><input type="hidden" name="check_he[]" value="0"> <input type="checkbox" name="check_he[]" value="1" onclick="this.previousElementSibling.disabled = this.checked;"></td>
                        <td><input type="hidden" name="check_c[]" value="0"> <input type="checkbox" name="check_c[]" value="1" onclick="this.previousElementSibling.disabled = this.checked;"></td>
                        <td><input type="hidden" name="check_o[]" value="0"> <input type="checkbox" name="check_o[]" value="1" onclick="this.previousElementSibling.disabled = this.checked;"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="10">
                            Description
                            <textarea class="form-control" name="description[]"></textarea>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="12"><button type="button" id="addRow" class="btn btn-primary mb-3">Add Another Item</button></td>
                    </tr>
                </tfoot>
            </table>

    
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
    </div>
</form>
@stop

@section('css')
<style>
    .step {
        display: none;
    }
    .step.active {
        display: block;
    }
    .delete-row{
        color: red;
        cursor: pointer;
    }
    .qty{
        text-align: right;
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
    $(document).ready(function() {
        // Add new row
        let rowIndex = 1; // Initial row index


        $('#addRow').on('click', function() {
            rowIndex++; // Increment row index
            let newRow = `
                <tr>
                    <td>    
                        <select class="form-control item-select" name="items[]" required>
                            <!-- Options will be dynamically populated -->
                        </select>
                    </td>
                    <td><input type="text" class="form-control qty" name="qty[]" placeholder="0" required></td>
                    <td><input type="text" class="form-control uom" name="uom[]" disabled></td>
                    <td><input type="date" class="form-control" name="date_needed[]" required></td>
                    <td><input type="text" class="form-control" name="boq[]"></td>
                    <td><input type="hidden" name="check_m[]" value="0"> <input type="checkbox" name="check_m[]" value="1" onclick="this.previousElementSibling.disabled = this.checked;"></td>
                    <td><input type="hidden" name="check_t[]" value="0"> <input type="checkbox" name="check_t[]" value="1" onclick="this.previousElementSibling.disabled = this.checked;"></td>
                    <td><input type="hidden" name="check_he[]" value="0"> <input type="checkbox" name="check_he[]" value="1" onclick="this.previousElementSibling.disabled = this.checked;"></td>
                    <td><input type="hidden" name="check_c[]" value="0"> <input type="checkbox" name="check_c[]" value="1" onclick="this.previousElementSibling.disabled = this.checked;"></td>
                    <td><input type="hidden" name="check_o[]" value="0"> <input type="checkbox" name="check_o[]" value="1" onclick="this.previousElementSibling.disabled = this.checked;"></td>
                    <td><span class="delete-row"><i class="fas fa-trash"></i></span></td>
                </tr>
                <tr>
                    <td colspan="10">
                        Description
                        <textarea class="form-control" name="description[]"></textarea>
                    </td>
                </tr>`;
            $('#tblItem tbody').append(newRow);

            initializeSelect2(); // Re-initialize Select2

        });
        
        $(document).on('change', '.item-select', function() {
            let selectedItem = $(this).select2('data')[0];
            let uom = selectedItem.uom || ''; 
            console.log(selectedItem)
            $(this).closest('tr').find('.uom').val(uom);
        });
        
        $(document).on('click', '.delete-row', function() {
            $(this).closest('tr').next('tr').remove();
            $(this).closest('tr').remove();
            initializeSelect2();
        });
        initializeSelect2()
    });
    function initializeSelect2() {
        $('.item-select').select2({
            placeholder: 'Search for an item',
            ajax: {
                url: '{{ route('items.search') }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.items,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },

                templateResult: function (item) {
                    if (!item.id) return item.text;

                    // Create the dropdown template with the new format
                    var result = $('<span>' +
                        '<strong>' + item.code + ' - ' + item.name + '</strong><br/>' + 
                        '<small>' + item.description + '</small>' + 
                        '</span>');

                    result.attr('data-uom', item.uom || '');

                    return result;
                },
                templateSelection: function (item) {
                    return item.name || item.id;
                },

                cache: true
            },
            minimumInputLength: 2
        });
        
    }

    document.querySelector('form').addEventListener('submit', function () {
        document.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
            if (!checkbox.checked) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = checkbox.name;
                hiddenInput.value = '0';
                checkbox.parentNode.appendChild(hiddenInput);
            }
        });
    });

    $('form').on('submit', function(event) {
        event.preventDefault(); // Prevent normal form submission

        // Get form data
        var formData = $(this).serialize();

        // Send AJAX request
        $.ajax({
            url: '{{ route('material.store') }}', // Set your route here
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Handle success (show message, reset form, etc.)
                    Swal.fire('Success', 'Material request submitted successfully!', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', 'Something went wrong. Please try again!', 'error');
                }
            },
            error: function(xhr, status, error) {
                // Handle errors (e.g., validation errors)
                var errors = xhr.responseJSON.errors;
                console.log(errors)
                var errorMessage = '';
                for (var key in errors) {
                    errorMessage += errors[key].join(', ') + '\n';
                }
                Swal.fire('Error', errorMessage, 'error');
            }
        });
    });

</script>
@include('layouts.errors.swal-alert')
@stop
