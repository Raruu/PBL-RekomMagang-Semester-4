<header class="header header-sticky p-0 mb-4">
    <div class="container-fluid border-bottom px-4">
        <button class="header-toggler" type="button"
            onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"
            style="margin-inline-start: -14px;">
            <svg class="icon icon-lg">
                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-menu') }}"></use>
            </svg>
        </button>
        <ul class="header-nav d-none d-lg-flex">
            <li class="nav-item">
                <a class="nav-link" href="https://github.com/Raruu/PBL-RekomMagang-Semester-4" target="_blank">
                    <svg class="icon icon-lg">
                        <use xlink:href="{{ url('build/@coreui/icons/sprites/brand.svg#cib-github') }}"></use>
                    </svg>
                    <!-- Raruu/PBL-RekomMagang-Semester-4 -->
                </a>
            </li>
        </ul>
        <ul class="header-nav ms-auto">
            <li class="nav-item dropdown position-relative">
                <button class="btn btn-link nav-link py-2 px-2 d-flex align-items-center" type="button"
                    id="notification-tooltip-button">
                    <svg class="icon icon-lg">
                        <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-lightbulb') }}">
                        </use><span class="badge badge-sm bg-info position-absolute notifikasi_count"
                            style="top: 0; right: 0;"></span>
                    </svg>
                </button>

                <div id="notification-tooltip" class="card position-absolute notification-tooltip"
                    style="width: 20rem; right: 0; display: none;">
                    <div class="card-header d-flex flex-row justify-content-between align-content-center">
                        <p class="fw-bold pb-0 my-auto">Notifikasi</p>
                        <button type="button"
                            class="btn flex-row d-flex justify-content-center align-content-center gap-2"
                            onclick="notifications.markReadAll(); fetchNotificationTooltip();">
                            <i class="fas fa-check-double my-auto"></i>
                            <p class="pb-0 mb-0">Semua dibaca</p>
                        </button>
                    </div>
                    <div class="card-body w-100 d-flex flex-column gap-2 py-2"
                        style="max-height: 75vh; overflow-y: auto;">
                    </div>
                    <div class="card-footer justify-content-center align-content-center d-flex">
                        <a href="{{ route('notifikasi') }}" class="text-decoration-none text-muted">
                            Lihat semua notifikasi
                        </a>
                    </div>
                </div>
            </li>
        </ul>
        <ul class="header-nav">
            <li class="nav-item py-1">
                <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>
            <li class="nav-item dropdown">
                <button class="btn btn-link nav-link py-2 px-2 d-flex align-items-center" type="button"
                    aria-expanded="false" data-coreui-toggle="dropdown">
                    <svg class="icon icon-lg theme-icon-active">
                        <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-contrast') }}">
                        </use>
                    </svg>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="--cui-dropdown-min-width: 8rem;">
                    <li>
                        <button class="dropdown-item d-flex align-items-center" type="button"
                            data-coreui-theme-value="light">
                            <svg class="icon icon-lg me-3">
                                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-sun') }}">
                                </use>
                            </svg>Light
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item d-flex align-items-center" type="button"
                            data-coreui-theme-value="dark">
                            <svg class="icon icon-lg me-3">
                                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-moon') }}">
                                </use>
                            </svg>Dark
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item d-flex align-items-center active" type="button"
                            data-coreui-theme-value="auto">
                            <svg class="icon icon-lg me-3">
                                <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-contrast') }}">
                                </use>
                            </svg>Auto
                        </button>
                    </li>
                </ul>
            </li>
            <li class="nav-item py-1">
                <div class="vr h-100 mx-2 text-body text-opacity-75"></div>
            </li>
            <li class="nav-item dropdown"><a class="nav-link py-0 pe-0" data-coreui-toggle="dropdown" href="#"
                    role="button" aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-md" style="clip-path: circle(50% at 50% 50%);">
                        <img class="avatar-img"
                            src="{{ Auth::user()->getPhotoProfile() ? asset(Auth::user()->getPhotoProfile()) : asset('imgs/profile_placeholder.jpg') }}?{{ now() }}"
                            alt="pfp">
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold mb-2">
                        <div class="fw-semibold">Settings</div>
                    </div>
                    <a class="dropdown-item" href="{{ route(Auth::user()->role . '.profile') }}">
                        <svg class="icon me-2">
                            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-user') }}"></use>
                        </svg> Profile</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ url('logout') }}">
                        <svg class="icon me-2">
                            <use xlink:href="{{ url('build/@coreui/icons/sprites/free.svg#cil-account-logout') }}">
                            </use>
                        </svg> Logout
                    </a>
                </div>
            </li>
        </ul>
    </div>
    @if (request()->path() !== Auth::user()->role)
        <div class="container-fluid px-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb my-0">
                    @foreach (array_slice(explode('/', request()->path()), 0) as $segment)
                        @php $link = implode('/', array_slice(explode('/', request()->path()), 0, $loop->index + 1)); @endphp
                        <li class="breadcrumb-item {{ request()->is($link) ? 'active' : '' }}">
                            <a href="{{ url($link) }}"
                                class="text-decoration-none fw-bold">{{ Str::ucfirst($segment) }}</a>
                        </li>
                    @endforeach
                </ol>
            </nav>
        </div>
    @endif
</header>
<script>
    let fetchNotificationTooltipRequesting = false;
    const fetchNotificationTooltip = async () => {
        if (fetchNotificationTooltipRequesting) {
            return;
        }
        fetchNotificationTooltipRequesting = true;
        const notificationTooltip = document.querySelector(
            "#notification-tooltip"
        );
        const notificationBody = notificationTooltip.querySelector(
            '.card-body'
        );
        notificationBody.innerHTML = '';
        const data = await notifications.fetchUnread();
        data.forEach((row) => {
            let item = `@include('layouts.notification-tooltip-content')`;
            if (row.read == "1") {
                item = item.replace('#showmarkread',
                    `<button class="btn btn-outline-success btn-sm" type="button"
                            onclick="notifications.markRead('${row.id}', '${row.link}').then(response => {
                                document.querySelector('#notif-toast-${row.id}').remove();
                                fetchNotificationTooltip();
                            });">
                            <i class="fa-regular fa-eye"></i>
                        </button>`);
            } else {
                item = item.replace('#showmarkread', '');
            }
            notificationBody.innerHTML += item;
        });
        if (data.length < 1) {
            notificationBody.innerHTML += `
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <img src="{{ asset('imgs/sanhua-froze.webp') }}" alt="ice" style="width: 8rem">
                    <h6 class="text-center">Tidak ada notifikasi</h6>
                </div>
            `;
        }
        document.querySelectorAll('.notifikasi_count').forEach(element => {
            element.innerHTML = data.length == 0 ? '' : data.length;
        });
        fetchNotificationTooltipRequesting = false;
    };

    document.addEventListener('DOMContentLoaded', () => {
        const notificationTooltip = document.querySelector(
            "#notification-tooltip"
        );
        if (notificationTooltip) {
            fetchNotificationTooltip();
            setInterval(() => {
                 const notificationTooltip = document.querySelector("#notification-tooltip");
                 if(notificationTooltip.style.opacity !== "1") {
                     fetchNotificationTooltip();                     
                 }
            }, 5000);
        }
    });
</script>
