<?php
    require '../../../app/Models/conexion.php';

    $GV = "SELECT * FROM gruposv;";
    $consulta = mysqli_query($conexion, $GV);
    $opciones = mysqli_fetch_all($consulta, MYSQLI_ASSOC);


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
                height: 100px;
                width: 200px;
                background-color: lightblue;
                margin: 30px;
                text-align: center;
                display: flex;
                justify-content: center;
                align-items: center;
                cursor: pointer;
                position: relative;
            }
            .dropdown {
            display: none;
            position: absolute;
            top: 0%;
            left: 100%;
            width: 200px;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            z-index: 10;
            }
            .dropdown ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            .dropdown li {
                padding: 10px;
                border-bottom: 1px solid #ccc;
            }
            .dropdown li:last-child {
                border-bottom: none;
            }
            .filtro1:hover .dropdown, 
            .filtro2:hover .dropdown, 
            .filtro3:hover .dropdown, 
            .filtro4:hover .dropdown {
                display: block;
            }

        </style>

        <div class="container">

            <div class="filtro1">
                Programas Educativos
            </div>

            <div class="filtro2" id="filtro">
                Grupos Vulnerables
                <div class="dropdown">
                    <ul>
                        <?php foreach ($opciones as $item): ?>
                                <li><?php echo htmlspecialchars($item['nombregv']); ?> </li>
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