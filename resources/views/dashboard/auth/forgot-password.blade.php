<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>@yield('title')</title>


    @if (App::getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('admin/rtl/css/simplebar.css') }}">
        <!-- Fonts CSS -->
        <link
            href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
            rel="stylesheet">
        <!-- Icons CSS -->
        <link rel="stylesheet" href="{{ asset('admin/rtl/css/feather.css') }}">
        <!-- Date Range Picker CSS -->
        <link rel="stylesheet" href="{{ asset('admin/rtl/css/daterangepicker.css') }}">
        <!-- App CSS -->
        <link rel="stylesheet" href="{{ asset('admin/rtl/css/app-light.css') }}" id="lightTheme">
        {{-- <link rel="stylesheet" href="{{asset('admin/rtl/css/app-dark.css')}}" id="darkTheme" disabled> --}}
    @else
        <!-- Simple bar CSS -->
        <link rel="stylesheet" href="{{ asset('admin/css/simplebar.css') }}">
        <!-- Fonts CSS -->
        <link
            href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
            rel="stylesheet">
        <!-- Icons CSS -->
        <link rel="stylesheet" href="{{ asset('admin/css/feather.css') }}">
        <!-- Date Range Picker CSS -->
        <link rel="stylesheet" href="{{ asset('admin/css/daterangepicker.css') }}">
        <!-- App CSS -->
        <link rel="stylesheet" href="{{ asset('admin/css/app-light.css') }}" id="lightTheme">
        <link rel="stylesheet" href="{{ asset('admin/css/app-dark.css') }}" id="darkTheme" disabled>
    @endif

    @yield('css')
</head>

<body class="light ">
    <div class="wrapper vh-100">
        <div class="row align-items-center h-100">
            <form method="POST" class="col-lg-3 col-md-4 col-10 mx-auto text-center"
                action="{{ route('admin.password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email">{{ __('Email') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror" required autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="form-group text-right mt-4">

                    <button type="submit" class="btn btn-primary">
                        {{ __('Email Password Reset Link') }}
                    </button>
                </div>
            </form>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

        </div>
    </div>


    <script src="{{ asset('admin/js/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/js/popper.min.js') }}"></script>
    <script src="{{ asset('admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('admin/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin/js/simplebar.min.js') }}"></script>
    <script src='{{ asset('admin/js/daterangepicker.js') }}'></script>
    <script src='{{ asset('admin/js/jquery.stickOnScroll.js') }}'></script>
    <script src="{{ asset('admin/js/tinycolor-min.js') }}"></script>
    <script src="{{ asset('admin/js/config.js') }}"></script>
    <script src="{{ asset('admin/js/apps.js') }}"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-56159088-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'UA-56159088-1');
    </script>

    @yield('js')
</body>

</html>
