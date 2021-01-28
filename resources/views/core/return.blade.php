@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url('cart/status/'.$orders->id)}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{url('/pesanan')}}">Pesanan</a></div>
            <div class="breadcrumb-item"><a href="{{url()->previous()}}">Rincian Pesanan</a></div>
            <div class="breadcrumb-item active">{{ $title }}</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                @if(session('status'))
                {!!session('status')!!}
                @endif
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Order Status</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mt-4">
                            <div class="col-12 col-lg-12">
                                <div class="wizard-steps">
                                    @if($orders->status == 0)
                                    <div class="wizard-step wizard-step-active">
                                        <div class="wizard-step-icon">
                                            <i class="fas fa-pen"></i>
                                        </div>
                                        <div class="wizard-step-label">
                                            Order Dibuat
                                        </div>
                                    </div>
                                    <div class="wizard-step wizard-step-danger">
                                        <div class="wizard-step-icon">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div class="wizard-step-label">
                                            Order Dibatalkan
                                        </div>
                                    </div>
                                    @else
                                    <div class="wizard-step wizard-step-{{($orders->status >= 1) ? 'success' : 'active'}}">
                                        <div class="wizard-step-icon">
                                            <i class="fas fa-pen"></i>
                                        </div>
                                        <div class="wizard-step-label">
                                            Order Dibuat
                                        </div>
                                    </div>
                                    <div class="wizard-step wizard-step-{{($orders->status >= 2) ? 'success' : 'active'}}">
                                        <div class="wizard-step-icon">
                                            <i class="fas fa-box"></i>
                                        </div>
                                        <div class="wizard-step-label">
                                            Produk Siap Diambil
                                        </div>
                                    </div>
                                    <div class="wizard-step wizard-step-{{($orders->status >= 3) ? 'success' : 'active'}}">
                                        <div class="wizard-step-icon">
                                            <i class="fas fa-shipping-fast"></i>
                                        </div>
                                        <div class="wizard-step-label">
                                            Produk Dibawa Reseller
                                        </div>
                                    </div>
                                    <div class="wizard-step wizard-step-{{($orders->status >= 4) ? 'success' : 'active'}}">
                                        <div class="wizard-step-icon">
                                            <i class="fas fa-map-marked-alt"></i>
                                        </div>
                                        <div class="wizard-step-label">
                                            Produk Sampai DiTujuan
                                        </div>
                                    </div>
                                    <div class="wizard-step wizard-step-{{($orders->status >= 5) ? 'success' : 'active'}}">
                                        <div class="wizard-step-icon">
                                            <i class="fas fa-dollar-sign"></i>
                                        </div>
                                        <div class="wizard-step-label">
                                            Pembayaran Diterima
                                        </div>
                                    </div>
                                    <div class="wizard-step wizard-step-{{($orders->status == 6) ? 'success' : 'active'}}">
                                        <div class="wizard-step-icon">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="wizard-step-label">
                                            Order Selesai
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-10 col-lg-10">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Apa yang Dikembalikan</h4>
                    </div>
                    <div class="card-body">
                        @if(!empty($product))
                        <form action="{{url('/cart/status/'.$orders->id.'/return')}}" method="POST">@csrf
                            <div class="row justify-content-center">
                                <div class="col-sm-12 col-md-10">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Produk</th>
                                                    <th>Kuantitas</th>
                                                    <th>Opsi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @for($i=0;$i < count($product);$i++)
                                                <tr>
                                                    <input type="hidden" name="r0[]" value="{{$product[$i]['id']}}">
                                                    <td>{{$i+1}}</td>
                                                    <td> <input type="text" name="r1[]" class="form-control" readonly value="{{$product[$i]['title']}}"></td>
                                                    <td>
                                                        <select name="r2[]" class="form-control" id="">
                                                            @for($j=0;$j<=$product[$i]['quantity'];$j++)
                                                            <option value="{{$j}}">{{$j}}</option>
                                                            @endfor
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="r3[]" class="form-control" id="">
                                                            <option value="1">Jual Kembali</option>
                                                            <option value="2">Buang / Hancurkan</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center mt-3">
                                <div class="col-12 text-center">
                                    <a href="{{url('cart/status/'.$orders->id)}}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Kembali</a>
                                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Return</button>
                                </div>
                            </div>
                        </form>
                            @else
                            <p>Tidak Ada Data</p>
                            @endif
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
