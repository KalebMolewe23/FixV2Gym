<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Fillow : Fillow Saas Admin Bootstrap 5 Template">
    <meta property="og:title" content="Fillow : Fillow Saas Admin Bootstrap 5 Template">
    <meta property="og:description" content="Fillow : Fillow Saas Admin Bootstrap 5 Template">
    <meta property="og:image" content="https://fillow.dexignlab.com/xhtml/social-image.png">
    <meta name="format-detection" content="telephone=no">
    
    <title>@yield('title', 'Informasi')</title>
    
    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link href="{{ asset('assets/vendor/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/owl-carousel/owl.carousel.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/vendor/nouislider/nouislider.min.css') }}">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>
<div class="container" style="border: 5px solid #000; padding: 20px; border-radius: 10px; margin-top: 30px;">
    <h1 style="text-align:center"><strong>Informasi Member</strong></h1>
    <div class="d-flex justify-content-center">
    <img 
        src="{{ $member->photo ? asset('storage/' . $member->photo) : asset('assets/images/' . ($v_active->gender == 'man' ? 'man.png' : 'woman.png')) }}" 
        alt="User Photo" 
        class="img-fluid rounded-circle" 
        style="width: 100px; height: 100px; object-fit: cover;"
    >
    </div>
    <div style="text-align:center;margin-top:50px;">
        <div class="row">
            <div class="col">
                <p><strong>Nama:</strong> {{ $member->name }}</p>
            </div>
            <div class="col">
                <p><strong>Nomor Telepon:</strong> {{ $member->notelp }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <!-- Registrasi: Mengambil created_at dan format waktu Indonesia -->
                <p><strong>Registrasi:</strong> 
                    {{ \Carbon\Carbon::parse($member->created_at)->timezone('Asia/Jakarta')->translatedFormat('j F Y') }}
                </p>
            </div>
            <div class="col">
            <!-- Tanggal Selesai: Menghitung berdasarkan idpaket -->
            <p><strong>Tanggal Selesai:</strong> 
                @php
                    if ($member->idpaket == 1) {
                        $endDate = \Carbon\Carbon::parse($member->created_at)->addDay();
                    } elseif ($member->idpaket == 2) {
                        $endDate = \Carbon\Carbon::parse($member->created_at)->addMonth();
                    } else {
                        $endDate = null;
                    }
                @endphp

                @if ($endDate)
                    {{ $endDate->translatedFormat('j F Y') }}
                @else
                    Tidak ada informasi paket
                @endif
            </p>
        </div>
        </div>
        <h3 style="margin-top:50px">Sisa Hari:</h3>
        @if ($member->end_date)
            @php
                $today = \Carbon\Carbon::now('Asia/Jakarta')->startOfDay(); // Hari ini (00:00)
                $endDate = \Carbon\Carbon::parse($member->end_date)->startOfDay();
                $daysRemaining = $endDate->diffInDays($today, false); // Hitung selisih hari
            @endphp
            @if ($daysRemaining > 0)
                {{ $daysRemaining }} hari lagi
            @elseif ($daysRemaining === 0)
                Berakhir hari ini
            @else
                Sudah berakhir {{ abs($daysRemaining) }} hari yang lalu
            @endif
        @else
            Tidak ada informasi paket
        @endif
    </div>
</div>

</body>
</html>