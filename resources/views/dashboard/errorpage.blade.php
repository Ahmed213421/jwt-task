<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>@yield('title')</title>


    @if(App::getLocale() =='ar')
    <link rel="stylesheet" href="{{asset('admin/rtl/css/simplebar.css')}}">
    <!-- Fonts CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="{{asset('admin/rtl/css/feather.css')}}">
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="{{asset('admin/rtl/css/daterangepicker.css')}}">
    <!-- App CSS -->
    <link rel="stylesheet" href="{{asset('admin/rtl/css/app-light.css')}}" id="lightTheme">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- <link rel="stylesheet" href="{{asset('admin/rtl/css/app-dark.css')}}" id="darkTheme" disabled> --}}

    @else

    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="{{asset('admin/css/simplebar.css')}}">
    <!-- Fonts CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="{{asset('admin/css/feather.css')}}">
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="{{asset('admin/css/daterangepicker.css')}}">
    <!-- App CSS -->
    <link rel="stylesheet" href="{{asset('admin/css/app-light.css')}}" id="lightTheme">
    <link rel="stylesheet" href="{{asset('admin/css/app-dark.css')}}" id="darkTheme" disabled>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endif

    @yield('css')
    <style>
        .activelink {
            color: #001a4e;
            background: #f8f9fa;
        }
    </style>
  </head>
  <body class="vertical  light {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} ">

    <div class="wrapper vh-100">
      <div class="align-items-center h-100 d-flex w-50 mx-auto">
        <div class="mx-auto text-center">
          <h1 class="display-1 m-0 font-weight-bolder text-muted" style="font-size:80px;">404</h1>
          <h1 class="mb-1 text-muted font-weight-bold">{{ trans('general.oops') }}</h1>
          <h6 class="mb-3 text-muted">{{ trans('general.nopagefound') }}</h6>
          <a href="{{route('admin.dashboard')}}" class="btn btn-lg btn-primary px-5">{{ trans('general.backto.dashboard') }}</a>
        </div>
      </div>
    </div>
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/popper.min.js')}}"></script>
    <script src="{{asset('js/moment.min.js')}}"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/simplebar.min.js')}}"></script>
    <script src="{{asset('js/daterangepicker.js')}}"></script>
    <script src="{{asset('js/jquery.stickOnScroll.js')}}"></script>
    <script src="{{asset('js/tinycolor-min.js')}}"></script>
    <script src="{{asset('js/config.js')}}"></script>
    <script src="{{asset('js/apps.js')}}"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-56159088-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];

      function gtag()
      {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());
      gtag('config', 'UA-56159088-1');
    </script>
  </body>
</html>
</body>
</html>
