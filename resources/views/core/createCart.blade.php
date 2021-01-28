@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url('/cart')}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/cart')}}">Pesanan</a></div>
            <div class="breadcrumb-item active">{{ $title }}</div>
        </div>
    </div>
    <div class="section-body">
        @if(session('status'))
        {!!session('status')!!}
        @endif
        <form action="{{url('/cart')}}" method="POST"> @csrf
            <div class="row justify-content-center">
            @if(!empty($products))
            @foreach($products as $product)
            <div class="col-sm-12 col-md-4">
                <article class="article article-style-b">
                    <div class="article-header">
                        <div class="article-image" data-background="{{url('/img/products/'. $product['cover'])}}"></div>
                        <div class="article-badge">
                            <div class="article-badge-item bg-danger">Rp. {{ $product['price'] }}</div>
                        </div>
                    </div>
                    <div class="article-details">
                        <div class="article-title">
                            <h4> {{ $product['title'] }}</h4>
                        </div>
                        <p>{{ $product['description'] }}</p>
                        <div class="form-group">
                            <label for="stock-{{$loop->iteration}}">Kuantitas</label>
                            <div class="input-group flex-nowrap">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">{{ $product['stock'] }}</span>
                                </div>
                                <input type="text" class="form-control" id="stock-{{$loop->iteration}}" placeholder="Kuantitas yang dipesan" name="product[{{ 'p'.$product['id'] }}]">
                            </div>
                        </div>
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
            @if(!empty($products))
            <div class="row justify-content-center">
                <div class="col-sm-12 col-md-8 col-lg-6 col-xl-4 text-center">
                    <button type="submit" class="btn btn-primary"> Selanjutnya <i class="fas fa-arrow-right"></i> </button>
                </div>
            </div>
            @endif
        </form>
    </div>
</section>
@endsection
