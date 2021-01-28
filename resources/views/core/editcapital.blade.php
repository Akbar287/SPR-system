@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url('/capital')}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/financial')}}">Keuangan</a></div>
            <div class="breadcrumb-item"><a href="{{url('/capital')}}">Modal</a></div>
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
                    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="hero bg-primary text-white">
                            <div class="hero-inner">
                                <h2>{{ $total }}</h2>
                                <p class="lead">Modal Bulan Ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-10 col-lg-10">
                        <div class="card card-primary shadow" style="margin-top: -30px;">
                            <div class="card-header">
                                <h4>Ubah Modal</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{url('capital/'.$data->id)}}" method="POST">@csrf @method('patch')
                                    <div class="form-group">
                                        <label for="capital">Modal</label>
                                        <input type="text" placeholder="Nominal Tunai" name="capital" class="currency form-control" id="capital" value="{{ $data->debitdesc }}">
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="scapital">Debet</label>
                                                <select name="debit" id="scapital" class="form-control">
                                                    @foreach($assets as $asset)
                                                    <option value="{{$asset->ref}}" {{ ($asset->ref == $data->refdebit) ?
                                                    'selected' : '' }}>{{$asset->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="scapital">Kredit</label>
                                                <select name="credit" id="scapital" class="form-control">
                                                    @foreach($assets as $asset)
                                                    <option value="{{$asset->ref}}" {{ ($asset->ref == $data->refcredit) ?
                                                        'selected' : '' }}>{{$asset->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="desc">Deskripsi</label>
                                        <textarea placeholder="(Wajib Diisi) Alasan Menambah Modal" name="description" id="desc" class="form-control @error('description') is_invalid @enderror" cols="30" rows="10" style="height:150px;">{{ $data->description }}</textarea>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-sm-12 col-md-8 col-lg-6 col-xl-6">
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Ubah Data Modal</button>
                                            <button onclick="event.preventDefault();(confirm('Data Modal Akan Dihapus ? ') ? document.getElementById('capitaldeleted').submit() : event.preventDefault() );" type="button" style="border-radius: 20px;" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                                            <a href="{{ url()->previous() }}" type="button" style="border-radius: 20px;" class="btn btn-light"><i class="fas fa-times"></i> Batalkan</a>
                                        </div>
                                    </div>
                                </form><form action="{{ url('/capital/' . $data->id) }}" id="capitaldeleted" method="POST">@csrf @method('delete')</form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
