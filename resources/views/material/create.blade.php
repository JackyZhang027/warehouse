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
                            <select class="form-control item-select" name="items[]" required>
                                <option value="">Select Item</option>
                                <!-- Options will be dynamically populated -->
                            </select>
                        </td>
                        <td><input type="text" class="form-control qty" name="qty[]"  placeholder="0" required></td>
                        <td><input type="text" class="form-control uom" name="uom[]" disabled></td>
                        <td><input type="date" class="form-control" name="date_needed[]" required></td>
                        <td><input type="text" class="form-control" name="boq[]"></td>
                        <td><input type="checkbox" name="check_m[]"></td>
                        <td><input type="checkbox" name="check_t[]"></td>
                        <td><input type="checkbox" name="check_he[]"></td>
                        <td><input type="checkbox" name="check_c[]"></td>
                        <td><input type="checkbox" name="check_o[]"></td>
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
    $(document).ready(function() {
        let items = @json($items); // Assuming $items is passed from the backend
        console.log(items)
        // Function to populate the dropdown
        function populateDropdown(selectElement) {
            selectElement.empty();
            selectElement.append('<option value="">Select Item</option>');
            $.each(items, function(index, item) {
                selectElement.append('<option value="' + item.id + '" data-uom="' + item.uom.name + '">' + item.code + ' - ' + item.name + '</option>');
            });
        }

        // Initialize the first dropdown
        populateDropdown($('.item-select'));

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
                    <td><input type="checkbox" name="check_m[]"></td>
                    <td><input type="checkbox" name="check_t[]"></td>
                    <td><input type="checkbox" name="check_he[]"></td>
                    <td><input type="checkbox" name="check_c[]"></td>
                    <td><input type="checkbox" name="check_o[]"></td>
                    <td><span class="delete-row"><i class="fas fa-trash"></i></span></td>
                </tr>
                <tr>
                    <td colspan="10">
                        Description
                        <textarea class="form-control" name="description[]"></textarea>
                    </td>
                </tr>`;
            $('#tblItem tbody').append(newRow);
            // Populate the dropdown for the new row
            populateDropdown($('#tblItem tbody tr').eq(-2).find('.item-select'));

            // Trigger change event to update the disabled options
            $('.item-select').trigger('change');

        });
         // Prevent duplicate selection
        $(document).on('change', '.item-select', function() {
            let selectedItems = [];
            $('.item-select').each(function() {
                if ($(this).val() !== "") {
                    selectedItems.push($(this).val());
                }
            });

            $('.item-select').each(function() {
                let currentValue = $(this).val();
                $(this).find('option').each(function() {
                    if ($(this).val() !== "" && $(this).val() !== currentValue && selectedItems.includes($(this).val())) {
                        $(this).prop('disabled', true);
                    } else {
                        $(this).prop('disabled', false);
                    }
                });
            });

            // Update UOM based on selected item
            let selectedItem = $(this).find('option:selected');
            let uom = selectedItem.data('uom') || '';
            $(this).closest('tr').find('.uom').val(uom);
        });
        // Delete row
        $(document).on('click', '.delete-row', function() {
            $(this).closest('tr').next('tr').remove(); // Remove description row
            $(this).closest('tr').remove(); // Remove main row
            $('.item-select').trigger('change'); // Update options after deletion
        });

    });
</script>

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
        var currentStep = 0;
        var steps = $('.step');
        
        function showStep(step) {
            steps.removeClass('active');
            $(steps[step]).addClass('active');
        }

        function validateStep(step) {
            var isValid = true;
            var firstInvalid = null;
            $(steps[step]).find('input, textarea').each(function() {
                if (!this.checkValidity()) {
                    $(this).addClass('is-invalid');
                    if (isValid) {
                        firstInvalid = this;
                    }
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            if (!isValid && firstInvalid) {
                firstInvalid.focus();
            }
            return isValid;
        }

        
        $('.next-step').click(function() {
            if (validateStep(currentStep)) {
                currentStep++;
                if (currentStep >= steps.length) {
                    currentStep = steps.length - 1;
                }
                showStep(currentStep);
            }
        });
        
        $('.prev-step').click(function() {
            currentStep--;
            if (currentStep < 0) {
                currentStep = 0;
            }
            showStep(currentStep);
        });
    });
</script>
@include('layouts.errors.swal-alert')
@stop
