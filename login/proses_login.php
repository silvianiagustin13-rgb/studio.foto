<?php
include '../koneksi.php';
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM tb_users WHERE username=? AND password=?";
$stmt  = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "ss", $username, $password);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $_SESSION['username'] = $username;
    $_SESSION['status']   = "login";
    ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Berhasil — SnapStudio</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700;800;900&family=Pacifico&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #2D1B4E 0%, #1a0a2e 100%);
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }

        .sq {
            position: fixed;
            opacity: 0.09;
            animation: sqFloat linear infinite;
        }
        @keyframes sqFloat {
            0%   { transform: translateY(0) rotate(0deg); opacity: 0.09; }
            100% { transform: translateY(-110vh) rotate(200deg); opacity: 0; }
        }

        .heart {
            position: fixed;
            font-size: 16px;
            pointer-events: none;
            opacity: 0;
        }

        .content {
            text-align: center;
            position: relative; z-index: 2;
            transform: translateY(18px); opacity: 0;
            animation: riseUp 0.5s cubic-bezier(0.34,1.3,0.64,1) 0.1s forwards;
        }
        @keyframes riseUp {
            to { transform: translateY(0); opacity: 1; }
        }

        .logo-stack {
            display: flex; justify-content: center; align-items: center;
            gap: 10px; margin-bottom: 20px;
        }
        .sparkle {
            font-size: 24px;
            transform: translateY(20px); opacity: 0;
            animation: eDrop 0.45s cubic-bezier(0.34,1.56,0.64,1) forwards;
        }
        .sparkle.left  { animation-delay: 0.15s; }
        .sparkle.right { animation-delay: 0.42s; }
        .logo-icon {
            transform: translateY(24px) scale(0.6); opacity: 0;
            animation: logoDrop 0.5s cubic-bezier(0.34,1.56,0.64,1) 0.28s forwards;
        }
        @keyframes eDrop    { to { transform: translateY(0); opacity: 1; } }
        @keyframes logoDrop { to { transform: translateY(0) scale(1); opacity: 1; } }

        .title {
            font-family: 'Nunito', sans-serif;
            font-size: 26px; font-weight: 900;
            color: white; margin-bottom: 6px;
            opacity: 0;
            animation: fadeSlide 0.4s ease 0.55s forwards;
        }
        .sub {
            font-family: 'Nunito', sans-serif;
            font-size: 14px; color: rgba(255,255,255,0.65);
            margin-bottom: 24px; line-height: 1.6;
            opacity: 0;
            animation: fadeSlide 0.4s ease 0.7s forwards;
        }
        .sub strong { color: #FFD93D; }
        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .dots-row {
            display: flex; justify-content: center; gap: 8px;
            margin-bottom: 14px;
        }
        .dot {
            width: 10px; height: 10px; border-radius: 50%;
            background: rgba(255,255,255,0.2);
        }
        .dot.d0 { animation: dotPop 0.3s cubic-bezier(0.34,1.56,0.64,1) 0.90s forwards; }
        .dot.d1 { animation: dotPop 0.3s cubic-bezier(0.34,1.56,0.64,1) 1.16s forwards; }
        .dot.d2 { animation: dotPop 0.3s cubic-bezier(0.34,1.56,0.64,1) 1.42s forwards; }
        .dot.d3 { animation: dotPop 0.3s cubic-bezier(0.34,1.56,0.64,1) 1.68s forwards; }
        .dot.d4 { animation: dotPop 0.3s cubic-bezier(0.34,1.56,0.64,1) 1.94s forwards; }
        @keyframes dotPop {
            0%  { transform: scale(1);   background: rgba(255,255,255,0.2); }
            50% { transform: scale(1.7); background: #FFD93D; }
            100%{ transform: scale(1);   background: #FF6B9D; }
        }

        .tag {
            display: inline-block;
            background: rgba(255,255,255,0.12);
            color: rgba(255,255,255,0.7);
            font-family: 'Nunito', sans-serif;
            font-size: 12px; font-weight: 800;
            padding: 6px 18px; border-radius: 50px;
            opacity: 0;
            animation: fadeSlide 0.35s ease 0.85s forwards;
        }
    </style>
</head>
<body>

<div class="content">
    <div class="logo-stack">
        <div class="sparkle left">✨</div>
        <div class="logo-icon">
            <svg width="72" height="72" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="64" height="64" rx="16" fill="rgba(255,255,255,0.18)"/>
                <rect x="10" y="23" width="44" height="27" rx="8" fill="white" fill-opacity="0.95"/>
                <rect x="22" y="15" width="20" height="12" rx="5" fill="white"/>
                <circle cx="32" cy="36" r="9" fill="rgba(6,182,212,0.45)"/>
                <circle cx="32" cy="36" r="6" fill="white"/>
                <circle cx="32" cy="36" r="3" fill="#06B6D4"/>
                <rect x="13" y="26" width="5" height="3" rx="1.5" fill="#FFD93D"/>
                <circle cx="48" cy="26" r="2.5" fill="#FF6B9D"/>
                <path d="M44 16 L45 18.5 L47.5 19.5 L45 20.5 L44 23 L43 20.5 L40.5 19.5 L43 18.5 Z" fill="#FFD93D"/>
                <path d="M18 18 L18.8 20 L21 20.8 L18.8 21.6 L18 23.6 L17.2 21.6 L15 20.8 L17.2 20 Z" fill="#FFD93D" fill-opacity="0.9"/>
            </svg>
        </div>
        <div class="sparkle right">✨</div>
    </div>

    <div class="title">Yeay, berhasil! 🎉</div>
    <div class="sub">
        Halo <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>,<br>
        SnapStudio menunggumu~
    </div>

    <div class="dots-row">
        <div class="dot d0"></div>
        <div class="dot d1"></div>
        <div class="dot d2"></div>
        <div class="dot d3"></div>

    </div>
    <div class="tag" id="tag">membuka dashboard...</div>
</div>

<script>
   const colors = ['#FF6B9D','#C084FC','#FFD93D','#06B6D4','#FF8C69'];
    for (let i = 0; i < 12; i++) {
        const s = document.createElement('div');
        s.className = 'sq';
        const size = 14 + Math.random()*38;
        const dur  = 5 + Math.random()*5;
        const del  = Math.random()*2;
        s.style.cssText = `
            width:${size}px;height:${size}px;
            background:${colors[i%colors.length]};
            left:${Math.random()*95}%;
            top:${55+Math.random()*45}%;
            border-radius:${Math.random()>0.5?'50%':'10px'};
            animation-duration:${dur}s;
            animation-delay:${del}s;
        `;
        document.body.appendChild(s);
    }

    const items = ['💕','✨','🌸','⭐','💖'];
    for (let i = 0; i < 10; i++) {
        const h = document.createElement('div');
        h.className = 'heart';
        h.textContent = items[i%items.length];
        const x=5+Math.random()*88;
        const del=0.3+Math.random()*0.9;
        const dur=1.2+Math.random()*1.2;
        const rise=70+Math.random()*100;
        const id='hrt'+i;
        const st=document.createElement('style');
        st.textContent=`@keyframes ${id}{
            0%{transform:translateY(0) scale(0.6);opacity:0;}
            25%{opacity:1;}
            100%{transform:translateY(-${rise}px) scale(1);opacity:0;}
        }`;
        document.head.appendChild(st);
        h.style.cssText=`left:${x}%;bottom:${5+Math.random()*20}%;animation:${id} ${dur}s ease ${del}s forwards;`;
        document.body.appendChild(h);
    }

    let s = 2;
    const tag = document.getElementById('tag');
    const tick = setInterval(() => {
        s--;
        if (s <= 0) {
            clearInterval(tick);
            tag.textContent = 'Siap! Yuk masuk~ 🎀';
            setTimeout(() => {
                window.location.href = '/studio_foto_project/index.php';
            }, 600);
        } else {
            tag.textContent = `membuka dalam ${s} detik...`;
        }
    }, 1000);
</script>

</body>
</html>
<?php
} else {
    header("Location: ../login.php?error=Username+atau+password+salah!");
    exit();
}
?>
