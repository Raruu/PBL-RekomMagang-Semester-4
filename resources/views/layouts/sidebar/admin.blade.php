<li class="nav-title">Manajemen</li>
<li class="nav-group">
    <a class="nav-link nav-group-toggle" href="#">
        <svg class="nav-icon">
            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-user') }}">
            </use>
        </svg>
        Pengguna
    </a>
    <ul class="nav-group-items compact">
        <li class="nav-item">
            <a class="nav-link" href="{{ url('/admin/pengguna/admin') }}">
                <span class="nav-icon">
                    <span class="nav-icon-bullet"></span>
                </span> Admin
            </a>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('/admin/pengguna/dosen') }}">
                <span class="nav-icon">
                    <span class="nav-icon-bullet"></span>
                </span> Dosen
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/admin/pengguna/mahasiswa">
                <span class="nav-icon">
                    <span class="nav-icon-bullet"></span>
                </span> Mahasiswa
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ url('admin/program_studi') }}">
        <svg class="nav-icon">
            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-book') }}">
            </use>
        </svg> Program Studi
    </a>
</li>

<li class="nav-group">
    <a class="nav-link nav-group-toggle" href="#6">
        <svg class="nav-icon">
            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-industry') }}">
            </use>
        </svg>
        Perusahaan
    </a>
    <ul class="nav-group-items compact">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.bidang_industri.index') }}">
                <span class="nav-icon">
                    <span class="nav-icon-bullet"></span>
                </span> Bidang Industri
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.perusahaan.index') }}">
                <span class="nav-icon">
                    <span class="nav-icon-bullet"></span>
                </span> Mitra
            </a>
    </ul>
</li>

<li class="nav-group">
    <a class="nav-link nav-group-toggle" href="#6">
        <svg class="nav-icon">
            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-briefcase') }}">
            </use>
        </svg>
        Magang
    </a>
    <ul class="nav-group-items compact">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.magang.kegiatan') }}">
                <span class="nav-icon">
                    <span class="nav-icon-bullet"></span>
                </span> Kegiatan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/magang/lowongan') }}">
                <span class="nav-icon">
                    <span class="nav-icon-bullet"></span>
                </span> Lowongan
            </a>
        <li class="nav-item">
            <a class="nav-link" href="#9">
                <span class="nav-icon">
                    <span class="nav-icon-bullet"></span>
                </span> Periode
            </a>
        </li>
    </ul>
</li>

<li class="nav-title">Evaluasi</li>
<li class="nav-group {{ str_contains(request()->url(), route('admin.statistik')) ? 'show' : '' }}">
    <a class="nav-link nav-group-toggle" href="#6">
        <svg class="nav-icon">
            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-industry') }}">
            </use>
        </svg>
        Monitoring & Statistik
    </a>
    <ul class="nav-group-items compact">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.statistik') }}#magang_vs_tidak">
                <span class="nav-icon">
                    <span class="nav-icon-bullet"></span>
                </span> Magang Vs Tidak
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.statistik') }}#tren_peminatan_mahasiswa">
                <span class="nav-icon">
                    <span class="nav-icon-bullet"></span>
                </span> Tren Peminatan
            </a>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.statistik') }}#jumlah_dosen_pembimbing">
                <span class="nav-icon">
                    <span class="nav-icon-bullet"></span>
                </span> Rasio Dosen & Mhs
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.evaluasi.spk') }}">
        <svg class="nav-icon">
            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-equalizer') }}">
            </use>
        </svg> Pendukung Keputusan
    </a>
</li>
