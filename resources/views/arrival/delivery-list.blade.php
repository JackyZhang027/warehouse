@if($items->isEmpty())
    <div class="alert alert-warning">
        Tidak ada barang ditemukan pada surat jalan ini!
    </div>
@else

<div class="card" id="item_list">
    <div class="card-body">
        <form action="{{ route('arrival.store') }}" method="POST">
            @csrf
            <table class="table table-bordered table-striped table-hover">
                <thead class="bg-info text-white text-center">
                    <tr>
                        <th>Item Name</th>
                        <th>Qty SPM</th>
                        <th>Qty Surat Jalan</th>
                        <th>Sisa Qty</th>
                        <th>Nomor PO</th>
                        <th style="width: 150px">Qty diterima</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>
                                <strong>[{{ $item->materialRequestItem->item->code }}] </strong>
                                {{ $item->materialRequestItem->item->name }}
                            </td>
                            <td class="text-center">{{ $item->materialRequestItem->qty }}</td>
                            <td class="text-center">{{ $item->qty }}</td>
                            <td class="text-center">{{ $item->balance }}</td>
                            <td class="text-center">{{ $item->po_number }}</td>
                            <td>
                                <input type="number" name="items[{{ $item->id }}][received_qty]" class="form-control text-center" 
                                    placeholder="0" data-max="{{$item->balance}}" value="{{ $item->balance }}" onchange="checkQty(this)" required/>
                            </td>
                            <td>
                                <input type="text" name="items[{{ $item->id }}][remark]" class="form-control text-left" 
                                    placeholder="Informasi Tambahan"/>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
    </div>
</div>
@endif