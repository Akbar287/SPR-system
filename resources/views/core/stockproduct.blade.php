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
        <div class="row justify-content-center">
            @if(!empty($products))
            @foreach($products as $product)
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-4">
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
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <ul>
                                    @foreach($materials as $material)
                                    @foreach($relation as $re)
                                    @if($re->id_product == $product['id'])
                                    @if($re->id_material == $material->id)
                                    <li>{{ $material->name }} ({{ $material->stock }}) <div style="color:red;display:inline;">{{($material->is_rewrite == 1) ? '-' . $re->unit : ''}}</div></li>
                                    @endif
                                    @endif
                                    @endforeach
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <form action="{{url('/stock')}}" class="restock" method="POST">
                            <div class="form-group">
                                <label for="stock-{{$loop->iteration}}">Stok</label>
                                <div class="input-group mb-3">
                                    <input type="number" id="stock-{{$loop->iteration}}" name="stock" class="form-control" placeholder="Stok Produk" value="{{ $product['stock'] }}">
                                    <input type="hidden" value="{{$product['id']}}" name="id">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-success" style="border-radius:0 5px 5px 0!important;" type="submit"><i class="fas fa-check"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
