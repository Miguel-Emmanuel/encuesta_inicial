<?php
require '../../../app/Models/conexion.php';

$GV = "SELECT * FROM gruposv;";
$consulta = mysqli_query($conexion, $GV);
$grupos_v = mysqli_fetch_all($consulta, MYSQLI_ASSOC);

$PE = "SELECT * FROM programa_edu;";
$consulta2 = mysqli_query($conexion, $PE);
$programas = mysqli_fetch_all($consulta2, MYSQLI_ASSOC);

$T = "SELECT 
                t.id AS id,
                t.usuario_id,
                CONCAT(u.nombre, ' ', u.apellido_paterno, ' ', u.apellido_materno) AS nombre
            FROM tutores t
            INNER JOIN usuarios u ON t.usuario_id = u.id;";
$consulta3 = mysqli_query($conexion, $T);
$tutores = mysqli_fetch_all($consulta3, MYSQLI_ASSOC);

$T = "SELECT 
                t.id AS id,
                t.usuario_id,
                CONCAT(u.nombre, ' ', u.apellido_paterno, ' ', u.apellido_materno) AS nombre
            FROM tutores t
            INNER JOIN usuarios u ON t.usuario_id = u.id;";
$consulta3 = mysqli_query($conexion, $T);
$tutores = mysqli_fetch_all($consulta3, MYSQLI_ASSOC);


if($rol == 2):
    $idU = intval($idUsuario);

// Consulta para obtener el ID del tutor relacionado
$tutor = "SELECT t.id AS tutor_id 
          FROM tutores AS t
          INNER JOIN usuarios AS u ON t.usuario_id = u.id
          WHERE u.id = $idU";

// Ejecutar la consulta
$tres = mysqli_query($conexion, $tutor);

if ($tres && $row = mysqli_fetch_assoc($tres)) {
    $tutor_id = $row['tutor_id']; // Aquí obtienes el ID del tutor como número
} else {
    echo "Su usuario no esta registrado como tutor";
}

$GrupoTutor = "SELECT g.id AS grupo_id, g.nomenclatura 
              FROM t_grupos AS g
              INNER JOIN grupo_tutor AS gt ON g.id = gt.grupo_id
              WHERE gt.tutor_id = $tutor_id AND g.activo = 1";
$consultagt = mysqli_query($conexion, $GrupoTutor);
$tutores2 = mysqli_fetch_all($consultagt, MYSQLI_ASSOC);
endif;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Raleway', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            width: 90%;
            max-width: 1200px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            background-color: #4CAF50;
            border-radius: 7px;
        }

        .filtro {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #fff;
            color: #333;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .filtro:hover {
            background-color: #e0e0e0;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            z-index: 10;
            width: 100%;
            max-width: 300px;
        }

        .dropdown ul {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 300px;
            overflow-y: auto;
        }

        .dropdown li {
            padding: 10px;
            font-size: 16px;
            color: #333;
            border-bottom: 1px solid #eaeaea;
            transition: background-color 0.2s ease;
        }

        .dropdown li:hover {
            background-color: #f0f0f0;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Scrollbar personalizado */
        .dropdown::-webkit-scrollbar {
            width: 6px;
        }

        .dropdown::-webkit-scrollbar-track {
            background: #f0f0f0;
        }

        .dropdown::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 10px;
        }

        .dropdown::-webkit-scrollbar-thumb:hover {
            background-color: #555;
        }

        /* Media queries para mejor adaptación */
        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }

            .filtro:hover .dropdown {
                display: none;
                /* Desactivamos el hover en móviles */
            }
        }
    </style>
</head>

<body>
    <h3>Bienvenid@:</h3><h4><?php echo $nombre; ?></h4>
    <div class="container">
        <?php if ($rol == 1): ?>
            <div class="filtro">
                Programas Educativos
                <div class="dropdown">
                    <ul>
                        <?php foreach ($programas as $item): ?>
                            <a href="<?php echo "../filtros/index.php?id=" . $item['id'] . "&f=1" . "&nombre=" . urlencode($item['nombre']); ?>">
                                <li><?php echo htmlspecialchars($item['nombre']); ?></li>
                            </a>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif ?>

        <!-- //<a href="../gruposV/filtro.html" class="" > -->
        <a href="../filtros/index.php?f=2" class="">
            <div class="filtro">
                Grupos Vulnerables
            </div>
        </a>

        <a href="../filtros/index.php?f=5" class="">
            <div class="filtro">
                Grupos Vulnerables NEW
            </div>
        </a>
        <?php if ($rol == 2): ?>
            <div class="filtro">
                Grupo Tutor
                <div class="dropdown">
                    <ul>
                        <?php foreach ($tutores2 as $item): ?>
                        <?php $grupoid = intval($item['grupo_id']); ?>
                            <a href="<?php echo "../filtros/index.php?it=" . $tutor_id . "&ig=" . $grupoid . "&f=4" . "&nombre=" . urlencode($item['nomenclatura']); ?>">
                                <li><?php echo htmlspecialchars($item['nomenclatura']); ?></li>
                            </a>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif ?>

        <?php if ($rol == 1): ?>
            <div class="filtro">
                Grupo Tutor
                <div class="dropdown">
                    <ul>
                        <?php foreach ($tutores as $item): ?>
                            <a href="<?php echo "../filtros/index.php?id=" . $item['id'] . "&f=3" . "&nombre=" . urlencode($item['nombre']); ?>">
                                <li><?php echo htmlspecialchars($item['nombre']); ?></li>
                            </a>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif ?>
    </div>

    <script>
        // Detectar si el dispositivo es móvil
        const isMobile = window.matchMedia("(max-width: 768px)").matches;

        if (isMobile) {
            document.querySelectorAll('.filtro').forEach(filtro => {
                filtro.addEventListener('click', function() {
                    const dropdown = this.querySelector('.dropdown');
                    const isVisible = dropdown.style.display === 'block';
                    // Cerrar todos los dropdowns
                    document.querySelectorAll('.dropdown').forEach(dd => dd.style.display = 'none');
                    // Alternar el dropdown del filtro actual
                    if (!isVisible) {
                        dropdown.style.display = 'block';
                    }
                });
            });

            // Cerrar el dropdown si se hace clic fuera
            window.addEventListener('click', function(e) {
                if (!e.target.closest('.filtro')) {
                    document.querySelectorAll('.dropdown').forEach(dd => dd.style.display = 'none');
                }
            });
        } else {
            // En PC usamos el hover para mostrar los dropdowns
            document.querySelectorAll('.filtro').forEach(filtro => {
                filtro.addEventListener('mouseenter', function() {
                    const dropdown = this.querySelector('.dropdown');
                    dropdown.style.display = 'block';
                });

                filtro.addEventListener('mouseleave', function() {
                    const dropdown = this.querySelector('.dropdown');
                    dropdown.style.display = 'none';
                });
            });
        }
    </script>

</body>

</html>