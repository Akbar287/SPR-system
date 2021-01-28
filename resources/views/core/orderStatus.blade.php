@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url()->previous()}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            @if(Auth::user()->id == 1)
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/pesanan')}}">Pesanan</a></div>
            <div class="breadcrumb-item"><a href="{{url('/cart/status/'.$id)}}">Rincian Pesanan</a></div>
            <div class="breadcrumb-item active">{{ $title }}</div>
            @else
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/cart')}}">Pesanan</a></div>
            <div class="breadcrumb-item"><a href="{{url('/cart/status/'.$id)}}">Rincian Pesanan</a></div>
            <div class="breadcrumb-item active">{{ $title }}</div>
            @endif
        </div>
    </div>
    <div class="section-body">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-12">
                @if(session('status'))
                {!!session('status')!!}
                @endif

                @if(!empty($history))
                <div class="row justify-content-center">
                    <div class="col-sm-12 col-md-10 col-lg-10">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Riwayat Pesanan</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-hover">
                                    <thead>
                                        <th>#</th>
                                        <th>Waktu</th>
                                        <th>Deskripsi</th>
                                        <th>status</th>
                                    </thead>
                                    <tbody>
                                        @foreach($history as $h)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$h['created_at']}}</td>
                                            <td>{{$h['description']}}</td>
                                            <td>{!! $status[$h['status']] !!}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="card card-primary">
                    <div class="card-body">
                        <h4>Tidak Ada Data</h4>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
