@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url('/product')}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/product')}}">Produk</a></div>
            <div class="breadcrumb-item active">{{ $title }}</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row justify-content-end mb-2">
            @if(session('status'))
            <div class="col-12">
                {!!session('status')!!}
            </div>
            @endif
        </div>
        <div class="row justify-content-center">
            @if(!empty($products))
            @foreach($products as $product)
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/products/'. $product['cover'])}}"></div>
                        <div class="article-badge">
                            <div class="article-badge-item bg-danger">Rp. {{$product['price']}}</div>
                        </div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h2><a href="{{ url('/producted/'.$product['id']) }}"> {{ $product['title'] }}</a></h2>
                        </div>
                        <p>{{ $product['description'] }}</p>
                    </div>
                </article>
            </div>
            @endforeach
            @else
            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="card card-primary shadow">
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            Saat Ini Tidak Ada Produk
                        </h5>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
