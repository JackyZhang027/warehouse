<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan</title>
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
        <tr class="parent"><td colspan="12" style="font-size:24px; font-weight:bold">Surat Jalan</td></tr>
        <tr><td colspan="100%" style="height: 20px"></td></tr>
        <tr class="parent">
            <td colspan="2" style="white-space: nowrap">No SJ</td>
            <td colspan="4">: {{$sj->do_number}}</td>
            <td colspan="2">No Polisi</td>
            <td colspan="4">: {{$sj->police_no}}</td>
        </tr>
        <tr class="parent">
            <td colspan="2" style="white-space: nowrap">Tanggal</td>
            <td colspan="4">: {{$sj->date}}</td>
            <td colspan="2">Penerima</td>
            <td colspan="4">: {{$sj->receipent}}</td>
        </tr>
        <tr class="parent">
            <td colspan="2" style="white-space: nowrap">Proyek</td>
            <td colspan="4">: {{$sj->warehouse->spk_number}}</td>
            <td colspan="2">Alamat</td>
            <td colspan="4">: {{$sj->address}}</td>
        </tr>
        <tr><td colspan="100%" style="height: 20px"></td></tr>
        <tr class="item header" style="background-color: #dddddd; text-align: center;">
            <td style="border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">NO</td>
            <td colspan="5" style="width: 430px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">Nama Barang</td>
            <td style="width: 87px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">VOL</td>
            <td style="width: 87px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">SAT</td>
            <td colspan="4" style="border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">KETERANGAN</td>
        </tr>

        @foreach ($sj->DeliveryItems as $index => $item)
            <tr class="item">
                <td style="border: 1px solid black; text-align:center">{{ $index + 1 }}</td>
                <td colspan="5" style="border: 1px solid black"><span style="font-weight: bold">[{{$item->materialRequestItem->item->code}}]</span> {{$item->materialRequestItem->item->name}}</td>
                <td style="border: 1px solid black">{{$item->qty}}</td>
                <td style="border: 1px solid black">{{$item->materialRequestItem->item->uom->name}}</td>
                <td colspan="4" style="border: 1px solid black">{{$item->materialRequestItem->materialRequest->mr_number}}</td>
            </tr>
        @endforeach
        <tr><td colspan="100%" style="height: 20px"></td></tr>
        <tr class="item">
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap">Dikeluarkan Oleh</td>
            <td style="border: 1px solid black; text-align:center; white-space: nowrap">Diketahui Oleh</td>
            <td style="border: 1px solid black; text-align:center; white-space: nowrap">Dibawa Oleh</td>
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap">Diterima Oleh</td>
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap">Diperiksa Oleh</td>
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap">Diketahui Oleh</td>
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap">Security/Owner</td>
        </tr>
        
        <tr class="item">
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap; height: 80px;"></td>
            <td style="border: 1px solid black; text-align:center; white-space: nowrap"></td>
            <td style="border: 1px solid black; text-align:center; white-space: nowrap"></td>
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap"></td>
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap"></td>
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap"></td>
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap"></td>
        </tr>
        <tr class="item">
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap; height: 30px;"></td>
            <td style="border: 1px solid black; text-align:center; white-space: nowrap"></td>
            <td style="border: 1px solid black; text-align:center; white-space: nowrap"></td>
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap"></td>
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap"></td>
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap"></td>
            <td colspan="2" style="border: 1px solid black; text-align:center; white-space: nowrap"></td>
        </tr>
        <!--
        <tr class="parent">
            <td style="white-space: nowrap">Project</td>
            <td colspan="16">: {{$sj->warehouse->project}}</td>
        </tr>
        <tr class="parent">
            <td style="white-space: nowrap">Lokasi</td>
            <td colspan="16">: {{$sj->warehouse->location}}</td>
        </tr>
        <tr class="parent">
            <td style="white-space: nowrap">Kode Proy</td>
            <td colspan="16">: {{$sj->warehouse->spk_number}}</td>
        </tr>
        <tr class="parent">
            <td style="white-space: nowrap">No. sj</td>
            <td colspan="16">: {{$sj->mr_number}}</td>
        </tr>
        <tr class="parent">
            <td style="white-space: nowrap">Tanggal</td>
            <td colspan="16">: {{$sj->date}}</td>
        </tr>
        <tr><td colspan="100%" style="height: 40px"></td></tr>
        <tr class="item header" style="background-color: #dddddd; text-align: center;">
            <td style="border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">NO</td>
            <td style="width: 230px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">URAIAN</td>
            <td style="width: 87px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">VOL</td>
            <td style="width: 87px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">SATUAN</td>
            <td style="width: 260px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">SPEC/TYPE/MERK</td>
            <td style="width: 100px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">KODE BOQ</td>
            <td style="width: 120px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">TANGGAL<br/>DIBUTUHKAN</td>
            <td colspan="5" style="border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">KEBUTUHAN UNTUK</td>
            <td colspan="5" style="border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">KETERANGAN</td>
        </tr>
        <tr class="item header" style="background-color: #dddddd; text-align: center;; font-weight:bold;" bgcolor="#ddddddd">
            <td style="width: 65px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">M</td>
            <td style="width: 65px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">T</td>
            <td style="width: 65px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">HE</td>
            <td style="width: 65px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">C</td>
            <td style="width: 65px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">O</td>
        </tr>
        
        <tr><td colspan="100%" style="height: 30px"></td></tr>
        <tr style="text-align: center">
            <td style="text-align:center; font-weight:bold"></td>
            <td colspan="4" style="text-align:center; font-weight:bold">SITE PROJECT OFFICE</td>
            <td colspan="5" style="text-align:center; font-weight:bold">BRANCH OFFICE</td>
            <td colspan="2" style="text-align:center;"></td>
            <td colspan="5" style="text-align:center; font-weight:bold">HEAD OFFICE</td>
        </tr>
        <tr><td colspan="100%" style="height: 80px"></td></tr>
        <tr style="text-align: center; font-weight: bold">
            <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top"></td>
            <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top"><u>{{$sj->warehouse->logistic}}</u></td>
            <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top"><u>{{$sj->warehouse->supervisor}}</u></td>
            <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top"><u>{{$sj->warehouse->site_engineer}}</u></td>
            <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top"><u>{{$sj->warehouse->project_manager}}</u></td>
            <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top" colspan="2"><u>{{$sj->warehouse->head_logistic}}</u></td>
            <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top" colspan="3"><u>{{$sj->warehouse->branch_manager}}</u></td>
            <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top" colspan="2"><u>{{$sj->warehouse->asset_controller}}</u></td>
            <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top" colspan="2"><u>{{$sj->warehouse->head_purchasing}}</u></td>
            <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top" colspan="3"><u>{{$sj->warehouse->project_management}}</u></td>
        </tr>
        <tr style="text-align: center; font-weight: bold">
            <td></td>
            <td style="text-align:center; height: 50px; font-weight:bold" valign="top">Logistic</td>
            <td style="text-align:center; height: 50px; font-weight:bold" valign="top">SPV</td>
            <td style="text-align:center; height: 50px; font-weight:bold" valign="top">ENG</td>
            <td style="text-align:center; height: 50px; font-weight:bold" valign="top">PM</td>
            <td style="text-align:center; height: 50px; font-weight:bold" valign="top" colspan="2">HEAD <br/>LOGISTIC</td>
            <td style="text-align:center; height: 50px; font-weight:bold" valign="top" colspan="3">BRANCH <br/>MANAGER</td>
            <td style="text-align:center; height: 50px; font-weight:bold" valign="top" colspan="2">ASSET <br/>CONTROLLER</td>
            <td style="text-align:center; height: 50px; font-weight:bold; width:150px" valign="top" colspan="2">HEAD OF <br/>PURCHASING</td>
            <td style="text-align:center; height: 50px; font-weight:bold; width:150px" valign="top" colspan="3">PROJECT <br/>MANAGEMENT</td>
        </tr>
        <tr><td colspan="100%" style="height: 30px"></td></tr>
        <tr>
            <td colspan="11"></td>
            <td style="font-weight: bold" colspan="6">KETERANGAN:</td>
        </tr>
        <tr>
            <td colspan="11"></td>
            <td style="font-weight: bold; text-align: center">M</td>
            <td colspan="3">MATERIAL INDUK</td>
        </tr>
        <tr>
            <td colspan="11"></td>
            <td style="font-weight: bold; text-align: center">I</td>
            <td colspan="3">ALAT KERJA</td>
        </tr>
        <tr>
            <td colspan="11"></td>
            <td style="font-weight: bold; text-align: center">HE</td>
            <td colspan="3">ALAT BERAT</td>
        </tr>
        <tr>
            <td colspan="11"></td>
            <td style="font-weight: bold; text-align: center">C</td>
            <td colspan="3">KONSUMABEL</td>
        </tr>
        <tr>
            <td colspan="11"></td>
            <td style="font-weight: bold; text-align: center">O</td>
            <td colspan="3">DAN LAIN-LAIN</td>
        </tr>-->
    </table>
</body>
</html>