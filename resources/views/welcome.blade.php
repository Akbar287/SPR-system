
@extends('layouts.app')

@section('container')
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="jumbotron jumbotron-fluid mt-3">
                    <div class="container">
                        <h1 class="display-4 text-center">SPR System</h1>
                        <div class="mt-2 mb-1">
                            <p class="card-text">Login Untuk masuk ke sistem.</p>
                            <p class="card-text">Ingin Menjadi Reseller? Daftar ke Admin.</p>
                        </div>
                    </div>
                </div>
                <div class="container">
                    @if (Route::has('login'))
                        @auth
                            <div class="card">
                                <div class="card-body">
                                    <a class="btn btn-primary mt-1" href="{{ url('/home') }}">Home</a>
                                </div>
                            </div>
                        @else
                            <div class="row justify-content-center">
                                <div class="col-10 col-sm-10 col-md-5 col-lg-5 col-xl-5 ">
                                    <div class="card shadow" style="margin-top: -60px;">
                                        <div class="card-body text-center">
                                            <a class="btn btn-primary mt-1" href="{{ route('login') }}">Login</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
        @endsection
