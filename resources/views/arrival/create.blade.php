@extends('adminlte::page')

@section('title', 'Penerimaan Barang')

@section('content_header')
<h1>Penerimaan Barang</h1>
@stop

@section('content')
<div>
    <form action="{{ route('arrival.store') }}" method="POST">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="warehouse_id">Gudang</label>
                            <x-adminlte-select2 id="warehouse_id" name="warehouse_id" onchange="fetchDeliveryOrderNumbers()">
                                @foreach ($warehouses as $value => $label)
                                    <option value="{{ $value }}">
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </x-adminlte-select2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="do_number">Pilih Nomor Surat Jalan</label>
                            <select class="form-control delivery-order-number" id="delivery_order_number" required></select>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <div class="form-group">
                            <label for="do_number">&nbsp;</label><br/>
                            <button type="button" id="searchButton" class="btn btn-info">Cari</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4" id="itemsContainer"></div>
    </form>
</div>
@stop

@section('css')

@stop

@section('js')
<script>
    const deliveryOrderBaseUrl = '{{ route("delivery.warehouse", ":warehouseId") }}';
    fetchDeliveryOrderNumbers();
    function fetchDeliveryOrderNumbers() {
        const warehouseId = $('#warehouse_id').val();
        const $deliveryOrderNumberSelect = $('#delivery_order_number');

        // Clear current options and add a loading option
        $deliveryOrderNumberSelect.html('<option value="">Loading...</option>');
        const url = deliveryOrderBaseUrl.replace(':warehouseId', warehouseId);

        // Make AJAX request to fetch delivery order numbers
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                // Clear the loading option
                $deliveryOrderNumberSelect.empty();

                // Populate the dropdown with new options
                $.each(data, function (index, order) {
                    $deliveryOrderNumberSelect.append(
                        $('<option>', { value: order.do_number, text: order.do_number })
                    );
                });
            },
            error: function (xhr, status, error) {
                console.error('Error fetching delivery order numbers:', error);
                console.log(xhr, status, error)
                $deliveryOrderNumberSelect.html('<option value="">Error loading data</option>');
            }
        });
    }

    $(document).ready(function() {
        disableEnter()
        $('#searchButton').click(function() {
            let doNumber = $('#delivery_order_number').val();

            $.ajax({
                url: "{{ route('getDeliveryItems') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    do_number: doNumber
                },
                success: function(response) {
                    $('#itemsContainer').html(response);
                    disableEnter()
                },
                error: function(xhr) {
                console.log(xhr)
                    $('#itemsContainer').html('<div class="alert alert-danger">Something went wrong. Please try again.</div>');
                }
            });
        });
    });
    function checkQty(obj){
        const maxQty = parseFloat(obj.getAttribute('data-max'));
        const currentQty = parseFloat(obj.value);

        if (currentQty > maxQty) {
            alert('Qty tidak boleh lebih besar dari Qty Surat Jalan: ' + maxQty);
            obj.value = maxQty;
        }
    }
    function disableEnter(){
        $('input').on('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });
    }
    
</script>
@include('layouts.errors.swal-alert')
@stop
