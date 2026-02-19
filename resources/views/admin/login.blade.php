<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Komunitas Mekanik Indonesia</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica Neue', sans-serif;
            background: linear-gradient(135deg, #0052A3 0%, #003D7A 50%, #1a1a2e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(230, 57, 70, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }

        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 215, 0, 0.05) 0%, transparent 70%);
            border-radius: 50%;
            bottom: -50px;
            left: -50px;
        }

        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 2rem;
            transition: all 0.3s;
            padding: 0.5rem 1rem;
            border-radius: 6px;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(-5px);
        }

        .back-button i {
            font-size: 1.2rem;
        }

        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 3rem;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #E63946 0%, #A4161A 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 20px rgba(230, 57, 70, 0.3);
        }

        .login-icon i {
            font-size: 2.5rem;
            color: white;
        }

        .login-header h1 {
            font-size: 1.8rem;
            color: #333;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #666;
            font-size: 0.95rem;
        }

        .alert {
            background: #fee;
            border: 2px solid #E63946;
            color: #A4161A;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
        }

        .form-group input {
            width: 100%;
            padding: 0.9rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            background: #f9f9f9;
        }

        .form-group input:focus {
            outline: none;
            border-color: #E63946;
            background: white;
            box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.1);
        }

        .form-group input::placeholder {
            color: #999;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }

        .remember-forgot a {
            color: #E63946;
            text-decoration: none;
            transition: color 0.3s;
        }

        .remember-forgot a:hover {
            color: #A4161A;
        }

        .btn-login {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #E63946 0%, #A4161A 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 8px 20px rgba(230, 57, 70, 0.2);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(230, 57, 70, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
            font-size: 0.9rem;
        }

        .login-footer a {
            color: #E63946;
            text-decoration: none;
            font-weight: 600;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 2rem;
            }

            .login-header h1 {
                font-size: 1.5rem;
            }

            .login-icon {
                width: 60px;
                height: 60px;
            }

            .login-icon i {
                font-size: 2rem;
            }

            .form-group input {
                padding: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <a href="{{ route('home') }}" class="back-button">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Website
        </a>

        <div class="login-card">
            <div class="login-header">
                <div class="login-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h1>Admin Panel</h1>
                <p>Masuk dengan kredensial admin Anda</p>
            </div>

            @if($errors->any())
                <div class="alert">
                    @foreach($errors->all() as $error)
                        <i class="fas fa-exclamation-circle"></i> {{ $error }}
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf

                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user" style="margin-right: 0.5rem; color: #E63946;"></i>Username
                    </label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-key" style="margin-right: 0.5rem; color: #E63946;"></i>Password
                    </label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                </div>

                <div class="remember-forgot">
                    <label style="display: flex; align-items: center; gap: 0.5rem; color: #666; cursor: pointer; font-weight: 400;">
                        <input type="checkbox" name="remember" style="width: auto; cursor: pointer;">
                        Ingat saya
                    </label>
                    <a href="#">Lupa password?</a>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt" style="margin-right: 0.5rem;"></i>LOGIN
                </button>
            </form>

            <div class="login-footer">
                Butuh bantuan? <a href="https://wa.me/628214693145" target="_blank">Hubungi Admin</a>
            </div>
        </div>
    </div>
</body>
</html>
