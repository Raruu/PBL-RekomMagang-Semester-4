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
            <div class="w-50">
                <!-- Username -->
                <label for="username">Username</label>
                <input name="username" class="form-control" id="username" placeholder="Masukkan username">
                <small id="error-username" class="text-danger error-text"></small>

                <!-- Email -->
                <label for="email">Email</label>
                <input name="email" class="form-control" id="email" placeholder="Masukkan email">
                <small id="error-email" class="text-danger error-text"></small>

                <img src="{{ asset('imgs/shigure-ui.webp') }}" alt="" class="img-fluid" id="picture-preview">
            </div>
            <div>
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
                    <div>
                        <button type="submit" class="btn btn-warning btn-lg btn-block mt-3">Register</button>
                    </div>
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
    const run = () => {
        const modalElement = document.getElementById('page-modal');
        modalElement.addEventListener('hidden.coreui.modal', function(event) {
            document.getElementById('cus-bg').style.backgroundColor = "";
            const title = event.target.querySelector('.modal-title')?.textContent;
            if (title === 'Berhasil') window.location.href = "{{ url('/login') }}";
        });
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
                        maxlength: 20
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
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            const modal = new coreui.Modal(modalElement);
                            const modalTitle = modalElement.querySelector(
                                '.modal-title')
                            modalTitle.textContent = response.status ?
                                'Berhasil' : 'Gagal';
                            modalElement.querySelector('.modal-body')
                                .textContent = response.message;

                            if (!response.status) {
                                console.log(response);
                                document.getElementById('cus-bg').style
                                    .backgroundColor =
                                    "red";
                                let errorMsg = '\n';
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                    errorMsg += val[0] + '\n';
                                });
                                modalElement.querySelector('.modal-body')
                                    .innerHTML += errorMsg.replace(/\n/g, '<br>');
                            }

                            modal.show();
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
