<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>GRACE</title>
    <link href="{{ asset('grace/assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('grace/assets/css/metisMenu.min.css') }}" rel="stylesheet">
    <link href="{{ asset('grace/assets/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3"
                    stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        @include('Grace::Grace.includes.header')

        @include('grace.includes.sidebar')

        @yield('content')

        @include('Grace::Grace.includes.footer')

    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <script src="{{ asset('grace/assets/js/common.min.js') }}"></script>
    <script src="{{ asset('grace/assets/js/custom.min.js') }}"></script>
    <script src="{{ asset('grace/assets/js/settings.js') }}"></script>
    <script src="{{ asset('grace/assets/js/gleek.js') }}"></script>
    <script src="{{ asset('grace/assets/js/styleSwitcher.js') }}"></script>
    <script src="{{ asset('grace/assets/js/circle-progress.min.js') }}"></script>
    <script src="{{ asset('grace/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('grace/assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('grace/assets/js/datatable-basic.min.js') }}"></script>
</body>

</html>
