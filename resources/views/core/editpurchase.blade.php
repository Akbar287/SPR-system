@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url('/purchase')}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/financial')}}">Keuangan</a></div>
            <div class="breadcrumb-item"><a href="{{url('/purchase')}}">Pembelian</a></div>
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
                                <h2>Rp. {{number_format($purchase->total,2,'.',',')}}</h2>
                                <p class="lead">Total Pembelian Catatan Ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-10 col-lg-10">
                        <div class="card card-primary shadow" style="margin-top: -30px;">
                            <div class="card-header">
                                <h4>Ubah Catatan Pembelian</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{url('purchase/'.$purchase->id)}}" method="POST" enctype="multipart/form-data"> @csrf @method('patch')
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="outcome">Biaya</label>
                                                <input type="text" name="purchase" class="currency form-control @error('purchase') is-invalid @enderror" value="{{ $purchase->price }}" id="outcome" >
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="discount">Diskon</label>
                                                <input type="text" name="discount" class="currency form-control @error('discount') is-invalid @enderror" value="{{ $purchase->discount }}" id="discount" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="vendor">Pemasok</label>
                                                <select name="vendor" id="vendor" class="form-control @error('vendor') is-invalid @enderror">
                                                    @if(!empty($vendors))
                                                    @foreach($vendors as $vendor)
                                                    <option value="{{$vendor->id}}" {{ ($purchase->id_vendor == $vendor->id) ? 'selected' : '' }}>{{$vendor->name}}</option>
                                                    @endforeach
                                                    @else
                                                    <option value="">No Option</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="material">Bahan Mentah</label>
                                                <select name="material" id="material" class="form-control @error('material') is-invalid @enderror">
                                                    @if(!empty($materials))
                                                    @foreach($materials as $material)
                                                    <option value="{{$material->id}}" {{ ($purchase->id_material == $material->id) ? 'selected' : '' }}>{{$material->name}}</option>
                                                    @endforeach
                                                    @else
                                                    <option value="">No Option</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <img src="{{ url('/img/purchase/'.$purchase->image) }}" alt="{{ $purchase->image }}" class="img-thumbnail img-responsive" id="img-purchase" style="width: 300px;height: 200px;">
                                        <div class="input-group mt-3 mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputFileImagepurchase">Upload</span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="imgpurchase" id="inputFileImagepurchase" onchange="document.getElementById('img-purchase').src = window.URL.createObjectURL(this.files[0])">
                                                <label class="custom-file-label" for="inputFileImagepurchase">Pilih Nota</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="stock">Kuantitas</label>
                                                <input type="number" name="stock" value="{{ $purchase->stock }}" class="form-control @error('num') is-invalid @enderror" id="stock" >
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="notanum">Nomor Nota</label>
                                                <input type="text" name="nota" value="{{ $purchase->invoice }}" class="form-control @error('nota') is-invalid @enderror" id="notanum" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="debit">Debet</label>
                                                <select name="debit" id="debit" class="form-control">
                                                    @foreach($ref as $r)
                                                    <option value="{{ $r->ref }}" {{($r->ref == $purchase->refdebit) ? 'selected' : ''}}>{{$r->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="credit">Kredit</label>
                                                <select name="credit" id="credit" class="form-control">
                                                    @foreach($ref as $r)
                                                    <option value="{{ $r->ref }}" {{($r->ref == $purchase->refcredit) ? 'selected' : ''}}>{{$r->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="desc">Deskripsi</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" style="height: 150px;" id="desc" cols="30" rows="10" placeholder="(Wajib Diisi) Catatan Pembelian">{{ $purchase->description }}</textarea>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-sm-12 col-md-8 col-lg-6 col-xl-6">
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-pen"></i> Ubah Data Pengeluaran</button>
                                            <button onclick="event.preventDefault();(confirm('Data Pembelian Akan dihapus ? ') ? document.getElementById('dPurchase').submit() : event.preventDefault() );" type="button" style="border-radius: 20px;" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                                            <a href="{{ url()->previous() }}" type="button" style="border-radius: 20px;" class="btn btn-light"><i class="fas fa-times"></i> Batalkan</a>
                                        </div>
                                    </div>
                                </form><form action="{{ url('/purchase/' . $purchase->id) }}" id="dPurchase" method="POST">@csrf @method('delete')</form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
