<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="Creative Tim">
    <title>Argon Dashboard - Free Dashboard for Bootstrap 4</title>
    <!-- Favicon -->
    <link href="{{ asset('/assets/img/brand/favicon.png') }}" rel="icon" type="image/png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Icons -->
    <link href="{{ asset('/assets/vendor/nucleo/css/nucleo.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <!-- Argon CSS -->
    <link type="text/css" href="{{ asset('/assets/css/argon.css?v=1.0.0') }}" rel="stylesheet">

    <link type="text/css" href="{{ asset('/css/bootstrap-timepicker.css') }}" rel="stylesheet">
</head>

<body class="bg-default">
<!-- Main content -->
@yield('content')

<!-- Argon Scripts -->
<!-- Core -->
<script src="{{ asset('/assets/vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

{{-- Datepicker --}}
<script src="{{ asset('/assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

<!-- Optional JS -->
<script src="{{ asset('/assets/vendor/chart.js/dist/Chart.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/chart.js/dist/Chart.extension.js') }}"></script>
<!-- Argon JS -->
<script src="{{ asset('/assets/js/argon.js?v=1.0.0') }}"></script>

<script src="{{ asset('/js/bootstrap-timepicker.js') }}"></script>

<script>
    $('.timepicker').timepicker();
</script>

@yield('script')

</body>

</html>
