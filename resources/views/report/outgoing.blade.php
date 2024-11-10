<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Keluar</title>
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
        <tr class="parent"><td colspan="8" style="font-size:24px; font-weight:bold">LAPORAN MATERIAL &amp; ALAT MINGGU INI KELUAR</td></tr>
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
            <td style="width: 30px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">No.</td>
            <td style="width:120px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">Tanggal</td>
            <td colspan="2" style="border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">Nama Material</td>
            <td style="width: 87px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">Vol</td>
            <td style="width: 87px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">Sat</td>
            <td colspan="2" style="border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">Keterangan</td>
        </tr>
        @foreach ($datas as $index => $item)
            @php
                $itemData = $item->item ?? null;
                $uom = $itemData->uom->name ?? '-';
            @endphp
            <tr class="item">
                <td style="border: 1px solid black; text-align:center;">{{$index + 1}}</td>
                <td style="border: 1px solid black; text-align:center;">{{$item->itemOut->date}}</td>
                <td colspan="2" style="border: 1px solid black;">
                    [{{$itemData->code ?? 'N/A'}}] {{$itemData->name ?? 'N/A'}}
                </td>
                <td style="border: 1px solid black; text-align:center; font-weight:bold;">
                    {{$item->qty}}
                </td>
                <td style="border: 1px solid black; text-align:center; font-weight:bold;">
                    {{$uom}}
                </td>
                <td colspan="2" style="border: 1px solid black; text-align:center; font-weight:bold;">{{$item->remark ?? ''}}</td>
            </tr>
        @endforeach

        
        <tr><td colspan="100%" style="height: 30px"></td></tr>
        <tr class="item">
            <td colspan="2" style="border: 1px solid black; text-align:left; white-space: nowrap">Dibuat Oleh</td>
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap">Diperiksa Oleh</td>
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap">Diketahui Oleh</td>
            <td style="border: 1px solid black; text-align:center; white-space: nowrap; width:135px">Diperiksa Oleh</td>
            <td style="border: 1px solid black; text-align:center; white-space: nowrap; width:135px">Diketahui Oleh</td>
        </tr>
        
        <tr class="item">
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap; height: 80px;"></td>
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap; height: 80px;"></td>
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap; height: 80px;"></td>
            <td style="border: 1px solid black; text-align:center; white-space: nowrap"></td>
            <td style="border: 1px solid black; text-align:center; white-space: nowrap"></td>
        </tr>
        <tr class="item">
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap; font-weight: bold">{{$warehouse->logistic}}</td>
            <td style="width: 150px; border: 1px solid black; text-align:center; white-space: nowrap; font-weight: bold">{{$warehouse->supervisor}}</td>
            <td style="width: 150px; border: 1px solid black; text-align:center; white-space: nowrap; font-weight: bold">{{$warehouse->site_manager}}</td>
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap; font-weight: bold">{{$warehouse->project_manager}}</td>
            <td style="border: 1px solid black; text-align:center; white-space: nowrap; font-weight: bold">{{$warehouse->head_logistic}}</td>
            <td style="border: 1px solid black; text-align:center; white-space: nowrap; font-weight: bold">{{$warehouse->branch_manager}}</td>
        </tr>    
        <tr class="item">
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap; font-weight: bold">LOGISTIK</td>
            <td style="border: 1px solid black; text-align:center; white-space: nowrap; font-weight: bold">SUPERVISOR</td>
            <td style="border: 1px solid black; text-align:center; white-space: nowrap; font-weight: bold">SITE MANAGER</td>
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap; font-weight: bold">PROJECT MANAGER</td>
            <td style="border: 1px solid black; text-align:center; white-space: nowrap; font-weight: bold">HEAD LOGISTIK</td>
            <td style="border: 1px solid black; text-align:center; white-space: nowrap; font-weight: bold">BRANCH MANAGER</td>
        </tr>       
    </table>
</body>
</html>
