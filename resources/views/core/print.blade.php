
@extends('core/templates/app')

@section('title', $title)
@section('section')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{url('/statement')}}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>{{$title}}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{url('/home')}}"> Home</a></div>
            <div class="breadcrumb-item"><a href="{{url('/financial')}}"> Keuangan</a></div>
            <div class="breadcrumb-item"><a href="{{url('/statement')}}"> Laporan Keuangan</a></div>
            <div class="breadcrumb-item active">{{$title}}</div>
        </div>
    </div>
    <div class="section-body">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-10 col-lg-10">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Cetak Dokumen</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Dokumen</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>Lihat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <form target="_blank" action="{{url('/print/ju')}}" method="get"> @csrf
                                        <input type="hidden" name="method" value="ju">
                                        <tr>
                                            <td>1</td>
                                            <td>Jurnal Umum</td>
                                            <td><select name="s1" class="form-control">@for($i=1;$i<=12;$i++) <option value="{{$i}}">{{ $month[$i] }}</option> @endfor</select></td>
                                            <td><select name="s2" class="form-control">@for($i=$year['0'];$i<=$year['1'];$i++) <option value="{{$i}}">{{ $i }}</option> @endfor</select></td>
                                            <td><button type="submit" class="btn btn-success"><i class="fas fa-print"></i> Cetak</button></td>
                                        </tr>
                                    </form>
                                    <form target="_blank" action="{{url('/print/lr')}}" method="get"> @csrf
                                        <input type="hidden" name="method" value="lr">
                                        <tr>
                                            <td>2</td>
                                            <td>Laporan Laba/Rugi</td>
                                            <td><select name="s1" class="form-control">@for($i=1;$i<=12;$i++) <option value="{{$i}}">{{ $month[$i] }}</option> @endfor</select></td>
                                            <td><select name="s2" class="form-control">@for($i=$year['0'];$i<=$year['1'];$i++) <option value="{{$i}}">{{ $i }}</option> @endfor</select></td>
                                            <td><button type="submit" class="btn btn-success"><i class="fas fa-print"></i> Cetak</button></td>
                                        </tr>
                                    </form>
                                    <form target="_blank" action="{{url('/print/pm')}}" method="get"> @csrf
                                        <input type="hidden" name="method" value="pm">
                                        <tr>
                                            <td>3</td>
                                            <td>Laporan Perubahan Modal</td>
                                            <td><select name="s1" class="form-control">@for($i=1;$i<=12;$i++) <option value="{{$i}}">{{ $month[$i] }}</option> @endfor</select></td>
                                            <td><select name="s2" class="form-control">@for($i=$year['0'];$i<=$year['1'];$i++) <option value="{{$i}}">{{ $i }}</option> @endfor</select></td>
                                            <td><button type="submit" class="btn btn-success"><i class="fas fa-print"></i> Cetak</button></td>
                                        </tr>
                                    </form>
                                    {{-- <form target="_blank" action="{{url('/print/nc')}}" method="get"> @csrf
                                        <input type="hidden" name="method" value="nc">
                                        <tr>
                                            <td>4</td>
                                            <td>Laporan Neraca</td>
                                            <td><select name="s1" class="form-control">@for($i=1;$i<=12;$i++) <option value="{{$i}}">{{ $month[$i] }}</option> @endfor</select></td>
                                            <td><select name="s2" class="form-control">@for($i=$year['0'];$i<=$year['1'];$i++) <option value="{{$i}}">{{ $i }}</option> @endfor</select></td>
                                            <td><button type="submit" class="btn btn-success"><i class="fas fa-print"></i> Cetak</button></td>
                                        </tr>
                                    </form>
                                    <form target="_blank" action="{{url('/print/ak')}}" method="get"> @csrf
                                        <input type="hidden" name="method" value="ak">
                                        <tr>
                                            <td>5</td>
                                            <td>Laporan Arus Kas</td>
                                            <td><select name="s1" class="form-control">@for($i=1;$i<=12;$i++) <option value="{{$i}}">{{ $month[$i] }}</option> @endfor</select></td>
                                            <td><select name="s2" class="form-control">@for($i=$year['0'];$i<=$year['1'];$i++) <option value="{{$i}}">{{ $i }}</option> @endfor</select></td>
                                            <td><button type="submit" class="btn btn-success"><i class="fas fa-print"></i> Cetak</button></td>
                                        </tr>
                                    </form> --}}
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
