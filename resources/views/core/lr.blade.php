<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$title}}</title>
    <style>
        p{
            line-height: 3px;
        }
        h1,h2,h3,h4,h5,h6,p {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        } td {
            margin: 3px;
            padding: 5px;
        }, table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border: 0;
        }
    </style>
</head>
<body>
    <table class="table" width="100%">
        <tr>
            <td style="width: 30%;"><img src="{{$logo}}" class="img-thumbnail img-responsive" style="border-radius:20px;width: 70px;height:70px;" alt="Logo Usaha"></td>
            <td style="width: 60%;"><h2 style="text-align: center">Laporan Laba Rugi</h2><p style="text-align:center;">Periode {{$now}}</p></td>
            <td style="width: 30%;"><p style="text-align: center">PT. Maheklist</p></td>
        </tr>
    </table><hr>
    <table border="1" width="100%">
        <tbody>
            <tr>
                <td style="width: 40%">Penjualan Bersih</td>
                <td style="width: 30%"></td>
                <td style="width: 30%">Rp. {{number_format($penjualan->total,2,'.',',')}}</td>
            </tr>
            <tr>
                <td style="width: 40%">Pendapatan Di luar Usaha</td>
                <td style="width: 30%"></td>
                <td style="width: 30%">Rp. {{number_format($pendapatan->total,2,'.',',')}}</td>
            </tr>
            <tr>
                <td style="width: 40%">Harga Pokok Penjualan</td>
                <td style="width: 30%"></td>
                <td style="width: 30%">(Rp. {{number_format($hpp,2,'.',',')}})</td>
            </tr>
            <tr>
                <td style="width: 40%">Laba Kotor</td>
                <td style="width: 30%"></td>
                <td style="width: 30%">Rp. {{number_format(($penjualan->total + $pendapatan->total) - $hpp,2,'.',',')}}</td>
            </tr>
            @if(!empty($beban))
            <tr style="margin-top: 20px;">
                <td style="width: 40%">Beban Usaha: </td>
                <td style="width: 30%"></td>
                <td style="width: 30%"></td>
            </tr>
            @for($i=0;$i<count($beban);$i++)
            <tr>
                <td style="padding-left: 20px;width: 40%">{{$beban[$i]['name']}}</td>
                <td style="width: 30%">Rp. {{number_format($beban[$i]['total'],2,'.',',')}}</td>
                <td style="width: 30%"></td>
            </tr>
            @endfor
            <tr style="margin-top: 20px;">
                <td style="width: 40%">Total Beban</td>
                <td style="width: 30%"></td>
                <td style="width: 30%">(Rp. {{number_format($totBeban->total,2,'.',',')}})</td>
            </tr>
            @endif
            <tr>
                <td style="width: 40%">Laba Bersih</td>
                <td style="width: 30%"></td>
                <td style="width: 30%">Rp. {{number_format((($penjualan->total + $pendapatan->total) - ($hpp + $totBeban->total)),2,'.',',')}}</td>
            </tr>
        </tbody>
    </table>
    <p style="text-align: center; padding-top: 25px;">Laporan Ini merupakan bukti resmi yang diterbitkan oleh PT. Maheklis</p>
    <table width="100%" style="padding-left: 50px; padding-right: 50px;padding-top: 35px;">
        <tr>
            <td width="50%"></td>
            <td width="40%">
                <p style="text-align: center;">Dicetak Pada: {{$cetak}}</p>
                <div style="height:75px;"></div>
                <p style="text-align: center;">{{Auth::user()->name}}</p>
                <div style="width: 150px;padding-left: 50px;"><hr></div>
                <p style="text-align: center;">Admin</p>
            </td>
        </tr>
    </table>
</body>
</html>
