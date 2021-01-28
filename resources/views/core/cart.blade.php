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
            <div class="col-sm-12 col-md-12">
                @if(session('status'))
                {!!session('status')!!}
                @endif
                <div class="row justify-content-center">
                    @if(!empty($orders))
                    <div class="col-sm-12 col-md-10 col-lg-10">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Pesanan</h4>
                                <div class="card-header-action">
                                    <a href="{{url('cart/create')}}" class="btn btn-primary" title="Buat Pesanan"><i class="fas fa-pen"></i> Buat</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-hover">
                                    <thead>
                                        <th>#</th>
                                        <th>Waktu</th>
                                        <th>Nama Toko</th>
                                        <th>Total Harga</th>
                                        <th>Status</th>
                                        <th>Detail</th>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$order->created_at}}</td>
                                            <td>{{$order->name}}</td>
                                            <td>{{number_format($order->total_price,2,'.',',')}}</td>
                                            <td>{!! $status[$order->status] !!}</td>
                                            <td><a href="{{url('cart/status/'.$order->id)}}" class="btn btn-primary" title="Detail"><i class="fas fa-info"></i></a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    @else
                    <p>Tidak Ada Data</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
