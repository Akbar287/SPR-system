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
                        <h4>Daftar Pemasok</h4>
                        <div class="card-header-action">
                            <a href="{{url('/supplier/create')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah pemasok</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(count($suppliers) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Phone</th>
                                    <th>Aktif</th>
                                    <th>Detail</th>
                                </thead>
                                <tbody>
                                    @foreach($suppliers as $supplier)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $supplier->name }}</td>
                                        <td>{{ $supplier->phone }}</td>
                                        <td>{!! ($supplier->is_active == 1) ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>' !!}</td>
                                        <td><a style="border-radius: 20px;" href="{{ url('/supplier/'.$supplier->id) }}" class="btn btn-outline-success" ><i class="fas fa-pen"></i> Detail</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center"><p>Belum Ada Pemasok</p></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
