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
            <div class="col-sm-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Tentang</h4>
                    </div>
                    <div class="card-body">
                        <h5>Will Be Success Fish</h5>
                        <p>Dibuat oleh Muhammad Akbar</p>
                        <p>Untuk Will Be Success Fish</p>
                        <p>Versi Beta</p>
                        <p>&copy; 2020</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
