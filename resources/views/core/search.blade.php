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
            <div class="col-sm-12 col-md-10 col-lg-10 col-xl-10">
                @if(session('status'))
                {!!session('status')!!}
                @endif
                <form action="{{url('/search')}}" method="GET">
                    <div class="search-element">
                        <div class="form-group">
                            <input autocomplete="off" autofocus type="text" class="form-control mb-3" name="q" placeholder="{{$data['q']}}">
                            <button class="btn" type="submit" style="border-radius: 0 3px 3px 0!important;"><i class="fas fa-search"></i></button>
                        </div>
                        <div class="search-backdrop"></div>
                    </div>
                </form>
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Cari...</h4>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
