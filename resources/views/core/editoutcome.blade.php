@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url('/outcome')}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/financial')}}">Keuangan</a></div>
            <div class="breadcrumb-item"><a href="{{url('/outcome')}}">Pengeluaran</a></div>
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
                                <h2>Rp. {{ number_format($outcome->total,2,'.',',') }}</h2>
                                <p class="lead">Total Pengeluaran Catatan Ini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-10 col-lg-10">
                        <div class="card card-primary shadow" style="margin-top: -30px;">
                            <div class="card-header">
                                <h4>Ubah Catatan Pengeluaran</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{url('outcome/'.$outcome->id)}}" method="POST" enctype="multipart/form-data">
                                    @csrf @method('patch')
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="outcome">Biaya</label>
                                                <input type="text" name="outcome" class="currency form-control @error('outcome') is-invalid @enderror" id="outcome" value="{{ $outcome->price }}">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="discount">Diskon</label>
                                                <input type="text" name="discount" class="currency form-control @error('discount') is-invalid @enderror" value="{{ $outcome->discount }}" id="discount" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="debit">Debit</label>
                                                <select name="debit" id="debit" class="form-control @error('debit') is-invalid @enderror">
                                                    @if(!empty($ref))
                                                    @foreach($ref as $r)
                                                    <option value="{{$r->ref}}" {{ ($r->ref == $outcome->refdebit) ? 'selected' : '' }}>{{$r->name}}</option>
                                                    @endforeach
                                                    @else
                                                    <option value="">No Option</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="credit">Kredit</label>
                                                <select name="credit" id="credit" class="form-control @error('credit') is-invalid @enderror">
                                                    @if(!empty($ref))
                                                    @foreach($ref as $r)
                                                    <option value="{{$r->ref}}" {{ ($r->ref == $outcome->refcredit) ? 'selected' : '' }}>{{$r->name}}</option>
                                                    @endforeach
                                                    @else
                                                    <option value="">No Option</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="nota">Nota</label>
                                        <input type="text" value="{{ $outcome->invoice }}" name="nota" class="form-control @error('nota') is-invalid @enderror" id="nota" >
                                    </div>
                                    <div class="form-group">
                                        <img src="{{ url('img/outcome/'.$outcome->image) }}" class="rounded mx-auto img-thumbnail img-responsive" id="img-outcome">
                                        <div class="input-group mt-3 mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="inputFileImageoutcome">Upload</span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="imgoutcome" id="inputFileImageoutcome" onchange="document.getElementById('img-outcome').src = window.URL.createObjectURL(this.files[0])">
                                                <label class="custom-file-label" for="inputFileImageoutcome">Pilih Nota</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Deskripsi</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" style="height: 150px" placeholder="(Wajib Diisi)">{{ $outcome->description }}</textarea>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-sm-12 col-md-8 col-lg-6 col-xl-6">
                                            <button type="submit" class="btn btn-success"><i class="fas fa-pen"></i> Ubah</button>
                                            <button onclick="event.preventDefault();(confirm('Data Pengeluaran Akan dihapus ? ') ? document.getElementById('dOutcome').submit() : event.preventDefault() );" type="button" style="border-radius: 20px;" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                                            <a href="{{ url()->previous() }}" type="button" style="border-radius: 20px;" class="btn btn-light"><i class="fas fa-times"></i> Batalkan</a>
                                        </div>
                                    </div>
                                </form><form action="{{ url('/outcome/' . $outcome->id) }}" id="dOutcome" method="POST">@csrf @method('delete')</form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
