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
                            <a href="{{url('/store/request')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Request Toko</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(count($stores) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <th>#</th>
                                    <th>Nama Toko</th>
                                    <th>Pemilik</th>
                                    <th>Detail</th>
                                </thead>
                                <tbody>
                                    @foreach($stores as $store)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $store->name }}</td>
                                        <td>{{ $store->owner }}</td>
                                        <td><a style="border-radius: 20px;" href="{{ url('/store/'.$store->id) }}" class="btn btn-outline-success" ><i class="fas fa-pen"></i> Detail</a></td>
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
