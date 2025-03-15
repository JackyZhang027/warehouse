<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPM</title>
    {{-- <style>
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

    </style> --}}
    <style>
        
        div{
            border: 1px solid black;
            padding: 2px;
        }
        table.signature{
            font-size: 12px;
            width: 100%;
        }
        table.parent{
            width: 100%;
        }
        table.parent td:nth-child(1){
            width: 75px;
        }
        table.item{
            width: 100%;
            border-collapse:collapse;
            border: 1px solid black;
        }
        table.item td{  
            padding-left: 5px; 
            padding-right: 5px;
        }
        h2{
            padding: 0px; 
            margin: 0px;
        }
        body{
            font-size: 13px
        }
        .logo{
            width: 150px;
            height: auto;
            padding-right: 20px
        }
    </style>
</head>
<body>
    <div>
        <h2>Surat Pengajuan Material</h2>
        <table class="parent">
            <tr>
                <td style="white-space: nowrap">Owner</td>
                <td>: {{$spm->warehouse->owner}}</td>
                <td rowspan="6" style="text-align: right">
                    <img src="var:logo" class="logo" />
                </td>
            </tr>
            <tr>
                <td style="white-space: nowrap">Project</td>
                <td>: {{$spm->warehouse->project}}</td>
            </tr>
            <tr>
                <td style="white-space: nowrap">Lokasi</td>
                <td>: {{$spm->warehouse->location}}</td>
            </tr>
            <tr>
                <td style="white-space: nowrap">Kode Proy</td>
                <td>: {{$spm->warehouse->spk_number}}</td>
            </tr>
            <tr>
                <td style="white-space: nowrap">No. SPM</td>
                <td>: {{$spm->mr_number}}</td>
            </tr>
            <tr>
                <td style="white-space: nowrap">Tanggal</td>
                <td>: {{$spm->date->format('d-m-Y')}}</td>
            </tr>
        </table>
        <br/>
        <table class="item">
            <tr class="header" style="background-color: #dddddd; text-align: center;">
                <td rowspan="2" style="border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">NO</td>
                <td rowspan="2" style="width: 230px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">URAIAN</td>
                <td rowspan="2" style="width: 87px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">VOL</td>
                <td rowspan="2" style="width: 87px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">SAT</td>
                <td rowspan="2" style="width: 260px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">SPEC/TYPE/MERK</td>
                <td rowspan="2" style="width: 100px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">KODE BOQ</td>
                <td rowspan="2" style="width: 120px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">TANGGAL<br/>DIBUTUHKAN</td>
                <td colspan="5" style="border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">KEBUTUHAN UNTUK</td>
                <td colspan="5" rowspan="2" style="border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">KETERANGAN</td>
            </tr>
            <tr class="header" style="background-color: #dddddd; text-align: center;; font-weight:bold;" bgcolor="#ddddddd">
                <td style="width: 65px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">M</td>
                <td style="width: 65px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">T</td>
                <td style="width: 65px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">HE</td>
                <td style="width: 65px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">C</td>
                <td style="width: 65px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">O</td>
            </tr>
            @foreach ($spm->items as $index => $item)
                <tr>
                    <td style="border: 1px solid black; text-align:center">{{ $index + 1 }}</td>
                    <td style="border: 1px solid black"><span style="font-weight: bold">[{{$item->item->code}}]</span> {{$item->item->name}}</td>
                    <td style="border: 1px solid black; text-align:right">{{$item->qty}}</td>
                    <td style="border: 1px solid black; text-align:center">{{$item->item->uom->name}}</td>
                    <td style="border: 1px solid black">{{$item->item->description}}</td>
                    <td style="border: 1px solid black">{{$item->boq_code}}</td>
                    <td style="border: 1px solid black; text-align:center">{{$item->date_needed->format('d-m-Y')}}</td>
                    <td style="border: 1px solid black; text-align:center">{{$item->check_m == 1 ? 'V' : ''}}</td>
                    <td style="border: 1px solid black; text-align:center">{{$item->check_t == 1 ? 'V' : ''}}</td>
                    <td style="border: 1px solid black; text-align:center">{{$item->check_he == 1 ? 'V' : ''}}</td>
                    <td style="border: 1px solid black; text-align:center">{{$item->check_c == 1 ? 'V' : ''}}</td>
                    <td style="border: 1px solid black; text-align:center">{{$item->check_o == 1 ? 'V' : ''}}</td>
                    <td style="border: 1px solid black" colspan="5">{{$item->description}}</td>
                </tr>
            @endforeach
        </table>
        {{-- Data for Signature --}}
        <br/>
        <table style="width: 100%" class="signature">
            <tr style="text-align: center">
                <td colspan="5" style="text-align:center; font-weight:bold">SITE PROJECT OFFICE</td>
                <td colspan="5" style="text-align:center; font-weight:bold">BRANCH OFFICE</td>
                <td colspan="2" style="text-align:center;"></td>
                <td colspan="5" style="text-align:center; font-weight:bold">HEAD OFFICE</td>
            </tr>
            <tr><td colspan="17" style="height: 80px"></td></tr>
            <tr style="text-align: center; font-weight: bold">
                <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top"><u>{{$spm->warehouse->logistic}}</u></td>
                <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top"><u>{{$spm->warehouse->supervisor}}</u></td>
                <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top"><u>{{$spm->warehouse->site_engineer}}</u></td>
                <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top"><u>{{$spm->warehouse->site_manager}}</u></td>
                <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top"><u>{{$spm->warehouse->project_manager}}</u></td>
                <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top" colspan="2"><u>{{$spm->warehouse->head_logistic}}</u></td>
                <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top" colspan="3"><u>{{$spm->warehouse->branch_manager}}</u></td>
                <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top" colspan="2"><u>{{$spm->warehouse->asset_controller}}</u></td>
                <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top" colspan="2"><u>{{$spm->warehouse->head_purchasing}}</u></td>
                <td style="text-align:center; text-decoration: underline; font-weight:bold" valign="top" colspan="3"><u>{{$spm->warehouse->project_management}}</u></td>
            </tr>
            <tr style="text-align: center; font-weight: bold">
                <td style="text-align:center; height: 50px; font-weight:bold" valign="top">Logistic</td>
                <td style="text-align:center; height: 50px; font-weight:bold" valign="top">SPV</td>
                <td style="text-align:center; height: 50px; font-weight:bold" valign="top">ENG</td>
                <td style="text-align:center; height: 50px; font-weight:bold" valign="top">SM</td>
                <td style="text-align:center; height: 50px; font-weight:bold" valign="top">PM</td>
                <td style="text-align:center; height: 50px; font-weight:bold" valign="top" colspan="2">HEAD <br/>LOGISTIC</td>
                <td style="text-align:center; height: 50px; font-weight:bold" valign="top" colspan="3">BRANCH <br/>MANAGER</td>
                <td style="text-align:center; height: 50px; font-weight:bold" valign="top" colspan="2">ASSET <br/>CONTROLLER</td>
                <td style="text-align:center; height: 50px; font-weight:bold;" valign="top" colspan="2">HEAD OF <br/>PURCHASING</td>
                <td style="text-align:center; height: 50px; font-weight:bold;" valign="top" colspan="3">PROJECT <br/>MANAGEMENT</td>
            </tr>
        </table>
    </div>
</body>
</html>