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
                                <h2>Rp. {{ number_format($total,2,'.',',') }}</h2>
                                <p class="lead">Total Pendapatan Bulan Ini</p>
                                <div class="mt-2">
                                    <a href="{{url('/income/create')}}" class="btn btn-outline-white btn-lg btn-icon icon-left"><i class="fas fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-10 col-lg-10">
                        <div class="card card-primary shadow" style="margin-top: -30px;">
                            <div class="card-header">
                                <h4>Riwayat Pendapatan</h4>
                            </div>
                            <div class="card-body">
                                @if(!empty($income))
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <th>#</th>
                                            <th>Waktu</th>
                                            <th>Debit</th>
                                            <th>Kredit</th>
                                            <th>Total</th>
                                            <th>Aksi</th>
                                        </thead>
                                        <tbody>
                                            @foreach($income as $in)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $in['created_at'] }}</td>
                                                <td>{{ $in['refdebit']}}</td>
                                                <td>{{ $in['refcredit'] }}</td>
                                                <td>{{ number_format($in['total'],2,'.',',') }}</td>
                                                <td><a href="{{ url('income/'.$in['id']) }}" class="btn btn-primary" title="Ubah"><i class="fas fa-pen"></i></a></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <p>Tidak Ada Data</p>
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
