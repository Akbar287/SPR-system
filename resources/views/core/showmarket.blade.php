@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url('/market')}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/market')}}">Toko</a></div>
            <div class="breadcrumb-item active">{{ $title }}</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-10 col-lg-10 col-xl-10">
                <div class="card card-primary shadow">
                    <div class="card-header">
                        <h4>{{$title}}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{url('/market/'.$market->id)}}" method="POST" enctype="multipart/form-data">
                            @csrf @method('patch')
                            <div class="row justify-content-center">
                                <div class="col-sm-12 col-md-4 col-lg-4">
                                    <img src="{{ url('/img/market/'.$market->image) }}" class="rounded mx-auto img-thumbnail img-responsive" id="img-market" alt="{{ $market->image }}">
                                    <div class="input-group mt-3 mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputFileImagemarket">Upload</span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="imgmarket" id="inputFileImagemarket" onchange="document.getElementById('img-market').src = window.URL.createObjectURL(this.files[0])">
                                            <label class="custom-file-label" for="inputFileImagemarket">Pilih Foto</label>
                                        </div>
                                    </div>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" {{ ($market->is_active == 1) ? 'checked' : '' }} name="is_active" class="custom-control-input" id="activated">
                                        <label class="custom-control-label" for="activated">Aktifkan Toko Ini</label>
                                    </div>
                                    <div class="form-group mt-2">
                                        <label for="resellerName">Reseller</label>
                                        <select {{ (empty($resellers)) ? 'readonly' : '' }} name="reseller" id="resellerName" class="form-control">
                                            @if(!empty($resellers))
                                            @foreach($resellers as $reseller)
                                            <option value="{{ $reseller->id }}" {{ ($market->id_reseller == $reseller->id) ? 'selected' : '' }}>{{ $reseller->name }}</option>
                                            @endforeach
                                            @else
                                            <option value="">Tidak Ada Reseller</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-8 col-lg-8">
                                    <div class="form-group">
                                        <label>Nama Toko</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-store"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ $market->name }}" name="name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Pemilik</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control @error('owner') is-invalid @enderror" value="{{ $market->owner }}" name="owner">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-map-marked-alt"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control @error('address') is-invalid @enderror" value="{{ $market->address }}" name="address">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Deskripsi</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" value="" style="height: 150px;">{{ $market->description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-5">
                                    <button type="submit" style="border-radius: 20px;" class="btn btn-success"><i class="fas fa-pen"></i> Ubah</button>
                                    <a href="{{ url()->previous() }}" type="button" style="border-radius: 20px;" class="btn btn-light"><i class="fas fa-times"></i> Batalkan</a>
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
