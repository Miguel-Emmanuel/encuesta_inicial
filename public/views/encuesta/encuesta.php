<?php
include("../../../app/Models/conexion.php");
session_start();
if (empty($_SESSION["id"])) {
    header("location: ../sesiones/login.php");
}
if ($_SESSION["id"] != 3) {
    header("location: ../sesion/inicio.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta Inicial | Inicio</title>
    <link rel="stylesheet" href="../../../bootstrap/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/letters.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Oswald:wght@200..700&family=Passion+One:wght@400;700;900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="row m-5">
        <div class="col-12 text-center">
            <h1 class="bebas-neue-regular" style="font-size: 100px;">Encuesta Inicial</h1>
        </div>
        <div class="col-12">
            <p><strong style="color: red;">*</strong> Indica que la pregunta es obligatoria.</p>
        </div>
        <hr>
        <div class="col-12 mb-3">
            <label for="programa_educativo" class="form-label"><strong style="color: red;">*</strong> Programa Educativo:</label>
            <select class="form-select form-select-lg mb-3" aria-label="Large select example" id="programa_educativo" name="programa_educativo">
                <option selected>Opciones...</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
            <div id="programaHelp" class="form-text">
                Por favor seleccione uno de los programas educativos.
            </div>
        </div>
        <hr>
        <div class="col-12 row shadow p-3 mb-5 bg-body-tertiary rounded">
            <h4 class="oswald-secondary">Datos Generales</h4>
            <div class="col-4 mt-3">
                <div class="mb-3">
                    <label for="nombre" class="form-label"> <strong style="color: red;">*</strong> Nombre(s):</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese su nombre...">
                </div>
            </div>
            <div class="col-4 mt-3">
                <div class="mb-3">
                    <label for="apellido_paterno" class="form-label"> <strong style="color: red;">*</strong> Apellido Paterno:</label>
                    <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" placeholder="Ingrese su apellido...">
                </div>
            </div>
            <div class="col-4 mt-3">
                <div class="mb-3">
                    <label for="apellido_materno" class="form-label"> <strong style="color: red;">*</strong> Apellido Materno:</label>
                    <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" placeholder="Ingrese su apellido...">
                </div>
            </div>
            <div class="col-6 mt-3">
                <div class="mb-3">
                    <label for="curp" class="form-label"> <strong style="color: red;">*</strong> CURP:</label>
                    <input type="text" class="form-control" id="curp" name="curp" placeholder="Ingrese su CURP, 18 elementos...">
                </div>
            </div>
            <div class="col-6 mt-3">
                <div class="mb-3">
                    <label for="rfc" class="form-label">RFC:</label>
                    <input type="text" class="form-control" id="rfc" name="rfc" placeholder="Ingrese su RFC, 13 elementos...">
                </div>
            </div>
            <div class="col-12 mt-2 justify-content-center">
                <label for="genero" class="form-label"><strong style="color: red;">*</strong> Genero:</label>
                <select class="form-select form-select-lg mb-3" aria-label="Default select example" id="genero" name="genero">
                    <option selected>Seleccione una opción...</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
            </div>
            <div class="col-6 mt-2">
                <label for="genero" class="form-label"><strong style="color: red;">*</strong> Estado Civil:</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="estado_civil" id="estado_civil1" value="option1" checked>
                    <label class="form-check-label" for="estado_civil1">
                        Soltero(a)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="estado_civil" id="estado_civil2" value="option2">
                    <label class="form-check-label" for="estado_civil2">
                        Divorsiad(a)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="estado_civil" id="estado_civil3" value="option2">
                    <label class="form-check-label" for="estado_civil3">
                        Viudo(a)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="estado_civil" id="estado_civil4" value="option2">
                    <label class="form-check-label" for="estado_civil4">
                        Casado(a)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="estado_civil" id="estado_civil5" value="option2">
                    <label class="form-check-label" for="estado_civil5">
                        Union libre
                    </label>
                </div>
            </div>
            <div class="col-6 mt-2">
                <label for="genero" class="form-label"><strong style="color: red;">*</strong> Número de Hijos:</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="numero_hijos" id="numero_hijos1" value="option1" checked>
                    <label class="form-check-label" for="numero_hijos1">
                        Ninguno
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="numero_hijos" id="numero_hijos2" value="option2">
                    <label class="form-check-label" for="numero_hijos2">
                        1 hijo(a)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="numero_hijos" id="numero_hijos3" value="option2">
                    <label class="form-check-label" for="numero_hijos3">
                        2 hijos(as)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="numero_hijos" id="numero_hijos4" value="option2">
                    <label class="form-check-label" for="numero_hijos4">
                        más de 2 hijos(as)
                    </label>
                </div>
            </div>
            <div class="col-6 mt-4">
                <label for="genero" class="form-label"><strong style="color: red;">*</strong> Económicamente alguien depende de ti:</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="economicamente" id="economicamente1" value="option1" checked>
                    <label class="form-check-label" for="economicamente1">
                        Si
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="economicamente" id="economicamente2" value="option2">
                    <label class="form-check-label" for="economicamente2">
                        No
                    </label>
                </div>
                <div id="economicamenteHelp" class="form-text">
                    Marca solo una opción.
                </div>
            </div>
            <div class="col-12 mt-3">
                <label for="religion" class="form-label"><strong style="color: red;">*</strong> Religión:</label>
                <select class="form-select form-select-lg mb-3" aria-label="Large select example" name="religion" id="religion">
                    <!-- Falta generar listado del INEGI -->
                    <option selected>Opciones...</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
            </div>
            <div class="col-12 mt-2">
                <label for="grupo_sanguineo" class="form-label"><strong style="color: red;">*</strong> Grupo Sanguíneo:</label>
                <select class="form-select form-select-lg mb-3" aria-label="Large select example" name="grupo_sanguineo" id="grupo_sanguineo">
                    <!-- Falta generar listado del INEGI -->
                    <option selected>Opciones...</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
            </div>
            <div class="col-6 mt-2">
                <div class="mt-3">
                    <label for="fn" class="form-label"> <strong style="color: red;">*</strong> Fecha de nacimiento:</label>
                    <input type="text" class="form-control" id="fn" name="fn">
                </div>
                <div id="economicamenteHelp" class="form-text">
                    Ejemplo: 7 de Enero de 2024.
                </div>
            </div>
            <div class="col-6 mt-2">
                <div class="mb-3">
                    <label for="edad" class="form-label"> <strong style="color: red;">*</strong> Edad:</label>
                    <input type="number" class="form-control" id="edad" name="edad" placeholder="Edad calculada" disabled>
                </div>
            </div>
            <div class="col-4 mt-3">
                <label for="pais_nacimiento" class="form-label"><strong style="color: red;">*</strong> País de nacimiento:</label>
                <select class="form-select mb-3" aria-label="Large select example" name="pais_nacimiento" id="pais_nacimiento">
                    <!-- Falta generar listado -->
                    <option selected>Opciones...</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
            </div>
            <div class="col-4 mt-3">
                <label for="pais_nacimiento" class="form-label"><strong style="color: red;">*</strong> Estado de nacimiento:</label>
                <select class="form-select mb-3" aria-label="Large select example" name="pais_nacimiento" id="pais_nacimiento">
                    <!-- Falta generar listado a partir del país seleccionado -->
                    <option selected>Opciones...</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
            </div>
            <div class="col-4 mt-3">
                <label for="pais_nacimiento" class="form-label"><strong style="color: red;">*</strong> Municipio de nacimiento:</label>
                <select class="form-select mb-3" aria-label="Large select example" name="pais_nacimiento" id="pais_nacimiento">
                    <!-- Falta generar listado a partir del estado seleccionado -->
                    <option selected>Opciones...</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
            </div>
        </div>
        <div class="col-12 row shadow p-3 mb-5 bg-body-tertiary rounded">
            <h4 class="oswald-secondary">Datos para Contactar</h4>
        </div>
        <div class="col-12 mt-2 d-flex justify-content-center">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="#">Anterior</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
                </ul>
            </nav>
        </div>
    </div>
</body>

<script src="../../../bootstrap/js/bootstrap.min.js"></script>

</html>

<a href="../../../app/Controllers/sessiondestroy_controller.php">
    <center><input type="submit" name="btningresar" class="btn btn-success" value="Cerrar sesión"></center>
</a>