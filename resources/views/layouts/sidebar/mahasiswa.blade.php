<li class="nav-title">Manajemen Akun & Profil</li>
<li class="nav-group {{ str_contains(request()->url(), '/edit') ? 'show' : '' }}">
    <a class="nav-link nav-group-toggle" href="#">
        <svg class="nav-icon">
            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-user') }}">
            </use>
        </svg>
        Akun
    </a>
    <ul class="nav-group-items compact">
        <li class="nav-item">
            <a class="nav-link" href="{{ url('mahasiswa/profile') }}">
                <span class="nav-icon">
                    <span class="nav-icon-bullet"></span>
                </span> Profil
            </a>
        <li class="nav-item">
            <a class="nav-link" href="#2">
                <span class="nav-icon">
                    <span class="nav-icon-bullet"></span>
                </span> Dokumen
            </a>
        </li>
    </ul>
</li>

<li class="nav-title">Magang</li>
<li class="nav-group">
    <a class="nav-link nav-group-toggle" href="#">
        <svg class="nav-icon">
            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-briefcase') }}">
            </use>
        </svg>
        Magang
    </a>
    <ul class="nav-group-items compact">
        <li class="nav-item">
            <a class="nav-link" href="#1">
                <span class="nav-icon">
                    <span class="nav-icon-bullet"></span>
                </span> Rekomendasi
            </a>
        <li class="nav-item">
            <a class="nav-link" href="#2">
                <span class="nav-icon">
                    <span class="nav-icon-bullet"></span>
                </span> Pengajuan
            </a>
        </li>
    </ul>
</li>

<li class="nav-title">Monitoring & Evaluasi</li>
<li class="nav-item">
    <a class="nav-link" href="#4">
        <svg class="nav-icon">
            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-notes') }}">
            </use>
        </svg> Log
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#4">
        <svg class="nav-icon">
            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-spreadsheet') }}">
            </use>
        </svg> Ambil Surat Keterangan
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="#4">
        <svg class="nav-icon">
            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-smile') }}">
            </use>
        </svg> Feedback Evaluasi
    </a>
</li>
