@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url('/purchase')}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/financial')}}">Keuangan</a></div>
            <div class="breadcrumb-item"><a href="{{url('/purchase')}}">Pembelian</a></div>
            <div class="breadcrumb-item active">{{ $title }}</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                @if(session('status'))
                {!!session('status')!!}
                @endif
                <div class="row justify-content-center">
                    <div class="col-sm-12 col-md-12 col-lg-10 col-xl-10">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>{{$title}}</h4>
                                <div class="card-header-action">
                                    <h4>Periode Tahun {{date('Y')}}</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>Persediaan, 1 Januari {{date('Y')}}</td>
                                                <td></td>
                                                <td style="text-align: right;">Rp. {{number_format($persediaan,2,'.',',')}}</td>
                                            </tr>
                                            <tr>
                                                <td>Pembelian</td>
                                                <td style="text-align: right;">Rp. {{number_format($pembelian->price,2,'.',',')}}</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Diskon Pembelian</td>
                                                <td style="text-align: right;text-decoration: underline;">Rp. {{number_format(($pembelian->discount + 0),2,'.',',')}}</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Pembelian Bersih</td>
                                                <td style="text-align: right;">Rp. {{number_format($pembelian->total,2,'.',',')}}</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Ongkir Pembelian</td>
                                                <td style="text-align: right;text-decoration: underline;">Rp. {{number_format(0,2,'.',',')}}</td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Harga Pokok Penjualan</td>
                                                <td></td>
                                                <td style="text-align: right;text-decoration: underline;">Rp. {{number_format(($pembelian->total + 0),2,'.',',')}}</td>
                                            </tr>
                                            <tr>
                                                <td>Barang Tersedia Untuk Dijual</td>
                                                <td></td>
                                                <td style="text-align: right;">Rp. {{number_format(($persediaan + $pembelian->total),2,'.',',')}}</td>
                                            </tr>
                                            <tr>
                                                <td>Dikurangi, Persediaan 31 Des {{date('Y') - 1}}</td>
                                                <td></td>
                                                <td style="text-align: right;text-decoration: underline;">Rp. {{number_format($oldpersediaan,2,'.',',')}}</td>
                                            </tr>
                                            <tr>
                                                <td>Beban Pokok Pembelian</td>
                                                <td></td>
                                                <td style="text-align: right;border-bottom: 3px double;">Rp. {{number_format((($persediaan + $pembelian->total) - $oldpersediaan),2,'.',',')}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-10 col-xl-10">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Update Beban Pokok Penjualan</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{url('purchase/hpp')}}" method="POST">@csrf
                                    <div class="form-group">
                                        <label>Beban Pokok Penjualan</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    Rp
                                                </div>
                                            </div>
                                            <input type="text" class="form-control currency @error('bpp') is-invalid @enderror" placeholder="Isi Jika Ingin Diganti" name="bpp" value="{{(($persediaan + $pembelian->total) - $oldpersediaan)}}" name="bpp">
                                        </div>
                                        <div class="container m-1 alert alert-warning" style="text-align: justify;">
                                            <div class="row"><div class="col-1"><i class="fas fa-exclamation-triangle fa fa-3x"></i></div><div class="col-11"> Beban Pokok Penjualan diatas merupakan hasil perhitungan otomatis dari sistem. Jika ingin menetapkan Beban Pokok Penjualan berdasarkan manual silahkan ubah form diatas. Merubah Beban Pokok Penjualan akan merubah Jurnal Umum hingga Neraca Saldo.</div></div></div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-6 text-center">
                                            <button class="btn btn-primary"><i class="fas fa-check"></i> Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
