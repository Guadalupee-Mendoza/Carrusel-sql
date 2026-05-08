<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nuevo_user = mysqli_real_escape_string($conexion, $_POST['username']);
    $nueva_pass = $_POST['password'];
    $sql = "INSERT INTO usuarios (username, password) VALUES ('$nuevo_user', '$nueva_pass')";
    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('Usuario registrado correctamente'); window.location='admin.php';</script>";
    } else { $db_error = mysqli_error($conexion); }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Usuario</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --lila:#c8b4fa;--cielo:#7ec8e3;--morado:#6c3fc4;--oscuro:#1a0f2e;--card:rgba(255,255,255,0.06);--border:rgba(200,180,250,0.15);--blanco:#f5f0ff; }
        * { box-sizing:border-box;margin:0;padding:0; }
        body { font-family:'Nunito',sans-serif;background:var(--oscuro);min-height:100vh;display:flex;align-items:center;justify-content:center; }
        .bg-orbs { position:fixed;inset:0;z-index:0;pointer-events:none; }
        .orb { position:absolute;border-radius:50%;filter:blur(90px);opacity:.28;animation:drift 14s ease-in-out infinite alternate; }
        .orb1{width:450px;height:450px;background:var(--morado);top:-120px;left:-80px;}
        .orb2{width:350px;height:350px;background:var(--cielo);bottom:-80px;right:-60px;animation-delay:-6s;}
        .orb3{width:280px;height:280px;background:var(--lila);top:50%;left:55%;animation-delay:-10s;}
        @keyframes drift{from{transform:translate(0,0)scale(1);}to{transform:translate(26px,16px)scale(1.07);}}
        .card { position:relative;z-index:1;background:var(--card);backdrop-filter:blur(24px);border:1px solid var(--border);border-radius:24px;padding:48px 40px;width:100%;max-width:400px;box-shadow:0 8px 64px rgba(108,63,196,0.3); }
        .card-icon { font-size:2.5rem;text-align:center;margin-bottom:10px; }
        h1 { text-align:center;font-size:1.8rem;font-weight:800;color:var(--blanco);margin-bottom:6px; }
        .sub { text-align:center;color:rgba(200,180,250,0.5);font-size:0.95rem;margin-bottom:32px; }
        .field { margin-bottom:16px; }
        .field label { display:block;font-weight:700;color:var(--lila);margin-bottom:7px;font-size:0.8rem;text-transform:uppercase;letter-spacing:.06em; }
        .field input { width:100%;padding:14px 18px;border:1px solid var(--border);border-radius:12px;font-family:'Nunito',sans-serif;font-size:1rem;color:var(--blanco);outline:none;background:rgba(255,255,255,0.06);transition:border-color .2s,background .2s; }
        .field input::placeholder { color:rgba(200,180,250,0.3); }
        .field input:focus { border-color:var(--cielo);background:rgba(126,200,227,0.08); }
        .btn-save { width:100%;padding:16px;margin-top:10px;background:linear-gradient(135deg,var(--morado),var(--cielo));border:none;border-radius:14px;color:#fff;font-family:'Nunito',sans-serif;font-size:1.1rem;font-weight:800;cursor:pointer;transition:opacity .2s,transform .15s; }
        .btn-save:hover { opacity:.9;transform:translateY(-2px); }
        .error-msg { background:rgba(255,80,80,0.15);border:1px solid rgba(255,100,100,0.3);color:#ffaaaa;border-radius:12px;padding:12px 16px;margin-bottom:18px;font-weight:700;font-size:0.9rem; }
        .back-link { display:block;text-align:center;margin-top:20px;color:rgba(200,180,250,0.45);font-size:0.88rem;text-decoration:none;font-weight:600; }
        .back-link:hover { color:var(--lila); }
    </style>
</head>
<body>
    <div class="bg-orbs"><div class="orb orb1"></div><div class="orb orb2"></div><div class="orb orb3"></div></div>
    <div class="card">
        <div class="card-icon">👤</div>
        <h1>Nuevo Usuario</h1>
        <p class="sub">Crea una cuenta de acceso al panel</p>
        <?php if(isset($db_error)): ?><div class="error-msg">⚠️ Error: <?php echo $db_error; ?></div><?php endif; ?>
        <form method="POST">
            <div class="field"><label>Nombre de usuario</label><input type="text" name="username" placeholder="Ej: editor01" required autocomplete="off"></div>
            <div class="field"><label>Contraseña</label><input type="password" name="password" placeholder="••••••••" required autocomplete="new-password"></div>
            <button type="submit" class="btn-save">✓ Guardar Usuario</button>
        </form>
        <a href="admin.php" class="back-link">← Volver al panel</a>
    </div>
</body>
</html>
