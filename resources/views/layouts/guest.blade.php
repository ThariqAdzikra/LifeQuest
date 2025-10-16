<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'HabitQuest - Authentication' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(10, 14, 39, 0.8), rgba(26, 26, 46, 0.9)), 
                        url('{{ asset("images/heroLanding.jpg") }}') center/cover fixed;
            color: #e0e0e0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(0, 212, 255, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(167, 139, 250, 0.15) 0%, transparent 50%);
            pointer-events: none;
            z-index: 1;
        }

        .auth-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 480px;
            animation: fadeInUp 0.8s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, #00d4ff, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 2px;
            text-shadow: 0 0 20px rgba(0, 212, 255, 0.5);
            margin-bottom: 0.5rem;
            display: inline-block;
        }

        .logo-subtitle {
            color: #b0b0c0;
            font-size: 0.95rem;
            letter-spacing: 1px;
        }

        .auth-card {
            background: rgba(15, 20, 50, 0.4);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 212, 255, 0.3);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5),
                        0 0 40px rgba(0, 212, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #00d4ff, #a78bfa, #ff006e);
            opacity: 0.8;
        }

        .auth-card::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(0, 212, 255, 0.05) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 0.5;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .auth-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
            background: linear-gradient(135deg, #00d4ff, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 1px;
            position: relative;
            z-index: 1;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 1;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #00d4ff;
            font-weight: 600;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.9rem 1.2rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 212, 255, 0.3);
            border-radius: 10px;
            color: #e0e0e0;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #00d4ff;
            background: rgba(0, 212, 255, 0.08);
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.2),
                        inset 0 0 20px rgba(0, 212, 255, 0.05);
        }

        input::placeholder {
            color: #707080;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(0, 212, 255, 0.5);
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.05);
            cursor: pointer;
            appearance: none;
            position: relative;
            transition: all 0.3s ease;
        }

        input[type="checkbox"]:checked {
            background: linear-gradient(135deg, #00d4ff, #a78bfa);
            border-color: #00d4ff;
        }

        input[type="checkbox"]:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #0a0e27;
            font-weight: bold;
            font-size: 12px;
        }

        .checkbox-label {
            color: #b0b0c0;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .btn {
            width: 100%;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(135deg, #00d4ff, #a78bfa);
            color: #0a0e27;
            box-shadow: 0 5px 25px rgba(0, 212, 255, 0.4);
            margin-top: 1rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 35px rgba(0, 212, 255, 0.6);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .form-footer {
            margin-top: 1.5rem;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .form-footer a {
            color: #00d4ff;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .form-footer a:hover {
            color: #a78bfa;
            text-shadow: 0 0 10px rgba(0, 212, 255, 0.5);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(0, 212, 255, 0.3), transparent);
        }

        .divider span {
            color: #707080;
            font-size: 0.85rem;
        }

        .error-message {
            color: #ff006e;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .success-message {
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid rgba(0, 212, 255, 0.3);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #00d4ff;
            font-size: 0.9rem;
            text-align: center;
        }

        .back-link {
            position: absolute;
            top: 2rem;
            left: 2rem;
            z-index: 100;
        }

        .back-link a {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #00d4ff;
            text-decoration: none;
            font-weight: 500;
            padding: 0.6rem 1.2rem;
            background: rgba(15, 20, 50, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 212, 255, 0.3);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .back-link a:hover {
            background: rgba(0, 212, 255, 0.1);
            border-color: #00d4ff;
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.3);
            transform: translateX(-5px);
        }

        @media (max-width: 768px) {
            .auth-container {
                max-width: 100%;
            }

            .auth-card {
                padding: 2rem 1.5rem;
            }

            .logo {
                font-size: 2rem;
            }

            .auth-title {
                font-size: 1.5rem;
            }

            .back-link {
                top: 1rem;
                left: 1rem;
            }

            body {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="back-link">
        <a href="{{ route('landing') }}">
            <span>←</span>
            <span>Kembali ke Beranda</span>
        </a>
    </div>

    <div class="auth-container">
        <div class="logo-section">
            <div class="logo">⚔️ HABITQUEST</div>
            <div class="logo-subtitle">MULAI PETUALANGAN ANDA</div>
        </div>

        <div class="auth-card">
            {{ $slot }}
        </div>
    </div>
</body>
</html>