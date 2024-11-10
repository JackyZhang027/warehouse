<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan SPM</title>
    <style>
        table{
            border-collapse:collapse;
            border: 1px solid black;
        }
        .item td {
          border: 1px solid black;
          border-collapse: collapse;
          padding-left: 5px; padding-right: 5px;
        }
        .parent td{
            padding-left: 15px;
        }

    </style>
</head>
<body>
    <table style="width: 100%">
        <tr class="parent"><td colspan="8" style="font-size:24px; font-weight:bold">LAPORAN BERDASARKAN SPM</td></tr>
        <tr><td colspan="100%" style="height: 20px"></td></tr>
        <tr class="parent">
            <td colspan="2" style="white-space: nowrap">Owner</td>
            <td colspan="6">: {{$warehouse->owner}}</td>
        </tr>
        <tr class="parent">
            <td colspan="2" style="white-space: nowrap">Proyek</td>
            <td colspan="6">: {{$warehouse->project}}</td>
        </tr>
        <tr class="parent">
            <td colspan="2" style="white-space: nowrap">No. SPK</td>
            <td colspan="6">: {{$warehouse->spk_number}}</td>
        </tr>
        <tr class="parent">
            <td colspan="2" style="white-space: nowrap">Lokasi</td>
            <td colspan="6">: {{$warehouse->location}}</td>
        </tr>
        <tr class="parent">
            <td colspan="2" style="white-space: nowrap">Periode</td>
            <td colspan="6">: {{$period}}</td>
        </tr>
        <tr><td colspan="100%" style="height: 20px"></td></tr>
        <tr class="item header" style="background-color: #dddddd; text-align: center;">
            <td style="width: 30px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">NO.</td>
            <td style="width: 100px;border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">SPM Date</td>
            <td style="width: 150px;border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">SPM Number</td>
            <td style="width: 350px;border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">Material Name</td>
            <td style="width: 87px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">UOM</td>
            <td style="width: 120px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">SPM QTY</td>
            <td style="width: 120px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">RCVD QTY</td>
            <td style="width: 150px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">Status</td>
            <td style="width: 150px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">PO Number</td>
            <td style="width: 150px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">Date of PO</td>
            <td style="width: 150px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">Vendor Name / Contact Person</td>
            <td style="width: 120px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">Onsite QTY</td>
            <td style="width: 120px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">Onsite Arrived Date</td>
        </tr>
        @foreach ($datas as $index => $item)
            <tr class="item">
                <td style="border: 1px solid black; text-align:center;">{{$index + 1}}</td>
                <td style="border: 1px solid black; text-align:center;">{{$item->date}}</td>
                <td style="border: 1px solid black; text-align:center;">{{$item->mr_number}}</td>
                <td style="border: 1px solid black;">
                    [{{$item->code ?? 'N/A'}}] {{$item->name ?? 'N/A'}}
                </td>
                <td style="border: 1px solid black; text-align:center; font-weight:bold;">
                    {{$item->uom}}
                </td>
                <td style="border: 1px solid black; text-align:center; font-weight:bold;">
                    {{$item->spm_qty}}
                </td>
                <td style="border: 1px solid black; text-align:center; font-weight:bold;">
                    {{$item->arrived_qty}}
                </td>
                <td style="border: 1px solid black; text-align:center; font-weight:bold;">{{$item->status}}</td>
                <td style="border: 1px solid black; text-align:center; font-weight:bold;">{{$item->po_numbers}}</td>
                <td style="border: 1px solid black; text-align:center; font-weight:bold;">{{$item->po_dates}}</td>
                <td style="border: 1px solid black; text-align:center; font-weight:bold;">{{$item->vendors}}</td>
                <td style="border: 1px solid black; text-align:center; font-weight:bold;">{{$item->arrived_qty}}</td>
                <td style="border: 1px solid black; text-align:center; font-weight:bold;">{{$item->arrival_dates}}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>
