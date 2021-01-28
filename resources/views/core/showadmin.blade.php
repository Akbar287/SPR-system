@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url('/admin')}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/admin')}}">Admin</a></div>
            <div class="breadcrumb-item active">{{ $title }}</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-10 col-lg-10 col-xl-10">
                <div class="card card-primary shadow">
                    <div class="card-header">
                        <h4>Tambah Admin</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{url('/admin/'.$admin->id)}}" method="POST" enctype="multipart/form-data">
                            @csrf @method('patch')
                            <div class="row justify-content-center">
                                <div class="col-sm-12 col-md-4 col-lg-4">
                                    <img src="{{ url('/img/profil/'. $admin->image) }}" class="rounded mx-auto img-thumbnail img-responsive" id="img-admin">
                                    <div class="input-group mt-3 mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputFileImageadmin">Upload</span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="imgadmin" id="inputFileImageadmin" onchange="document.getElementById('img-admin').src = window.URL.createObjectURL(this.files[0])">
                                            <label class="custom-file-label" for="inputFileImageadmin">Pilih Foto</label>
                                        </div>
                                    </div>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" {{ ($admin->is_active == 1) ? 'checked' : '' }} name="is_active" class="custom-control-input" id="activated">
                                        <label class="custom-control-label" for="activated">Aktifkan Admin</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-8 col-lg-8">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ $admin->name }}" name="name">
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-envelope"></i>
                                                </div>
                                            </div>
                                            <input type="text" readonly class="form-control @error('email') is-invalid @enderror" value="{{ $admin->email }}" placeholder="Masukan Email Aktif" name="email">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Username</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control @error('username') is-invalid @enderror" placeholder="Masukan Username" value="{{ $admin->username }}" name="username">
                                                </div>
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
                                                    <input type="text" placeholder="628XX" class="form-control @error('phone') is-invalid @enderror" value="{{ $admin->phone }}" name="phone">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <select name="gender" class="form-control">
                                                    <option value="1" {{($admin->gender == 1) ? 'selected' : ''}}>Pria</option>
                                                    <option value="2" {{($admin->gender == 1) ? 'selected' : ''}}>Wanita</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Religi</label>
                                                <select name="religion" class="form-control">
                                                    <option value="1" {{($admin->religion == 1) ? 'selected' : ''}} >Islam</option>
                                                    <option value="2" {{($admin->religion == 2) ? 'selected' : ''}} >Kristen</option>
                                                    <option value="3" {{($admin->religion == 3) ? 'selected' : ''}} >Hindu</option>
                                                    <option value="4" {{($admin->religion == 4) ? 'selected' : ''}} >Buddha</option>
                                                    <option value="5" {{($admin->religion == 5) ? 'selected' : ''}} >Konghucu</option>
                                                    <option value="6" {{($admin->religion == 6) ? 'selected' : ''}} >Lain-lain</option>
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
                                            <input type="text" class="form-control @error('address') is-invalid @enderror" value="{{ $admin->address }}" name="address">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <button type="submit" style="border-radius: 20px;" class="btn btn-success"><i class="fas fa-pen"></i> Ubah</button>
                                        <a href="{{ url()->previous() }}" type="button" style="border-radius: 20px;" class="btn btn-light"><i class="fas fa-times"></i> Batalkan</a>
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
