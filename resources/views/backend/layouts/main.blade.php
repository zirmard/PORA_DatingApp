<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>
            @if(View::hasSection('title'))
                @yield('title') | {{ config('app.name') }}
            @else
                {{ config('app.name') }}
            @endif
        </title>

        {{-- The below line will remove the favicon.io not found error on console --}}
        <link rel="icon" href="data:;base64,iVBORwOKGO=" />

        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('theme/dist/img/favicon/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('theme/dist/img/fav-icon-pora.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('theme/dist/img/fav-icon-pora.png') }}">
        <link rel="manifest" href="{{ asset('theme/dist/img/favicon/site.webmanifest') }}">
        <link rel="mask-icon" href="{{ asset('theme/dist/img/favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">

        @stack('top_css')
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('theme/plugins/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('theme/plugins/jquery-toast-plugin/dist/jquery.toast.min.css') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('theme/dist/css/adminlte.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin_assets/css/dataTables.bootstrap4.min.cssdataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin_assets/css/custom.css') }}">
        @stack('css')
    </head>
    <body class="hold-transition sidebar-mini">
        <div class="wrapper">
            @include('backend.layouts.header')
            @include('backend.layouts.sidebar')
            <div class="content-wrapper">
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>@yield('title')</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    @if(Route::currentRouteName() != 'backend.home')
                                        <li class="breadcrumb-item"><a href="{{ route('backend.home') }}">Home</a></li>
                                    @endif
                                    @yield('breadcrumb')
                                </ol>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="content">
                    @yield('content')
                </section>
            </div>
            @include('backend.layouts.footer')
        </div>
        <!-- ./wrapper -->

        <!-- jQuery -->
        <script src="{{ asset('theme/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('admin_assets/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('admin_assets/js/dataTables.bootstrap4.min.js') }}"></script>
        <script>window.jQuery || document.write('<script src="{{ asset('theme/plugins/jquery/jquery.min.js') }}"><\/script>')</script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset('theme/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('theme/plugins/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('theme/dist/js/adminlte.min.js') }}"></script>


        <!-- AdminLTE for demo purposes -->
        <script src="{{ asset('theme/dist/js/demo.js') }}"></script>
        <script>
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            });
        </script>

        @if ($message = Session::get('success'))
        <script>
            $.toast({
                heading: 'Success',
                text: '{{ $message }}',
                position: String('top-center'),
                icon: 'success',
                loaderBg: '#f96868',
                hideAfter: false,
                hideAfter: 8000
            });
        </script>
        @endif
        @if ($message = Session::get('error'))
        <script>
            $.toast({
                heading: 'Error',
                text: '{{ $message }}',
                position: 'top-center',
                icon: 'error',
                loaderBg: '#f2a654',
                hideAfter: 20000
            });
        </script>
        @endif
        @stack('script')
    </body>
</html>
