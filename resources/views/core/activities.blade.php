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
            <div class="col-sm-12 col-md-12 col-lg-10 col-xl-10">
                <div class="card card-primary">
                    <div class="card-body text-center">
                        <div class="table-responsive">
                        <table class="table table-hover" id="activityTable">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Waktu</th>
                                    <th>Ikon</th>
                                    <th>Aktivitas</th>
                                    <th>Deskripsi</th>
                                    <th>Alamat IP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activities as $activity)
                                <tr>
                                    <td>{{ $activity->created_at }}</td>
                                    <td><i class="{!! $activity->icon !!}"></i></td>
                                    <td>{{ $activity->title }}</td>
                                    <td>{{ $activity->description }}</td>
                                    <td>{{ $activity->ip_address }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
