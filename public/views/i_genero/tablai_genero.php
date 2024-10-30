<?php
require '../../../database/conexion.php';

$sqlGrupos = "SELECT id, nombreig, descripcion FROM i_genero";
$grupos = $conexion->query($sqlGrupos);
?>

<head>
    <title>Generos</title>
</head>

<div class="container py-3">
    <h2 class="text-center">Generos</h2>
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
            <?php while ($row_grupos = $grupos->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row_grupos['id']; ?></td>
                    <td><?= $row_grupos['nombreig']; ?></td>
                    <td><?= $row_grupos['descripcion']; ?></td>
                    <td>
                        <a href="" class="btn btn-small btn-warning" data-bs-toggle="modal" data-bs-target="#editarmodal" data-bs-id="<?= $row_grupos['id']; ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="" class="btn btn-small btn-danger" data-bs-toggle="modal" data-bs-target="#eliminamodal" data-bs-id="<?= $row_grupos['id']; ?>"><i class="fa-solid fa-trash"></i></a>
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
        nuevomodal.querySelector('.modal-body #nombreig').value = ""
        nuevomodal.querySelector('.modal-body #descripcion').value = ""
    })

    editarmodal.addEventListener('hide.bs.modal', event => {
        editarmodal.querySelector('.modal-body #nombreig').value = ""
        editarmodal.querySelector('.modal-body #descripcion').value = ""
    })

    editarmodal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')

        let inputId = editarmodal.querySelector('.modal-body #id')
        let inputNombreig = editarmodal.querySelector('.modal-body #nombreig')
        let inputDes = editarmodal.querySelector('.modal-body #descripcion')

        let url = "../../../app/Controllers/I_genero/getIgenero.php"
        let formData = new FormData()
        formData.append('id', id)

        fetch(url, {
                method: "POST",
                body: formData
            }).then(response => response.json())
            .then(data => {

                inputId.value = data.id
                inputNombreig.value = data.nombreig
                inputDes.value = data.descripcion

            }).catch(err => console.log(err))
    })

    eliminamodal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')

        eliminamodal.querySelector('.modal-footer #id').value = id
    })
</script>