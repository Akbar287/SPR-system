@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url('/expense')}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/financial')}}">Keuangan</a></div>
            <div class="breadcrumb-item"><a href="{{url('/expense')}}">Beban</a></div>
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
                            <h4>Periode {{$time}}</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{url('/expense')}}" method="POST">@csrf
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
                                            <select name="ref" id="ref" class="form-control">
                                                @if(!empty($refbeban)) @foreach($refbeban as $ref)
                                                <option value="{{$ref->ref}}">{{$ref->name}}</option>
                                                @endforeach @endif
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
                                            <input type="text" placeholder="Nominal" name="nominal" class="form-control currency @error('nominal') is-invalid @enderror" id="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4 mt-2">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-pen"></i> Buat</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
