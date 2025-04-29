<div class="sidebar sidebar-dark sidebar-fixed border-end" id="sidebar">
    <div class="sidebar-header border-bottom">
        <div class="sidebar-brand">
            <svg class="sidebar-brand-full" width="88" height="32" alt="Logo">
                <use xlink:href="assets/brand/coreui.svg#full"></use>
            </svg>
            <svg class="sidebar-brand-narrow" width="32" height="32" alt="Logo">
                <use xlink:href="assets/brand/coreui.svg#signet"></use>
            </svg>
        </div>
        <button class="btn-close d-lg-none" type="button" data-coreui-theme="dark" aria-label="Close"
            onclick="coreui.Sidebar.getInstance(document.querySelector(&quot;#sidebar&quot;)).toggle()"></button>
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
        <li class="nav-item"><a class="nav-link" href="index.html">
                <svg class="nav-icon">
                    <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-speedometer') }}">
                    </use>
                </svg> Dashboard</a></li>
        {{-- Engga ada menunya? tambahkan saja --}}
        <h1>ADMIN</h1>
        @if ('admin' == 'admin')
            @include('layouts.sidebar.admin')
        @endif
        <h1>MAHASISWA</h1>
        @if ('mahasiswa' == 'mahasiswa')
            @include('layouts.sidebar.mahasiswa')
        @endif
        <h1>DOSEN</h1>
        @if ('dosen' == 'dosen')
            @include('layouts.sidebar.dosen')
        @endif
    </ul>
    <div class="sidebar-footer border-top d-none d-md-flex">
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
    </div>
</div>
