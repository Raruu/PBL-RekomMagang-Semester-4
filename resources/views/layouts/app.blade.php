<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v5.2.0
* @link https://coreui.io/product/free-bootstrap-admin-template/
* Copyright (c) 2025 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://github.com/coreui/coreui-free-bootstrap-admin-template/blob/main/LICENSE)
-->

<html lang="en">

<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <title>{{ trim($__env->yieldContent('title')) ?: 'Sistem Rekomendasi Magang' }}</title>
    @stack('start')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body style="opacity: 0; transition: opacity 0.0005s;">
    @include('layouts.sidebar.sidebar')
    <div class="wrapper d-flex flex-column min-vh-100" style="transition: all 0.0s;">
        @include('layouts.header')
        @yield('content-top')
        @if (trim($__env->yieldContent('content')))
            <div class="body flex-grow-1 d-flex">
                <div class="container-lg px-4 flex-fill">
                    @yield('content')
                </div>
            </div>
        @endif
        @yield('content-bottom')
        {{-- @include('layouts.footer') --}}
    </div>
    @stack('end')
</body>

</html>
