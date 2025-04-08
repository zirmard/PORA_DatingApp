<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ (isset($title)) ? $title.' | '.config('app.name') : config('app.name') }}</title>

  {{-- The below line will remove the favicon.io not found error on console --}}
  <link rel="icon" href="data:;base64,iVBORwOKGO=" />

  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('theme/dist/img/favicon/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('theme/dist/img/fav-icon-pora.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('theme/dist/img/fav-icon-pora.png') }}">
  {{-- <link rel="manifest" href="{{ asset('theme/dist/img/favicon/site.webmanifest') }}"> --}}
  <link rel="mask-icon" href="{{ asset('theme/dist/img/favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('theme/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('theme/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('theme/dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin_assets/css/custom.css') }}">

</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
           <img src="{{ asset('theme/dist/img/logo.png') }}" width="200" alt="{{ config('app.name') }}"/><br><br>
        </div>
        @yield('content')
    </div>
<!-- jQuery -->
<script src="{{ asset('theme/plugins/jquery/jquery.min.js') }}"></script>
<script>window.jQuery || document.write('<script src="{{ asset('theme/plugins/jquery/jquery.min.js') }}"><\/script>')</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('theme/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('theme/dist/js/adminlte.min.js') }}"></script>
{{-- Admin Panel - toggle password script --}}
<script src="{{ asset('admin_assets/js/toggle-password-script.js') }}"></script>
@stack('script')
</body>
</html>
