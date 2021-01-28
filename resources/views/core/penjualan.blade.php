@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url('/financial')}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/financial')}}">Keuangan</a></div>
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
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="hero bg-primary text-white">
                            <div class="hero-inner">
                                <h2>Rp. {{number_format($total,2,'.',',')}}</h2>
                                <p class="lead">Total Penjualan Bulan Ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-11 col-lg-11">
                        <div class="card card-primary shadow" style="margin-top: -30px;">
                            <div class="card-header">
                                <h4>Riwayat Penjualan</h4>
                            </div>
                            <div class="card-body">
                                @if(!empty($penjualan))
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <th>#</th>
                                            <th>Waktu</th>
                                            <th>Reseller</th>
                                            <th>Toko</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                        </thead>
                                        <tbody>
                                            @foreach($penjualan as $p)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$p['created_at']}}</td>
                                                <td>{{$p['reseller']}}</td>
                                                <td>{{$p['market']}}</td>
                                                <td>Rp.{{number_format($p['total_price'],2,'.',',')}}</td>
                                                <td>{!!($p['status'] == 6) ? '<div class="badge badge-success">Selesai</div>' : '<div class="badge badge-warning">Belum</div>'!!}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
