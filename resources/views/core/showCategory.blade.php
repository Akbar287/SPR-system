@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url('/product')}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
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
                        <h5 class="card-title">Ubah Kategori</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{url('/category/'.$category->id)}}" method="POST" enctype="multipart/form-data">
                            @csrf @method('patch')
                            <div class="row justify-content-center">
                                <div class="col-sm-12 col-md-4 col-lg-4">
                                    <img src="{{ url('img/category/'. $category->image) }}" class="rounded mx-auto img-thumbnail img-responsive" id="img-category" alt="">
                                    <div class="input-group mt-3 mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputFileImageCategory">Upload</span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="imgCategory" id="inputFileImageCategory" onchange="document.getElementById('img-category').src = window.URL.createObjectURL(this.files[0])">
                                            <label class="custom-file-label" for="inputFileImageCategory">Pilih Foto</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-8 col-lg-8">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ $category->name }}" name="name">
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" style="height: 150px;">{{ $category->description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-8 col-lg-7 col-xl-5">
                                    <button type="submit" style="border-radius: 20px;" class="btn btn-success"><i class="fas fa-pen"></i> Ubah</button>
                                    <button onclick="event.preventDefault();(confirm('Kategori Akan dihapus ? ') ? document.getElementById('deleteCategory').submit() : event.preventDefault() );" type="button" style="border-radius: 20px;" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                                    <a href="{{ url()->previous() }}" type="button" style="border-radius: 20px;" class="btn btn-light"><i class="fas fa-times"></i> Batalkan</a>
                                </div>
                            </div>
                        </form><form action="{{ url('/category/' . $category->id) }}" id="deleteCategory" method="POST">@csrf @method('delete')</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
