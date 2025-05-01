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

    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <form action="{{ url('/register') }}" method="POST" id="form-register">
        @csrf
        <h3 class="fw-bolder">Daftar Akun Baru</h3>

        <!-- Username -->
        <label for="username">Username</label>
        <input name="username" class="form-control" id="username" placeholder="Masukkan username">
        <small id="error-username" class="text-danger error-text"></small>

        <!-- Nama -->
        <label for="nama">Nama</label>
        <input name="nama" class="form-control" id="nama" placeholder="Masukkan nama lengkap">
        <small id="error-nama" class="text-danger error-text"></small>

        <label for="prodi" class="form-label">Program Studi</label>
        <select name="prodi" class="form-select" id="prodi">
            <option value="" disabled selected class="text-muted">Program Studi</option>
            @foreach ($prodi as $l)
                <option value="{{ $l->program_id }}">{{ $l->nama_program }}</option>
            @endforeach
        </select>
        <div id="error-prodi" class="invalid-feedback"></div>

        <!-- Password -->
        <label for="password">Password</label>
        <input name="password" type="password" class="form-control" id="password" placeholder="Masukkan password">
        <small id="error-password" class="text-danger error-text"></small>

        <!-- Konfirmasi Password -->
        <label for="password_confirmation">Konfirmasi Password</label>
        <input name="password_confirmation" type="password" class="form-control" id="password_confirmation"
            placeholder="Masukkan konfirmasi password">

        <!-- Aksi -->
        <div class="row">
            <div>
                <button type="submit" class="btn btn-primary btn-block mt-3">Register</button>
            </div>
            <p class="mt-3">
                Sudah punya akun? <a href="{{ url('/login') }}" style="color: #23a2f6;">Silahkan Login</a>
            </p>
        </div>
    </form>


</body>

</html>
<script>
    const run = () => {
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
                    prodi: {
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
                            if (response.status) {

                            } else {

                            }
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
