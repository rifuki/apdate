<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APDATE | Login Guru</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* CSS Kustom untuk Halaman Login */
        body.login-page {
            /* Gradient background seperti di contoh */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif; /* Menggunakan font Poppins */
        }

        .login-box {
            max-width: 850px;
            width: 100%;
        }

        .login-card {
            border: none;
            border-radius: 1rem; /* Sudut lebih tumpul */
            overflow: hidden; /* Penting agar gambar tidak keluar dari sudut tumpul */
        }

        .login-card-img {
            background-image: url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=2070&auto=format&fit=crop'); /* Ganti dengan URL gambar Anda */
            background-size: cover;
            background-position: center;
            border-top-left-radius: 1rem;
            border-bottom-left-radius: 1rem;
        }

        /* Styling untuk form di sisi kanan */
        .login-form-container {
            padding: 3rem;
        }

        /* Menghilangkan border pada input saat focus untuk tampilan yang lebih bersih */
        .form-control:focus {
            box-shadow: none;
            border-color: #764ba2;
        }
        
        .btn-login {
            background-color: #0d6efd; /* Warna biru primer Bootstrap */
            border: none;
            padding: 0.75rem;
            font-weight: 600;
        }

        .btn-login:hover {
            background-color: #0b5ed7;
        }

        /* Responsive: Sembunyikan gambar pada layar kecil (di bawah 768px) */
        @media (max-width: 767.98px) {
            .login-card-img {
                display: none;
            }
            .login-form-container {
                padding: 2rem;
            }
        }
    </style>
</head>
<body class="login-page">

    <div class="login-box p-3">
        <div class="card shadow-lg login-card">
            <div class="row g-0">
                <div class="col-md-6 login-card-img">
                </div>
                
                <div class="col-md-6 bg-light">
                    <div class="login-form-container">

                      <?php if ($this->session->flashdata('alert')): ?>
                        <div class="alert alert-danger" role="alert">
                          <?php echo $this->session->flashdata('alert') ?>
                        </div>
                      <?php endif ?>
                      <div class="text-center mb-4">
                          <img src="<?php echo base_url() ?>/assets/dist/images/logo.jpg" alt="Logo Sekolah" class="mb-3" style="width: 80px;">
                          <h4 class="fw-bold">APDATE</h4>
                          <p class="text-muted">Login Account</p>
                      </div>

                      <form action="<?php echo base_url() ?>guru/login" method="post">
                          <div class="form-floating mb-3">
                              <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
                              <label for="username">Username</label>
                          </div>
                          
                          <div class="form-floating mb-4">
                              <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                              <label for="password">Password</label>
                          </div>
                          
                          <div class="d-grid">
                              <button type="submit" class="btn btn-primary btn-lg btn-login">LOGIN</button>
                          </div>
                      </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>