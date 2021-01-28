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
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/example/penjualan.png')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{ url('/penjualan') }}"> Penjualan</a></h2>
                        </div>
                        <p>Melihat Pendapatan dari hasil Penjualan</p>
                    </div>
                </article>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/example/income.png')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{ url('/income') }}"> Pendapatan Usaha</a></h2>
                        </div>
                        <p>Mencatat dan mengubah Pendapatan selain dari penjualan</p>
                    </div>
                </article>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/example/outcome.png')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{ url('/outcome') }}"> Pengeluaran Usaha</a></h2>
                        </div>
                        <p>Melihat Seluruh Pengeluaran Usaha</p>
                    </div>
                </article>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/example/capital.png')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{ url('/capital') }}"> Modal</a></h2>
                        </div>
                        <p>Mengatur Modal yang ada untuk pertumbuhan usaha.</p>
                    </div>
                </article>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/example/buy.png')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{ url('/purchase') }}"> Pembelian Barang Mentah</a></h2>
                        </div>
                        <p>Membeli barang mentah untuk menambah stok barang dagang.</p>
                    </div>
                </article>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/example/report.png')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{ url('/statement') }}"> Laporan keuangan</a></h2>
                        </div>
                        <p>Membuat serta mencetak laporan keuangan yang ada.</p>
                    </div>
                </article>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/example/discount.png')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{ url('/discount') }}"> Penerapan Diskon</a></h2>
                        </div>
                        <p>Mengatur diskon untuk setiap produk ke reseller.</p>
                    </div>
                </article>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/example/expense.png')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{ url('/expense') }}"> Beban</a></h2>
                        </div>
                        <p>Mendefinisikan Setiap Beban untuk memperoleh laporan keuangan.</p>
                    </div>
                </article>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/example/gaji.jpg')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{ url('/salary') }}"> Beban Gaji</a></h2>
                        </div>
                        <p>Mengatur serta mengolah Gaji Pegawai.</p>
                    </div>
                </article>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/example/accounting.png')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{ url('/accounting') }}"> Penerapan Akutansi</a></h2>
                        </div>
                        <p>Mendefinisikan Setiap jenis dan ref Akuntansi untuk memperoleh laporan keuangan.</p>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection
