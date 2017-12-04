<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'LaraBBS') |  Tiicle - 共享编程知识 </title>
    <meta name="author" content="Summer" />
    <meta name="description" content="Tiicle 是一个程序员分享编程知识和协作的平台。你可以在 Tiicle 上记录编程小贴士、编程小技巧或者编码日志。" />
    <meta name="keywords" content="编程,编码,编程学习,编程技巧,编程知识,php,javascript,laravel,java,vuejs " />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>

<body class="{{ route_class() }}-page">
<div class="main container">

    @include('home.layouts._header')

    {{--网页主体--}}
    {{--<div class="ui centered grid container stackable">--}}

        {{--@yield('content')--}}

    {{--</div>--}}
    {{--end网页主体--}}
</div>
@include('home.layouts._footer')
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>