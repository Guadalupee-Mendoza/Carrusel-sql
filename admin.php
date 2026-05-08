<?php
session_start();
include 'db.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$resultado = mysqli_query($conexion, "SELECT * FROM imagenes ORDER BY id DESC");
$total = mysqli_num_rows(mysqli_query($conexion, "SELECT id FROM imagenes"));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin</title>
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

        /* Orbes de fondo */
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
        .user-badge {
            background: rgba(200,180,250,0.1);
            border: 1px solid var(--border);
            border-radius: 50px; padding: 7px 18px;
            font-weight: 700; font-size: 0.88rem; color: var(--lila);
        }
        .btn-logout {
            background: rgba(255,80,80,0.12);
            border: 1px solid rgba(255,100,100,0.25);
            border-radius: 50px; padding: 8px 20px;
            color: #ff9999; font-family: 'Nunito', sans-serif;
            font-weight: 700; font-size: 0.88rem;
            cursor: pointer; text-decoration: none;
            transition: background .2s;
        }
        .btn-logout:hover { background: rgba(255,80,80,0.22); }

        /* Contenido */
        .content {
            position: relative; z-index: 1;
            max-width: 900px; margin: 40px auto; padding: 0 24px 60px;
        }

        .section-label {
            font-size: 0.78rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .1em;
            color: rgba(200,180,250,0.45);
            margin-bottom: 16px;
        }

        /* Grid de botones principales */
        .btn-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 16px;
            margin-bottom: 36px;
        }

        .btn-action {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 30px 20px;
            display: flex; flex-direction: column;
            align-items: center; gap: 12px;
            cursor: pointer; text-decoration: none;
            color: var(--blanco);
            font-family: 'Nunito', sans-serif;
            font-size: 1rem; font-weight: 700;
            transition: all .2s;
            box-shadow: 0 4px 20px rgba(108,63,196,0.1);
        }
        .btn-action:hover {
            background: rgba(108,63,196,0.25);
            border-color: var(--lila);
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(108,63,196,0.25);
        }
        .btn-action .btn-icon { font-size: 2.4rem; }
        .btn-action small { color: rgba(200,180,250,0.45); font-size:.8rem; font-weight:600; }

        .btn-action.danger:hover { border-color: #ff9999; background: rgba(255,80,80,0.12); }
        .btn-action.sky:hover    { border-color: var(--cielo); background: rgba(126,200,227,0.12); }

        /* Paneles desplegables */
        .panel {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 24px;
            display: none;
            box-shadow: 0 8px 40px rgba(108,63,196,0.15);
        }
        .panel.active { display: block; }
        .panel-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 24px;
        }
        .panel-title { font-size: 1.15rem; font-weight: 800; color: var(--blanco); }
        .btn-close {
            background: rgba(200,180,250,0.08);
            border: 1px solid var(--border);
            border-radius: 10px; padding: 7px 16px;
            cursor: pointer; font-family: 'Nunito', sans-serif;
            font-weight: 700; color: rgba(200,180,250,0.5);
            font-size: 0.88rem; transition: all .2s;
        }
        .btn-close:hover { background: rgba(200,180,250,0.15); color: var(--lila); }

        /* Formulario subir */
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
        .field label {
            display: block; font-weight: 700; color: var(--lila);
            margin-bottom: 8px; font-size: 0.8rem;
            text-transform: uppercase; letter-spacing: .07em;
        }
        .field input[type="text"],
        .field input[type="file"] {
            width: 100%; padding: 13px 16px;
            border: 1px solid var(--border); border-radius: 12px;
            font-family: 'Nunito', sans-serif; font-size: 0.95rem;
            color: var(--blanco); outline: none;
            background: rgba(255,255,255,0.05);
            transition: border-color .2s, background .2s;
        }
        .field input[type="text"]::placeholder { color: rgba(200,180,250,0.3); }
        .field input[type="text"]:focus { border-color: var(--cielo); background: rgba(126,200,227,0.07); }

        .btn-submit {
            width: 100%; padding: 15px;
            background: linear-gradient(135deg, var(--morado), var(--cielo));
            border: none; border-radius: 12px; color: #fff;
            font-family: 'Nunito', sans-serif; font-size: 1.05rem; font-weight: 800;
            cursor: pointer; transition: opacity .2s, transform .15s;
        }
        .btn-submit:hover { opacity:.9; transform:translateY(-2px); }

        /* Tabla */
        .table-wrap {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border);
            border-radius: 16px; overflow: hidden;
        }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            background: rgba(108,63,196,0.2);
            padding: 13px 20px;
            font-size: 0.75rem; text-transform: uppercase;
            letter-spacing: .09em; color: var(--lila);
            font-weight: 700; text-align: left;
        }
        tbody tr { border-top: 1px solid rgba(200,180,250,0.06); transition: background .15s; }
        tbody tr:hover { background: rgba(200,180,250,0.04); }
        tbody td { padding: 13px 20px; font-size: 0.92rem; vertical-align: middle; }
        tbody td:first-child { color: rgba(200,180,250,0.35); font-weight:700; font-size:.82rem; }

        .thumb {
            width: 64px; height: 46px; object-fit: cover;
            border-radius: 10px; border: 1px solid var(--border);
        }
        .btn-del {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px;
            background: rgba(255,80,80,0.1);
            border: 1px solid rgba(255,100,100,0.25);
            border-radius: 10px; color: #ff9999;
            text-decoration: none; font-weight: 700; font-size: 0.82rem;
            transition: background .2s;
        }
        .btn-del:hover { background: rgba(255,80,80,0.22); }
        .empty-row td { text-align:center; padding:44px; color:rgba(200,180,250,0.3); font-size:.9rem; }

        /* topbar flex gap */
        .topbar-right { display:flex; align-items:center; gap:12px; }
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
            <span class="user-badge">👤 <?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
            <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </div>

    <div class="content">
        <div class="section-label">¿Qué deseas hacer?</div>

        <div class="btn-grid">
            <button class="btn-action" onclick="togglePanel('panel-subir')">
                <span class="btn-icon">⬆️</span>
                Subir Imagen
            </button>
            <button class="btn-action sky" onclick="togglePanel('panel-lista')">
                <span class="btn-icon">🗂️</span>
                Ver Imágenes
                <small><?php echo $total; ?> en total</small>
            </button>
            <a href="registro_user.php" class="btn-action">
                <span class="btn-icon">👤</span>
                Nuevo Usuario
            </a>
            <a href="index.php" class="btn-action sky" target="_blank">
                <span class="btn-icon">🔗</span>
                Ver Carrusel
            </a>
            <a href="eliminar_usuario.php" class="btn-action danger">
                <span class="btn-icon">🗑️</span>
                Eliminar Usuario
            </a>
        </div>

        <!-- Panel: Subir imagen -->
        <div class="panel" id="panel-subir">
            <div class="panel-header">
                <div class="panel-title">⬆️ Subir Nueva Imagen</div>
                <button class="btn-close" onclick="togglePanel('panel-subir')">✕ Cerrar</button>
            </div>
            <form action="subir.php" method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="field">
                        <label>Nombre en el carrusel</label>
                        <input type="text" name="nombre_personalizado" placeholder="Ej: Atardecer en el lago" required>
                    </div>
                    <div class="field">
                        <label>Archivo de imagen</label>
                        <input type="file" name="imagen" accept="image/*" required>
                    </div>
                </div>
                <button type="submit" class="btn-submit">⬆ Subir al Carrusel</button>
            </form>
        </div>

        <!-- Panel: Lista de imágenes -->
        <div class="panel" id="panel-lista">
            <div class="panel-header">
                <div class="panel-title">🗂️ Imágenes Cargadas</div>
                <button class="btn-close" onclick="togglePanel('panel-lista')">✕ Cerrar</button>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Vista Previa</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $tiene = false;
                        while($row = mysqli_fetch_assoc($resultado)):
                            $tiene = true;
                        ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><img src="<?php echo $row['ruta']; ?>" class="thumb" alt="<?php echo htmlspecialchars($row['nombre']); ?>"></td>
                            <td>
                                <a href="eliminar.php?id=<?php echo $row['id']; ?>"
                                   class="btn-del"
                                   onclick="return confirm('¿Eliminar esta imagen?')">🗑 Eliminar</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if(!$tiene): ?>
                        <tr class="empty-row"><td colspan="4">📭 No hay imágenes cargadas aún.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function togglePanel(id) {
            const panel = document.getElementById(id);
            const isActive = panel.classList.contains('active');
            document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
            if (!isActive) {
                panel.classList.add('active');
                panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    </script>
</body>
</html>
