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
            <div class="col-sm-12 col-md-10 col-lg-10 col-xl-10">
                @if(session('status'))
                {!!session('status')!!}
                @endif
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Ketentuan</h4>
                        <div class="card-header-action">
                            <h4><a href="{{url('print')}}" class="btn btn-primary"><i class="fas fa-print"></i> Cetak</a></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Awal</label>
                                    <input type="text" name="start" class="form-control datepicker">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Akhir</label>
                                    <input type="text" name="end" class="form-control datepicker">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="income" id="income">
                                    <label class="custom-control-label" for="income">Pendapatan Dari Penjualan</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="outcome" id="outcome">
                                    <label class="custom-control-label" for="outcome">Pengeluaran</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="capital" id="capital">
                                    <label class="custom-control-label" for="capital">Modal</label>
                                </div>
                            </div>
                            <div class="col-6"></div>
                        </div>
                        <div class="row justify-content-center mt-2">
                            <div class="col-md-5"><button class="btn btn-success text-center"><i class="fas fa-search"></i> Tampilkan Laporan</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
