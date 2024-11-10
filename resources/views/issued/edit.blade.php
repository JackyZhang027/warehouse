@extends('adminlte::page')

@section('title', 'Edit Barang Keluar')

@section('content_header')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="float-left">
            <h1>Edit Barang Keluar</h1>
        </div>
        <div class="float-right">
            @can('item-create')
                <div class="btn-group">
                    <button type="button" class="btn btn-primary"><i class="fas fa-download"></i> Download</button>
                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" role="menu" style="">
                        <a class="dropdown-item" href="{{route('delivery.export', ['id'=>$itemOut->id, 'type'=>'EXCEL'])}}"><i class="fas fa-file-excel text-success"></i> Excel</a>
                        <a class="dropdown-item" href="{{route('delivery.export', ['id'=>$itemOut->id, 'type'=>'PDF'])}}" target="blank"><i class="fas fa-file-pdf text-danger"></i> PDF</a>
                    </div>
                </div>
            @endcan
        </div>
    </div>
</div>
@stop

@section('content')
<form action="{{ route('delivery.update', $itemOut->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="card">
        <div class="card-body">
            <h3>General Information</h3>
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="io_number">No. Barnag Keluar</label>
                        <input type="text" class="form-control" id="io_number" name="io_number" value="{{ $itemOut->io_number }}" disabled>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="warehouse_id">Gudang</label>
                        <input type="text" class="form-control" id="warehouse_id" value="{{$itemOut->warehouse->spk_number}} - {{$itemOut->warehouse->project}}" disabled/>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="date">Tanggal Barang Keluar</label>
                        <input type="datetime-local" class="form-control" id="date" name="date" value="{{ $itemOut->date }}" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="remark">Remark</label>
                        <textarea class="form-control" id="remark" name="remark">{{ $itemOut->remark }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="card">
    <div class="card-body">
        <div id="itemsTableDiv" style="display: block;">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="float-left">Daftar Barang Keluar</h4>
                    <button type="button" class="btn btn-info float-right" data-toggle="modal" data-target="#mdlAddItem"><i class="fas fa-plus"></i> Tambah</button>
                </div>
                <div class="col-md-12">
                    <table id="itemsTable" class="table table-bordered table-hover table-striped">
                        <thead class="bg-primary text-white text-center">
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Satuan</th>
                                <th>Keterangan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($itemOutDetails as $item)
                                <tr>
                                    <td>
                                        [{{$item->item->code}}] 
                                        {{$item->item->name}}

                                    </td>
                                    <td>{{$item->qty}}</td>
                                    <td>{{$item->item->uom->name}}</td>
                                    <td>{{$item->remark}}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ route('out.item.destroy', $item->id) }}', '');">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<x-adminlte-modal id="mdlAddItem" title="Tambah Barang" size="xl" theme="primary"
    icon="fas fa-box" v-centered static-backdrop scrollable>
    
    <form id="items-form" action="{{ route('out.items.store', $itemOut->id) }}" method="POST">
        @csrf
        <div style="height:650px;">
            <div class="row">
                <div class="col-md-12">

                    <!-- Search Input -->
                    <div class="form-group">
                        <input type="text" id="search-item" class="form-control" placeholder="Cari Barang ...">
                    </div>
                    <table id="items-table" class="table table-bordered table-hover table-striped">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>UOM</th>
                                <th style="width: 100px">Stock Qty</th>
                                <th style="width: 150px">Qty Keluar</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="items-table-body">
                            <!-- Items will be inserted here dynamically -->
                        </tbody>
                    </table>
                    
                </div>
            </div>
        

        </div>
        <x-slot name="footerSlot">
            <x-adminlte-button class="mr-auto" theme="success" label="Tambah" onclick="submitCheckedRows()"/>
            <x-adminlte-button theme="danger" label="Batal" data-dismiss="modal"/>
        </x-slot>
    </form>
</x-adminlte-modal>
@stop

@include("layouts.helper.delete")
@include('layouts.errors.swal-alert')
@section('css')
<style>
    .step {
        display: none;
    }
    .step.active {
        display: block;
    }
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
    $('#search-item').on('keyup', function() {
        let keyword = $(this).val();
        if (keyword.length >= 2) {
            $.ajax({
                url: "{{ route('warehouse.items.search') }}",
                type: "GET",
                data: { keyword: keyword },
                success: function(data) {
                    let tableBody = $('#items-table-body');
                    tableBody.empty();

                    data.forEach(function(item) {
                        tableBody.append(`
                            <tr>
                                <td>
                                    <input type="checkbox" class="update-qty-checkbox" onchange="toggleQtyInput(this)">
                                    <input type="hidden" name="item_id[]" value="${item.item.id}">
                                    ${item.item.code}
                                </td>
                                <td>${item.item.name}</td>
                                <td>${item.item.uom.name}</td>
                                <td>${item.qty}</td>
                                <td><input type="number" name="qty[]" class="form-control" min="0" disabled></td>
                                <td><input type="text" name="remark[]" class="form-control" disabled></td>
                            </tr>
                        `);
                    });
                },
                error: function(xhr) {
                    console.error('Error fetching items:', xhr.responseText);
                }
            });
        }
    });
});

function toggleQtyInput(checkbox) {
    var qtyInput = checkbox.closest('tr').querySelector('input[name="qty[]"]');
    var remarkInput = checkbox.closest('tr').querySelector('input[name="remark[]"]');
    qtyInput.disabled = !checkbox.checked;
    remarkInput.disabled = !checkbox.checked;
}

function submitCheckedRows() {
    // Get the form
    var form = $('#items-form');
    
    // Find all checked rows
    var checkedRows = $('#items-table-body tr').filter(function() {
        return $(this).find('.update-qty-checkbox').is(':checked');
    });
    
    // Check if there are any checked rows
    if (checkedRows.length === 0) {
        alert('Please select at least one item.');
        return false;
    }

    // Create a new empty form data
    var formData = new FormData();
    
    // Append only the checked rows' data
    checkedRows.each(function() {
        var row = $(this);
        
        // Collect item ID
        var itemId = row.find('input[name="item_id[]"]').val();
        formData.append('item_id[]', itemId);
        
        // Collect quantity
        var qty = row.find('input[name="qty[]"]').val();
        formData.append('qty[]', qty);
        
        // Collect remark
        var remark = row.find('input[name="remark[]"]').val();
        formData.append('remark[]', remark);
    });

    // Submit the form data via AJAX
    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token for Laravel
        },
        contentType: false,
        processData: false,
        success: function(response) {
            alert('Barang berhasil ditambahkan');
            location.reload()
        },
        error: function(xhr) {
            console.error('Error submitting form:', xhr.responseText);
        }
    });

    return false; 
}



</script>
@stop
