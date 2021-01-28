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
                        <div class="article-image" data-background="{{url('/img/example/categoryProducts.png')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{ url('/category') }}"> Kategori Produk</a></h2>
                        </div>
                        <p>Membuat Jenis Kategori Produk yang akan dijual.</p>
                    </div>
                </article>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/example/product.png')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{ url('/products') }}"> Sub Kategori Produk</a></h2>
                        </div>
                        <p>Mengelola Rincian Produk yang akan dijual.</p>
                    </div>
                </article>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/example/productInformation.png')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{ url('/producted') }}"> Informasi Produk</a></h2>
                        </div>
                        <p>Mengelola Persediaan Bahan Mentah menjadi Barang Dagang.</p>
                    </div>
                </article>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/example/stock.png')}}"></div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{ url('/stock') }}"> Stok Produk</a></h2>
                        </div>
                        <p>Mengatur Stok Produk yang dapat dijual.</p>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection
