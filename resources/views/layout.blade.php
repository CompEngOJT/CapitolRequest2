<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Laravel AUTH')</title>
    <link href="{{asset('assets/css/bootstrap.css')}}" rel="stylesheet" >
    @stack('styles')
  </head>
  <body>
@yield('content')
    <script src="{{asset('assets/js/bootstrap.js')}}" ></script>
    @stack('scripts')
  </body>
</html>