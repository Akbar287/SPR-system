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
            <td style="width: 60%;"><h2 style="text-align: center">Jurnal Umum</h2><p style="text-align:center;">Periode {{$now}}</p></td>
            <td style="width: 30%;"><p style="text-align: center">PT. Maheklist</p></td>
        </tr>
    </table><hr>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th width="3%">Tgl</th>
                <th width="44%">Deskripsi</th>
                <th width="3%">Ref</th>
                <th width="25%">Debit</th>
                <th width="25%">Kredit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($finance as $f)
            <tr>
                <td>{{$f['created_at'] }}</td>
                <td>{{$f['debit'] }}</td>
                <td>{{$f['refdebit']}}</td>
                <td>Rp. {{number_format($f['debitdesc'],2,'.',',')}}</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td style="padding-left: 100px;">{{$f['credit'] }}</td>
                <td>{{$f['refcredit']}}</td>
                <td></td>
                <td>Rp. {{number_format($f['creditdesc'],2,'.',',')}}</td>
            </tr>
            <tr>
                <td></td>
                <td style="font-style: italic;">{{$f['description'] }}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @endforeach
            <tr>
                <td colspan="3" style="font-size: 25px;font-size:bold;text-align: center;">Total</td>
                <td>Rp. {{number_format($total['debit'],2,'.',',')}}</td>
                <td>Rp. {{number_format($total['credit'],2,'.',',')}}</td>
            </tr>
        </tbody>
    </table>
    <p style="text-align: center; padding-top: 25px;">Jurnal Ini merupakan bukti resmi yang diterbitkan oleh PT. Maheklis</p>
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
