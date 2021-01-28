@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url('/products')}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/product')}}">Produk</a></div>
            <div class="breadcrumb-item active">{{ $title }}</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-10 col-lg-10 col-xl-10">
                <div class="card card-primary shadow">
                    <div class="card-header">
                        <h4>Buat Produk</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{url('/products')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-center">
                                @if(empty($categories))
                                <div class="alert alert-danger" role="alert">
                                    <strong>Peringatan</strong>, Kamu harus membuat kategori produk di halaman Kategori <a href="/category">Kategori</a>!
                                </div>
                                @endif
                                <div class="col-sm-12 col-md-4 col-lg-4">
                                    <img src="" class="rounded mx-auto img-thumbnail img-responsive" id="img-products" alt="" style="display: block;">
                                    <div class="input-group mt-3 mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputFileImageproducts">Upload</span>
                                        </div>
                                        <div class="custom-file">
                                            <input {{ (empty($categories)) ? 'disabled' : '' }} type="file" class="custom-file-input" name="imgproducts" id="inputFileImageproducts" onchange="document.getElementById('img-products').src = window.URL.createObjectURL(this.files[0])">
                                            <label class="custom-file-label" for="inputFileImageproducts">Pilih Foto</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Harga</label>
                                        <input type="text" class="form-control currency @error('price') is-invalid @enderror" value="{{ old('price') }}" name="price">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-8 col-lg-8">
                                    <div class="form-group">
                                        <label>Judul</label>
                                        <input type="text" {{ (empty($categories)) ? 'disabled' : '' }} class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" name="title">
                                    </div>
                                    <div class="form-group">
                                        <label>Kategori</label>
                                        <select name="category" {{ (empty($categories)) ? 'disabled' : '' }} class="form-control @error('category') is-invalid @enderror">
                                            @if(!empty($categories))
                                            @foreach($categories as $category)
                                            <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                                            @endforeach
                                            @else
                                            <option value="">No Action</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Bahan Mentah</label>
                                        <select multiple name="materials[]" {{ (empty($materials)) ? 'disabled' : '' }} class="form-control materialProduct @error('materials') is-invalid @enderror">
                                            @if(!empty($materials))
                                            @foreach($materials as $material)
                                            <option value="{{ $material['id'] }}">{{ $material['name'] }}</option>
                                            @endforeach
                                            @else
                                            <option value="">No Action</option>
                                            @endif
                                        </select>
                                        <span style="font-style:italic;color: red;">@error('materials') {{$message}} @enderror</span>
                                    </div>
                                    <div class="form-group">
                                        <label>Deskripsi</label>
                                        <textarea {{ (empty($categories)) ? 'disabled' : '' }} name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}" style="height: 150px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4 col-lg-4 col-xl-3">
                                    <button type="submit" {{ (empty($categories)) ? 'disabled' : '' }} style="border-radius: 20px;" class="btn btn-success"><i class="fas fa-pen"></i> Buat</button>
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
