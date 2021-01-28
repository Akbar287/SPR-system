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
            <div class="col-sm-12 col-md-10 col-lg-10 col-xl-10">
                @if(session('status'))
                {!!session('status')!!}
                @endif
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Daftar Toko</h4>
                        <div class="card-header-action">
                            <a href="{{url('/market/create')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Toko</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(count($markets) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <th>#</th>
                                    <th>Reseller</th>
                                    <th>Nama Toko</th>
                                    <th>Pemilik</th>
                                    <th>Status</th>
                                    <th>Detail</th>
                                </thead>
                                <tbody>
                                    @foreach($markets as $market)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $market->reseller }}</td>
                                        <td>{{ $market->name }}</td>
                                        <td>{{ $market->owner }}</td>
                                        <td>{!! ($market->is_active == 1) ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>' !!}</td>
                                        <td><a style="border-radius: 20px;" href="{{ url('/market/'.$market->id) }}" class="btn btn-outline-success" ><i class="fas fa-pen"></i> Detail</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center"><p>Belum Ada Toko</p></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
