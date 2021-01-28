<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title> {{ config('app.name', 'SPR System') }}</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/all.css">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="/css/selectric.css">

    <!-- Template CSS -->
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/components.css">
    </head>

    <body>
    <div id="app">
        <section class="section">
        @yield('container')
        </section>
    </div>

    <!-- General JS Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/tooltip.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.nicescroll.min.js"></script>
    <script src="js/moment.min.js"></script>
    <script src="js/stisla.js"></script>
</body>
</html>
