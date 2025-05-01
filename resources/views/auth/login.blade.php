<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/auth.css'])
    <title>Login Pengguna</title>
</head>

<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <form id="form-login" method="POST" action="{{ route('login') }}">
        @csrf
        <h3 class="fw-bolder">Sistem Rekomendasi Magang</h3>

        <label for="username">Username</label>
        <div class="input-group">
            <input type="text" name="username" id="username" placeholder="Masukkan username" class="form-control">
            <span id="error-username" class="error-text text-danger"></span>
        </div>

        <label for="password">Password</label>
        <div class="input-group">
            <input type="password" name="password" id="password" placeholder="Masukkan password" class="form-control">
            <span id="error-password" class="error-text text-danger"></span>
        </div>
        <button type="submit" class="btn btn-primary mt-3" style="margin-top: 20px; margin-bottom: 5px;">Log
            In</button>

        <p style="color: #fff; text-align:center;" class="mt-3">
            Belum punya akun? <a href="{{ url('register') }}" style="color: #23a2f6;">Daftar di sini</a>
        </p>

    </form>

    <script>
        const run = () => {
            $(document).ready(function() {
                $("#form-login").validate({
                    rules: {
                        username: {
                            required: true,
                            minlength: 4,
                            maxlength: 20
                        },
                        password: {
                            required: true,
                            minlength: 5,
                            maxlength: 20
                        }
                    },
                    submitHandler: function(form) {
                        $.ajax({
                            url: form.action,
                            type: form.method,
                            data: $(form).serialize(),
                            success: function(response) {
                                console.log(response);
                                if (response.status) {
                                    window.location = response.redirect;
                                } else {

                                }
                            }
                        });
                        return false;
                    },
                    errorElement: 'span',
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
</body>

</html>
