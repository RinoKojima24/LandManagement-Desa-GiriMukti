<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

    <style>
        body {
            font-family: Arial;
            background: #f3f3f3;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            background: white;
            padding: 20px;
            width: 320px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .btn {
            background: #1e88e5;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
        }
        .otp-container {
            display: flex;
            justify-content: space-between;
        }
        .otp-input {
            width: 60px;
            padding: 12px;
            text-align: center;
            font-size: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
    </style>
</head>
<body style="background: linear-gradient(135deg, #e8f5e8 0%, #f0f8f0 100%);">

<div class="card" id="registerForm" style="background: linear-gradient(135deg, #e8f5e8 0%, #f0f8f0 100%);">
    <div class="logo">
        <span>🏛️</span>
    </div>

    <h1 class="title" style="text-align: center; font-size: 18px;"><b>Desa Girimukti</b></h1>
    <p class="subtitle" style="text-align: center; font-size: 12px;">
        Memanfaatkan sistem dan mengolah administrasi
        dengan pembaharuan sistem yang memungkinkan
        efektif dan efisien dalam mengolah data segala
        administrasi dan mengoptimalkan sistem
    </p>
    <hr>
    <div class="login-section">
        <h2 class="login-title" style="text-align: center; font-size: 15px;"><b>Registrasi</b></h2>
        <p class="login-desc" style="text-align: center; font-size: 13px;">Lengkapi form dibawah</p>
        <input type="text" id="nama" placeholder="Nama Lengkap">
        <input type="text" id="nik" placeholder="NIK">
        <input type="email" id="email" placeholder="Email">
        <input type="text" id="telepon" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="Nomor Telepon">
        <label for="">Foto KTP</label>
        <input type="file" id="foto_ktp">
        <button class="btn" id="Lanjutkan">Lanjutkan</button>
    </div>
    <span style="text-align: center;">Sudah Register? <a href="{{ url('login') }}"> Login</a></span>

</div>

<div id="otpForm" style="display:none;">
    <div class="card" style="background: linear-gradient(135deg, #e8f5e8 0%, #f0f8f0 100%);">
        <div class="logo">
            <span>🏛️</span>
        </div>

        <h1 class="title" style="text-align: center; font-size: 18px;"><b>Desa Girimukti</b></h1>
        <p class="subtitle" style="text-align: center; font-size: 12px;">
            Memanfaatkan sistem dan mengolah administrasi
            dengan pembaharuan sistem yang memungkinkan
            efektif dan efisien dalam mengolah data segala
            administrasi dan mengoptimalkan sistem
        </p>
        <hr>
        <div class="login-section">
            <h2>Masukkan OTP</h2>
            <p>Kode OTP telah dikirim (contoh: 1234)</p>

            <div class="otp-container">
                <input maxlength="1" class="otp-input" oninput="moveNext(this, 'otp2')" onkeydown="handleBackspace(event, null, 'otp1')" id="otp1">
                <input maxlength="1" class="otp-input" oninput="moveNext(this, 'otp3')" onkeydown="handleBackspace(event, 'otp1', 'otp2')" id="otp2">
                <input maxlength="1" class="otp-input" oninput="moveNext(this, 'otp4')" onkeydown="handleBackspace(event, 'otp2', 'otp3')" id="otp3">
                <input maxlength="1" class="otp-input" onkeydown="handleBackspace(event, 'otp3', 'otp4')" id="otp4">
            </div>

            <button class="btn" id="verifikasiOTP">Verifikasi</button>
        </div>
    </div>
    <br>
    <center>
        <span>Ingin Register Ulang? <a href="{{ url('register') }}">Kembali Register</a></span>
    </center>
</div>

<!-- LOADING -->
<div id="loading" style="
    display:none;
    position:fixed;
    top:0; left:0; right:0; bottom:0;
    background:rgba(0,0,0,0.6);
    color:white;
    font-size:20px;
    text-align:center;
    padding-top:20%;
">
    <div>Sedang memproses...</div>
</div>

<!-- NOTIFIKASI -->
<div id="notif" style="
    display:none;
    position:fixed;
    top:20px; right:20px;
    padding:10px 15px;
    background:green;
    color:white;
    border-radius:5px;">
</div>

<!-- Bootstrap 4 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Pindah ke kolom berikutnya setelah isi 1 digit
    function moveNext(current, nextId) {
        if (current.value.length === 1) {
            document.getElementById(nextId).focus();
        }
    }

    // Tekan backspace → kembali ke kolom sebelumnya
    function movePrev(e, prevId) {
        if (e.key === "Backspace" && e.target.value === "") {
            document.getElementById(prevId).focus();
        }
    }

    function handleBackspace(e, prevId, currentId) {
        if (e.key === "Backspace") {
            let current = document.getElementById(currentId);

            // Jika masih ada isi → hapus dulu tapi jangan pindah
            if (current.value !== "") {
                current.value = "";
                e.preventDefault();
                return;
            }

            // Jika kolom kosong dan ada kolom sebelumnya → pindah & hapus sebelumnya
            if (prevId) {
                let prev = document.getElementById(prevId);
                prev.value = "";     // hapus isi sebelumnya
                prev.focus();        // pindah fokus
                e.preventDefault();
            }
        }
    }


    // fungsi notif
    function showNotif(text, color = "green") {
        $("#notif")
            .text(text)
            .css("background", color)
            .fadeIn();

        setTimeout(() => $("#notif").fadeOut(), 2000);
    }

    $(document).ready(function() {

        $('#Lanjutkan').click(function() {
            const nama = $('#nama').val();
            const nik = $('#nik').val();
            const email = $('#email').val();
            const telepon = $('#telepon').val();

            $("#loading").show();
            $.ajax({
                url: "/register/send-otp",
                type: "POST",
                data: {
                    nama: nama,
                    email: email,
                    telepon: telepon,
                    nik: nik,

                    type: 'register',
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {
                    $("#loading").hide();
                    if (res.status === "ok") {
                        $("#registerForm").hide();
                        $("#otpForm").show();
                        $("#otp1").focus();
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        $("#loading").hide();
                        // Laravel validation errors
                        let errors = xhr.responseJSON.errors;
                        let message = "";

                        if (errors.nama) message += errors.nama[0] + "\n";
                        if (errors.email) message += errors.email[0] + "\n";
                        if (errors.telepon) message += errors.telepon[0] + "\n";

                        alert(message); // tampilkan error
                    } else {
                        alert("Terjadi kesalahan server!");
                    }
                }
            });
        });

        $('#verifikasiOTP').click(function() {
            $("#loading").show();
            const nama = $('#nama').val();
            const nik = $('#nik').val();
            const email = $('#email').val();
            const telepon = $('#telepon').val();

            let formData = new FormData();

            // text field
            formData.append('nama', $('#nama').val());
            formData.append('nik', $('#nik').val());
            formData.append('email', $('#email').val());
            formData.append('telepon', $('#telepon').val());
                        // file
            let file = $('#foto_ktp')[0].files[0];
            formData.append('foto_ktp', file);
            const otp =
                $("#otp1").val() +
                $("#otp2").val() +
                $("#otp3").val() +
                $("#otp4").val();
            formData.append('otp', otp);
            formData.append('type', "register");
            formData.append('_token', "{{ csrf_token() }}");





            $.ajax({
                url: "/register/check-otp",
                type: "POST",
                data: formData,
                contentType: false,   // WAJIB
                processData: false,   // WAJIB
                success: function(res) {
                    $("#loading").hide();


                    if (res.status === "ok") {
                        console.log(res);
                        // alert("Registrasi berhasil!");
                        showNotif("Register berhasil (Mohon ditunggu konfirmasi admin)!");

                        setTimeout(() => {
                            window.location.href = "{{ url('login') }}";
                        }, 3000);
                    } else {
                        // alert("OTP salah!");
                        showNotif(res.message);
                    }
                }
            });
        });


    });
</script>

</body>

</html>
