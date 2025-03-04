<!doctype html>
<html lang="en">

<head>
    <title>Encuesta Inicial | UTVT</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/3aafa2d207.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body>
    <?php
    require("../sesiones/emailverified.php");

    if ($email_verificado == 0):
        echo '<script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                var myModal = new bootstrap.Modal(document.getElementById("myModal"));
                myModal.show();
            });
          </script>';
    endif;
     ?>
    <style>
    input.form-control {
        border: 1px solid grey;
        /* Grosor y color del borde */
        border-radius: 3px;
        /* Bordes redondeados */
        padding: 10px;
        /* Espaciado interno */
    }
    </style>
    <div class="wrapper d-flex align-items-stretch">
        <nav id="sidebar">
            <div class="custom-menu">
                <button type="button" id="sidebarCollapse" class="btn btn-secondary">
                    <i class="fa fa-bars"></i>
                    <span class="sr-only">Toggle Menu</span>
                </button>
            </div>
            <div class="p-4 pt-5">
                <h1><a href="/public/views/sesiones/index.php" class="logo">Encuesta Inicial</a></h1>
                <ul class="list-unstyled components mb-5">
                    <b>Usuario: <?php echo $nrol; ?></b>
                    <p><?php echo $nombre; ?></p>
                    <li id="inicio" class="active">
                        <a href="/public/views/sesiones/index.php">Inicio</a>
                    </li>

                    <?php if ($rol == 1 || $rol == 2): ?>
                    <li>
                        <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle">CRUD's</a>
                        <ul class="collapse list-unstyled" id="pageSubmenu">
                            <?php
                                // if ($rol == 1): 
                                ?>
                            <!--
                                    <li>
                                        <a href="/public/views/roles/index.php">Roles</a>
                                    </li>
                                    -->
                            <?php
                                // endif 
                                ?>

                            <?php if ($rol == 1): ?>
                            <li>
                                <a href="/public/views/educativo/index.php">Programas Educativos</a>
                            </li>
                            <?php endif ?>
                            <?php if ($rol == 1): ?>
                            <li>
                                <a href="/public/views/usuarios/index.php">Usuarios</a>
                            </li>
                            <?php endif ?>
                            <?php if ($rol == 1): ?>
                            <li>
                                <a href="/public/views/periodos/index.php">Periodos Escolares</a>
                            </li>
                            <?php endif ?>
                            <?php if ($rol == 1): ?>
                            <li>
                                <a href="/public/views/grupos/index.php">Grupos</a>
                            </li>
                            <?php endif ?>
                            <?php if ($rol == 1): ?>
                            <li>
                                <a href="/public/views/grupo_tutor/index.php">Grupo_Tutor</a>
                            </li>
                            <?php endif ?>
                            <li>
                                <a href="/public/views/estudiante_grupo/index.php">Estudiante_Grupo</a>
                            </li>
                        </ul>
                    </li>
                    <?php endif ?>

                    <li>
                        <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false"
                            class="dropdown-toggle">Reportes</a>
                        <ul class="collapse list-unstyled" id="homeSubmenu">
                            <li>
                                <a href="/public/views/seguimiento/index.php">Seguimiento de Estudiantes</a>
                            </li>
                            <li>
                                <a href="/public/views/encreportes/index.php">Reporte Encuesta</a>
                            </li>
                        </ul>
                    </li>

                    <?php if ($rol == 1): ?>
                    <li>
                        <a href="#dbSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Base
                            de Datos</a>
                        <ul class="collapse list-unstyled" id="dbSubmenu">
                            <li>
                                <a href="../dbbackup/index.php">Exportar DB</a>
                            </li>
                        </ul>
                    </li>
                    <?php endif ?>
                    <!--
                    <li>
                        <a href="#">Gráficos</a>
                    </li>
                                -->
                    <li>
                        <a href="../../../app/Controllers/sessiondestroy_controller.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Page Content  -->
        <div id="content" class="p-4 p-md-5 pt-5">
            <?php include($content); ?>
        </div>
    </div>

    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/popper.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>