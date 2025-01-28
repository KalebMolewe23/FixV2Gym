<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/remixicon@3.4.0/fonts/remixicon.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="{{ asset('assets/frontend/styles.css') }}" />
    <title>Web Design Mastery | Fitclub</title>

    <style>
      .logo-container {
          display: flex;
          align-items: center;
          text-decoration: none; /* Menghilangkan garis bawah link */
      }

      .logo-container img {
          margin-right: 10px; /* Spasi antara gambar dan teks */
      }

      .logo-container span {
          font-size: 18px; /* Atur ukuran font */
          color: #000; /* Warna teks */
      }
  </style>
  </head>
  <body>
    <nav>
      <div class="nav__logo">
      <a href="#" class="logo-container">
        <img src="{{ asset('assets/images/logo_gym.png') }}" alt="logo" style="width:25%;" />
        <span style="color:white"><strong>ELITE FITNESS</strong></span>
      </a>
      </div>
      <ul class="nav__links" id="menu-nav">
        <li class="link" id="menu-home"><a href="#header">Home</a></li>
        <li class="link" id="menu-program"><a href="#program">Program</a></li>
        <li class="link" id="menu-service"><a href="#service">Service</a></li>
        <li class="link" id="menu-about"><a href="#about">About</a></li>
        <li class="link" id="menu-community"><a href="#community">Community</a></li>
    </ul>

      <button class="btn" style="color:black">Join Now</button>
    </nav>

    <header class="section__container header__container">
      <div class="header__content">
        <span class="bg__blur"></span>
        <span class="bg__blur header__blur"></span>
        <h4>BEST FITNESS IN THE TOWN</h4>
        <h1><span>MAKE</span> YOUR BODY SHAPE</h1>
        <p>
            Bebaskan potensi Anda dan mulailah bentuk tubuh yang lebih kuat, lebih bugar, dan lebih percaya diri. Daftar 'Bentuk Tubuh Anda' sekarang dan saksikan transformasi luar biasa yang dapat dilakukan oleh tubuh Anda!
        </p>
        <!-- <button class="btn">Get Started</button> -->
      </div>
      <div class="header__image">
        <img src="{{ asset('assets/frontend/assets/header.png') }}" alt="header" />
      </div>
    </header>

    <section id="program" class="section__container explore__container">
      <div class="explore__header">
        <h2 class="section__header">JELAJAHI PROGRAM KAMI</h2>
        <!-- <div class="explore__nav">
          <span><i class="ri-arrow-left-line"></i></span>
          <span><i class="ri-arrow-right-line"></i></span>
        </div> -->
      </div>
      <div class="explore__grid">
        <div class="explore__card">
          <span><i class="ri-boxing-fill" style="color:black"></i></span>
          <h4>Strength</h4>
          <p>
            Rangkullah esensi kekuatan saat kita menyelami berbagai dimensinya secara fisik, mental, dan emosional.
          </p>
          <!-- <a href="#">Join Now <i class="ri-arrow-right-line"></i></a> -->
        </div>
        <div class="explore__card">
          <span><i class="ri-heart-pulse-fill" style="color:black"></i></span>
          <h4>Physical Fitness</h4>
          <p>
            Mencakup berbagai aktivitas untuk meningkatkan kesehatan, kekuatan, fleksibilitas, dan kesejahteraan secara keseluruhan.
          </p>
        </div>
        <div class="explore__card">
          <span><i class="ri-run-line" style="color:black"></i></span>
          <h4>Fat Lose</h4>
          <p>
            Melalui kombinasi rutinitas latihan dan bimbingan ahli, kami akan memberdayakan Anda untuk mencapai tujuan Anda.
          </p>
        </div>
        <div class="explore__card">
          <span><i class="ri-shopping-basket-fill" style="color:black"></i></span>
          <h4>Weight Gain</h4>
          <p>
            Dirancang untuk individu, program kami menawarkan pendekatan yang efektif untuk menambah berat badan secara berkelanjutan.
          </p>
        </div>
      </div>
    </section>

    <section id="service" class="section__container class__container">
      <div class="class__image">
        <span class="bg__blur"></span>
        <img src="{{ asset('assets/frontend/assets/class-1.jpg') }}" alt="class" class="class__img-1" />
        <img src="{{ asset('assets/frontend/assets/class-2.jpg') }}" alt="class" class="class__img-2" />
      </div>
      <div class="class__content">
        <h2 class="section__header">THE CLASS YOU WILL GET HERE</h2>
        <p>
            Dipimpin oleh tim instruktur ahli dan motivator kami, “Kelas yang Akan Anda Dapatkan di Sini” adalah sesi berenergi tinggi dan berorientasi pada hasil yang menggabungkan perpaduan sempurna antara latihan kardio, latihan kekuatan, dan latihan fungsional. Setiap kelas dirancang dengan cermat untuk membuat Anda tetap terlibat dan tertantang, memastikan Anda tidak pernah mencapai titik terendah dalam upaya kebugaran Anda.
        </p>
        <!-- <button class="btn">Book A Class</button> -->
      </div>
    </section>

    <section id="about" class="section__container join__container">
      <h2 class="section__header">WHY JOIN US ?</h2>
      <p class="section__subheader">
        Basis keanggotaan kami yang beragam menciptakan suasana yang bersahabat dan suportif, di mana Anda dapat menjalin pertemanan dan tetap termotivasi.
      </p>
      <div class="join__image">
        <img src="{{ asset('assets/frontend/assets/join.jpg') }}" alt="Join" />
        <div class="join__grid">
          <div class="join__card">
            <span><i class="ri-user-star-fill" style="color:black"></i></span>
            <div class="join__card__content">
              <h4>Personal Trainer</h4>
              <p>Buka potensi Anda dengan Personal Trainer ahli kami.</p>
            </div>
          </div>
          <div class="join__card">
            <span><i class="ri-vidicon-fill" style="color:black"></i></span>
            <div class="join__card__content">
              <h4>Practice Sessions</h4>
              <p>Tingkatkan kebugaran Anda dengan sesi latihan.</p>
            </div>
          </div>
          <div class="join__card">
            <span><i class="ri-building-line" style="color:black"></i></span>
            <div class="join__card__content">
              <h4>Good Management</h4>
              <p>Manajemen yang mendukung, untuk kesuksesan kebugaran Anda.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section__container price__container">
      <h2 class="section__header">OUR PRICING PLAN</h2>
      <p class="section__subheader">
        Berikut paket harga kami dengan berbagai tingkatan sesuai kebutuhan Anda, masing-masing disesuaikan untuk memenuhi preferensi dan aspirasi kebugaran yang berbeda.
      </p>
      <div class="price__grid">
        <div class="price__card">
          <div class="price__card__content">
            <h4>Paket Harian</h4>
            <h3>Rp{{ number_format($daily->price, 0, ',', '.') }}</h3>
            <p>
              <i class="ri-checkbox-circle-line"></i>
              Smart workout plan
            </p>
            <p>
              <i class="ri-checkbox-circle-line"></i>
              At home workouts
            </p>
          </div>
        </div>
        <div class="price__card">
          <div class="price__card__content">
            <h4>Paket Bulanan</h4>
            <h3>Rp{{ number_format($montly->price, 0, ',', '.') }}</h3>
            <p>
              <i class="ri-checkbox-circle-line"></i>
              PRO Gyms
            </p>
            <p>
              <i class="ri-checkbox-circle-line"></i>
              Smart workout plan
            </p>
            <p>
              <i class="ri-checkbox-circle-line"></i>
              At home workouts
            </p>
          </div>
        </div>
        <div class="price__card">
          <div class="price__card__content">
            <h4>Paket Trainer</h4>
            <h3>Rp{{ number_format($trainer->price, 0, ',', '.') }}</h3>
            <p>
              <i class="ri-checkbox-circle-line"></i>
              ELITE Gyms & Classes
            </p>
            <p>
              <i class="ri-checkbox-circle-line"></i>
              PRO Gyms
            </p>
            <p>
              <i class="ri-checkbox-circle-line"></i>
              Smart workout plan
            </p>
            <p>
              <i class="ri-checkbox-circle-line"></i>
              At home workouts
            </p>
            <p>
              <i class="ri-checkbox-circle-line"></i>
              Personal Training
            </p>
          </div>
        </div>
      </div>
    </section>

    <section id="community" class="review">
      <div class="section__container review__container">
        <span><i class="ri-double-quotes-r"></i></span>
        @foreach($review as $index => $v_review)
            <div class="review__content" data-index="{{ $index }}" style="{{ $index === 0 ? '' : 'display: none;' }}">
                <h4>MEMBER REVIEW</h4>
                <p>
                    {{ $v_review->description }}
                </p>
                <div class="review__rating">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($v_review->start))
                            <span><i class="ri-star-fill"></i></span> <!-- Full Star -->
                        @elseif($i - $v_review->start < 1)
                            <span><i class="ri-star-half-fill"></i></span> <!-- Half Star -->
                        @else
                            <span><i class="ri-star-line"></i></span> <!-- Empty Star -->
                        @endif
                    @endfor
                </div>
                <div class="review__footer">
                    <div class="review__member">
                        <img src="{{ asset('storage/' . $v_review->photo) }}" alt="member">
                        <div class="review__member__details">
                            <h4>{{ $v_review->name }}</h4>
                        </div>
                    </div>
                    <div class="review__nav">
                        <span class="nav-left"><i class="ri-arrow-left-line"></i></span>
                        <span class="nav-right"><i class="ri-arrow-right-line"></i></span>
                    </div>
                </div>
            </div>
        @endforeach
      </div>
    </section>

    <footer class="section__container footer__container">
      <span class="bg__blur"></span>
      <span class="bg__blur footer__blur"></span>
      <div class="footer__col">
        <div class="footer__logo">
          <a href="#" class="logo-container">
            <img src="{{ asset('assets/images/logo_gym.png') }}" alt="logo" style="width:25%;" />
            <span style="color:white"><strong>ELITE FITNESS</strong></span>
          </a>
        </div>
        <p>
          Take the first step towards a healthier, stronger you with our
          unbeatable pricing plans. Let's sweat, achieve, and conquer together!
        </p>
        <div class="footer__socials">
          <a href="#"><i class="ri-facebook-fill"></i></a>
          <a href="#"><i class="ri-instagram-line"></i></a>
          <a href="#"><i class="ri-twitter-fill"></i></a>
        </div>
      </div>
      <div class="footer__col">
        <h4>Company</h4>
        <a href="#">Business</a>
        <a href="#">Franchise</a>
        <a href="#">Partnership</a>
        <a href="#">Network</a>
      </div>
      <div class="footer__col">
        <h4>About Us</h4>
        <a href="#">Blogs</a>
        <a href="#">Security</a>
        <a href="#">Careers</a>
      </div>
      <div class="footer__col">
        <h4>Contact</h4>
        <a href="#">Contact Us</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Terms & Conditions</a>
        <a href="#">BMI Calculator</a>
      </div>
    </footer>
    <div class="footer__bar">
      Copyright © 2023 Web Design Mastery. All rights reserved.
    </div>
    <script src="{{ asset('assets/frontend/script.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let currentIndex = 0; // Mulai dari review pertama
            const reviews = document.querySelectorAll('.review__content');

            // Fungsi untuk memperbarui tampilan
            function showReview(index) {
                reviews.forEach((review, i) => {
                    review.style.display = i === index ? 'block' : 'none';
                });
            }

            // Navigasi ke kiri
            document.querySelectorAll('.nav-left').forEach(button => {
                button.addEventListener('click', () => {
                    currentIndex = (currentIndex - 1 + reviews.length) % reviews.length;
                    showReview(currentIndex);
                });
            });

            // Navigasi ke kanan
            document.querySelectorAll('.nav-right').forEach(button => {
                button.addEventListener('click', () => {
                    currentIndex = (currentIndex + 1) % reviews.length;
                    showReview(currentIndex);
                });
            });

            // Tampilkan review pertama kali
            showReview(currentIndex);
        });
    </script>
  </body>

</html>