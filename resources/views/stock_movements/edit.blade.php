@extends('adminlte::page')

@section('title', 'Edit Mutasi')

@section('content_header')
<h1>Edit Mutasi Barang</h1>
@stop

@section('content')
<form action="{{ route('stock_movements.update', $stockMovement->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-row">
        <div class="form-group col-md-3">
            <label>Tanggal</label>
            <input type="date" name="date" class="form-control" value="{{ $stockMovement->date }}" required>
        </div>
        <div class="form-group col-md-3">
            <label>Tipe</label>
            <select name="movement_type" class="form-control" required>
                <option value="in" {{ $stockMovement->movement_type == 'in' ? 'selected' : '' }}>Masuk</option>
                <option value="out" {{ $stockMovement->movement_type == 'out' ? 'selected' : '' }}>Keluar</option>
                <option value="transfer" {{ $stockMovement->movement_type == 'transfer' ? 'selected' : '' }}>Transfer</option>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label>Gudang Asal</label>
            <select id="warehouse_from" class="form-control" required>
                <option value=""></option>
                @foreach($warehouses as $w)
                    <option value="{{ $w->id }}" {{ $stockMovement->warehouse_from_id == $w->id ? 'selected' : '' }}>
                        {{ $w->project }} - {{ $w->spk_number }} [{{ $w->code }}]
                    </option>
                @endforeach
            </select>
            <input type="hidden" name="warehouse_from_id" id="warehouse_from_id" value="{{ $stockMovement->warehouse_from_id }}">
        </div>
        <div class="form-group col-md-3">
            <label>Gudang Tujuan</label>
            <select name="warehouse_to_id" class="form-control" required>
                <option value="">-</option>
                @foreach($warehouses as $w)
                    <option value="{{ $w->id }}" {{ $stockMovement->warehouse_to_id == $w->id ? 'selected' : '' }}>
                        {{ $w->project }} - {{ $w->spk_number }} [{{ $w->code }}]
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <h4 class="mt-4">Barang</h4>

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
            @foreach($stockMovement->lines as $i => $line)
            <tr class="line-item">
                <td>
                    <select name="lines[{{ $i }}][item_id]" class="form-control form-control-sm item-select" required>
                        <option value="{{ $line->item->id }}" selected>[{{ $line->item->code }}] {{ $line->item->name }} - {{ $line->item->uom->name }}</option>
                    </select>
                </td>
                <td>
                    <input type="number" step="1" name="lines[{{ $i }}][qty]" class="form-control" value="{{ $line->qty }}" required>
                </td>
                <td>
                    <input type="text" name="lines[{{ $i }}][remarks]" class="form-control" value="{{ $line->remarks }}">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm delete-line">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <button type="button" class="btn btn-secondary mb-3" id="addLineBtn">Add Line</button>
    <button type="submit" class="btn btn-primary mb-3">Update</button>
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
    let lineIndex = {{ $stockMovement->lines->count() }};

    function updateItemDropdowns() {
        const warehouseId = $('#warehouse_from').val();
        $('.item-select').each(function() {
            const select = $(this);
            const selectedVal = select.val();
            if (warehouseId) {
                $.ajax({
                    url: "{{ route('warehouse.items.search') }}",
                    data: { keyword: '', warehouse_id: warehouseId },
                    method: 'GET',
                    success: function(data) {
                        const oldVal = select.val();
                        select.empty().append('<option value="">Select Item</option>');
                        data.forEach(item => {
                            select.append(`<option value="${item.item.id}">[${item.item.code}] ${item.item.name} - ${item.item.uom.name}</option>`);
                        });
                        if (oldVal) select.val(oldVal).trigger('change');
                    }
                });
            }
        });
    }

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
        updateItemDropdowns();
        lineIndex++;
    });

    $('#lines-table').on('click', '.delete-line', function() {
        $(this).closest('tr').remove();
        checkWarehouseLock();
    });

    $('#warehouse_from').change(function() {
        updateItemDropdowns();
    });

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

    $('#lines-table').on('change', '.item-select', function() {
        checkWarehouseLock();
    });

    // Lock if existing items already present
    checkWarehouseLock();
});
</script>
@endsection
