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
    @stack('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</head>

<body style="opacity: 0; transition: opacity 0.0005s;">
    @include('layouts.sidebar.sidebar')
    <div class="wrapper d-flex flex-column min-vh-100" style="transition: all 0.0s;">
        @include('layouts.header')
        <div class="overflow-auto d-flex {{ trim($__env->yieldContent('content-top')) ? 'flex-fill' : '' }}"
            id="content-top"
            style="{{ trim($__env->yieldContent('content-top')) ? 'max-height: calc(100vh - 113px);' : '' }}">
            @yield('content-top')
        </div>
        @if (trim($__env->yieldContent('content')))
            <div class="body flex-grow-1 d-flex overflow-auto pt-4" style="max-height: calc(100vh - 113px);"
                id="content-mid">
                <div class="container-lg px-4 flex-fill">
                    @yield('content')
                </div>
            </div>
        @endif
        <div class="overflow-auto" style="max-height: calc(100vh - 113px);" id="content-bottom">
            @yield('content-bottom')
        </div>
    </div>
    @stack('end')
</body>

</html>
