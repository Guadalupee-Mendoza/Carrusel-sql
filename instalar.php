<?php
$host="127.0.0.1";$port=3306;$user="root";$pass="";
$conexion_inicial=@mysqli_connect($host,$user,$pass,"",$port);
$pasos=[];$error=null;
if(!$conexion_inicial){$error=mysqli_connect_error();}else{
    mysqli_query($conexion_inicial,"CREATE DATABASE IF NOT EXISTS proyecto_carrusel");
    $pasos[]="Base de datos <strong>proyecto_carrusel</strong> verificada.";
    mysqli_select_db($conexion_inicial,"proyecto_carrusel");
    mysqli_query($conexion_inicial,"CREATE TABLE IF NOT EXISTS imagenes(id INT AUTO_INCREMENT PRIMARY KEY,nombre VARCHAR(100),ruta VARCHAR(255))");
    mysqli_query($conexion_inicial,"CREATE TABLE IF NOT EXISTS usuarios(id INT AUTO_INCREMENT PRIMARY KEY,username VARCHAR(50),password VARCHAR(255))");
    $pasos[]="Tablas <strong>imagenes</strong> y <strong>usuarios</strong> creadas.";
    mysqli_query($conexion_inicial,"INSERT IGNORE INTO usuarios(id,username,password)VALUES(1,'admin','1234')");
    $pasos[]="Usuario <strong>admin</strong> con contraseña <strong>1234</strong> listo.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Instalación</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --lila:#c8b4fa;--cielo:#7ec8e3;--morado:#6c3fc4;--oscuro:#1a0f2e;--card:rgba(255,255,255,0.06);--border:rgba(200,180,250,0.15);--blanco:#f5f0ff; }
        * { box-sizing:border-box;margin:0;padding:0; }
        body { font-family:'Nunito',sans-serif;background:var(--oscuro);min-height:100vh;display:flex;align-items:center;justify-content:center; }
        .bg-orbs{position:fixed;inset:0;z-index:0;pointer-events:none;}
        .orb{position:absolute;border-radius:50%;filter:blur(90px);opacity:.28;animation:drift 14s ease-in-out infinite alternate;}
        .orb1{width:450px;height:450px;background:var(--morado);top:-120px;left:-80px;}
        .orb2{width:350px;height:350px;background:var(--cielo);bottom:-80px;right:-60px;animation-delay:-6s;}
        .orb3{width:280px;height:280px;background:var(--lila);top:50%;left:55%;animation-delay:-10s;}
        @keyframes drift{from{transform:translate(0,0)scale(1);}to{transform:translate(26px,16px)scale(1.07);}}
        .card{position:relative;z-index:1;background:var(--card);backdrop-filter:blur(24px);border:1px solid var(--border);border-radius:24px;padding:48px 40px;width:100%;max-width:420px;box-shadow:0 8px 64px rgba(108,63,196,0.3);}
        .card-icon{font-size:2.5rem;text-align:center;margin-bottom:10px;}
        h1{text-align:center;font-size:1.8rem;font-weight:800;color:var(--blanco);margin-bottom:6px;}
        .sub{text-align:center;color:rgba(200,180,250,0.5);font-size:0.9rem;margin-bottom:28px;}
        .steps{display:flex;flex-direction:column;gap:12px;margin-bottom:28px;}
        .step{display:flex;align-items:center;gap:14px;padding:14px 18px;background:rgba(200,180,250,0.05);border:1px solid var(--border);border-radius:14px;font-size:0.92rem;color:var(--blanco);}
        .step-icon{width:32px;height:32px;background:linear-gradient(135deg,var(--morado),var(--cielo));border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1rem;flex-shrink:0;}
        .success-msg{background:rgba(126,200,227,0.1);border:1px solid rgba(126,200,227,0.3);border-radius:14px;padding:14px 18px;color:var(--cielo);font-weight:700;text-align:center;margin-bottom:24px;font-size:0.95rem;}
        .error-msg{background:rgba(255,80,80,0.15);border:1px solid rgba(255,100,100,0.3);border-radius:14px;padding:14px 18px;color:#ffaaaa;font-weight:700;font-size:0.9rem;}
        .btn-go{display:block;width:100%;padding:16px;background:linear-gradient(135deg,var(--morado),var(--cielo));border:none;border-radius:14px;color:#fff;font-family:'Nunito',sans-serif;font-size:1.1rem;font-weight:800;text-align:center;text-decoration:none;transition:opacity .2s,transform .15s;}
        .btn-go:hover{opacity:.9;transform:translateY(-2px);}
    </style>
</head>
<body>
    <div class="bg-orbs"><div class="orb orb1"></div><div class="orb orb2"></div><div class="orb orb3"></div></div>
    <div class="card">
        <div class="card-icon">⚙️</div>
        <h1>Instalación</h1>
        <p class="sub">Configurando la base de datos del proyecto</p>
        <?php if($error): ?>
            <div class="error-msg">❌ Error de conexión: <?php echo htmlspecialchars($error); ?></div>
        <?php else: ?>
            <div class="steps">
                <?php foreach($pasos as $paso): ?>
                <div class="step"><div class="step-icon">✓</div><div><?php echo $paso; ?></div></div>
                <?php endforeach; ?>
            </div>
            <div class="success-msg">🎉 ¡Instalación completada con éxito!</div>
            <a href="login.php" class="btn-go">Ir al Login →</a>
        <?php endif; ?>
    </div>
</body>
</html>
