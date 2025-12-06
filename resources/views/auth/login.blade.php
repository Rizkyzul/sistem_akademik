<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - SIAM IKMI</title>
    <link rel="icon" href="{{ asset('ikmi.png') }}" type="image/x-icon">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            /* Palette Modern */
            --primary-color: #4361ee;
            --primary-dark: #3a0ca3;
            --accent-color: #4cc9f0;
            --text-main: #2b2d42;
            --text-muted: #8d99ae;
            --white: #ffffff;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.6);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #0f172a;
            position: relative;
            overflow: hidden; /* Mencegah scrollbar karena animasi background */
        }

        /* --- Animated Background Elements --- */
        .bg-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: -1;
            animation: float 10s infinite alternate;
        }

        .shape-1 {
            top: -10%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, #4361ee, #7209b7);
            animation-delay: 0s;
        }

        .shape-2 {
            bottom: -10%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: linear-gradient(135deg, #4cc9f0, #4361ee);
            animation-delay: -5s;
        }

        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(30px, 50px) rotate(10deg); }
        }

        /* --- Login Container --- */
        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 1.5rem;
            z-index: 10;
        }

        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 3rem 2rem;
            box-shadow: var(--shadow-xl);
            text-align: center;
            position: relative;
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Dekorasi garis atas */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, var(--accent-color), var(--primary-color));
        }

        .logo {
            margin-bottom: 1.5rem;
            display: inline-block;
            position: relative;
        }

        .logo img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
            transition: transform 0.3s ease;
        }
        
        .logo:hover img {
            transform: scale(1.05) rotate(5deg);
        }

        .card-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }
        
        .card-header p {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        /* --- Floating Label Inputs --- */
        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .input-field {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem; /* Left padding for icon */
            font-size: 1rem;
            color: var(--text-main);
            background: rgba(255, 255, 255, 0.6);
            border: 2px solid transparent;
            border-radius: 12px;
            transition: var(--transition);
            font-family: 'Poppins', sans-serif;
        }

        .input-field:focus {
            outline: none;
            background: #fff;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.15);
        }

        /* Floating Label Logic */
        .floating-label {
            position: absolute;
            left: 3rem;
            top: 1rem;
            color: var(--text-muted);
            pointer-events: none;
            transition: 0.2s ease all;
            background-color: transparent;
            padding: 0 4px;
            font-size: 0.95rem;
        }

        .input-field:focus ~ .floating-label,
        .input-field:not(:placeholder-shown) ~ .floating-label {
            top: -0.6rem;
            left: 0.8rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--primary-color);
            background-color: #fff; /* Menutupi border */
            border-radius: 4px;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 1.1rem;
            color: var(--text-muted);
            font-size: 1.1rem;
            transition: var(--transition);
        }

        .input-field:focus ~ .input-icon {
            color: var(--primary-color);
        }

        /* Toggle Password Eye */
        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 1.1rem;
            color: var(--text-muted);
            cursor: pointer;
            transition: var(--transition);
        }
        
        .toggle-password:hover {
            color: var(--text-main);
        }

        /* --- Button --- */
        .btn-submit {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
            filter: brightness(1.1);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit:disabled {
            background: var(--text-muted);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* --- Alerts & Footer --- */
        .alert-box {
            margin-bottom: 1.5rem;
            display: none; /* Hidden by default */
        }
        
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: fadeIn 0.3s;
        }
        
        .alert-danger {
            background-color: #fef2f2;
            color: #ef4444;
            border: 1px solid #fee2e2;
        }

        .footer-text {
            margin-top: 2rem;
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        @media (max-width: 480px) {
            .login-card { padding: 2rem 1.5rem; }
            .bg-shape { display: none; } /* Hide heavy animation on mobile */
        }
    </style>
</head>

<body>
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="logo">
                <img src="{{ asset('ikmi.png') }}" alt="Logo IKMI">
            </div>
            
            <div class="card-header">
                <h1>Selamat Datang</h1>
                <p>Silakan masuk ke Portal Akademik Anda</p>
            </div>

            <div id="error-container" class="alert-box"></div>

            <form method="POST" action="{{ route('login.attempt') }}" id="login-form">
                @csrf
                
                <div class="form-group">
                    <input type="text" id="identifier" name="identifier" class="input-field" placeholder=" " value="{{ old('identifier') }}" required autofocus>
                    <label for="identifier" class="floating-label">Email atau NIM</label>
                    <i class="fas fa-user input-icon"></i>
                </div>

                <div class="form-group">
                    <input type="password" id="password" name="password" class="input-field" placeholder=" " required>
                    <label for="password" class="floating-label">Password</label>
                    <i class="fas fa-lock input-icon"></i>
                    <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                </div>

                <button type="submit" class="btn-submit" id="submit-button">
                    <span class="btn-text">Masuk Sekarang</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="footer-text">
                &copy; {{ date('Y') }} Sistem Informasi Akademik STMIK IKMI
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            // 1. Password Toggle Visibility
            $('#togglePassword').on('click', function() {
                const passwordInput = $('#password');
                const icon = $(this);
                
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // 2. Login Logic
            $('#login-form').on('submit', function (e) {
                e.preventDefault();
                const form = $(this);
                const submitButton = $('#submit-button');
                const btnText = submitButton.find('.btn-text');
                const btnIcon = submitButton.find('.fa-arrow-right');
                const errorContainer = $('#error-container');

                // Loading State
                submitButton.prop('disabled', true);
                btnText.text('Memproses...');
                btnIcon.attr('class', 'fas fa-circle-notch fa-spin'); // Loading spinner icon
                errorContainer.slideUp();

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function (response) {
                        if (response.success) {
                            // SweetAlert Success
                            Swal.fire({
                                icon: 'success',
                                title: 'Login Berhasil!',
                                text: 'Mengalihkan ke dashboard...',
                                timer: 1500,
                                timerProgressBar: true,
                                showConfirmButton: false,
                                backdrop: `rgba(0,0,123,0.4)`
                            });
                            
                            setTimeout(function() {
                                window.location.href = response.redirect_url;
                            }, 1500);
                        }
                    },
                    error: function (xhr) {
                        // Reset State
                        submitButton.prop('disabled', false);
                        btnText.text('Masuk Sekarang');
                        btnIcon.attr('class', 'fas fa-arrow-right');

                        let errorMessage = 'Terjadi kesalahan sistem. Silakan coba lagi.';
                        if (xhr.status === 422 && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        // Tampilkan Error di Container dengan animasi
                        const errorHtml = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>${errorMessage}</span>
                            </div>
                        `;
                        errorContainer.html(errorHtml).slideDown();
                        
                        // Shake Animation pada card jika error
                        $('.login-card').addClass('animate__animated animate__shakeX');
                        setTimeout(() => {
                            $('.login-card').removeClass('animate__animated animate__shakeX');
                        }, 1000);
                    }
                });
            });
        });
    </script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</body>

</html>