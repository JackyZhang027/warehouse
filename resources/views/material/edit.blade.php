@extends('adminlte::page')

@section('title', 'Edit Material Request')

@section('content_header')
<h1>Surat Pengajuan Material {{$materialRequest->mr_number}}</h1>
@stop

@section('content')
<form action="{{ route('material.update', $materialRequest->id) }}" method="POST">
    @csrf
    @method('PUT')
    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
    <div class="btn-group">
        <button type="button" class="btn btn-primary"><i class="fas fa-download"></i> Download</button>
        <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu" role="menu" style="">
            <a class="dropdown-item" href="{{route('material.export', ['id'=>$materialRequest->id, 'type'=>'EXCEL'])}}"><i class="fas fa-file-excel text-success"></i> Excel</a>
            <a class="dropdown-item" href="{{route('material.export', ['id'=>$materialRequest->id, 'type'=>'PDF'])}}" target="blank"><i class="fas fa-file-pdf text-danger"></i> PDF</a>
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-3">
            <div class="form-group">
                <label for="mr_number">No. SPM</label>
                <input type="text" class="form-control" id="mr_number" name="mr_number" value="{{ $materialRequest->mr_number }}" disabled>
            </div>
        </div>
        <div class="col-3">
            <div class="form-group">
                <label for="date">Tanggal SPM</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ $materialRequest->date }}" required>
            </div>
        </div>
        <div class="col-3">
            <div class="form-group">
                <label for="warehouse_id">Gudang</label>
                <x-adminlte-select2 name="warehouse_id" required disabled>
                    @foreach ($warehouses as $value => $label)
                        <option value="{{ $value }}" {{ $materialRequest->warehouse_id == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </x-adminlte-select2>
            </div>
        </div>
        <div class="col-12">
            <table id="tblItem" class="table table-bordered data-table table-striped" style="width: 100%">
                <thead class="bg-primary text-white">
                    <tr>
                        <th rowspan="2" style="min-width: 35vw">Material</th>
                        <th rowspan="2">Qty</th>
                        <th rowspan="2">Satuan</th>
                        <th rowspan="2">Tanggal dibutuhkan</th>
                        <th rowspan="2">No. BOQ</th>
                        <th colspan="5" class="text-center">Keperluan</th>
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
                    
                    @foreach($materialRequest->items as $index => $item)
                        @php
                            $is_readonly = false;
                            if ($item->do_qty > 0){
                                $is_readonly = true;
                            }   
                        @endphp
                        <tr>
                            <td>
                                @if ($is_readonly)
                                    <select class="form-control item-select" name="items[]" required @readonly($is_readonly) style="pointer-events: none; background-color: #f0f0f0;">    
                                @else
                                    <select class="form-control item-select" name="items[]" required>
                                @endif
                                    <option value="">Pilih Material</option>
                                    @foreach($items as $option)
                                        <option value="{{ $option->id }}" 
                                                data-uom="{{ $option->uom->name }}" 
                                                {{ $item->item->id == $option->id ? 'selected' : '' }}>
                                            {{ $option->code }} - {{ $option->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" class="form-control qty" name="qty[]" value="{{ $item->qty }}" required @readonly($is_readonly)></td>
                            <td><input type="text" class="form-control uom" name="uom[]" value="{{ $item->item->uom->name }}" disabled></td>
                            <td><input type="date" class="form-control" name="date_needed[]" value="{{ $item->date_needed }}" required></td>
                            <td><input type="text" class="form-control" name="boq[]" value="{{ $item->boq_code }}"></td>
                            <td><input type="checkbox" name="check_m[]" {{ $item->check_m ? 'checked' : '' }}></td>
                            <td><input type="checkbox" name="check_t[]" {{ $item->check_t ? 'checked' : '' }}></td>
                            <td><input type="checkbox" name="check_he[]" {{ $item->check_he ? 'checked' : '' }}></td>
                            <td><input type="checkbox" name="check_c[]" {{ $item->check_c ? 'checked' : '' }}></td>
                            <td><input type="checkbox" name="check_o[]" {{ $item->check_o ? 'checked' : '' }}></td>
                            <td>
                                @if ($item->do_qty <= 0)
                                    <span class="delete-row"><i class="fas fa-trash"></i></span>    
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10">
                                Deskripsi
                                <textarea class="form-control" name="description[]">{{ $item->description }}</textarea>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="11"><button type="button" id="addRow" class="btn btn-primary mb-3">Tambah Item Lain</button></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</form>
@stop

@section('css')
<style>
    .delete-row {
        color: red;
        cursor: pointer;
    }
    .qty {
        text-align: right;
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        let items = @json($items); // Assuming $items is passed from the backend

        function populateDropdown(selectElement) {
            console.log(items)
            if (selectElement.children('option').length > 1) return; // Prevent resetting existing dropdowns
            selectElement.append('<option value="">Pilih Material</option>');
            $.each(items, function(index, item) {
                selectElement.append('<option value="' + item.id + '" data-uom="' + item.uom.name + '">' + item.code + ' - ' + item.name + '</option>');
            });
        }

        // Populate only new dropdowns on page load
        $('.item-select').each(function() {
            populateDropdown($(this));
            let selectedItem = $(this).data('selected-item');
            if (selectedItem) {
                $(this).val(selectedItem).trigger('change');
            }
        });


        let rowIndex = $('#tblItem tbody tr').length / 2;

        $('#addRow').on('click', function() {
            rowIndex++;
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
                        Deskripsi
                        <textarea class="form-control" name="description[]"></textarea>
                    </td>
                </tr>`;
            $('#tblItem tbody').append(newRow);
            populateDropdown($('#tblItem tbody tr').eq(-2).find('.item-select'));
            $('.item-select').trigger('change');
        });

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

            let selectedItem = $(this).find('option:selected');
            let uom = selectedItem.data('uom') || '';
            $(this).closest('tr').find('.uom').val(uom);
        });

        $(document).on('click', '.delete-row', function() {
            $(this).closest('tr').next('tr').remove();
            $(this).closest('tr').remove();
            updateRowNumbers();
            $('.item-select').trigger('change');
        });
    });
</script>

@include('layouts.errors.swal-alert')
@stop
