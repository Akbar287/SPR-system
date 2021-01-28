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
                                <h2>{{ $total }}</h2>
                                <p class="lead">Modal Bulan Ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-10 col-lg-10">
                        <div class="card card-primary shadow" style="margin-top: -30px;">
                            <div class="card-header">
                                <h4>Riwayat Modal</h4>
                                <div class="card-header-action">
                                    <h4><a href="{{ url('capital/add') }}" style="border-radius: 20px;" class="btn btn-outline-primary btn-lg btn-icon icon-left"><i class="fas fa-plus"></i></a></h4>
                                </div>
                            </div>
                            <div class="card-body">
                                @if(!is_null($capitals))
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <th>#</th>
                                            <th>Admin</th>
                                            <th>Debet</th>
                                            <th>Kredit</th>
                                            <th>Waktu</th>
                                            <th>Nominal</th>
                                            <th>Aksi</th>
                                        </thead>
                                        <tbody>
                                            @foreach($capitals as $capital)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $capital['name'] }}</td>
                                                <td>{{ $capital['refdebit'] }}</td>
                                                <td>{{ $capital['refcredit'] }}</td>
                                                <td>{{ $capital['created_at'] }}</td>
                                                <td>{{ number_format($capital['creditdesc'],2,'.',',') }}</td>
                                                <td><a href="{{ url('capital/'.$capital['id']) }}" class="btn btn-primary" title="Ubah "><i class="fas fa-pen"></i></a></td>
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
