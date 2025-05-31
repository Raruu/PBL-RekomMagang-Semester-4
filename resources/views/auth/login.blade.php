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
    <div id="cus-bg"></div>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <form id="form-login" method="POST" action="{{ route('login') }}" style="width: 400px;">
        @csrf
        <div class="position-relative" style="user-select: none;">
            <h2 class="fw-bolder " style="text-indent: 105px;">emagang</h2>
            <div class="position-relative w-100" onclick="switchLogo()">
                <img src="{{ asset('imgs/logo.webp') }}" alt=""
                    class="img-fluid w-50 position-absolute ahh_logo" id="picture-preview"
                    style="bottom: -40px; right: 160px;">
                <img src="{{ asset('imgs/shigure-ui.webp') }}" alt="" style="bottom: -20px; right: 160px;"
                    class="img-fluid w-50 position-absolute shigure_ui opacity-0 shigure_ui" id="picture-preview">
            </div>
        </div>

        <label for="username">Username / Email</label>
        <div class="input-group">
            <input type="text" name="username" id="username" placeholder="Masukkan username atau email"
                class="form-control">
            <span id="error-username" class="error-text text-danger"></span>
        </div>

        <label for="password">Password</label>
        <div class="input-group">
            <input type="password" name="password" id="password" placeholder="Masukkan password" class="form-control">
            <span id="error-password" class="error-text text-danger"></span>
        </div>
        <x-btn-submit-spinner class="btn btn-primary mt-3 btn-lg" style="margin-top: 20px; margin-bottom: 5px;">
            Masuk
        </x-btn-submit-spinner>

        <p style="color: #fff; text-align:center;" class="mt-3">
            Belum punya akun? <a href="{{ url('register') }}" style="color: #23a2f6;">Daftar di sini</a>
        </p>

    </form>

    @include('components.page-modal')

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
                $("#form-login").validate({
                    rules: {
                        username: {
                            required: true,
                            minlength: 4,
                            maxlength: 50
                        },
                        password: {
                            required: true,
                            minlength: 5,
                            maxlength: 255
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
                                window.location = response.redirect;

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
