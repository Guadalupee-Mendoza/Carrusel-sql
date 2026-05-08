<?php
session_start();
include 'db.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$mensaje = null;
$tipo = null;

// Obtener usuario actual para no permitir auto-eliminación
$usuario_actual = $_SESSION['usuario'];

// Eliminar usuario
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // No permitir eliminar al usuario actual
    $check = mysqli_query($conexion, "SELECT username FROM usuarios WHERE id = $id");
    $datos = mysqli_fetch_assoc($check);

    if ($datos && $datos['username'] === $usuario_actual) {
        $mensaje = "No puedes eliminar tu propia cuenta mientras estás en sesión.";
        $tipo = "error";
    } else if ($datos) {
        mysqli_query($conexion, "DELETE FROM usuarios WHERE id = $id");
        $mensaje = "Usuario <strong>" . htmlspecialchars($datos['username']) . "</strong> eliminado correctamente.";
        $tipo = "success";
    }
}

$usuarios = mysqli_query($conexion, "SELECT * FROM usuarios ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Usuario</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --lila:   #c8b4fa;
            --cielo:  #7ec8e3;
            --morado: #6c3fc4;
            --morado2:#4a2880;
            --oscuro: #1a0f2e;
            --mid:    #22124a;
            --card:   rgba(255,255,255,0.06);
            --border: rgba(200,180,250,0.15);
            --blanco: #f5f0ff;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Nunito', sans-serif;
            background: var(--oscuro);
            min-height: 100vh;
            color: var(--blanco);
        }

        .bg-orbs { position:fixed;inset:0;z-index:0;pointer-events:none; }
        .orb { position:absolute;border-radius:50%;filter:blur(90px);opacity:.25;animation:drift 14s ease-in-out infinite alternate; }
        .orb1{width:500px;height:500px;background:var(--morado);top:-150px;left:-120px;}
        .orb2{width:350px;height:350px;background:var(--cielo);bottom:-80px;right:-60px;animation-delay:-7s;}
        .orb3{width:280px;height:280px;background:var(--lila);top:40%;left:60%;animation-delay:-12s;}
        @keyframes drift{from{transform:translate(0,0)scale(1);}to{transform:translate(28px,18px)scale(1.07);}}

        /* Topbar */
        .topbar {
            position: relative; z-index: 10;
            background: rgba(26,15,46,0.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            padding: 18px 36px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .topbar-brand {
            font-size: 1.3rem; font-weight: 800;
            background: linear-gradient(90deg, var(--lila), var(--cielo));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .topbar-right { display:flex; align-items:center; gap:12px; }
        .btn-back {
            background: rgba(200,180,250,0.1);
            border: 1px solid var(--border);
            border-radius: 50px; padding: 8px 20px;
            color: var(--lila); font-family: 'Nunito', sans-serif;
            font-weight: 700; font-size: 0.88rem;
            text-decoration: none; transition: background .2s, border-color .2s;
        }
        .btn-back:hover { background: rgba(200,180,250,0.2); border-color: var(--lila); }
        .btn-logout {
            background: rgba(255,80,80,0.12);
            border: 1px solid rgba(255,100,100,0.25);
            border-radius: 50px; padding: 8px 20px;
            color: #ff9999; font-family: 'Nunito', sans-serif;
            font-weight: 700; font-size: 0.88rem;
            text-decoration: none; transition: background .2s;
        }
        .btn-logout:hover { background: rgba(255,80,80,0.22); }

        /* Contenido */
        .content {
            position: relative; z-index: 1;
            max-width: 700px; margin: 40px auto; padding: 0 24px 60px;
        }

        .page-title {
            font-size: 1.6rem; font-weight: 800; margin-bottom: 6px;
            color: var(--blanco);
        }
        .page-title span {
            background: linear-gradient(90deg, var(--lila), var(--cielo));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .page-sub { color: rgba(200,180,250,0.45); font-size: 0.9rem; margin-bottom: 28px; }

        /* Mensaje */
        .msg {
            border-radius: 14px; padding: 14px 20px;
            font-weight: 700; font-size: 0.95rem;
            margin-bottom: 24px;
            display: flex; align-items: center; gap: 10px;
        }
        .msg.success {
            background: rgba(126,200,227,0.12);
            border: 1px solid rgba(126,200,227,0.3);
            color: var(--cielo);
        }
        .msg.error {
            background: rgba(255,80,80,0.12);
            border: 1px solid rgba(255,100,100,0.28);
            color: #ffaaaa;
        }

        /* Tarjeta de lista */
        .list-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(108,63,196,0.15);
        }

        .list-header {
            padding: 18px 24px;
            background: rgba(108,63,196,0.2);
            border-bottom: 1px solid var(--border);
            font-size: 0.78rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .09em;
            color: var(--lila);
            display: grid;
            grid-template-columns: 60px 1fr 160px;
            gap: 16px;
        }

        .user-row {
            display: grid;
            grid-template-columns: 60px 1fr 160px;
            gap: 16px;
            align-items: center;
            padding: 16px 24px;
            border-bottom: 1px solid rgba(200,180,250,0.06);
            transition: background .15s;
        }
        .user-row:last-child { border-bottom: none; }
        .user-row:hover { background: rgba(200,180,250,0.04); }

        .user-id { color: rgba(200,180,250,0.35); font-weight: 700; font-size: .85rem; }

        .user-info { display: flex; align-items: center; gap: 12px; }
        .user-avatar {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--morado), var(--lila));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; flex-shrink: 0;
        }
        .user-name { font-weight: 700; font-size: 0.95rem; color: var(--blanco); }
        .user-badge-current {
            display: inline-block;
            background: rgba(126,200,227,0.15);
            border: 1px solid rgba(126,200,227,0.3);
            border-radius: 50px; padding: 2px 10px;
            color: var(--cielo); font-size: 0.72rem; font-weight: 700;
            margin-left: 8px; letter-spacing: .05em;
        }

        .btn-del {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 18px;
            background: rgba(255,80,80,0.1);
            border: 1px solid rgba(255,100,100,0.25);
            border-radius: 10px; color: #ff9999;
            text-decoration: none; font-weight: 700; font-size: 0.83rem;
            transition: all .2s; white-space: nowrap;
        }
        .btn-del:hover { background: rgba(255,80,80,0.22); border-color: rgba(255,100,100,0.5); }

        .btn-del-disabled {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 18px;
            background: rgba(200,180,250,0.04);
            border: 1px solid rgba(200,180,250,0.08);
            border-radius: 10px; color: rgba(200,180,250,0.2);
            font-weight: 700; font-size: 0.83rem;
            cursor: not-allowed; white-space: nowrap;
        }

        .empty-state {
            padding: 48px; text-align: center;
            color: rgba(200,180,250,0.3); font-size: 0.95rem;
        }

    </style>
</head>
<body>
    <div class="bg-orbs">
        <div class="orb orb1"></div>
        <div class="orb orb2"></div>
        <div class="orb orb3"></div>
    </div>

    <div class="topbar">
        <div class="topbar-brand">🖼️ Mi Galería</div>
        <div class="topbar-right">
            <a href="admin.php" class="btn-back">← Panel Admin</a>
            <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </div>

    <div class="content">
        <h1 class="page-title">Gestionar <span>Usuarios</span></h1>
        <p class="page-sub">Administra las cuentas con acceso al panel</p>

        <?php if ($mensaje): ?>
            <div class="msg <?php echo $tipo; ?>">
                <?php echo $tipo === 'success' ? '✓' : '⚠️'; ?>
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <div class="list-card">
            <div class="list-header">
                <span>ID</span>
                <span>Usuario</span>
                <span>Acción</span>
            </div>

            <?php
            $hay = false;
            while ($row = mysqli_fetch_assoc($usuarios)):
                $hay = true;
                $es_actual = ($row['username'] === $usuario_actual);
            ?>
            <div class="user-row">
                <div class="user-id">#<?php echo $row['id']; ?></div>
                <div class="user-info">
                    <div class="user-avatar">👤</div>
                    <div>
                        <span class="user-name"><?php echo htmlspecialchars($row['username']); ?></span>
                        <?php if ($es_actual): ?>
                            <span class="user-badge-current">Tú</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div>
                    <?php if ($es_actual): ?>
                        <span class="btn-del-disabled">🔒 En sesión</span>
                    <?php else: ?>
                        <a href="eliminar_usuario.php?id=<?php echo $row['id']; ?>"
                           class="btn-del"
                           onclick="return confirm('¿Eliminar al usuario \'<?php echo htmlspecialchars($row['username']); ?>\'? Esta acción no se puede deshacer.')">
                            🗑 Eliminar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>

            <?php if (!$hay): ?>
                <div class="empty-state">📭 No hay usuarios registrados.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
