<?php
require '../../../database/conexion.php';

$sqlGrupos = "SELECT g.id, g.nombre AS nombreG, g.nomenclatura, pedu.nombre AS programa_e FROM t_grupos AS g 
INNER JOIN programa_edu as pedu ON g.programa_e = pedu.id";
$grupos = $conexion->query($sqlGrupos);
?>

<head>
    <title>Grupos</title>
</head>

<div class="container py-3">
    <h2 class="text-center">Grupos</h2>
    <div class="row justify-content-end">
        <div class="col-auto">
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevomodal">Nuevo Registro</a>
        </div>
    </div>
    <table class="table table-sm table-striped table-hover mt-4">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre del grupo</th>
                <th>Programa educativo</th>
                <th>Nomenclatura</th>
                <th>Acciones </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row_grupos = $grupos->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row_grupos['id']; ?></td>
                    <td><?= $row_grupos['nombreG']; ?></td>
                    <td><?= $row_grupos['programa_e']; ?></td>
                    <td><?= $row_grupos['nomenclatura']; ?></td>
                    <td>
                        <a href="" class="btn btn-small btn-warning" data-bs-toggle="modal" data-bs-target="#editarmodal" data-bs-id="<?= $row_grupos['id']; ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="" class="btn btn-small btn-danger" data-bs-toggle="modal" data-bs-target="#eliminamodal" data-bs-id="<?= $row_grupos['id']; ?>"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
            <?php } ?>

        </tbody>
    </table>
    <?php
    $sqlPrograma = "SELECT id, nombre FROM programa_edu";
    $programas = $conexion->query($sqlPrograma);
    ?>
</div>


<?php include "modalcreate.php"; ?>

<?php
$programas->data_seek(0);
?>

<?php include "modaledit.php"; ?>
<?php include "modalelimina.php"; ?>

<script>
    let nuevomodal = document.getElementById('nuevomodal')
    let editarmodal = document.getElementById('editarmodal')
    let eliminamodal = document.getElementById('eliminamodal')

    nuevomodal.addEventListener('hide.bs.modal', event => {
        nuevomodal.querySelector('.modal-body #nombre').value = ""
        nuevomodal.querySelector('.modal-body #programa_e').value = ""
        nuevomodal.querySelector('.modal-body #nomenclatura').value = ""
    })

    editarmodal.addEventListener('hide.bs.modal', event => {
        editarmodal.querySelector('.modal-body #nombre').value = ""
        editarmodal.querySelector('.modal-body #programa_e').value = ""
        editarmodal.querySelector('.modal-body #nomenclatura').value = ""
    })

    editarmodal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')

        let inputId = editarmodal.querySelector('.modal-body #id')
        let inputNombre = editarmodal.querySelector('.modal-body #nombre')
        let inputPedu = editarmodal.querySelector('.modal-body #programa_e')
        let inputNomen = editarmodal.querySelector('.modal-body #nomenclatura')

        let url = "../../../app/Controllers/Grupos/getGrupos.php"
        let formData = new FormData()
        formData.append('id', id)

        fetch(url, {
                method: "POST",
                body: formData
            }).then(response => response.json())
            .then(data => {

                inputId.value = data.id
                inputNombre.value = data.nombre
                inputPedu.value = data.programa_e
                inputNomen.value = data.nomenclatura

            }).catch(err => console.log(err))
    })

    eliminamodal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')

        eliminamodal.querySelector('.modal-footer #id').value = id
    })
</script>