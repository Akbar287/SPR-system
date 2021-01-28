@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url('/supplier')}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/supplier')}}">Pemasok</a></div>
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
                        <form action="{{url('/supplier/'.$supplier->id)}}" method="POST" enctype="multipart/form-data">
                            @csrf @method('patch')
                            <div class="row justify-content-center">
                                <div class="col-sm-12 col-md-4 col-lg-4">
                                    <img src="{{ url('/img/vendor/'.$supplier->image) }}" class="rounded mx-auto img-thumbnail img-responsive" id="img-supplier" alt="{{ $supplier->image }}">
                                    <div class="input-group mt-3 mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputFileImagesupplier">Upload</span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="imgsupplier" id="inputFileImagesupplier" onchange="document.getElementById('img-supplier').src = window.URL.createObjectURL(this.files[0])">
                                            <label class="custom-file-label" for="inputFileImagesupplier">Pilih Foto</label>
                                        </div>
                                    </div>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" {{ ($supplier->is_active ==1 ) ? 'checked' : '' }} name="is_active" class="custom-control-input" id="activated">
                                        <label class="custom-control-label" for="activated">Aktifkan Pemasok Ini</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-8 col-lg-8">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ $supplier->name }}" name="name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-phone"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ $supplier->phone }}" name="phone">
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
                                            <input type="text" class="form-control @error('address') is-invalid @enderror" value="{{ $supplier->address }}" name="address">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Deskripsi</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" style="height: 150px;">{{$supplier->description }}</textarea>
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
