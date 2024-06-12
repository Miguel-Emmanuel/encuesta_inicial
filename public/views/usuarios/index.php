<?php
require '../../../app/Models/conexion.php';

$sqlUsuarios = "SELECT u.id, u.nombre, u.apellido_paterno, u.apellido_materno, u.matricula, u.carrera, r.nombre AS rol_id FROM usuarios AS u 
INNER JOIN roles as r ON u.rol_id=r.id";
$users = $conexion->query($sqlUsuarios);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/3aafa2d207.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container py-3">
        <h2 class="text-center">Usuarios</h2>
        <div class="row justify-content-end">
            <div class="col-auto">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevomodal">Nuevo Registro</a>
            </div>
        </div>
        <table class="table table-sm table-striped table-hover mt-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Matricula</th>
                    <th>Carrera</th>
                    <th>Rol</th>
                    <th>Acciones </th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row_users = $users->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row_users['id']; ?></td>
                        <td><?= $row_users['nombre']; ?></td>
                        <td><?= $row_users['apellido_paterno']; ?>  <?= $row_users['apellido_materno']; ?></td>
                        <td><?= $row_users['matricula']; ?></td>
                        <td><?= $row_users['carrera']; ?></td>
                        <td><?= $row_users['rol_id']; ?></td>
                        <td>
                            <a href="" class="btn btn-small btn-warning" data-bs-toggle="modal" data-bs-target="#editarmodal" data-bs-id="<?= $row_users['id']; ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="" class="btn btn-small btn-danger" data-bs-toggle="modal" data-bs-target="#eliminamodal" data-bs-id="<?= $row_users['id']; ?>"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
        <?php 
        $sqlRoles = "SELECT id, nombre FROM roles";
        $roles = $conexion->query($sqlRoles);
        ?>
    </div>


    <?php include "modalcreate.php"; ?>

    <?php 
    $roles->data_seek(0); 
    ?>

    <?php include "modaledit.php"; ?>
    <?php include "modalelimina.php"; ?>

    <script>
        let nuevomodal = document.getElementById('nuevomodal')
        let editarmodal = document.getElementById('editarmodal')
        let eliminamodal = document.getElementById('eliminamodal')

        nuevomodal.addEventListener('hide.bs.modal', event => {
            nuevomodal.querySelector('.modal-body #nombre').value = ""
            nuevomodal.querySelector('.modal-body #apellido_paterno').value = ""
            nuevomodal.querySelector('.modal-body #apellido_materno').value = ""
            nuevomodal.querySelector('.modal-body #matricula').value = ""
            nuevomodal.querySelector('.modal-body #carrera').value = ""
            nuevomodal.querySelector('.modal-body #email').value = ""
            nuevomodal.querySelector('.modal-body #pass').value = ""
            nuevomodal.querySelector('.modal-body #rol_id').value = ""
        })

        editarmodal.addEventListener('hide.bs.modal', event => {
            editarmodal.querySelector('.modal-body #nombre').value = ""
            editarmodal.querySelector('.modal-body #apellido_paterno').value = ""
            editarmodal.querySelector('.modal-body #apellido_materno').value = ""
            editarmodal.querySelector('.modal-body #matricula').value = ""
            editarmodal.querySelector('.modal-body #carrera').value = ""
            editarmodal.querySelector('.modal-body #email').value = ""
            editarmodal.querySelector('.modal-body #pass').value = ""
            editarmodal.querySelector('.modal-body #rol_id').value = ""
        })

        editarmodal.addEventListener('shown.bs.modal', event => {
            let button = event.relatedTarget
            let id = button.getAttribute('data-bs-id')

            let inputId = editarmodal.querySelector('.modal-body #id')
            let inputNombre = editarmodal.querySelector('.modal-body #nombre')
            let inputApp = editarmodal.querySelector('.modal-body #apellido_paterno')
            let inputApm = editarmodal.querySelector('.modal-body #apellido_materno')
            let inputMatri = editarmodal.querySelector('.modal-body #matricula')
            let inputCarre = editarmodal.querySelector('.modal-body #carrera')
            let inputEmail = editarmodal.querySelector('.modal-body #email')
            let inputPass = editarmodal.querySelector('.modal-body #pass')
            let inputRol = editarmodal.querySelector('.modal-body #rol_id')

            let url = "../../../app/Usuarios/getUsers.php"
            let formData = new FormData()
            formData.append('id',id)

            fetch(url,{
                method: "POST",
                body: formData
            }).then(response => response.json())
            .then(data => {

                inputId.value = data.id
                inputNombre.value = data.nombre
                inputApp.value = data.apellido_paterno
                inputApm.value = data.apellido_materno
                inputMatri.value = data.matricula
                inputCarre.value = data.carrera
                inputEmail.value = data.email
                inputPass.value = data.pass
                inputRol.value = data.rol_id

            }).catch(err => console.log(err))
        })

        eliminamodal.addEventListener('shown.bs.modal', event => {
            let button = event.relatedTarget
            let id = button.getAttribute('data-bs-id')

            eliminamodal.querySelector('.modal-footer #id').value = id
        })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>