<?php
require '../../../database/conexion.php';

$sqlTutores = "SELECT t.id, t.nombre, t.apellido_p, t.apellido_m, t.clave_sp, t.correo, t.telefono FROM tutores AS t";
$tutor = $conexion->query($sqlTutores);
?>

<head>
    <title>Tutores</title>
</head>

<div class="container py-3">
    <h2 class="text-center">Tutores</h2>
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
                <th>Clave_SP</th>
                <th>Correo</th>
                <th>Telefono</th>
                <th>Acciones </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row_tutor = $tutor->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row_tutor['id']; ?></td>
                    <td><?= $row_tutor['nombre']; ?></td>
                    <td><?= $row_tutor['apellido_p']; ?> <?= $row_tutor['apellido_m']; ?></td>
                    <td><?= $row_tutor['clave_sp']; ?></td>
                    <td><?= $row_tutor['correo']; ?></td>
                    <td><?= $row_tutor['telefono']; ?></td>
                    <td>
                        <a href="" class="btn btn-small btn-warning" data-bs-toggle="modal" data-bs-target="#editarmodal" data-bs-id="<?= $row_tutor['id']; ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="" class="btn btn-small btn-danger" data-bs-toggle="modal" data-bs-target="#eliminamodal" data-bs-id="<?= $row_tutor['id']; ?>"><i class="fa-solid fa-trash"></i></a>
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
        nuevomodal.querySelector('.modal-body #apellido_p').value = ""
        nuevomodal.querySelector('.modal-body #apellido_m').value = ""
        nuevomodal.querySelector('.modal-body #clave_sp').value = ""
        nuevomodal.querySelector('.modal-body #correo').value = ""
        nuevomodal.querySelector('.modal-body #telefono').value = ""
    })

    editarmodal.addEventListener('hide.bs.modal', event => {
        editarmodal.querySelector('.modal-body #nombre').value = ""
        editarmodal.querySelector('.modal-body #apellido_p').value = ""
        editarmodal.querySelector('.modal-body #apellido_m').value = ""
        editarmodal.querySelector('.modal-body #clave_sp').value = ""
        editarmodal.querySelector('.modal-body #correo').value = ""
        editarmodal.querySelector('.modal-body #telefono').value = ""
    })

    editarmodal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')

        let inputId = editarmodal.querySelector('.modal-body #id')
        let inputNombre = editarmodal.querySelector('.modal-body #nombre')
        let inputApp = editarmodal.querySelector('.modal-body #apellido_p')
        let inputApm = editarmodal.querySelector('.modal-body #apellido_m')
        let inputClave = editarmodal.querySelector('.modal-body #clave_sp')
        let inputCorre = editarmodal.querySelector('.modal-body #correo')
        let inputTel = editarmodal.querySelector('.modal-body #telefono')

        let url = "../../../app/Tutores/getTutor.php"
        let formData = new FormData()
        formData.append('id', id)

        fetch(url, {
                method: "POST",
                body: formData
            }).then(response => response.json())
            .then(data => {

                inputId.value = data.id
                inputNombre.value = data.nombre
                inputApp.value = data.apellido_p
                inputApm.value = data.apellido_m
                inputClave.value = data.clave_sp
                inputCorre.value = data.correo
                inputTel.value = data.telefono

            }).catch(err => console.log(err))
    })

    eliminamodal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')

        eliminamodal.querySelector('.modal-footer #id').value = id
    })
</script>