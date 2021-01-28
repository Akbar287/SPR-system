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
        <h2 class="section-title">Bahasa</h2>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-body">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td><p>Bahasa</p></td>
                                    <td><select name="" class="form-control" id="" disabled><option value="id">Indonesia</option></select></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <h2 class="section-title">Reset</h2>
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card card-danger">
                    <div class="card-body text-center">
                        <form action="{{url('/settings/reset')}}" name="dReset" method="POST">@csrf<button onclick="event.preventDefault();(confirm('Segala transaksi akan dihapus ? ') ? document.dReset.submit(): event.preventDefault() );" type="button" class="btn btn-danger btn-lg"><i class="fas fa-eraser"></i> Reset</button></form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
