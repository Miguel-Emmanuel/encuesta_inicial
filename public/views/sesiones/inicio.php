<?php
    require '../../../app/Models/conexion.php';

    $GV = "SELECT * FROM gruposv;";
    $consulta = mysqli_query($conexion, $GV);
    $grupos_v = mysqli_fetch_all($consulta, MYSQLI_ASSOC);

    $PE = "SELECT * FROM programa_edu;";
    $consulta2 = mysqli_query($conexion, $PE);
    $programas = mysqli_fetch_all($consulta2, MYSQLI_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <style>
            body {
                margin: 0;
                display: flex;
                justify-content: center;
                height: 100vh;
                align-items: center;
            }
            .container {
                padding-top: 15%;
                padding-left: 10%;
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 30px;
            }
            .filtro1, .filtro2, .filtro3, .filtro4{
                height: 120px;
                width: 250px;
                background-color: #28a745;
                color: white;
                border-radius: 3px;
                border: solid 1px black;
                margin: 30px;
                font-size:large;
                text-align: center;
                display: flex;
                justify-content: center;
                align-items: center;
                position: relative;
            }
            .dropdown {
                max-height: 400px; /* Ajusta según el tamaño deseado */
                overflow-y: auto; /* Activa la barra de desplazamiento vertical */
                display: none;
                position: absolute;
                top: 0%;
                left: 50%;
                width: 350px;
                background-color: white;
                border: 2px solid #708090; /* Color del borde */
                border-radius: 8px; /* Bordes redondeados */
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                z-index: 10;
            }
            .filtro2 .dropdown{
                width: 200px;
            }
            .dropdown ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            .dropdown li {
                padding: 10px;
                color: black;
                font-size:small     ;
            }
            .dropdown li:hover{
                background-color: grey;
                color: white;
            }
            .dropdown li:last-child {
                border-bottom: 0.5px solid black;
            }
            .filtro1:hover .dropdown, 
            .filtro2:hover .dropdown, 
            .filtro3:hover .dropdown, 
            .filtro4:hover .dropdown {
                display: block;
            }
            a{
                color: inherit;
            }

            /* Estilo minimalista para el scrollbar en WebKit */
        .dropdown::-webkit-scrollbar {
            width: 5px; /* Ancho del scrollbar */
        }
        .dropdown::-webkit-scrollbar-track {
            background: #EAEAEA; /* Fondo del track */
        }
        .dropdown::-webkit-scrollbar-thumb {
            background: #2F4F4F; /* Color del thumb */
            border-radius: 4px; /* Bordes redondeados del thumb */
        }
        .dropdown::-webkit-scrollbar-thumb:hover {
            background: #0056b3; /* Color del thumb al pasar el mouse */
        }
            

        </style>

        <div class="container">

            <div class="filtro1">
                Programas Educativos
                <div class="dropdown">
                    <ul>
                        <?php foreach ($programas as $item): ?>
                                <a href="<?php echo "../filtros/index.php?id=" . $item['id'] ."&f=1". "&nombre=" . urlencode($item['nombre']); ?>"><li><?php echo htmlspecialchars($item['nombre']); ?> </li></a>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="filtro2" id="filtro">
                Grupos Vulnerables
                <div class="dropdown">
                    <ul>
                        <?php foreach ($grupos_v as $item): ?>  
                                <a href= <?php echo "../filtros/index.php?id=" . $item['id'] ."&f=2". "&nombre=" . urlencode($item['nombregv']); ?>><li><?php echo htmlspecialchars($item['nombregv']); ?> </li></a>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            
            <div class="filtro3">
                Grupo Tutor
            </div>
            
            <div class="filtro4">
                Padecimientos de Salud
            </div>
            
            

        </div>
    </body>
</html>