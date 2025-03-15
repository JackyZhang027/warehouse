@extends('adminlte::page')

@section('title', 'Edit Material Request')

@section('content_header')
<h1>Surat Pengajuan Material {{$materialRequest->mr_number}}</h1>
@stop

@section('content')
<form action="{{ route('material.update', $materialRequest->id) }}" method="POST">
    @csrf
    @method('PUT')
    @can('spm-edit')
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
    @endcan
    @can('spm-download')
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
    @endcan
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
                <input type="date" class="form-control" id="date" name="date" value="{{ $materialRequest->date->format('Y-m-d') }}" 
                    @unless(auth()->user()->can('spm-edit-date')) readonly @endunless required>
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
                        <th rowspan="2" style="min-width: 35%; width: 35%;">Material</th>
                        <th rowspan="2" style="width: 10%;">Qty</th>
                        <th rowspan="2" style="width: 10%;">Sat</th>
                        <th rowspan="2" style="width: 10%;">Tanggal dibutuhkan</th>
                        <th rowspan="2" style="width: 10%;">No. BOQ</th>
                        <th colspan="5" class="text-center" style="width: 25%;">Keperluan</th>
                        <th rowspan="2" style="width: 5%;"></th>
                    </tr>
                    <tr>
                        <th style="width: 5%;">M</th>
                        <th style="width: 5%;">T</th>
                        <th style="width: 5%;">HE</th>
                        <th style="width: 5%;">C</th>
                        <th style="width: 5%;">O</th>
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
                                <input type="hidden" value="{{ $item->id }}" name="item_id[]"/>
                            </td>
                            <td><input type="text" class="form-control qty" name="qty[]" value="{{ $item->qty }}" required @readonly($is_readonly)></td>
                            <td><input type="text" class="form-control uom" name="uom[]" value="{{ $item->item->uom->name }}" disabled></td>
                            <td><input type="date" class="form-control" name="date_needed[]" value="{{ $item->date_needed->format('Y-m-d') }}" required></td>
                            <td><input type="text" class="form-control" name="boq[]" value="{{ $item->boq_code }}"></td>
                            <td>
                                <input type="hidden" name="check_m[{{ $index }}]" value="0">
                                <input type="checkbox" name="check_m[{{ $index }}]" value="1" {{ $item->check_m ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="hidden" name="check_t[{{ $index }}]" value="0">
                                <input type="checkbox" name="check_t[{{ $index }}]" value="1" {{ $item->check_t ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="hidden" name="check_he[{{ $index }}]" value="0">
                                <input type="checkbox" name="check_he[{{ $index }}]" value="1" {{ $item->check_he ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="hidden" name="check_c[{{ $index }}]" value="0">
                                <input type="checkbox" name="check_c[{{ $index }}]" value="1" {{ $item->check_c ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="hidden" name="check_o[{{ $index }}]" value="0">
                                <input type="checkbox" name="check_o[{{ $index }}]" value="1" {{ $item->check_o ? 'checked' : '' }}>
                            </td>
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
   // Get CSRF token from meta tag
   var csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Add CSRF token to AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });
    $(document).ready(function() {
        $('#addRow').on('click', function() {
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

            initializeSelect2(); 

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
</script>

@include('layouts.errors.swal-alert')
@stop
