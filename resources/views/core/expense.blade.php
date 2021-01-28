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
                        <h4>{{$title}}</h4>
                        <div class="card-header-action">
                            <a href="{{url('/expense/create')}}" class="btn btn-primary" title="Masukan Data Beban"><i class="fas fa-pen"></i> Buat</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{url('/expense')}}" method="GET">
                            <div class="row justify-content-center text-center">
                                <div class="col-sm-12 col-md-6 col-lg-4 mt-2">
                                    <select name="s1" class="form-control">@for($i=1;$i<=12;$i++) <option value="{{$i}}">{{ $month[$i] }}</option> @endfor</select>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4 mt-2">
                                    <select name="s2" class="form-control">@for($i=$year['0'];$i<=$year['1'];$i++) <option value="{{$i}}">{{ $i }}</option> @endfor</select>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4 mt-2">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Lihat</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @if(!is_null($expense))
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Hasil</h4>
                    </div>
                    <div class="card-body">
                        @if(!empty($expense))
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <th>#</th>
                                    <th>Beban</th>
                                    <th>Total</th>
                                    <th>Detail</th>
                                </thead>
                                <tbody>
                                    @foreach($expense as $e)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$e['name']}}</td>
                                        <td>Rp. {{number_format($e['total'],2,'.',',')}}</td>
                                        <td><a href="{{url('/expense/'.$e['id'])}}" class="btn btn-primary"><i class="fas fa-info"></i></a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="row justify-content-center">
                            <div class="col-6 text-center">
                                <p>Tidak Ada Data</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
