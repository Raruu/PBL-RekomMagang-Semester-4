<div class="sidebar sidebar-dark sidebar-fixed border-end" id="sidebar"
    style="z-index: 1037; transition: all 0.0s ease-in-out;">
    <div class="sidebar-header border-bottom">
        <div class="sidebar-brand d-flex justify-content-center align-items-center">
            <img src="{{ asset('imgs/5.png') }}" alt="" class="img-fluid w-50 position-absolute" id="picture-preview"
                style="right: 70px;">
        </div>
        <button class="btn-close d-lg-none" type="button" data-coreui-theme="dark" aria-label="Close"
            onclick="coreui.Sidebar.getInstance(document.querySelector(&quot;#sidebar&quot;)).toggle()"></button>
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('/') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-speedometer') }}">
                    </use>
                </svg> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('/notifikasi') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-bell') }}">
                    </use>
                </svg> Notifikasi
                <span class="badge badge-sm bg-info ms-auto notifikasi_count"></span>
            </a>
        </li>
        @if (Auth::user()->getRole() == 'admin')
            @include('layouts.sidebar.admin')
        @elseif (Auth::user()->getRole() == 'mahasiswa')
            @include('layouts.sidebar.mahasiswa')
        @elseif (Auth::user()->getRole() == 'dosen')
            @include('layouts.sidebar.dosen')
        @endif
    </ul>
    <div class="sidebar-footer border-top d-none d-md-flex">
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"
            onClick="setStateSidebar()"></button>
    </div>
</div>