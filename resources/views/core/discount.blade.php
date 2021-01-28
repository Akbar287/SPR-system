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
                    <div class="col-sm-12 col-md-10 col-lg-10">
                        <div class="card card-primary shadow">
                            <div class="card-header">
                                <h4>Diskon</h4>
                                <div class="card-header-action">
                                    <a href="{{url('discount/create')}}" class="btn btn-primary"><i class="fas fa-pen"></i> Buat Diskon</a>
                                </div>
                            </div>
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <th>#</th>
                                            <th>Reseller</th>
                                            <th>Jenis Reseller</th>
                                            <th>Produk</th>
                                            <th>Diskon</th>
                                            <th>Aksi</th>
                                        </thead>
                                        <tbody>
                                            @foreach($discount as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $d->name }}</td>
                                                <td>{!! ($d->roles == 2) ? '<div class="badge badge-success">Product</div>' : '<div class="badge badge-danger">Non-Product</div>' !!}</td>
                                                <td>{{ $d->title }}</td>
                                                <td>{{ number_format($d->sale,2,'.',',') }}</td>
                                                <td><form method="POST" action="{{ url('discount/'.$d->id) }}">@csrf @method('delete')<button onclick="event.preventDefault();(confirm('Data Diskon Terpilih Akan Dihapus ? ') ? this.parentNode.submit() : event.preventDefault() );" type="submit" class="btn btn-danger" title="Hapus"><i class="fas fa-trash"></i></button></form></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
