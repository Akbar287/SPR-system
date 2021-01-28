@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="@if(Auth::user()->roles == 1){{url('pesanan')}} @else {{url('cart')}} @endif" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            @if(Auth::user()->roles == 2 || Auth::user()->roles == 3)
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/cart')}}">Pesanan</a></div>
            <div class="breadcrumb-item active">{{ $title }}</div>
            @else
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/pesanan')}}">Pesanan</a></div>
            <div class="breadcrumb-item active">{{ $title }}</div>
            @endif
        </div>
    </div>
    <div class="section-body">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                @if(session('status'))
                {!!session('status')!!}
                @endif
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Order Status</h4>
                        <div class="card-header-action">
                            <a href="{{url('cart/status/'.$orders->id . '/order')}}" class="btn btn-primary" title="Order Detail"><i class="fas fa-info"></i> Detail</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mt-4">
                            <div class="col-12 col-lg-12">
                                <div class="wizard-steps">
                                    @if($orders->status == 0)
                                    <div class="wizard-step wizard-step-active">
                                        <div class="wizard-step-icon">
                                            <i class="fas fa-pen"></i>
                                        </div>
                                        <div class="wizard-step-label">
                                            Order Dibuat
                                        </div>
                                    </div>
                                    <div class="wizard-step wizard-step-danger">
                                        <div class="wizard-step-icon">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div class="wizard-step-label">
                                            Order Dibatalkan
                                        </div>
                                    </div>
                                    @else
                                    <div class="wizard-step wizard-step-{{($orders->status >= 1) ? 'success' : 'active'}}">
                                        <div class="wizard-step-icon">
                                            <i class="fas fa-pen"></i>
                                        </div>
                                        <div class="wizard-step-label">
                                            Order Dibuat
                                        </div>
                                    </div>
                                    <div class="wizard-step wizard-step-{{($orders->status >= 2) ? 'success' : 'active'}}">
                                        <div class="wizard-step-icon">
                                            <i class="fas fa-box"></i>
                                        </div>
                                        <div class="wizard-step-label">
                                            Produk Siap Diambil
                                        </div>
                                    </div>
                                    <div class="wizard-step wizard-step-{{($orders->status >= 3) ? 'success' : 'active'}}">
                                        <div class="wizard-step-icon">
                                            <i class="fas fa-shipping-fast"></i>
                                        </div>
                                        <div class="wizard-step-label">
                                            Produk Dibawa Reseller
                                        </div>
                                    </div>
                                    <div class="wizard-step wizard-step-{{($orders->status >= 4) ? 'success' : 'active'}}">
                                        <div class="wizard-step-icon">
                                            <i class="fas fa-map-marked-alt"></i>
                                        </div>
                                        <div class="wizard-step-label">
                                            Produk Sampai DiTujuan
                                        </div>
                                    </div>
                                    <div class="wizard-step wizard-step-{{($orders->status >= 5) ? 'success' : 'active'}}">
                                        <div class="wizard-step-icon">
                                            <i class="fas fa-dollar-sign"></i>
                                        </div>
                                        <div class="wizard-step-label">
                                            Pembayaran Diterima
                                        </div>
                                    </div>
                                    <div class="wizard-step wizard-step-{{($orders->status == 6) ? 'success' : 'active'}}">
                                        <div class="wizard-step-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="wizard-step-label">
                                            Order Selesai
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Rincian Order</h4>
                        <div class="card-header-action">
                            <h4>{{$time }}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="inv">Invoice</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-receipt"></i>
                                            </div>
                                        </div>
                                        <input id="invoice" type="text" class="form-control" value="{{$orders->invoice}}" readonly name="invoice">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label for="billTo">Pembeli</label>
                                    <input id="billTo" type="text" class="form-control" value="{{$orders->name}}" readonly name="invoice">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="purchase">Pembayaran</label>
                                    <input id="purchase" type="text" class="form-control" value="{{ ($orders->purchase == 1) ? 'Tunai - Bayar Setelah barang diterima' : 'Kredit - Bayar Saat jatuh tempo / Utang' }}" readonly name="invoice">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="method">Metode Pembayaran</label>
                                    <input id="method" type="text" class="form-control" value="{{ ($orders->metode == 1) ? 'Cash On Delivery' : 'Kartu Kredit' }}" readonly name="invoice">
                                </div>
                            </div>
                            @if(Auth::user()->roles == 1)
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="reseller">Reseller</label>
                                    <input id="reseller" type="text" class="form-control" value="{{$orders->reseller}}" readonly name="invoice">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="jreseller">Jenis Reseller</label>
                                    <input id="jreseller" type="text" class="form-control" value="{{($orders->roles == 2) ? 'Produk' : 'Non-Produk'}}" readonly name="invoice">
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row justify-content-center mt-2">
                            <div class="col-sm-12 col-lg-10 col-md-10">
                                <h4>Deskripsi Pesanan</h4>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <th>#</th>
                                            <th>qty</th>
                                            <th>Item</th>
                                            <th>Harga</th>
                                            <th>Diskon</th>
                                            <th>Total</th>
                                        </thead>
                                        <tbody>
                                            @if(!empty($product))
                                            @foreach($product as $c)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $c['quantity'] }}</td>
                                                <td>{{ $c['title'] }}</td>
                                                <td>Rp. {{ number_format($c['price'],2,'.',',') }}</td>
                                                <td>Rp. {{ number_format((!empty($c['discount']) ? $c['discount'] : 0),2,'.',',') }}</td>
                                                <td>Rp. {{ number_format($c['total'],2,'.',',') }}</td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="5" class="text-right">Total</td>
                                                <td>Rp. {{ number_format($total,2,'.',',') }}</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-sm-12 col-md-3 mb-2">
                                <a href="{{url()->previous()}}" class="btn btn-block btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
                            </div>@if(Auth::user()->roles == 2 || Auth::user()->id == 3)<div class="col-sm-12 col-md-3 mb-2">
                                <form action="{{ url('/cart/cancel/' . $orders->id) }}" id="cOrder" method="POST">@csrf @method('delete') <button {{ ($orders->status == 0 || $orders->status == 6) ?'disabled' : ''}} onclick="event.preventDefault();(confirm('Order Akan Dibatalkan ? ') ? this.parentNode.submit() : event.preventDefault() );" type="button" style="border-radius: 20px;" class="btn btn-block btn-danger"><i class="fas fa-times"></i> Batalkan Order</button></form>
                            </div>@endif<div class="col-sm-12 col-md-3 mb-2">
                                <a target="_blank" href="{{url('cart/cetak/'.$orders->id)}}" class="btn btn-block btn-warning"><i class="fas fa-print"></i> Cetak Nota</a>
                            </div>
                            @if(Auth::user()->roles == 1)
                            @if($orders->status == 4)
                            <div class="col-sm-12 col-md-3 mb-2"><form action="{{ url('/cart/done/' . $orders->id) }}" method="POST">@csrf <button {{ ($orders->status == 0) ?'disabled' : ''}} onclick="event.preventDefault();(confirm('Pastikan Pembayaran Sudah Selesai!\nOrder Sudah Selesai ? ') ? this.parentNode.submit() : event.preventDefault() );" type="button" style="border-radius: 20px;" class="btn btn-block btn-success"><i class="fas fa-check"></i> Order Selesai</button></form></div>
                            <div class="col-sm-12 col-md-3 mb-2"><a href="{{url('cart/status/'.$orders->id.'/return')}}" {{ ($orders->status == 0) ?'disabled' : ''}} type="button" style="border-radius: 20px;" class="btn btn-block btn-warning"><i class="fas fa-check"></i> Return Barang</a></div>
                            @endif
                            @if($orders->status == 1)
                            <div class="col-sm-12 col-md-3 mb-2"><form action="{{ url('/cart/product/' . $orders->id) }}" method="POST">@csrf <button {{ ($orders->status == 0) ?'disabled' : ''}} onclick="event.preventDefault();(confirm('Produk Sudah Siap untuk diambil Reseller ? ') ? this.parentNode.submit() : event.preventDefault() );" type="button" style="border-radius: 20px;" class="btn btn-block btn-success"><i class="fas fa-box"></i> Produk Siap</button></form>
                            </div>
                            @endif
                            @if($orders->status == 2)
                            <div class="col-sm-12 col-md-3 mb-2"><form action="{{ url('/cart/bring/' . $orders->id) }}" method="POST">@csrf <button {{ ($orders->status == 0) ?'disabled' : ''}} onclick="event.preventDefault();(confirm('Produk Sudah Dibawa oleh Reseller ? ') ? this.parentNode.submit() : event.preventDefault() );" type="button" style="border-radius: 20px;" class="btn btn-block btn-success"><i class="fas fa-box"></i> Produk Sudah Dibawa</button></form>
                            </div>
                            @endif
                            @if($orders->status == 3)
                            <div class="col-sm-12 col-md-3 mb-2"><form action="{{ url('/cart/to/' . $orders->id) }}" method="POST">@csrf <button {{ ($orders->status == 0) ?'disabled' : ''}} onclick="event.preventDefault();(confirm('Produk Sudah Sampai Di Tujuan ? ') ? this.parentNode.submit() : event.preventDefault() );" type="button" style="border-radius: 20px;" class="btn btn-block btn-success"><i class="fas fa-box"></i> Produk Sampai DiTujuan</button></form>
                            </div>
                            @endif
                            @endif
                            @if(Auth::user()->roles == 2)
                            @if($orders->status == 3)
                            <div class="col-sm-12 col-md-3 mb-2"><form action="{{ url('/cart/to/' . $orders->id) }}" method="POST">@csrf <button {{ ($orders->status == 0) ?'disabled' : ''}} onclick="event.preventDefault();(confirm('Produk Sudah Dibawa oleh Reseller ? ') ? this.parentNode.submit() : event.preventDefault() );" type="button" style="border-radius: 20px;" class="btn btn-block btn-success"><i class="fas fa-box"></i> Produk Sampai DiTujuan</button></form>
                            </div>
                            @endif

                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
