@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url('/materials')}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/materials')}}">Bahan Mentah</a></div>
            <div class="breadcrumb-item active">{{ $title }}</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-10 col-lg-10 col-xl-10">
                <div class="card card-primary shadow">
                    <div class="card-header">
                        <h4>{{ $title }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{url('/materials')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-sm-12 col-md-4 col-lg-4">
                                    <img src="" class="rounded mx-auto img-thumbnail img-responsive" id="img-material" alt="" style="display: block;">
                                    <div class="input-group mt-3 mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputFileImagematerial">Upload</span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="imgmaterial" id="inputFileImagematerial" onchange="document.getElementById('img-material').src = window.URL.createObjectURL(this.files[0])">
                                            <label class="custom-file-label" for="inputFileImagematerial">Pilih Foto</label>
                                        </div>
                                    </div>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="is_active" class="custom-control-input" id="activated">
                                        <label class="custom-control-label" for="activated">Aktifkan Bahan Mentah Ini</label>
                                    </div>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="is_rewrite" class="custom-control-input" id="rewrite">
                                        <label class="custom-control-label" for="rewrite">Kurangi Otomatis Saat Stok Ditambah</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-8 col-lg-8">
                                    <div class="form-group">
                                        <label>Nama Bahan</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-luggage-cart"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" name="name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Stok</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-sort-numeric-up"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock') }}" name="stock">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Harga / Unit</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    Rp
                                                </div>
                                            </div>
                                            <input type="text" class="form-control currency @error('price') is-invalid @enderror" value="{{ old('price') }}" name="price">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Deskripsi</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}" style="height: 150px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-5">
                                    <button type="submit" style="border-radius: 20px;" class="btn btn-success"><i class="fas fa-pen"></i> Tambahkan</button>
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
