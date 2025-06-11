<div class="sidebar sidebar-dark sidebar-fixed border-end" id="sidebar"
    style="z-index: 1037; transition: all 0.0s ease-in-out;">
    <div class="sidebar-header border-bottom" style="clip-path: view-box;">
        <div class="sidebar-brand d-flex justify-content-center align-items-center">
            <div class="sidebar-brand-full d-flex flex-row position-relative align-content-end"
                style="height: 32px; width: 110px;">
                <img src="{{ asset('imgs/logo.webp') }}" alt="" class="img-fluid position-absolute pt-2"
                    style="width: 110px; height: auto; object-fit: cover; top: -29px; left: -40px;">
                <h4 class="fw-bolder position-absolute user-select-none"
                    style="top: 6px; left: 30px; pointer-events: none; opacity: 0;">emagang</h4>
            </div>    
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
                </svg> Beranda
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
