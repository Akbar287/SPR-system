@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url('/producted')}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/product')}}">Produk</a></div>
            <div class="breadcrumb-item"><a href="{{url('/producted')}}">Informasi Produk</a></div>
            <div class="breadcrumb-item active">{{ $title }}</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-10 col-lg-10 col-xl-10">
                <div class="card card-primary shadow">
                    <div class="card-header">
                        <h4 class="card-title">Ubah Informasi</h4>
                        <div class="card-header-action">
                            <h4><a href="{{url('producted/show/'.$products->id)}}" class="btn btn-primary"><i class="fas fa-info"></i> Info</a></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{url('/producted/'.$products->id)}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-sm-12 col-md-4 col-lg-4">
                                    <img src="{{ url('img/products/'. $products->cover) }}" class="rounded mx-auto img-thumbnail img-responsive" id="img-products" alt="">
                                </div>
                                <div class="col-sm-12 col-md-8 col-lg-8">
                                    <h4>{{$products->title}}</h4>
                                    @if(!empty($pm))
                                    <div class="table-responsive container mt-3 ml-1 mr-1">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Bahan Mentah</th>
                                                    <th>Unit</th>
                                                    <th>Harga/Unit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pm as $p)
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>{{$p->name}}</td>
                                                    <td><select name="unit[{{$p->id}}]" id="" class="form-control">
                                                        @for($i=0;$i < 100; $i++) <option value="{{$i}}">{{$i}}</option> @endfor</select></td>
                                                    <td>Rp. {{number_format($p->price,2,'.',',')}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @else
                                    <p>Belum dimasukan Bahan Mentah</p>
                                    @endif
                                </div>
                                <div class="col-sm-12 col-md-8 col-lg-7 col-xl-5">
                                    <button type="submit" style="border-radius: 20px;" class="btn btn-success"><i class="fas fa-pen"></i> Terapkan</button>
                                    <a href="{{ url()->previous() }}" type="button" style="border-radius: 20px;" class="btn btn-light"><i class="fas fa-times"></i> Batalkan</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
