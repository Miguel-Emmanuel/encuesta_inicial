<?php
require '../../../app/Models/conexion.php';

$sqlRoles = "SELECT id, nombre, descripcion FROM roles";
$roles = $conexion->query($sqlRoles);
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
        <h2 class="text-center">Roles</h2>
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
                    <th>Descripción</th>
                    <th>Acciones </th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row_roles = $roles->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row_roles['id']; ?></td>
                        <td><?= $row_roles['nombre']; ?></td>
                        <td><?= $row_roles['descripcion']; ?></td>
                        <td>
                            <a href="" class="btn btn-small btn-warning" data-bs-toggle="modal" data-bs-target="#editarmodal" data-bs-id="<?= $row_roles['id']; ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="" class="btn btn-small btn-danger" data-bs-toggle="modal" data-bs-target="#eliminamodal" data-bs-id="<?= $row_roles['id']; ?>"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
    </div>


    <?php include "modalcreate.php"; ?>
    <?php include "modaledit.php"; ?>
    <?php include "modalelimina.php"; ?>

    <script>
        let nuevomodal = document.getElementById('nuevomodal')
        let editarmodal = document.getElementById('editarmodal')
        let eliminamodal = document.getElementById('eliminamodal')

        nuevomodal.addEventListener('hide.bs.modal', event => {
            nuevomodal.querySelector('.modal-body #nombre').value = ""
            nuevomodal.querySelector('.modal-body #descripcion').value = ""
        })

        editarmodal.addEventListener('hide.bs.modal', event => {
            editarmodal.querySelector('.modal-body #nombre').value = ""
            editarmodal.querySelector('.modal-body #descripcion').value = ""
        })

        editarmodal.addEventListener('shown.bs.modal', event => {
            let button = event.relatedTarget
            let id = button.getAttribute('data-bs-id')

            let inputId = editarmodal.querySelector('.modal-body #id')
            let inputNombre = editarmodal.querySelector('.modal-body #nombre')
            let inputDescripcion = editarmodal.querySelector('.modal-body #descripcion')

            let url = "../../../app/Roles/getRoles.php"
            let formData = new FormData()
            formData.append('id',id)

            fetch(url,{
                method: "POST",
                body: formData
            }).then(response => response.json())
            .then(data => {

                inputId.value = data.id
                inputNombre.value = data.nombre
                inputDescripcion.value = data.descripcion
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