@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url('/discount')}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/financial')}}">Keuangan</a></div>
            <div class="breadcrumb-item"><a href="{{url('/discount')}}">Diskon</a></div>
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
                    <div class="col-sm-12 col-md-10 col-lg-10">
                        <div class="card card-primary shadow">
                            <div class="card-header">
                                <h4>Buat Diskon</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{url('discount')}}" method="POST">@csrf
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group">
                                                <label>Reseller</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                    </div>
                                                    <select name="reseller" id="" class="form-control">
                                                        @if(!is_null($reseller))
                                                        <option value="a">Semua Reseller</option>
                                                        <option value="b">Semua Reseller Produk</option>
                                                        <option value="c">Semua Reseller Non-Produk</option>
                                                        @foreach($reseller as $r)
                                                        <option value="{{ $r->id }}">{{ $r->name }} - {{ ($r->roles == 2) ? 'Produk' : 'Non-Produk' }}</option>
                                                        @endforeach
                                                        @else
                                                        <option value="">- No Action -</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label>Produk</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-box"></i>
                                                        </div>
                                                    </div>
                                                    <select name="product" id="" class="form-control">
                                                        @if(!is_null($products))
                                                        <option value="0">Semua Produk</option>
                                                        @foreach($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->title }} - Rp. {{ $product->price }}</option>
                                                        @endforeach
                                                        @else
                                                        <option value="">- No Action -</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Diskon</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-dollar-sign"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control currency @error('discount') is-invalid @enderror" name="discount" placeholder="Masukan Besaran Diskon">
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-3 text-center">
                                            <button class="btn btn-success" type="submit"><i class="fas fa-pen"></i> Simpan</button>
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
