@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url('/financial')}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/financial')}}">Keuangan</a></div>
            <div class="breadcrumb-item active">{{ $title }}</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-lg-10 col-md-10 col-xl-8">
                <form action="{{ url('accounting') }}" method="POST"> @csrf
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Tambah Akuntansi</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="type">Jenis</label>
                                        <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                                            <option value="1">Aset</option>
                                            <option value="2">Liabilitas</option>
                                            <option value="3">Ekuitas</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="ref">No. Referensi</label>
                                        <input type="number" id="ref" name="ref" placeholder="123" class="form-control @error('ref') is-invalid @enderror">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" id="name" placeholder="Masukan Nama Akun cth: kas" name="name" class="form-control @error('name') is-invalid @enderror">
                            </div>
                            <div class="form-group">
                                <label for="description">Deskripsi</label>
                                <textarea id="description" style="height: 150px;" class="form-control @error('description') is-invalid @enderror" name="description"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-sm-8 col-md-6 col-lg-4 col-xl-3">
                                    <button class="btn btn-success" style="border-radius: 20px;" type="submit"><i class="fas fa-check"></i> Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
