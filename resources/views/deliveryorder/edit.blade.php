@extends('adminlte::page')

@section('title', 'Edit Surat Jalan')
@section('style')
@endsection
@section('content_header')

<style>
    #mdlAddItem .modal-lg{
        width: 90%!important;
        min-width: 90%!important;
    }
</style>
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="float-left">
            <h1>Edit Surat Jalan</h1>
        </div>
        
    
        <div class="float-right">
            @can('delivery-note-download')
                <div class="btn-group">
                    <button type="button" class="btn btn-primary"><i class="fas fa-download"></i> Download</button>
                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu" role="menu" style="">
                        <a class="dropdown-item" href="{{route('delivery.export', ['id'=>$deliveryOrder->id, 'type'=>'EXCEL'])}}"><i class="fas fa-file-excel text-success"></i> Excel</a>
                        <a class="dropdown-item" href="{{route('delivery.export', ['id'=>$deliveryOrder->id, 'type'=>'PDF'])}}" target="blank"><i class="fas fa-file-pdf text-danger"></i> PDF</a>
                    </div>
                </div>
            @endcan
        </div>
    </div>
</div>
@stop

@section('content')
<form action="{{ route('delivery.update', $deliveryOrder->id) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="card">
        <div class="card-body">
            <h3>General Information</h3>
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="do_number">No. Surat Jalan</label>
                        <input type="text" class="form-control" id="do_number" name="do_number" value="{{ $deliveryOrder->do_number }}" disabled>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="warehouse_id">Gudang</label>
                        <x-adminlte-select2 id="warehouse_id" name="warehouse_id" disabled>
                            @foreach ($warehouses as $value => $label)
                                <option value="{{ $value }}" {{ $value == $deliveryOrder->warehouse_id ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </x-adminlte-select2>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="date">Tanggal SJ</label>
                        <input type="date" class="form-control" id="date" name="date" value="{{ $deliveryOrder->date->format('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="police_no">Nomor Polisi</label>
                        <input type="text" class="form-control" id="police_no" name="police_no" value="{{ $deliveryOrder->police_no }}" required>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="receipent">Penerima</label>
                        <input type="text" class="form-control" id="receipent" name="receipent" value="{{ $deliveryOrder->receipent }}" required>
                    </div>
                </div>
                <div class="col-9">
                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ $deliveryOrder->address }}" required>
                    </div>
                </div>
            </div>
            @can('delivery-note-edit')
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
            @endcan
        </div>
        
    </div>
</form>
<div class="card">
    <div class="card-body">
        <div id="itemsTableDiv" style="display: block;">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="float-left">Daftar Barang</h4>
                    @can('delivery-note-item-add')
                        <button type="button" class="btn btn-info float-right" data-toggle="modal" data-target="#mdlAddItem"><i class="fas fa-plus"></i> Tambah</button>
                    @endcan
                </div>
                <div class="col-md-12">
                    
                    <table id="itemsTable" class="table table-bordered table-hover table-striped">
                        <thead class="bg-primary text-white text-center">
                            <tr>
                                <th>SPM</th>
                                <th>Item</th>
                                <th>Qty Surat Jalan</th>
                                <th>SPM Qty</th>
                                <th>Qty Diterima</th>
                                <th>Sat</th>
                                <th>PO Number</th>
                                <th>PO Date</th>
                                <th>Vendor/CP</th>
                                @can('delivery-note-item-delete')
                                    <th></th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($deliveryOrderItems as $item)
                                <tr>
                                    <td>{{$item->materialRequestItem->materialRequest->mr_number}}</td>
                                    <td>[{{$item->materialRequestItem->item->code}}] {{$item->materialRequestItem->item->name}}</td>
                                    <td>{{$item->qty}}</td>
                                    <td>{{$item->materialRequestItem->qty}}</td>
                                    <td>{{$item->received_qty}}</td>
                                    <td>{{$item->materialRequestItem->item->uom->name}}</td>
                                    <td>{{$item->po_number}}</td>
                                    <td>{{$item->po_date}}</td>
                                    <td>{{$item->vendor}}</td>
                                    @can('delivery-note-item-delete')
                                        <td>
                                            @if ($item->received_qty == 0)
                                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ route('delivery.item.destroy', $item->id) }}', '');">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            @endif
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<x-adminlte-modal id="mdlAddItem" title="Tambah Barang" size="lg" theme="primary" icon="fas fa-bell" v-centered static-backdrop scrollable>
    <div style="height:800px;">
        @if($materialRequests->isEmpty())
            <x-adminlte-alert theme="info" title="Info">
                Belum ada SPM untuk gudang ini !
            </x-adminlte-alert>
        @else
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="material_request_id">SPM</label>
                        <x-adminlte-select2 id="material_request_id" name="material_request_id" onchange="loadItems()">
                            <option value="">-- Pilih SPM --</option>
                            @foreach ($materialRequests as $item)
                                <option value="{{$item->id}}">{{$item->mr_number}}</option>
                            @endforeach
                        </x-adminlte-select2>
                    </div>
                </div>
                <div class="col-md-12">
                    <form id="items-form" method="POST">
                        @csrf
                        <table id="items-table" class="table table-bordered table-hover table-striped">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>UOM</th>
                                    <th>Bal Qty</th>
                                    <th width="150px">Qty Surat Jalan</th>
                                    <th>PO Number</th>
                                    <th>PO Date</th>
                                    <th>Vendor/CP</th>
                                </tr>
                            </thead>
                            <tbody id="items-table-body">
                                <!-- Items will be inserted here dynamically -->
                            </tbody>
                        </table>
                    </form>

                    
                </div>
            </div>
        @endif
    </div>
    <x-slot name="footerSlot">
        @if(!$materialRequests->isEmpty())
            <x-adminlte-button class="mr-auto" theme="success" label="Tambah" onclick="submitCheckedRows()"/>
        @endif
        <x-adminlte-button theme="danger" label="Batal" data-dismiss="modal"/>
    </x-slot>
</x-adminlte-modal>

<!-- The Modal -->
{{-- <div class="modal" id="mdlAddItem">
    <div class="modal-dialog" style="min-width: 90%!important">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header bg-primary text-white">
            <h4 class="modal-title">Tambah Barang</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
  
        <!-- Modal body -->
        <div class="modal-body">
            <div style="height:700px;">
                
                
            </div>
        </div>
  
        <!-- Modal footer -->
        <div class="modal-footer">
            @if(!$materialRequests->isEmpty())
                <x-adminlte-button class="mr-auto" theme="success" label="Tambah" onclick="submitCheckedRows()"/>
            @endif
            <x-adminlte-button theme="danger" label="Batal" data-dismiss="modal"/>
        </div>
  
      </div>
    </div>
  </div>
  
 --}}
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

        $('#material_request_id').change(function() {
            loadItems();
        });

        $('#item_id').change(function() {
            setItemInfo(this);
        });
    });

    function loadMaterialRequests() {
        var warehouseId = document.getElementById('warehouse_id').value;
        
        if (warehouseId) {
            $.ajax({
                url: '{{ route("material.warehouse.list") }}',
                type: 'GET',
                data: { warehouse_id: warehouseId },
                success: function(data) {
                    var materialRequestSelect = document.getElementById('material_request_id');
                    materialRequestSelect.innerHTML = '<option value="">-- Pilih SPM --</option>';
                    data.forEach(function(item) {
                        materialRequestSelect.innerHTML += `<option value="${item.id}">${item.mr_number}</option>`;
                    });
                }
            });
        }
    }
    
    function loadItems() {
        var mr_id = document.getElementById('material_request_id').value;

        $.ajax({
            url: '{{ route("material.item.list") }}',
            type: 'GET',
            data: { mr_id: mr_id },
            success: function(data) {
                var tableBody = document.getElementById('items-table-body');
                tableBody.innerHTML = ''; // Clear any existing rows

                data.forEach(function(item) {
                    var row = `<tr>
                        <td>
                            <input type="checkbox" class="update-qty-checkbox" onchange="toggleQtyInput(this)">
                            <input type="hidden" name="material_request_item_id[]" value="${item.id}">
                            ${item.item.code}
                        </td>
                        <td>${item.item.name}</td>
                        <td>${item.item.uom.name}</td>
                        <td>${item.qty - item.do_qty}</td>
                        <td>
                            <input type="number" name="qty[]" class="form-control" disabled/>
                        </td>
                        <td>
                            <input type="text" name="po[]" class="form-control" disabled/>
                        </td>
                        <td>
                            <input type="date" name="po_date[]" class="form-control" disabled/>
                        </td>
                        <td>
                            <input type="text" name="vendor[]" class="form-control" disabled/>
                        </td>
                    </tr>`;
                    tableBody.innerHTML += row;
                });
            },
            error: function(error){
                console.log(error);
            }
        });
    }

    function toggleQtyInput(checkbox) {
        var qtyInput = checkbox.closest('tr').querySelector('input[name="qty[]"]');
        var poInput = checkbox.closest('tr').querySelector('input[name="po[]"]');
        var po_dateInput = checkbox.closest('tr').querySelector('input[name="po_date[]"]');
        var vendorInput = checkbox.closest('tr').querySelector('input[name="vendor[]"]');
        qtyInput.disabled = !checkbox.checked;
        poInput.disabled = !checkbox.checked;
        vendorInput.disabled = !checkbox.checked;
        po_dateInput.disabled = !checkbox.checked;
    }

function submitCheckedRows() {
    var checkedRows = document.querySelectorAll('.update-qty-checkbox:checked');
    var formData = new FormData();
    var valid = true;

    // Loop through each checked row and append the necessary data to formData
    checkedRows.forEach(function(checkbox) {
        var row = checkbox.closest('tr');
        var materialRequestItemId = row.querySelector('input[name="material_request_item_id[]"]').value;
        var qtyInput = row.querySelector('input[name="qty[]"]');
        var poInput = row.querySelector('input[name="po[]"]');
        var po_dateInput = row.querySelector('input[name="po_date[]"]');
        var vendorInput = row.querySelector('input[name="vendor[]"]');

        if (qtyInput.value === '' || qtyInput.value <= 0) {
            valid = false;
            qtyInput.classList.add('is-invalid'); // Optional: add a class to highlight the error
        } else {
            qtyInput.classList.remove('is-invalid'); // Remove the error class if valid
            formData.append('material_request_item_id[]', materialRequestItemId);
            formData.append('qty[]', qtyInput.value);
            formData.append('po[]', poInput.value);
            formData.append('po_date[]', po_dateInput.value);
            formData.append('vendor[]', vendorInput.value);
        }
    });

    if (valid && checkedRows.length > 0) {
        var storeUrl = '{{ route("delivery.items.store", ["id" => $deliveryOrder->id]) }}';
        // If the data is valid, send the AJAX request to store the data
        $.ajax({
            url: storeUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token for Laravel
            },
            success: function(response) {
                alert('Barang berhasil ditambahkan!');
                location.reload(); // Optionally reload the page or reset the form
            },
            error: function(error) {
                console.log(error);
                alert('Error, silahkan coba lagi.');
            }
        });
    } else {
        alert('Please ensure all checked rows have a valid quantity.');
    }
}




    function setItemInfo(obj){
        var selectedOption = obj.options[obj.selectedIndex];
        var uom = selectedOption.getAttribute('data-uom');
        var qty = selectedOption.getAttribute('data-qty');
        var received = selectedOption.getAttribute('data-received');
        var date_needed = selectedOption.getAttribute('data-date');
        $('#spm_qty').val(qty)
        $('#spm_uom').val(uom)
        $('#spm_received').val(received)
        $('#date_needed').val(date_needed)
    }

    function addItem() {
        var materialRequestId = $('#material_request_id').val();
        var itemId = $('#item_id').val();
        var qty = $('#txtqty').val();

        if (materialRequestId && itemId && qty) {
            $('#itemsTableDiv').show();
            var row = '<tr>';
            row += '<td>' + materialRequestId + '<input type="hidden" name="material_request_ids[]" value="' + materialRequestId + '"></td>';
            row += '<td>' + itemId + '<input type="hidden" name="material_request_item_id[]" value="' + itemId + '"></td>';
            row += '<td class="text-right">' + qty + '<input type="hidden" name="qty[]" value="' + qty + '"></td>';
            row += '<td class="text-right">' + $('#spm_qty').val() + '</td>';
            row += '<td>' + $('#spm_uom').val() + '</td>';
            row += '<td class="text-right"><button type="button" class="btn btn-danger delete-row" onclick="removeItem(this)"><i class="fas fa-trash"></i></button></td>';
            row += '</tr>';
            $('#itemsTable tbody').append(row);
            $('#txtqty').val('');
        }
    }

    function removeItem(button) {
        $(button).closest('tr').remove();
        if ($('#itemsTable tbody tr').length === 0) {
            $('#itemsTableDiv').hide();
        }
    }
</script>
@stop
