<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SnapStudio</title>
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            font-family: 'Nunito', sans-serif;
            display: flex; align-items: center; justify-content: center;
            background: #1a0a2e;
            overflow: hidden;
        }

        .bubbles {
            position: fixed; inset: 0; pointer-events: none; z-index: 0;
        }
        .bubble {
            position: absolute;
            border-radius: 50%;
            animation: floatUp linear infinite;
            opacity: 0.15;
        }
        @keyframes floatUp {
            0%   { transform: translateY(110vh) scale(0.8); opacity: 0; }
            10%  { opacity: 0.15; }
            90%  { opacity: 0.12; }
            100% { transform: translateY(-10vh) scale(1.1); opacity: 0; }
        }

        .login-wrapper {
            position: relative; z-index: 1;
            display: flex;
            width: 880px; max-width: 96vw;
            min-height: 530px;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,0.5);
        }

        .login-left {
            flex: 1;
            background: linear-gradient(160deg, #FF6B9D 0%, #C084FC 55%, #06B6D4 100%);
            padding: 48px 36px;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            color: white; text-align: center;
            position: relative; overflow: hidden;
        }
        .login-left::before {
            content: '';
            position: absolute; top: -60px; right: -60px;
            width: 180px; height: 180px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .login-left::after {
            content: '';
            position: absolute; bottom: -50px; left: -50px;
            width: 150px; height: 150px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }
        .camera-wrap {
            position: relative; z-index: 1;
            width: 90px; height: 90px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            animation: cameraPulse 2.5s ease-in-out infinite;
        }
        @keyframes cameraPulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255,255,255,0.3); }
            50% { transform: scale(1.05); box-shadow: 0 0 0 16px rgba(255,255,255,0); }
        }
        .login-logo {
            font-family: 'Pacifico', cursive;
            font-size: 2.6rem;
            color: white;
            position: relative; z-index: 1;
            margin-bottom: 8px;
            text-shadow: 2px 4px 12px rgba(0,0,0,0.2);
        }
        .login-logo span { color: #FFD93D; }
        .login-tagline {
            font-size: 0.88rem;
            opacity: 0.85; line-height: 1.7;
            position: relative; z-index: 1;
            margin-bottom: 28px;
        }
        .feature-list {
            list-style: none; padding: 0;
            position: relative; z-index: 1;
            text-align: left; width: 100%;
        }
        .feature-list li {
            font-size: 0.82rem; font-weight: 600;
            opacity: 0.9;
            display: flex; align-items: center; gap: 8px;
            padding: 5px 0;
        }
        .feature-list li i {
            width: 22px; height: 22px;
            background: rgba(255,255,255,0.25);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.7rem;
        }

        .login-right {
            flex: 1;
            background: white;
            padding: 52px 44px;
            display: flex; flex-direction: column; justify-content: center;
        }

        .logout-banner {
            background: linear-gradient(135deg, #DCFCE7, #BBF7D0);
            border: 1.5px solid #86EFAC;
            border-radius: 14px;
            padding: 12px 16px;
            margin-bottom: 22px;
            display: flex; align-items: center; gap: 10px;
            animation: slideDown 0.4s ease;
        }
        @keyframes slideDown {
            from { transform: translateY(-10px); opacity: 0; }
            to   { transform: translateY(0); opacity: 1; }
        }
        .logout-banner i { color: #16A34A; font-size: 1.1rem; }
        .logout-banner span { font-size: 0.85rem; font-weight: 700; color: #15803D; }

        .login-right h2 {
            font-size: 1.7rem; font-weight: 800;
            color: #2D1B4E; margin-bottom: 4px;
        }
        .login-subtitle {
            font-size: 0.88rem; color: #aaa;
            margin-bottom: 28px;
        }

        .input-wrap {
            position: relative; margin-bottom: 16px;
        }
        .input-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #C084FC; font-size: 0.9rem;
            transition: color 0.3s;
            z-index: 1;
        }
        .input-wrap:focus-within .input-icon { color: #FF6B9D; }
        .input-wrap input {
            width: 100%;
            padding: 13px 44px 13px 40px;
            border: 2px solid #F0D0E8;
            border-radius: 14px;
            font-family: 'Nunito', sans-serif;
            font-size: 0.92rem;
            transition: all 0.3s;
            background: #FDFAFF;
            color: #2D1B4E;
        }
        .input-wrap input:focus {
            border-color: #FF6B9D;
            box-shadow: 0 0 0 4px rgba(255,107,157,0.12);
            outline: none;
            background: white;
        }
        .input-wrap input::placeholder { color: #ccc; }
        .toggle-pw {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: #ccc; cursor: pointer; font-size: 0.9rem;
            padding: 0; transition: color 0.2s;
        }
        .toggle-pw:hover { color: #FF6B9D; }

        .error-msg {
            background: #FFF0F0; color: #DC2626;
            border: 1.5px solid #FECACA;
            border-radius: 12px; padding: 10px 14px;
            font-size: 0.85rem; font-weight: 700;
            margin-bottom: 16px;
            display: flex; align-items: center; gap: 8px;
            animation: shake 0.4s ease;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px); }
            40%, 80% { transform: translateX(6px); }
        }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #FF6B9D 0%, #C084FC 100%);
            color: white; border: none;
            padding: 14px;
            border-radius: 14px;
            font-family: 'Nunito', sans-serif;
            font-size: 1rem; font-weight: 800;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 6px 20px rgba(255,107,157,0.4);
            margin-top: 6px;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(255,107,157,0.5);
        }
        .btn-login:active { transform: translateY(0); }
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .spinner {
            width: 18px; height: 18px;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .login-footer {
            margin-top: 20px; text-align: center;
            font-size: 0.75rem; color: #ddd;
        }

        @media (max-width: 600px) {
            .login-left { display: none; }
            .login-right { padding: 36px 24px; }
        }
    </style>
</head>
<body>

<div class="bubbles" id="bubbles"></div>

<div class="login-wrapper">
    <div class="login-left">
        <div class="camera-wrap">
            <svg width="62" height="62" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="64" height="64" rx="16" fill="rgba(255,255,255,0.22)"/>
                <rect x="10" y="23" width="44" height="27" rx="8" fill="white" fill-opacity="0.95"/>
                <rect x="22" y="15" width="20" height="12" rx="5" fill="white"/>
                <circle cx="32" cy="36" r="9" fill="rgba(6,182,212,0.55)"/>
                <circle cx="32" cy="36" r="6" fill="white"/>
                <circle cx="32" cy="36" r="3" fill="#06B6D4"/>
                <rect x="13" y="26" width="5" height="3" rx="1.5" fill="#FFD93D"/>
                <circle cx="48" cy="26" r="2.5" fill="#FF6B9D"/>
                <path d="M44 16 L45 18.5 L47.5 19.5 L45 20.5 L44 23 L43 20.5 L40.5 19.5 L43 18.5 Z" fill="#FFD93D"/>
                <path d="M18 18 L18.8 20 L21 20.8 L18.8 21.6 L18 23.6 L17.2 21.6 L15 20.8 L17.2 20 Z" fill="#FFD93D" fill-opacity="0.9"/>
            </svg>
        </div>
        <div class="login-logo">Snap<span>Studio</span></div>
        <p class="login-tagline">
            Sistem Manajemen Studio Foto Profesional<br>
            Kelola paket foto dengan mudah ✨
        </p>
    </div>

    <div class="login-right">

        <?php if (isset($_GET['logout'])): ?>
        <div class="logout-banner">
            <i class="fas fa-check-circle"></i>
            <span>Kamu berhasil logout. Sampai jumpa lagi! 👋</span>
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
        <div class="error-msg">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
        <?php endif; ?>

        <h2>Selamat Datang! 👋</h2>
        <p class="login-subtitle">Masuk ke dashboard SnapStudio kamu</p>

        <form action="login/proses_login.php" method="post" id="loginForm">
            <div class="input-wrap">
                <i class="fas fa-user input-icon"></i>
                <input type="text" name="username" placeholder="Username" required autocomplete="username">
            </div>
            <div class="input-wrap">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" name="password" id="pwInput" placeholder="Password" required autocomplete="current-password">
                <button type="button" class="toggle-pw" id="togglePw" onclick="togglePassword()">
                    <i class="fas fa-eye" id="pwIcon"></i>
                </button>
            </div>
            <button type="submit" class="btn-login" id="btnLogin">
                <span id="btnText"><i class="fas fa-sign-in-alt"></i> Masuk ke Dashboard</span>
                <div class="spinner" id="spinner"></div>
            </button>
        </form>

        <p class="login-footer">© 2026 SnapStudio — Studio Foto Management</p>
    </div>
</div>

<script>
const colors = ['#FF6B9D','#C084FC','#06B6D4','#FFD93D','#FF8C69'];
const wrap = document.getElementById('bubbles');
for (let i = 0; i < 18; i++) {
    const b = document.createElement('div');
    b.className = 'bubble';
    const size = Math.random() * 80 + 30;
    b.style.cssText = `
        width: ${size}px; height: ${size}px;
        left: ${Math.random() * 100}%;
        background: ${colors[Math.floor(Math.random() * colors.length)]};
        animation-duration: ${Math.random() * 12 + 8}s;
        animation-delay: ${Math.random() * 8}s;
    `;
    wrap.appendChild(b);
}

function togglePassword() {
    const inp  = document.getElementById('pwInput');
    const icon = document.getElementById('pwIcon');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        inp.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

document.getElementById('loginForm').addEventListener('submit', function() {
    const btn     = document.getElementById('btnLogin');
    const txt     = document.getElementById('btnText');
    const spinner = document.getElementById('spinner');
    btn.classList.add('loading');
    txt.style.display = 'none';
    spinner.style.display = 'block';
});
</script>

</body>
</html>