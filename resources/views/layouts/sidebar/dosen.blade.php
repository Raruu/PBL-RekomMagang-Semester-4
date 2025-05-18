<li class="nav-title">Manajemen Akun & Profil</li>
<li class="nav-group">
    <a class="nav-link nav-group-toggle" href="#">
        <svg class="nav-icon">
            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-user') }}">
            </use>
        </svg>
        Akun
    </a>
    <ul class="nav-group-items compact">
        <li class="nav-item">
            <a class="nav-link" href="{{ url('dosen/profile') }}">
                <span class="nav-icon">
                    <span class="nav-icon-bullet"></span>
                </span> Profil
            </a>
    </ul>
</li>

<li class="nav-title">Manajemen</li>
<li class="nav-item">
    <a class="nav-link" href="{{url ("dosen/mahasiswabimbingan")}}">
        <svg class="nav-icon">
            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-people') }}">
            </use>
        </svg> Mahasiswa Bimbingan
    </a>
</li>