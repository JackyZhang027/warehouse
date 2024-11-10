@extends('adminlte::page')

@section('title', 'Surat Jalan - Baru')

@section('content_header')
<h1>Surat Jalan - Baru</h1>
@stop

@section('content')
<form action="{{ route('delivery.store') }}" method="POST">
    @csrf
    <!-- Step 1 -->
    <div class="card">
        <div class="card-body">
            
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="do_number">No. Surat Jalan</label>
                        <input type="text" class="form-control" id="do_number" name="do_number" value="Nomor Otomatis" disabled>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="date">Tanggal SJ</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="warehouse_id">Gudang</label>
                        <x-adminlte-select2 id="warehouse_id" name="warehouse_id" required>
                            @foreach ($warehouses as $value => $label)
                                <option value="{{ $value }}">
                                    {{ $label }}
                                </option>
                            @endforeach
                        </x-adminlte-select2>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="police_no">Nomor Polisi</label>
                        <input type="text" class="form-control" id="police_no" name="police_no" required>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="receipent">Penerima</label>
                        <input type="text" class="form-control" id="receipent" name="receipent" required>
                    </div>
                </div>
                <div class="col-9">
                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>

            <!-- 
            <div class="step">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Tambah Barang</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="material_request_id">SPM</label>
                            <x-adminlte-select2 id="material_request_id" name="material_request_id" onchange="loadItems()">
                                <option value="">-- Pilih SPM --</option>
                            </x-adminlte-select2>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="item_id">Item</label>
                            <x-adminlte-select2 id="item_id" name="item_id" onchange="setItemInfo(this)">
                                <option value="">-- Pilih Barang --</option>
                            </x-adminlte-select2>
                        </div>
                    </div>
        
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="spm_qty">Qty Surat Jalan</label>
                            <input type="text" id="txtqty" name="txtqty" class="form-control" value=""/>
                        </div>
                    </div>
        
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="spm_qty">SPM Qty</label>
                            <input type="text" id="spm_qty" name="spm_qty" class="form-control" value="" disabled/>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="spm_qty">SPM Received</label>
                            <input type="text" id="spm_received" name="spm_received" class="form-control" value="" disabled/>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="spm_qty">Satuan</label>
                            <input type="text" id="spm_uom" name="spm_uom" class="form-control" value="" disabled/>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="date_needed">Tanggal Dibutuhkan</label>
                            <input type="text" id="date_needed" name="date_needed" class="form-control" value="" disabled/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">&nbsp;</label><br/>
                            <button type="button" class="btn btn-info btn-block" onclick="addItem()">Tambah</button>
                        </div>
                    </div>
                </div>
                <!--
                <div id="itemsTableDiv" style="display: none;">
                    <div class="row">
                        <div class="col-md-12">
                            <h4>Daftar Barang</h4>
                            <table id="itemsTable" class="table table-bordered table-hover table-striped">
                                <thead class="bg-primary text-white text-center">
                                    <tr>
                                        <th>SPM</th>
                                        <th>Item</th>
                                        <th>Qty Surat Jalan</th>
                                        <th>SPM Qty</th>
                                        <th>Satuan</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            
            </div>
            Step 2 -->
        </div>
    </div>
    <div id="itemDiv" style="display: none">
        
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
        $('#warehouseForm input').on('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });

    });
    function createDelivery(){
        var date = $('#date').val()
        var warehouse_id = $('#warehouse_id').val()
        if(date && warehouse_id){
            $('#warehouse_id').prop('disabled', true);
            $('#date').prop('readonly', true);
            $('#itemDiv').show();
            loadMaterialRequests();
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "Harap masukan tanggal dan gudang !",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
            });
        }
    }

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
                var itemSelect = document.getElementById('item_id');
                itemSelect.innerHTML = '<option value="">-- Pilih Barang --</option>';
                data.forEach(function(item) {
                    itemSelect.innerHTML += `<option value="${item.id}" data-uom="${item.item.uom.name}" 
                        data-qty="${item.qty}" data-received="${item.received_qty}" data-date="${item.date_needed}">
                            ${item.item.code} - ${item.item.name}
                    </option>`;
                });
            },
            error: function(error){
                console.log(error)
            }
        });
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
        // Get values from the form inputs
        var materialRequestId = document.getElementById("material_request_id").value;
        var materialRequestText = document.getElementById("material_request_id").options[document.getElementById("material_request_id").selectedIndex].text;
        var itemId = document.getElementById("item_id").value;
        var itemText = document.getElementById("item_id").options[document.getElementById("item_id").selectedIndex].text;
        var qty = document.getElementById("txtqty").value;
        var spmQty = document.getElementById("spm_qty").value;
        var spmUom = document.getElementById("spm_uom").value;
        console.log(materialRequestId, itemId, qty)
        // Check if the required fields are filled
        if (!materialRequestId || !itemId || !qty) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "Harap pilih barang dan masukan qty !",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
            });
            return;
        }

        // Validate that qty is not greater than spm_qty
        if (parseFloat(qty) > parseFloat(spmQty)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "Qty Surat Jalan tidak boleh lebih besar daripada Qty SPM !",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
            });
            return;
        }
        // Add the item to the table
        var table = document.getElementById("itemsTable").getElementsByTagName('tbody')[0];
        // Check if the selected item already exists in the table
        for (var i = 0; i < table.rows.length; i++) {
            var existingMaterialRequestId = table.rows[i].cells[0].innerText;
            var existingItemId = table.rows[i].cells[1].innerText;

            if (materialRequestText === existingMaterialRequestId && itemText === existingItemId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "Barang telah ada pada list !",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                });
                return;
            }
        }
        var newRow = table.insertRow();

        newRow.innerHTML = `
            <tr>
                <td><input type="hidden" name="material_request_ids[]" value="${materialRequestId}">${materialRequestText}</td>
                <td><input type="hidden" name="material_request_item_id[]" value="${itemId}">${itemText}</td>
                <td class="text-right"><input type="hidden" name="qty[]" value="${qty}">${qty}</td>
                <td class="text-right">${spmQty}</td>
                <td>${spmUom}</td>
                <td class="text-right"><button type="button" class="btn btn-danger" onclick="removeItem(this)"><i class="fas fa-trash"></i></button></td>
            </tr>
        `;

        // Clear the input fields after adding the item
        document.getElementById("item_id").value = '';
        document.getElementById("txtqty").value = '';
        document.getElementById("spm_qty").value = '';
        document.getElementById("spm_received").value = '';
        document.getElementById("spm_uom").value = '';
        document.getElementById("date_needed").value = '';

        // Show the items table if it's hidden
        document.getElementById("itemsTableDiv").style.display = "block";
        loadItems();
    }

    // Function to remove an item from the table
    function removeItem(button) {
        // Remove the corresponding row
        var row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);
        
        // Hide the table if there are no items left
        var table = document.getElementById("itemsTable").getElementsByTagName('tbody')[0];
        if (table.rows.length === 0) {
            document.getElementById("itemsTableDiv").style.display = "none";
        }
    }
    $('form').on('submit', function() {
        $('#warehouse_id').prop('disabled', false); // Re-enable the dropdown
    });


</script>
@include('layouts.errors.swal-alert')
@stop
