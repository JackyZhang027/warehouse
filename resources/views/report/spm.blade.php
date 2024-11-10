<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPM</title>
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
        <tr class="parent"><td colspan="12" style="font-size:24px; font-weight:bold">Surat Pengajuan Material</td></tr>
        <tr><td colspan="100%" style="height: 20px"></td></tr>
        <tr class="parent">
            <td style="white-space: nowrap">Owner</td>
            <td colspan="16">: {{$spm->warehouse->owner}}</td>
        </tr>
        <tr class="parent">
            <td style="white-space: nowrap">Project</td>
            <td colspan="16">: {{$spm->warehouse->project}}</td>
        </tr>
        <tr class="parent">
            <td style="white-space: nowrap">Lokasi</td>
            <td colspan="16">: {{$spm->warehouse->location}}</td>
        </tr>
        <tr class="parent">
            <td style="white-space: nowrap">Kode Proy</td>
            <td colspan="16">: {{$spm->warehouse->spk_number}}</td>
        </tr>
        <tr class="parent">
            <td style="white-space: nowrap">No. SPM</td>
            <td colspan="16">: {{$spm->mr_number}}</td>
        </tr>
        <tr class="parent">
            <td style="white-space: nowrap">Tanggal</td>
            <td colspan="16">: {{$spm->date}}</td>
        </tr>
        <tr><td colspan="100%" style="height: 40px"></td></tr>
        <tr class="item header" style="background-color: #dddddd; text-align: center;">
            <td rowspan="2" style="border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">NO</td>
            <td rowspan="2" style="width: 230px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">URAIAN</td>
            <td rowspan="2" style="width: 87px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">VOLUME</td>
            <td rowspan="2" style="width: 87px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">SATUAN</td>
            <td rowspan="2" style="width: 260px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">SPEC/TYPE/MERK</td>
            <td rowspan="2" style="width: 100px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">KODE BOQ</td>
            <td rowspan="2" style="width: 120px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">TANGGAL<br/>DIBUTUHKAN</td>
            <td colspan="5" style="border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">KEBUTUHAN UNTUK</td>
            <td colspan="5" rowspan="2" style="border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">KETERANGAN</td>
        </tr>
        <tr class="item header" style="background-color: #dddddd; text-align: center;; font-weight:bold;" bgcolor="#ddddddd">
            <td style="width: 65px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">M</td>
            <td style="width: 65px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">T</td>
            <td style="width: 65px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">HE</td>
            <td style="width: 65px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">C</td>
            <td style="width: 65px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">O</td>
        </tr>
        @foreach ($spm->items as $index => $item)
            <tr class="item">
                <td style="border: 1px solid black; text-align:center">{{ $index + 1 }}</td>
                <td style="border: 1px solid black"><span style="font-weight: bold">[{{$item->item->code}}]</span> {{$item->item->name}}</td>
                <td style="border: 1px solid black; text-align:right">{{$item->qty}}</td>
                <td style="border: 1px solid black; text-align:center">{{$item->item->uom->name}}</td>
                <td style="border: 1px solid black">{{$item->item->description}}</td>
                <td style="border: 1px solid black">{{$item->boq_code}}</td>
                <td style="border: 1px solid black; text-align:center">{{$item->date_needed}}</td>
                <td style="border: 1px solid black; text-align:center">{{$item->check_m == 1 ? 'V' : ''}}</td>
                <td style="border: 1px solid black; text-align:center">{{$item->check_t == 1 ? 'V' : ''}}</td>
                <td style="border: 1px solid black; text-align:center">{{$item->check_he == 1 ? 'V' : ''}}</td>
                <td style="border: 1px solid black; text-align:center">{{$item->check_c == 1 ? 'V' : ''}}</td>
                <td style="border: 1px solid black; text-align:center">{{$item->check_o == 1 ? 'V' : ''}}</td>
                <td style="border: 1px solid black" colspan="5">{{$item->description}}</td>
            </tr>
        @endforeach
        
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
            <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top"><u>{{$spm->warehouse->logistic}}</u></td>
            <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top"><u>{{$spm->warehouse->supervisor}}</u></td>
            <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top"><u>{{$spm->warehouse->site_engineer}}</u></td>
            <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top"><u>{{$spm->warehouse->project_manager}}</u></td>
            <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top" colspan="2"><u>{{$spm->warehouse->head_logistic}}</u></td>
            <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top" colspan="3"><u>{{$spm->warehouse->branch_manager}}</u></td>
            <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top" colspan="2"><u>{{$spm->warehouse->asset_controller}}</u></td>
            <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top" colspan="2"><u>{{$spm->warehouse->head_purchasing}}</u></td>
            <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top" colspan="3"><u>{{$spm->warehouse->project_management}}</u></td>
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
        </tr>
    </table>
</body>
</html>