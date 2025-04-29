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
    <title>CoreUI Free Bootstrap Admin Template</title>
    @stack('start')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @include('layouts.sidebar.sidebar')
    <div class="wrapper d-flex flex-column min-vh-100">
        @include('layouts.header')
        <div class="body flex-grow-1">
            <div class="container-lg px-4">
                @yield('content')
            </div>
        </div>
        @include('layouts.footer')
    </div>
    <script>
        const header = document.querySelector('header.header');

        document.addEventListener('scroll', () => {
            if (header) {
                header.classList.toggle('shadow-sm', document.documentElement.scrollTop > 0);
            }
        });
    </script>
    @stack('end')
</body>

</html>
