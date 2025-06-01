<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/auth.css'])
    <title>Register Pengguna</title>
</head>

<body>
    <div id="cus-bg"></div>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <form action="{{ url('/register') }}" method="POST" id="form-register">
        @csrf

        <h3 class="fw-bolder">Daftar Akun Baru</h3>
        <div class="d-flex flex-row gap-5">
            <div class="flex-fill w-50">
                <!-- Username -->
                <label for="username">NIM</label>
                <input name="username" class="form-control" id="username" placeholder="Masukkan NIM">
                <small id="error-username" class="text-danger error-text"></small>

                <!-- Email -->
                <label for="email">Email</label>
                <input name="email" class="form-control" id="email" placeholder="Masukkan email">
                <small id="error-email" class="text-danger error-text"></small>

                <div class="position-relative w-100" onclick="switchLogo()">
                    <img src="{{ asset('imgs/shigure-ui.webp') }}" alt=""
                        class="img-fluid position-absolute shigure_ui opacity-0" id="picture-preview">
                    <img src="{{ asset('imgs/logo.webp') }}" alt="" style="top: 29px; left: 5px;"
                        class="img-fluid anim-breathing position-absolute ahh_logo" id="picture-preview w-100">
                </div>
            </div>
            <div class="flex-fill w-50">
                <!-- Nama -->
                <label for="nama">Nama</label>
                <input name="nama" class="form-control" id="nama" placeholder="Masukkan nama lengkap">
                <small id="error-nama" class="text-danger error-text"></small>

                <label for="program_id" class="form-label">Program Studi</label>
                <select name="program_id" class="form-select" id="program_id">
                    <option value="" disabled selected class="text-muted">Program Studi</option>
                    @foreach ($prodi as $l)
                        <option value="{{ $l->program_id }}">{{ $l->nama_program }}</option>
                    @endforeach
                </select>
                <div id="error-program_id" class="invalid-feedback"></div>

                <!-- Password -->
                <label for="password">Password</label>
                <input name="password" type="password" class="form-control" id="password"
                    placeholder="Masukkan password">
                <small id="error-password" class="text-danger error-text"></small>

                <!-- Konfirmasi Password -->
                <label for="password_confirmation">Konfirmasi Password</label>
                <input name="password_confirmation" type="password" class="form-control" id="password_confirmation"
                    placeholder="Masukkan konfirmasi password">

                <!-- Aksi -->
                <div class="row">
                    <x-btn-submit-spinner class="btn btn-warning btn-lg btn-block mt-3">
                        Register
                    </x-btn-submit-spinner>
                    <p class="mt-3">
                        Sudah punya akun? <a href="{{ url('/login') }}" style="color: #23a2f6;">Silahkan Login</a>
                    </p>
                </div>
            </div>
        </div>
    </form>

    @include('components.page-modal')

</body>

</html>
<script>
    const switchLogo = () => {
        const logo = document.querySelector('.ahh_logo');
        const shigureUI = document.querySelector('.shigure_ui');
        logo.classList.toggle('opacity-0');
        shigureUI.classList.toggle('opacity-0');
    };

    const run = () => {
        document.documentElement.setAttribute('data-coreui-theme', 'dark');

        const btnSpiner = document.getElementById('btn-submit-spinner');
        $(document).ready(function() {
            $("#form-register").validate({
                rules: {
                    nama: {
                        required: true,
                        minlength: 3
                    },
                    username: {
                        required: true,
                        minlength: 4,
                        maxlength: 20
                    },
                    program_id: {
                        required: true
                    },
                    password: {
                        required: true,
                        minlength: 5,
                        maxlength: 255
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password"
                    },
                    email: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    password_confirmation: {
                        equalTo: "Password tidak sama!"
                    }
                },
                submitHandler: function(form) {
                    btnSpiner.closest('button').disabled = true;
                    document.getElementById('btn-submit-text').classList.add('d-none');
                    btnSpiner.classList.remove('d-none');
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href =
                                    "{{ url('/login') }}";
                            });
                        },
                        error: function(response) {
                            console.log(response);
                            document.getElementById('cus-bg').style
                                .backgroundColor = "red";
                            $.each(response.responseJSON.msgField, function(prefix,
                                val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                title: `Gagal ${response.status}`,
                                html: response.responseJSON.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                document.getElementById('btn-submit-text')
                                    .classList.remove('d-none');
                                btnSpiner.classList.add('d-none');
                                btnSpiner.closest('button').disabled =
                                    false;
                                setTimeout(() => {
                                    document.getElementById(
                                            'cus-bg').style
                                        .backgroundColor = "";
                                }, 500);
                            });
                        }
                    });
                    return false;
                },
                errorElement: "span",
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.input-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    }
    document.addEventListener('DOMContentLoaded', run);
</script>

</html>
