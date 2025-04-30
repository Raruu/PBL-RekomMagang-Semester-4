<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Register Pengguna</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.css') }}">

    <!-- jQuery -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <style media="screen">
        *,
        *:before,
        *:after {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            background-color: rgb(66, 47, 208);

        }

        .background {
            width: 430px;
            height: 520px;
            position: absolute;
            transform: translate(-50%, -50%);
            left: 50%;
            top: 50%;
        }

        .background .shape {
            height: 200px;
            width: 200px;
            position: absolute;
            border-radius: 50%;
        }

        .shape:first-child {
            background: linear-gradient(#1845ad, #23a2f6);
            left: -80px;
            top: -80px;
        }

        .shape:last-child {
            background: linear-gradient(to right, #ff512f, #f09819);
            right: -30px;
            bottom: -80px;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.13);
            padding: 50px 35px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 40px rgba(8, 7, 16, 0.6);
        }

        form {
            height: 850px;
            width: 400px;
            background-color: rgba(159, 5, 5, 0.13);
            position: absolute;
            transform: translate(-50%, -50%);
            top: 50%;
            left: 50%;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 40px rgba(22, 22, 26, 0.6);
            padding: 50px 35px;
        }

        form * {
            font-family: 'Poppins', sans-serif;
            color: rgb(252, 252, 252);
            letter-spacing: 0.5px;
            outline: none;
            border: none;
        }

        form h3 {
            font-size: 32px;
            font-weight: 500;
            line-height: 42px;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 30px;
            font-size: 16px;
            font-weight: 500;
        }

        input,
        select {
            display: block;
            height: 50px;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.07);
            border-radius: 3px;
            padding: 0 10px;
            margin-top: 8px;
            font-size: 14px;
            font-weight: 300;
        }

        ::placeholder {
            color: #e5e5e5;
        }

        button {
            margin-top: 50px;
            width: 100%;
            background-color: #ffffff;
            color: #080710;
            padding: 15px 0;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <form action="{{ url('/register') }}" method="POST" id="form-register">
        @csrf
        <h3>Daftar Akun Baru</h3>

        <!-- Level Pengguna -->
        <label for="level_id">Level Pengguna</label>
        <select name="level_id" class="form-control" id="level_id">
            <option value="" disabled selected style="color: #999;">Silakan Pilih Hak Akses</option>

            @foreach($level as $l)
            <option value="{{ $l->level_id }}" style="color: #000;">{{ $l->level_nama }}</option>
            @endforeach

        </select>
        <small id="error-level_id" class="text-danger error-text"></small>

        <!-- Username -->
        <label for="username">Username</label>
        <input name="username" class="form-control" id="username">
        <small id="error-username" class="text-danger error-text"></small>

        <!-- Nama -->
        <label for="nama">Nama</label>
        <input name="nama" class="form-control" id="nama">
        <small id="error-nama" class="text-danger error-text"></small>

        <!-- Password -->
        <label for="password">Password</label>
        <input name="password" type="password" class="form-control" id="password">
        <small id="error-password" class="text-danger error-text"></small>

        <!-- Konfirmasi Password -->
        <label for="password_confirmation">Konfirmasi Password</label>
        <input name="password_confirmation" type="password" class="form-control" id="password_confirmation">

        <!-- Aksi -->
        <div class="row mt-4">
            <div class="col-6">
                <a href="{{ url('/login') }}" style="color: #23a2f6;">Sudah punya akun? Silahkan Login</a>
            </div>
            <div class="col-6">
                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </div>
        </div>
    </form>


</body>
<!-- jQuery -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- jquery-validation -->
<script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>


</html>
<script>
    $(document).ready(function() {
        $('#form-register').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.status) {
                        Swal.fire('Berhasil', res.message, 'success').then(() => {
                            window.location.href = res.redirect;
                        });
                    } else {
                        $('.error-text').text('');
                        $.each(res.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                        Swal.fire('Gagal', res.message, 'error');
                    }
                }
            });
        });
    });
</script>

</html>