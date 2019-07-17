<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token 了方便前端的 JavaScript 脚本获取 CSRF 令牌 -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- 如果没有定制 title 区域，默认显示第二个的参数作为标题前缀 -->
    <title>@yield('title', 'LaraBBS') - Lara-bbs</title>
    <meta name="description" content="@yield('description', 'LaraBBS 爱好者社区')"/>

    <!-- Styles  mix('css/app.css') 会根据 webpack.mix.js 的逻辑来生成 CSS 文件链接 -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @yield('customCSS')
</head>

<body>
<div id="app" class="{{ route_class() }}-page">

    @include('layouts._header')

    <div class="container">

        @include('shared._messages')

        @yield('content')

    </div>

    @include('layouts._footer')
</div>

@if (Auth::check() && app()->isLocal())
    @include('sudosu::user-selector')
@endif

<!-- Scripts -->
<script src="{{ mix('js/app.js') }}"></script>

@yield('customJS')
</body>

</html>