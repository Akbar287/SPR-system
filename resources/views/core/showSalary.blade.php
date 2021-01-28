@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url('/salary')}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/financial')}}">Keuangan</a></div>
            <div class="breadcrumb-item"><a href="{{url('/salary')}}">Gaji</a></div>
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
                            <h4>Periode {{$gaji->created_at}}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{url('/salary/'.$gaji->id)}}" method="POST">@csrf @method('patch')
                            <div class="row justify-content-center text-center">
                                <div class="col-12">
                                    <h5>Kas : Rp. {{number_format($balance->description,2,'.',',')}}</h5>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-key"></i>
                                                </div>
                                            </div>
                                            <select name="user" class="form-control" readonly id="">
                                                <option value="{{$gaji->id_user}}">{{$gaji->name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    Rp.
                                                </div>
                                            </div>
                                            <input type="text" value="{{$gaji->total}}" placeholder="Nominal" name="nominal" class="form-control currency" id="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6 mt-2">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-pen"></i> Ubah</button>
                                    <button onclick="event.preventDefault();(confirm('Data Gaji akan dihapus ? ') ? document.getElementById('dGaji').submit() : event.preventDefault() );" type="button" style="border-radius: 20px;" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                                    <a href="{{ url()->previous() }}" type="button" style="border-radius: 20px;" class="btn btn-light"><i class="fas fa-times"></i> Batalkan</a>
                                    {{-- <a href="{{url('salary/'.$gaji->id.'/print')}}" class="btn btn-success"><i class="fas fa-print"></i> Cetak Slip Gaji</a> --}}
                                </div>
                            </div>
                        </form><form action="{{ url('/salary/' . $gaji->id) }}" id="dGaji" method="POST">@csrf @method('delete')</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
