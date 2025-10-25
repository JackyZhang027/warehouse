@extends('adminlte::page')

@section('title', 'Mutasi')

@section('content_header')
<h1>Buat Mutasi Barang</h1>
@stop

@section('content')
<form action="{{ route('stock_movements.store') }}" method="POST">
    @csrf
    <div class="form-row">
        <div class="form-group col-md-3">
            <label>Tanggal</label>
            <input type="date" name="date" class="form-control" required>
        </div>
        <div class="form-group col-md-3">
            <label>Tipe</label>
            <select name="movement_type" class="form-control">
                <option value="in">Masuk</option>
                <option value="out">Keluar</option>
                <option value="transfer">Transfer</option>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label>Gudang Asal</label>
            <select id="warehouse_from" class="form-control" required>
                <option value=""></option>
                @foreach($warehouses as $w)
                    <option value="{{ $w->id }}">{{ $w->project }} - {{ $w->spk_number }} [{{ $w->code }}]</option>
                @endforeach
            </select>
            <input type="hidden" name="warehouse_from_id" id="warehouse_from_id">
        </div>
        <div class="form-group col-md-3">
            <label>Gudang Tujuan</label>
            <select name="warehouse_to_id" class="form-control" required>
                <option value="">-</option>
                @foreach($warehouses as $w)
                    <option value="{{ $w->id }}">{{ $w->project }} - {{ $w->spk_number }} [{{ $w->code }}]</option>
                @endforeach
            </select>
        </div>
    </div>

    <h4 class="mt-4">Barang</h4>
    <div class="d-flex flex-wrap w-100 mb-3">
        <div class="card p-2 mr-3 mb-2" style="flex: 1 1 250px;">
            <h6 class="mb-2 font-weight-bold">Informasi Mutasi</h6>
            <ul class="mb-0 pl-3">
                <li>Pilih gudang asal terlebih dahulu</li>
                <li>Jika ada barang yang dipilih, gudang asal tidak dapat diubah</li>
                <li>Qty barang harus lebih dari 0</li>
                <li>Qty barang harus lebih kecil atau sama dengan stok di gudang asal</li>
            </ul>
        </div>

        <div class="card p-2 mr-3 mb-2" style="flex: 1 1 250px;">
            <h6 class="mb-2 font-weight-bold">Surat Jalan</h6>
            <ul class="mb-0 pl-3">
                <li>Surat jalan dapat dibuat setelah menyimpan mutasi</li>
                <li>Penerimaan barang dapat dilakukan setelah membuat surat jalan melalui tombol <strong>"Buat Surat Jalan"</strong></li>
            </ul>
        </div>

        <div class="card p-2 mb-2" style="flex: 1 1 250px;">
            <h6 class="mb-2 font-weight-bold">Stock Update</h6>
            <ul class="mb-0 pl-3">
                <li>Stock barang akan berkurang di gudang asal setelah membuat surat jalan</li>
                <li>Stock barang akan bertambah di gudang tujuan setelah penerimaan barang</li>
            </ul>
        </div>
    </div>
    <table class="table table-bordered" id="lines-table">
        <thead class="thead-light">
            <tr>
                <th>Barang</th>
                <th>Qty</th>
                <th>Keterangan</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr class="line-item">
                <td>
                    <select name="lines[0][item_id]" class="form-control form-control-sm item-select" required>
                        <option value="">Select Warehouse First</option>
                    </select>
                </td>
                <td>
                    <input type="number" step="1" name="lines[0][qty]" class="form-control" required>
                </td>
                <td>
                    <input type="text" name="lines[0][remarks]" class="form-control">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm delete-line">Delete</button>
                </td>
            </tr>
        </tbody>
    </table>

    <button type="button" class="btn btn-secondary mb-3" id="addLineBtn">Add Line</button>
    <button type="submit" class="btn btn-primary mb-3">Save</button>
</form>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    function initSelect2() {
        $('.item-select').select2({
            placeholder: 'Select Item',
            width: '100%'
        });
    }
    $('form').on('submit', function() {
        $('#warehouse_from_id').val($('#warehouse_from').val());
    });

    initSelect2();
    let lineIndex = 1;

    function updateItemDropdowns() {
        const warehouseId = $('#warehouse_from').val();
        $('.item-select').each(function() {
            const select = $(this);
            const selectedVal = select.val(); // preserve selection if any
            select.empty().append('<option value="">Loading...</option>');
            if (warehouseId) {
                $.ajax({
                    url: "{{ route('warehouse.items.search') }}",
                    data: { keyword: '', warehouse_id: warehouseId },
                    method: 'GET',
                    success: function(data) {
                        select.empty().append('<option value="">Select Item</option>');
                        data.forEach(item => {
                            select.append(`<option value="${item.item.id}">[${item.item.code}] ${item.item.name} - ${item.item.uom.name}</option>`);
                        });
                        // Restore previous selection if still valid
                        if(selectedVal) select.val(selectedVal).trigger('change');
                    }
                });
            } else {
                select.empty().append('<option value="">Select Warehouse First</option>');
            }
        });
    }

    // Add new line
    $('#addLineBtn').click(function() {
        const newRow = $(`
            <tr class="line-item">
                <td>
                    <select name="lines[${lineIndex}][item_id]" class="form-control form-control-sm item-select" required>
                        <option value="">Select Warehouse First</option>
                    </select>
                </td>
                <td>
                    <input type="number" step="1" name="lines[${lineIndex}][qty]" class="form-control" required>
                </td>
                <td>
                    <input type="text" name="lines[${lineIndex}][remarks]" class="form-control">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm delete-line">Delete</button>
                </td>
            </tr>
        `);
        $('#lines-table tbody').append(newRow);
        initSelect2();
        updateItemDropdowns(); // fetch items for new row
        lineIndex++;
    });

    // Delete line
    $('#lines-table').on('click', '.delete-line', function() {
        $(this).closest('tr').remove();
        checkWarehouseLock();
    });

    // Warehouse change
    $('#warehouse_from').change(function() {
        updateItemDropdowns();
    });

    // Lock warehouse_from if at least one item is selected
    function checkWarehouseLock() {
        const anySelected = $('.item-select').filter(function() {
            return $(this).val() !== null && $(this).val() !== '';
        }).length > 0;

        if (anySelected) {
            $('#warehouse_from').prop('disabled', true);
        } else {
            $('#warehouse_from').prop('disabled', false);
        }
    }

    // Monitor changes on item selects
    $('#lines-table').on('change', '.item-select', function() {
        checkWarehouseLock();
    });

});
</script>
@endsection
