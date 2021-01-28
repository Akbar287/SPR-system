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
            <div class="col-sm-12 col-md-12">
                @if(session('status'))
                {!!session('status')!!}
                @endif
                <div class="row justify-content-center">
                    <div class="col-sm-12 col-md-10 col-lg-10">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Pesanan</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{url('/pesanan')}}" method="GET">@csrf
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Reseller</label>
                                                <select name="reseller" class="form-control">
                                                    @if(!is_null($resellers))
                                                    <option value="a">Semua Reseller</option>
                                                    <option value="b">Semua Reseller Produk</option>
                                                    <option value="c">Semua Reseller Non-Produk</option>
                                                    @foreach($resellers as $r)
                                                    <option value="{{$r->id}}">{{$r->name}} - {{($r->roles == 2) ? 'Produk' : 'Non-Produk'}}</option>
                                                    @endforeach
                                                    @else
                                                    <option value="Error">No Option</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Toko</label>
                                                <select name="market" class="form-control">
                                                    @if(!is_null($markets))
                                                    <option value="a">Semua Toko</option>
                                                    @foreach($markets as $market)
                                                    <option value="{{$market->id}}">{{$market->name}}</option>
                                                    @endforeach
                                                    @else
                                                    <option value="Error">No Option</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select name="status" class="form-control">
                                                    <option value="a">Semua Status</option>
                                                    @if(!is_null($stats))
                                                    @foreach($stats as $s)
                                                    <option value="{{$loop->iteration - 1}}">{{$s}}</option>
                                                    @endforeach
                                                    @else
                                                    <option value="Error">No Option</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Waktu</label>
                                                <select name="time" class="form-control">
                                                    @if(!is_null($option))
                                                    @for($i=0;$i < count($option['0']);$i++)
                                                    <option value="{{$option['0'][$i]}}">{{$option['1'][$i]}}</option>
                                                    @endfor
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-3 text-center"><button class="btn btn-primary" type="submit"><i class="fas fa-check"></i> Cari Order</button></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @if(!empty($orders))
                <div class="row justify-content-center">
                    <div class="col-sm-12 col-md-10 col-lg-10">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Pesanan</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-hover">
                                    <thead>
                                        <th>#</th>
                                        <th>Waktu</th>
                                        <th>Reseller</th>
                                        <th>Toko</th>
                                        <th>Total Harga</th>
                                        <th>Status</th>
                                        <th>Detail</th>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$order->created_at}}</td>
                                            <td>{{$order->reseller}}</td>
                                            <td>{{$order->market}}</td>
                                            <td>{{number_format($order->total_price,2,'.',',')}}</td>
                                            <td>{!! $status[$order->status] !!}</td>
                                            <td><a href="{{url('cart/status/'.$order->id)}}" class="btn btn-primary" title="Detail"><i class="fas fa-info"></i></a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
