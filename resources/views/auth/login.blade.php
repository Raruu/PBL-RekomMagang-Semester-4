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
        <div class="position-relative" style="pointer-events: none; user-select: none;">
            <h3 class="fw-bolder">Sistem Rekomendasi Magang</h3>
            <img src="{{ asset('imgs/shigure-ui.webp') }}" alt="" class="img-fluid w-50 position-absolute"
                id="picture-preview" style="bottom: -30px; right: -20px;">
        </div>

        <label for="username">Username / Email</label>
        <div class="input-group">
            <input type="text" name="username" id="username" placeholder="Masukkan username atau email" class="form-control">
            <span id="error-username" class="error-text text-danger"></span>
        </div>

        <label for="password">Password</label>
        <div class="input-group">
            <input type="password" name="password" id="password" placeholder="Masukkan password" class="form-control">
            <span id="error-password" class="error-text text-danger"></span>
        </div>
        <button id="btn-submit" type="submit" class="btn btn-primary mt-3 btn-lg"
            style="margin-top: 20px; margin-bottom: 5px;">
            <span id="btn-submit-text">Log In</span>
            @include('components.btn-submit-spinner')
        </button>



        <p style="color: #fff; text-align:center;" class="mt-3">
            Belum punya akun? <a href="{{ url('register') }}" style="color: #23a2f6;">Daftar di sini</a>
        </p>

    </form>

    @include('components.page-modal')

    <script>
        const run = () => {
            const modalElement = document.getElementById('page-modal');
            const btnSpiner = document.getElementById('btn-submit-spinner');              
            modalElement.addEventListener('hidden.coreui.modal', function(event) {
                document.getElementById('btn-submit-text').classList.remove('d-none');
                btnSpiner.classList.add('d-none');
                btnSpiner.closest('button').disabled = false;
                setTimeout(() => {
                    document.getElementById('cus-bg').style.backgroundColor = "";
                }, 500);
            });

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
                                if (response.status) {
                                    window.location = response.redirect;
                                } else {
                                    console.log(response);
                                    document.getElementById('cus-bg').style
                                        .backgroundColor =
                                        "red";
                                    const modal = new coreui.Modal(modalElement);
                                    const modalTitle = modalElement.querySelector(
                                        '.modal-title')
                                    modalTitle.textContent = response.status ?
                                        'Berhasil' : 'Gagal';
                                    modalElement.querySelector('.modal-body')
                                        .textContent = response.message;

                                    let errorMsg = '\n';
                                    $.each(response.msgField, function(prefix, val) {
                                        $('#error-' + prefix).text(val[0]);
                                        errorMsg += val[0] + '\n';
                                    });
                                    modalElement.querySelector('.modal-body')
                                        .innerHTML += errorMsg.replace(/\n/g, '<br>');
                                    modal.show();
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
