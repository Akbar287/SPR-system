@extends('core/templates/app')

@section('title', 'Home')
@section('section')
<section class="section">
    <div class="section-header">
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active">Home</div>
        </div>
    </div>
    <div class="section-body">
        @if(session('status'))
        {!!session('status')!!}
        @endif
        @if(Auth::user()->roles == 1)
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                <div class="card card-statistic-2">
                    <div class="card-stats">
                        <div class="card-stats-title">Pesanan Statistik Bulan Ini</div>
                        <div class="card-stats-items">
                            @if(!empty($order))
                            <div class="card-stats-item">
                            <div class="card-stats-item-count">{{$order['cancel']}}</div>
                            <div class="card-stats-item-label">Dibatalkan</div>
                            </div>
                            <div class="card-stats-item">
                            <div class="card-stats-item-count">{{$order['process']}}</div>
                            <div class="card-stats-item-label">Diproses</div>
                            </div>
                            <div class="card-stats-item">
                            <div class="card-stats-item-count">{{$order['complete']}}</div>
                            <div class="card-stats-item-label">Selesai</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-icon shadow-primary bg-primary">
                        <i class="fas fa-archive"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Orders</h4>
                        </div>
                        <div class="card-body">
                            @if(!empty($order))
                            {{$order['cancel'] + $order['process'] + $order['complete']}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                <div class="card card-statistic-2">
                    <div class="card-chart">
                        <canvas id="balance-chart" height="80"></canvas>
                    </div>
                    <div class="card-icon shadow-primary bg-primary">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Kas</h4>
                        </div>
                        <div class="card-body">
                            Rp. {{number_format($balance->description,2,'.',',')}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
                <div class="card card-statistic-2">
                    <div class="card-chart">
                        <canvas id="sales-chart" height="80"></canvas>
                    </div>
                    <div class="card-icon shadow-primary bg-primary">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Penjualan Minggu Ini</h4>
                        </div>
                        <div class="card-body">
                            Rp. {{number_format($weekOrder->total,2,'.',',')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <h2 class="section-title">Layanan</h2>
        <div class="row justify-content-center">
            @if(Auth::user()->roles == 1)
            <div class="col-sm-12 col-md-6 col-lg-4">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/example/shopping-bags.png')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{ url('/product') }}"> Informasi Produk</a></h2>
                        </div>
                        <p>Mengatur Stok produk, kategori dan harga satuan dari produk yang dijual.</p>
                    </div>
                </article>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/example/money.jpg')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{url('/financial')}}"> Informasi keuangan</a></h2>
                        </div>
                        <p>Melihat Setiap Informasi keuangan berupa pendapatan, pengeluaran, modal secara real. </p>
                    </div>
                </article>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/example/vendor.png')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{url('/supplier')}}"> Daftar Pemasok</a></h2>
                        </div>
                        <p>Mengatur, Menambah Pemasok serta catatan pembelian bahan.</p>
                    </div>
                </article>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/example/reseller.png')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{url('/reseller')}}"> Daftar Reseller</a></h2>
                        </div>
                        <p>Mengatur, Menambah Reseller serta catatan pembelian produk ke warung.</p>
                    </div>
                </article>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/example/admin.png')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{url('/admin')}}"> Daftar Admin</a></h2>
                        </div>
                        <p>Mengatur, Menambah Admin untuk mengelola sistem.</p>
                    </div>
                </article>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/example/toko.png')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{url('/market')}}"> Daftar Toko</a></h2>
                        </div>
                        <p>Mengatur, Menambah Toko dan Reseller yang bertanggung jawab.</p>
                    </div>
                </article>
            </div>
            @elseif(Auth::user()->roles == 2 || Auth::user()->roles == 3)
            <div class="col-sm-12 col-md-6 col-lg-4">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/example/toko.png')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{url('/store')}}"> Daftar Toko</a></h2>
                        </div>
                        <p>Toko yang sudah disediakan oleh admin.</p>
                    </div>
                </article>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/example/shopping-bags.png')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{url('/cart')}}"> Pesanan</a></h2>
                        </div>
                        <p>Buat Pesanan Untuk Toko.</p>
                    </div>
                </article>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
