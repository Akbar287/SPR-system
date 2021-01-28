@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item active">{{ $title }}</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-10 col-lg-10">
                @if(session('status'))
                {!!session('status')!!}
                @endif
                <div class="card card-primary mt-1">
                    <div class="card-header">
                        <h4 class="card-title">Ubah Profil</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{url('/profile')}}" id="profileUsers" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-sm-12 col-md-4 col-lg-4">
                                    <img src="{{ url('/img/profil/'. $data->image) }}" class="rounded mx-auto img-thumbnail img-responsive" alt="{{ $data->image }}">
                                    <div class="input-group mt-3 mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputFileImage">Upload</span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="image" id="inputFileImage">
                                            <label class="custom-file-label" for="inputFileImage">Pilih Foto</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-8 col-lg-6">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ $data->name }}" name="name">
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Username</label>
                                                <input type="text" class="form-control @error('username') is-invalid @enderror" readonly value="{{ $data->username }}" name="username">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Phone</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-phone"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ $data->phone }}" name="phone">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-envelope"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control @error('email') is-invalid @enderror" value="{{ $data->email }}" name="email">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <select name="gender" class="form-control">
                                                    <option value="1" {{ ($data->gender == 1) ? 'selected' : '' }}>Pria</option>
                                                    <option value="2" {{ ($data->gender == 2) ? 'selected' : '' }}>Wanita</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Religi</label>
                                                <select name="religion" class="form-control">
                                                    <option value="1" {{ ($data->religion == 1) ? 'selected' : '' }}>Islam</option>
                                                    <option value="2" {{ ($data->religion == 2) ? 'selected' : '' }}>Kristen</option>
                                                    <option value="3" {{ ($data->religion == 3) ? 'selected' : '' }}>Hindu</option>
                                                    <option value="4" {{ ($data->religion == 4) ? 'selected' : '' }}>Buddha</option>
                                                    <option value="5" {{ ($data->religion == 5) ? 'selected' : '' }}>Konghucu</option>
                                                    <option value="6" {{ ($data->religion == 6) ? 'selected' : '' }}>Lain-lain</option>
                                                </select>
                                            </div>
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
                                            <input type="text" class="form-control @error('address') is-invalid @enderror" value="{{ $data->address }}" name="address">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-key"></i>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control @error('password') is-invalid @enderror" placeholder="Isi Jika Ingin Diganti" name="password">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <button type="submit" class="btn btn-block btn-success mt-1"><i class="fas fa-pen"></i> Simpan</button>
                                        </div>
                                        <div class="col-6">
                                            <a href="{{url('/home')}}" type="button" class="btn btn-block btn-primary mt-1"><i class="fas fa-times"></i> Kembali</a>
                                        </div>
                                    </div>
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
