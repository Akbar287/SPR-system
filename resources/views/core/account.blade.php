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
        @if(session('status'))
            {!!session('status')!!}
        @endif
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-4 col-lg-4">
                {{-- <div class="card-header-action">
                    <a href="{{ url('accounting/create') }}" class="btn btn-primary" title="Buat"><i class="fas fa-plus"></i></a>
                </div> --}}
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Aset</h4>
                    </div>
                    <div class="card-body">
                        @if(!empty($asset))
                        <table class="table table-hover">
                            <thead>
                                <th>Ref</th>
                                <th>Nama</th>
                            </thead>
                            <tbody>
                                @foreach($asset as $a)
                                <tr>
                                    <td>{{ $a->ref }}</td>
                                    <td>{{ $a->name }}</td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p>Tidak Ada Data</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Liabilitas</h4>
                    </div>
                    <div class="card-body">
                        @if(!empty($liabilitas))
                        <table class="table table-hover">
                            <thead>
                                <th>Ref</th>
                                <th>Nama</th>
                            </thead>
                            <tbody>
                                @foreach($liabilitas as $b)
                                <tr>
                                    <td>{{ $b->ref }}</td>
                                    <td>{{ $b->name }}</td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p>Tidak Ada Data</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Ekuitas</h4>
                    </div>
                    <div class="card-body">
                        @if(!empty($equitas))
                        <table class="table table-hover">
                            <thead>
                                <th>Ref</th>
                                <th>Nama</th>
                            </thead>
                            <tbody>
                                @foreach($equitas as $e)
                                <tr>
                                    <td>{{ $e->ref }}</td>
                                    <td>{{ $e->name }}</td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p>Tidak Ada Data</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
