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
                        <h5 class="card-title">Ubah Informasi</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{url('/products/'.$products->id)}}" method="POST" enctype="multipart/form-data">
                            @csrf @method('patch')
                            <div class="row justify-content-center">
                                <div class="col-sm-12 col-md-4 col-lg-4">
                                    <img src="{{ url('img/products/'. $products->cover) }}" class="rounded mx-auto img-thumbnail img-responsive" id="img-products" alt="">
                                    <div class="input-group mt-3 mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputFileImageproducts">Upload</span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="imgproducts" id="inputFileImageproducts" onchange="document.getElementById('img-products').src = window.URL.createObjectURL(this.files[0])">
                                            <label class="custom-file-label" for="inputFileImageproducts">Pilih Foto</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Harga</label>
                                        <input type="text" class="form-control currency @error('price') is-invalid @enderror" value="{{ $products->price }}" name="price">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-8 col-lg-8">
                                    <div class="form-group">
                                        <label>Judul</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" value="{{ $products->title }}" name="title">
                                    </div>
                                    <div class="form-group">
                                        <label>Kategori</label>
                                        <select name="category" {{ (empty($categories)) ? 'disabled' : '' }} class="form-control @error('category') is-invalid @enderror">
                                            @if(!empty($categories))
                                            @foreach($categories as $category)
                                            <option value="{{ $category['id'] }}" {{ ($sc->category_id == $category['id']) ? 'selected' : '' }} >{{ $category['name'] }}</option>
                                            @endforeach
                                            @else
                                            <option value="">No Action</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Bahan Mentah</label>
                                        <select multiple name="materials[]" class="form-control materialProduct @error('materials') is-invalid @enderror">
                                            @if(!empty($materials))
                                            @foreach($materials as $material)
                                            <option value="{{ $material['id'] }}" @foreach($sm as $s){{ ($material['id'] == $s['id_material']) ? 'selected' : '' }}@endforeach>{{ $material['name'] }}</option>
                                            @endforeach
                                            @else
                                            <option value="">No Action</option>
                                            @endif
                                        </select>
                                        <span style="font-style:italic;color: red;">@error('materials') {{$message}} @enderror</span>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" style="height: 150px;">{{ $products->description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-8 col-lg-7 col-xl-5">
                                    <button type="submit" style="border-radius: 20px;" class="btn btn-success"><i class="fas fa-pen"></i> Ubah</button>
                                    <button onclick="event.preventDefault();(confirm('Produk Akan dihapus ? ') ? document.getElementById('deleteproducts').submit() : event.preventDefault() );" type="button" style="border-radius: 20px;" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                                    <a href="{{ url('/products') }}" type="button" style="border-radius: 20px;" class="btn btn-light"><i class="fas fa-times"></i> Batalkan</a>
                                </div>
                            </div>
                        </form><form action="{{ url('/products/' . $products->id) }}" id="deleteproducts" method="POST">@csrf @method('delete')</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
