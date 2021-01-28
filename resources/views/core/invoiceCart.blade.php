@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item active">{{ $title }}</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-10 col-lg-10 col-xl-10">
                @if(session('status'))
                {!!session('status')!!}
                @endif
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Pesanan</h4>
                        <div class="card-header-action">
                            <h4>{{ date('d/m/Y') }}</h4>
                        </div>
                    </div>
                    <form action="{{ url('/cart/create') }}" method="POST"> @csrf
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="inv">Invoice</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-receipt"></i>
                                            </div>
                                        </div>
                                        <input id="inv" type="text" class="form-control" readonly value="{{ ($invoice) }}" name="invoice">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="billTo">Pembeli</label>
                                    <select name="billTo" id="billTo" class="form-control">
                                        @if(!is_null($stores))
                                        @foreach($stores as $store)
                                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                        @else
                                        <option value="">Tidak Ada Toko</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="purchase">Pembayaran</label>
                                    <select name="purchase" id="purchase" class="form-control">
                                        <option value="1">Tunai - Bayar Setelah barang diterima</option>
                                        <option value="2">Kredit - Bayar Saat jatuh tempo / Utang</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="method">Metode Pembayaran</label>
                                    <select readonly name="method" id="method" class="form-control">
                                        <option value="1" selected>Cash On Delivery</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-sm-12 col-lg-10 col-md-10">
                                <label>Deskripsi Pesanan</label>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <th>#</th>
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>Harga</th>
                                            <th>Diskon</th>
                                            <th>Total</th>
                                        </thead>
                                        <tbody>
                                            @if(!empty($cart))
                                            @foreach($cart as $c)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $c['item'] }}</td>
                                                <td>{{ $c['qty'] }}</td>
                                                <td>{{ number_format($c['price'],2,'.',',') }}</td>
                                                <td>{{ number_format((!empty($c['discount']) ? $c['discount'] : 0),2,'.',',') }}</td>
                                                <td>{{ number_format($c['total'],2,'.',',') }}</td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="5">Total</td>
                                                <td>Rp. {{ number_format($tot,2,'.',',') }}</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-1">
                            <div class="col-sm-12 col-md-10 col-lg-8 col-xl-6">
                                <div class="row">
                                    <div class="col-6">
                                        <a href="{{ url('/create') }}" type="button" style="border-radius: 20px;" class="btn-block btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
                                    </div>
                                    <div class="col-6">
                                        <button type="submit" style="border-radius: 20px;" class="btn-block btn btn-success"><i class="fas fa-save"></i> Buat Pesanan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
