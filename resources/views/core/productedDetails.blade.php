@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url('/producted/'.$products->id)}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/product')}}">Produk</a></div>
            <div class="breadcrumb-item"><a href="{{url('/producted')}}">Informasi Produk</a></div>
            <div class="breadcrumb-item"><a href="{{url('/producted/'.$products->id)}}">Detail Informasi Produk</a></div>
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
                            <h4><a href="{{url('producted/show/{id}')}}" class="btn btn-primary"><i class="fas fa-info"></i> Info</a></h4>
                        </div>
                    </div>
                    <div class="card-body">
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
                                        <th>Harga Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pm as $p)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$p->name}}</td>
                                        <td>{{ $p->unit }}</td>
                                        <td>Rp. {{number_format($p->price,2,'.',',')}}</td>
                                        <td>Rp. {{number_format(($p->price * $p->unit),2,'.',',')}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4">Total</td>
                                        <td>Rp. {{number_format(($total),2,'.',',')}}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @else
                        <p>Belum dimasukan Bahan Mentah</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
