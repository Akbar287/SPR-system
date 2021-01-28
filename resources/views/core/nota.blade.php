<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
    <title>Nota Pembelian</title>
</head>
<body>
    <table class="table" width="100%">
        <tr>
            <td style="width: 30%;"><img src="{{$logo}}" class="img-thumbnail img-responsive" style="border-radius:20px;width: 70px;height:70px;" alt="Logo Usaha"></td>
            <td style="width: 60%;"><h2 style="text-align: center">Will Be Success Fish</h2></td>
            <td style="width: 30%;"><h3 style="text-align: center">Nota</h3></td>
        </tr>
    </table>
    <fieldset style="border-radius: 20px;">
        <table width="100%">
            <tr width="100%">
                <td width="50%">
                    <h5>PT. Will Be Success Fish</h5>
                    <p>Jalan Monumen Pancasila Sakti No.46</p>
                    <p>Lubang Buaya, Jakarta, 12345</p>
                    <p>08123456789 / 021 327382</p>
                </td>
                <td width="50%" style="padding-top:35px;">
                    <p>Invoice      : {{$orders->invoice}}</p>
                    <p>Tanggal      : {{$time}}</p>
                    <p>ID Pelanggan : {{$orders->IDPEL}}</p>
                </td>
            </tr>
        </table>
        <table width="100%">
            <tr width="100%">
                <td>
                    <p>Nama Usaha : {{$orders->MarketName}}</p>
                    <p>Nama Pemilik : {{$orders->MarketOwner}}</p>
                    <p>Alamat : {{$orders->MarketAddress}}</p>
                </td>
            </tr>
        </table>
    </fieldset>
    <h3 style="text-align:center;">Deskripsi Pesanan</h3>
    <table border="2" width="100%">
        <thead>
            <tr>
                <th>Qty</th>
                <th>Deskripsi</th>
                <th>Harga Satuan</th>
                <th>Diskon Satuan</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($product as $p)
            <tr width="100%">
                <td width="3%">
                    <p>{{$p['quantity']}}</p>
                </td>
                <td width="40%">
                    <p>{{$p['title']}}</p>
                </td>
                <td width="18%">
                    <p>Rp. {{number_format($p['price'],2,'.',',')}}</p>
                </td>
                <td width="18%">
                    <p>Rp. {{number_format($p['discount'],2,'.',',')}}</p>
                </td>
                <td width="24%">
                    <p>Rp. {{number_format($p['total'],2,'.',',') }}</p>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4"><h3 style="text-align: right;">Total</h3></td>
                <td><p>Rp {{number_format($total,2,'.',',')}}</p></td>
            </tr>
        </tfoot>
    </table>
    <h3 style="text-align: center; padding-top: 5px;">TERIMA KASIH ATAS KERJA SAMA ANDA</h3>
    <p style="text-align: center; padding-top: 5px;">Nota Pembelian Ini merupakan bukti resmi yang diterbitkan oleh PT. Maheklis</p>
    <table width="100%" style="padding-left: 50px; padding-right: 50px;padding-top: 35px;">
        <tr>
            <td width="40%">
                <p style="text-align: center;">Pengirim</p>
                <div style="height:50px;"></div>
                <div style="width: 150px;padding-left: 50px;"><hr></div>
                <p style="text-align: center;">{{$orders->reseller}}</p>
            </td>
            <td width="10%"></td>
            <td width="40%">
                <p style="text-align: center;">Penerima</p>
                <div style="height:75px;"></div>
                <div style="width: 150px;padding-left: 50px;"><hr></div>
                <p style="text-align: center;">{{$orders->MarketOwner}}</p>
            </td>
        </tr>
    </table>
</body>
</html>
