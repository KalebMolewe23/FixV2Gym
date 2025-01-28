<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Member</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }

        .card {
            width: 54mm; /* Lebar kartu ID (potret) */
            height: 80mm; /* Menurunkan tinggi kartu untuk memberikan ruang lebih banyak */
            border: 1px solid #000;
            border-radius: 5px;
            overflow: hidden;
            position: relative;
            background-image: url('{{ asset("assets/images/bg_barcodee.jpeg") }}'); /* Gambar latar belakang */
            background-size: 100% 100%; /* Menurunkan tinggi gambar agar tidak terpotong */
            background-position: center;
            background-repeat: no-repeat;
        }

        .content {
            position: absolute;
            top: 50%; /* Menempatkan konten pada posisi tengah vertikal */
            left: 50%;
            transform: translate(-50%, -50%); /* Mengatur konten agar benar-benar di tengah */
            width: 100%;
            text-align: center;
        }

        .content h2 {
            margin: 0;
            font-size: 10px;
            font-weight: bold;
        }

        .content p {
            margin: 5px 0;
            font-size: 8px;
        }

        .barcode {
            margin-top: -0mm; /* Menambahkan jarak atas agar barcode sedikit lebih tinggi */
        }

        .barcode img {
            width: 100%; /* Membuat barcode lebih besar dan menyesuaikan lebar kartu */
            max-width: 120px; /* Maksimal lebar barcode */
            height: auto;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                background-color: #fff;
            }

            .card {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="content">
            <h2 style="color:white;font-size:20px;margin-top:-10px;">{{ $member->name }}</h2>
            <div class="barcode">
                <img src="{{ asset('assets/images/barcode/' . $member->barcode . '_qrcode.png') }}" alt="Barcode">
            </div>
        </div>
    </div>

    <script>
        // Cetak otomatis setelah halaman dimuat
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>