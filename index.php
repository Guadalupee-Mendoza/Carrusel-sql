<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Galería</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        :root {
            --lila:   #c8b4fa;
            --cielo:  #7ec8e3;
            --morado: #6c3fc4;
            --oscuro: #1a0f2e;
            --mid:    #22124a;
            --card:   rgba(255,255,255,0.06);
            --border: rgba(200,180,250,0.15);
            --blanco: #f5f0ff;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Nunito', sans-serif; background: var(--oscuro); min-height: 100vh; color: var(--blanco); }

        .bg-orbs { position:fixed;inset:0;z-index:0;pointer-events:none; }
        .orb { position:absolute;border-radius:50%;filter:blur(90px);opacity:.28;animation:drift 14s ease-in-out infinite alternate; }
        .orb1{width:500px;height:500px;background:var(--morado);top:-150px;left:-100px;}
        .orb2{width:400px;height:400px;background:var(--cielo);bottom:-100px;right:-80px;animation-delay:-6s;}
        .orb3{width:300px;height:300px;background:var(--lila);top:50%;left:40%;animation-delay:-11s;}
        @keyframes drift{from{transform:translate(0,0)scale(1);}to{transform:translate(25px,18px)scale(1.06);}}

        nav {
            position: relative; z-index: 10;
            background: rgba(26,15,46,0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 18px 36px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .nav-brand {
            font-size: 1.3rem; font-weight: 800;
            background: linear-gradient(90deg, var(--lila), var(--cielo));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .btn-nav {
            background: rgba(200,180,250,0.1);
            border: 1px solid var(--border);
            border-radius: 50px; padding: 10px 22px;
            color: var(--lila); font-family: 'Nunito', sans-serif;
            font-weight: 700; font-size: 0.88rem;
            text-decoration: none; transition: background .2s, border-color .2s;
        }
        .btn-nav:hover { background: rgba(200,180,250,0.2); border-color: var(--lila); }

        main {
            position: relative; z-index: 1;
            max-width: 860px; margin: 40px auto; padding: 0 24px 60px;
        }
        .hero { text-align: center; margin-bottom: 36px; }
        .hero h1 { font-size: 2.2rem; font-weight: 800; color: var(--blanco); margin-bottom: 8px; }
        .hero h1 span {
            background: linear-gradient(90deg, var(--lila), var(--cielo));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .hero p { color: rgba(200,180,250,0.5); font-size: 1rem; }

        .visor-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 24px; overflow: hidden;
            box-shadow: 0 8px 40px rgba(108,63,196,0.2);
        }
        #contenedor-ajax {
            width: 100%; height: 460px;
            background: var(--mid);
            display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden;
        }
        .img-sustituida { width:100%; height:100%; object-fit:cover; display:block; }
        .loading-text { color: rgba(200,180,250,0.5); font-weight: 700; font-size: 0.9rem; }

        .controls {
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px 28px;
            border-top: 1px solid var(--border);
            background: rgba(34,18,74,0.6);
            backdrop-filter: blur(10px);
            gap: 16px;
        }
        .btn-ctrl {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 13px 28px;
            background: rgba(200,180,250,0.08);
            border: 1px solid var(--border);
            border-radius: 50px; color: var(--lila);
            font-family: 'Nunito', sans-serif; font-size: 0.95rem; font-weight: 700;
            cursor: pointer; transition: all .2s;
        }
        .btn-ctrl:hover {
            background: var(--morado); border-color: var(--morado);
            color: #fff; transform: scale(1.04);
        }
        .info-center { text-align: center; flex: 1; }
        .badge-count {
            display: inline-block;
            background: rgba(126,200,227,0.12);
            border: 1px solid rgba(126,200,227,0.28);
            border-radius: 50px; padding: 4px 14px;
            color: var(--cielo); font-size: 0.75rem; font-weight: 700;
            letter-spacing: .08em; text-transform: uppercase; margin-bottom: 6px;
        }
        .foto-nombre { display:block; font-size:1rem; font-weight:800; color:var(--blanco); }

        #dots { display:flex; justify-content:center; gap:8px; padding:20px 0 0; }
        .dot { width:10px; height:10px; border-radius:50%; background:rgba(200,180,250,0.2); cursor:pointer; transition:all .25s; }
        .dot.active { background:var(--lila); transform:scale(1.4); }
    </style>
</head>
<body>
    <div class="bg-orbs">
        <div class="orb orb1"></div>
        <div class="orb orb2"></div>
        <div class="orb orb3"></div>
    </div>
    <nav>
        <div class="nav-brand">🖼️ Mi Galería</div>
        <a href="admin.php" class="btn-nav">⚙️ Panel Admin</a>
    </nav>
    <main>
        <div class="hero">
            <h1>Mi <span>Galería</span> de Fotos</h1>
            <p>Explora todas las imágenes del carrusel</p>
        </div>
        <div class="visor-card">
            <div id="contenedor-ajax">
                <span class="loading-text">Cargando imágenes…</span>
            </div>
            <div class="controls">
                <button class="btn-ctrl" id="btn-prev">← Anterior</button>
                <div class="info-center">
                    <span id="contador" class="badge-count">Cargando…</span>
                    <span id="nombre-foto" class="foto-nombre"></span>
                </div>
                <button class="btn-ctrl" id="btn-next">Siguiente →</button>
            </div>
        </div>
        <div id="dots"></div>
    </main>
    <script>
   $(document).ready(function () {
    let total = 0;
    let indiceActual = 0;

    // Carga una imagen específica por índice
    function cargarImagen(index) {
        $.ajax({
            url: 'get_imagenes.php',
            type: 'GET',
            dataType: 'json',
            data: { index: index },
            success: function (data) {
                if (data.total === 0) {
                    $('#contenedor-ajax').html('<div class="estado-msg">No hay imágenes.</div>');
                    return;
                }

                total = data.total;
                indiceActual = data.index;
                const foto = data.imagen;

                // Construir puntos solo la primera vez
                if ($('#puntos').children().length !== total) {
                    construirPuntos(total);
                }

                // Sustituir imagen en el DOM
                const timestamp = new Date().getTime();
                $('#contenedor-ajax').empty();
                $('#contenedor-ajax').append(
                    '<img src="' + foto.ruta + '" id="img-node-' + timestamp + '" alt="' + foto.nombre + '">'
                );
                $('#contenedor-ajax').append(
                    '<div id="info-foto">' +
                        '<span id="nombre-foto">' + foto.nombre + '</span>' +
                        '<span id="contador">' + (indiceActual + 1) + ' / ' + total + '</span>' +
                    '</div>'
                );

                $('.punto').removeClass('activo');
                $('.punto[data-i="' + indiceActual + '"]').addClass('activo');
            },
            error: function () {
                $('#contenedor-ajax').html('<div class="estado-msg">Error al conectar con el servidor.</div>');
            }
        });
    }

    function construirPuntos(n) {
        const $p = $('#puntos').empty();
        for (let i = 0; i < n; i++) {
            $p.append('<div class="punto" data-i="' + i + '"></div>');
        }
        $(document).off('click', '.punto').on('click', '.punto', function () {
            cargarImagen(parseInt($(this).data('i')));
        });
    }

    $('#btn-next').click(function () { cargarImagen((indiceActual + 1) % total); });
    $('#btn-prev').click(function () { cargarImagen((indiceActual - 1 + total) % total); });

    $(document).keydown(function (e) {
        if (e.key === 'ArrowDown' || e.key === 'ArrowRight') $('#btn-next').click();
        if (e.key === 'ArrowUp'   || e.key === 'ArrowLeft')  $('#btn-prev').click();
    });

    // Carga inicial
    cargarImagen(0);
});
    </script>
</body>
</html>
