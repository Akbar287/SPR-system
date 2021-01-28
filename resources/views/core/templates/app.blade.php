<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ url('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendor/jQuery-Selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ url('vendor/fontawesome/css/all.css') }}">
    <link rel="stylesheet" href="{{ url('vendor/datatables/dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendor/datatables/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendor/iziToast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ url('vendor/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{url('vendor/pace/themes/red/pace-theme-flash.css')}}">
    @if($title == 'Home' || $title == 'Keuangan') <link rel="stylesheet" href="{{ url('vendor/Chart.js/chart.min.css') }}"> @endif

    <!-- Custom Style -->
    <link rel="stylesheet" href="{{ url('css/components.css') }}">
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
    <link rel="stylesheet" href="{{ url('css/custom.css') }}">
    <!-- Start GA -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-94034622-3');

    </script>
</head>

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <form class="form-inline mr-auto" action="{{url('/search')}}" method="GET">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
                        <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
                    </ul>
                    <div class="search-element">
                        <input class="form-control" type="search" placeholder="Search" name="q" aria-label="Search" data-width="250">
                        <button class="btn" type="submit" style="border-radius: 0 3px 3px 0!important;"><i class="fas fa-search"></i></button>
                        <div class="search-backdrop"></div>
                    </div>
                </form>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image" id="image-user" src="{{'/img/profil/'.Auth::user()->image}}" class="rounded-circle mr-1">
                            <div class="d-sm-none d-lg-inline-block">{{ Auth::user()->name }} </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="{{ url('/activities') }}" class="dropdown-item has-icon">
                                <i class="fas fa-bolt"></i> Riwayat
                            </a>
                            <a href="{{ url('/licenses') }}" class="dropdown-item has-icon">
                                <i class="fas fa-file"></i> Licenses
                            </a>
                            <a href="{{ url('/settings') }}" class="dropdown-item has-icon">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="dropdown-item has-icon text-danger">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a href="{{url('/home')}}">Will Be Success Fish</a>
                    </div>
                    <div class="sidebar-brand sidebar-brand-sm">
                        <a href="{{url('/home')}}">Fish</a>
                    </div>
                    <ul class="sidebar-menu">
                        <li class=" {{($title == 'Profil') ? 'active' : '' }}"><a class="nav-link" href="{{url('/profile')}}"><i class="fas fa-user"></i> <span>Profil</span></a></li>
                        <li class=" {{($title == 'Home') ? 'active' : '' }}"><a class="nav-link" href="{{url('/home')}}"><i class="fas fa-home"></i> <span>Home</span></a></li>
                        <li class="menu-header">Layanan</li>
                        @if(Auth::user()->roles == 1)
                        <li class=" {{($title == 'Keuangan' || $title == 'Cetak Laporan' || $title == 'Modal' || $title == 'Tambah Modal' || $title == 'Ubah Modal' || $title == 'Beban' || $title == 'Buat Data Beban' || $title == 'Data Beban' || $title == 'Gaji' || $title == 'Pendapatan' || $title == 'Pengeluaran' || $title == 'Tambah Pengeluaran' || $title == 'Ubah Data Pengeluaran' || $title == 'Laporan Keuangan' || $title == 'Detail Gaji' || $title == 'Beban Pokok Penjualan' || $title == 'Akuntansi' || $title == 'Penjualan' || $title == 'Tambah Pendapatan' || $title == 'Tambah Catatan Pembelian' || $title == 'Diskon' || $title == 'Pembelian' || $title == 'Ubah Catatan Pembelian') ? 'active' : '' }}"><a href="{{url('/financial')}}" class="nav-link"><i class="fas fa-dollar-sign"></i><span>Keuangan</span></a></li>
                        <li class=" {{($title == 'Pesanan' || $title == 'Return Pesanan' || $title == 'Pesanan Baru' || $title == 'Status Order' || $title == 'Rincian Pesanan') ? 'active' : '' }}"><a href="{{url('/pesanan')}}" class="nav-link"><i class="fas fa-shopping-cart"></i><span>Pesanan</span></a></li>
                        <li class=" {{($title == 'Produk' || $title == 'Informasi Produk' || $title == 'Kategori Produk' || $title == 'Stok Produk' || $title == 'Detail Informasi Produk' || $title == 'Sub Kategori Produk') ? 'active' : '' }}"><a href="{{url('/product')}}" class="nav-link"><i class="fas fa-shopping-bag"></i><span>Produk</span></a></li>
                        <li class=" {{($title == 'Pemasok' || $title == 'Tambah Pemasok' || $title == 'Detail Pemasok') ? 'active' : '' }}"><a href="{{url('/supplier')}}" class="nav-link"><i class="fas fa-truck-moving"></i><span>Pemasok</span></a></li>
                        <li class=" {{($title == 'Bahan Mentah' || $title == 'Tambahkan Bahan Mentah' || $title == 'Detail Bahan Mentah') ? 'active' : '' }}"><a href="{{url('/materials')}}" class="nav-link"><i class="fas fa-luggage-cart"></i><span>Bahan Mentah</span></a></li>
                        <li class=" {{($title == 'Admin' || $title=='Tambah Admin' || $title == 'Detail Admin') ? 'active' : '' }}"><a href="{{url('/admin')}}" class="nav-link"><i class="fas fa-user-cog"></i><span>Admin</span></a></li>
                        <li class=" {{($title == 'Reseller' || $title== 'Tambah Reseller' || $title == 'Detail Reseller') ? 'active' : '' }}"><a href="{{url('/reseller')}}" class="nav-link"><i class="fas fa-user-friends"></i><span>Reseller</span></a></li>
                        <li class=" {{($title == 'Toko' || $title=='Tambah Toko' || $title == 'Detail Toko') ? 'active' : '' }}"><a href="{{url('/market')}}" class="nav-link"><i class="fas fa-address-book"></i><span>Toko</span></a></li>
                        @elseif(Auth::user()->roles == 2 || Auth::user()->roles == 3)
                        <li class=" {{($title == 'Toko' || $title=='Tambah Toko' || $title == 'Detail Toko') ? 'active' : '' }}"><a href="{{url('/store')}}" class="nav-link"><i class="fas fa-address-book"></i><span>Toko</span></a></li>
                        <li class=" {{($title == 'Pesanan' || $title == 'Detail Pesanan' || $title == 'Buat Pesanan' || $title == 'Rincian Pesanan' || $title == 'Status Order') ? 'active' : '' }}"><a href="{{url('/cart')}}" class="nav-link"><i class="fas fa-shopping-cart"></i><span>Pesanan</span></a></li>
                        @endif
                    </ul>
                </aside>
            </div>
            <div class="main-content">
                @yield('section')
            </div>
            <footer class="main-footer">
                <div class="footer-left">Copyright &copy; 2020 <div class="bullet"></div> Will Be Success Fish System</a></div>
                <div class="footer-right"></div>
            </footer>
        </div>
    </div>
    <div id="form-template"></div>
    <div class="modal-content"></div>
    <!-- General JS Scripts -->
    <script src="{{ url('js/popper.js') }}"></script>
    <script src="{{ url('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('vendor/jQuery-Selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ url('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{url('vendor/pace/pace.js')}}"></script>
    <script src="{{ url('js/tooltip.js') }}"></script>
    <script src="{{ url('js/jquery.nicescroll.js') }}"></script>
    @if($title == 'Home' || $title == 'Keuangan') <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script><script src="{{ url('js/chart.custom.js') }}"></script> @endif
    <script src="{{ url('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('vendor/datatables/dataTables.min.js') }}"></script>
    <script src="{{ url('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('js/moment.min.js') }}"></script>
    <script src="{{ url('vendor/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ url('vendor/iziToast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ url('vendor/cleave/dist/cleave.min.js') }}"></script>
    <script src="{{ url('js/stisla.js') }}"></script>
    <script src="{{ url('js/scripts.js') }}"></script>
    <script src="{{ url('js/custom.js') }}"></script>
</body>
</html>
