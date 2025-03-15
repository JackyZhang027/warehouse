<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Keluar</title>
    <style>
        table{
            border-collapse:collapse;
            
        }
        .item td {
          border: 1px solid black;
          border-collapse: collapse;
          padding-left: 5px; padding-right: 5px;
        }
        .parent td{
            padding-left: 15px;
        }
        
        .logo{
            width: auto;
            height: 60px;
            padding-right: 20px
        }
    </style>
</head>
<body>
    <div style="border: 1 solid black; padding:2px">
        <table style="width:100%">
            <tr>
                <td style="padding-left: 10px"><h2>Barang Keluar</h2></td>
                <td style="text-align: right; padding-right:15px; padding-top:2px"><img src="var:logo" class="logo" /></td>
            </tr>
        </table>
        <table style="width: 100%">
            <tr class="parent">
                <td colspan="2" style="white-space: nowrap">No Barang Keuar</td>
                <td colspan="4">: {{$out->io_number}}</td>
            </tr>
            <tr class="parent">
                <td colspan="2" style="white-space: nowrap">Proyek</td>
                <td colspan="4">: {{$out->warehouse->spk_number}}</td>
            </tr>
            <tr class="parent">
                <td colspan="2" style="white-space: nowrap">Tanggal Keluar</td>
                <td colspan="4">: {{$out->date}}</td>
            </tr>
            <tr><td colspan="100%" style="height: 20px"></td></tr>
            <tr class="item header" style="background-color: #dddddd; text-align: center;">
                <td style="border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">NO</td>
                <td colspan="5" style="width: 430px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">Nama Barang</td>
                <td style="width: 87px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">VOL</td>
                <td style="width: 87px; border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">SAT</td>
                <td colspan="4" style="border: 1px solid black; text-align:center; font-weight:bold;" bgcolor="#ddddddd">KETERANGAN</td>
            </tr>

            @foreach ($out->details as $index => $item)
                <tr class="item">
                    <td style="border: 1px solid black; text-align:center">{{ $index + 1 }}</td>
                    <td colspan="5" style="border: 1px solid black"><span style="font-weight: bold">[{{$item->item->code}}]</span> {{$item->item->name}}</td>
                    <td style="border: 1px solid black">{{$item->qty}}</td>
                    <td style="border: 1px solid black">{{$item->item->uom->name}}</td>
                    <td colspan="4" style="border: 1px solid black">{{$item->remark}}</td>
                </tr>
            @endforeach
        </table>
        <br/>
    </div>
</body>
</html>