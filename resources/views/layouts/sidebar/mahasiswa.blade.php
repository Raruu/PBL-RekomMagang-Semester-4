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
            <a class="nav-link" href="{{ route('mahasiswa.profile') }}">
                <span class="nav-icon">
                    <span class="nav-icon-bullet"></span>
                </span> Profil
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('mahasiswa.dokumen') }}">
                <span class="nav-icon">
                    <span class="nav-icon-bullet"></span>
                </span> Dokumen
            </a>
        </li>
    </ul>
</li>

@if (App\Http\Controllers\MahasiswaAkunProfilController::checkCompletedSetup())
    <li class="nav-title">Magang</li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('mahasiswa.magang') }}">
            <svg class="nav-icon">
                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-newspaper') }}">
                </use>
            </svg> Lowongan
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('mahasiswa.magang.pengajuan') }}">
            <svg class="nav-icon">
                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-briefcase') }}">
                </use>
            </svg> Pengajuan
        </a>
    </li>

    <li class="nav-title">Evaluasi</li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('mahasiswa.evaluasi.feedback.spk') }}">
            <svg class="nav-icon">
                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-smile') }}">
                </use>
            </svg> Feedback SPK
        </a>
    </li>
@endif
