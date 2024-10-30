<?php
require '../../../database/conexion.php';

$sqlPeriodos = "SELECT id, alias, inicio, fin, activo FROM periodos_escolar";
$periodos = $conexion->query($sqlPeriodos);
?>
<div class="container py-3">
    <h2 class="text-center">Periodos Escolares</h2>
    <div class="row justify-content-end">
        <div class="col-auto">
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevomodal">Nuevo Registro</a>
        </div>
    </div>
    <table class="table table-sm table-striped table-hover mt-4">
        <thead>
            <tr>
                <th>#</th>
                <th>Alias</th>
                <th>Inicio del cuatrimestre</th>
                <th>Fin del cuatrimestre</th>
                <th>Estado</th>
                <th>Acciones </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row_periodos = $periodos->fetch_assoc()) { ?>             
                <tr>
                    <td><?= $row_periodos['id']; ?></td>
                    <td><?= $row_periodos['alias']; ?></td>
                    <td><?= $row_periodos['inicio']; ?></td>
                    <td><?= $row_periodos['fin']; ?></td>
                    <td><?= $row_periodos['activo'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
                    <td>
                        <a href="" class="btn btn-small btn-warning" data-bs-toggle="modal" data-bs-target="#editarmodal" data-bs-id="<?= $row_periodos['id']; ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="" class="btn btn-small btn-danger" data-bs-toggle="modal" data-bs-target="#eliminamodal" data-bs-id="<?= $row_periodos['id']; ?>"><i class="fa-solid fa-trash"></i></a>
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
        nuevomodal.querySelector('.modal-body #alias').value = ""
        nuevomodal.querySelector('.modal-body #inicio').value = ""
        nuevomodal.querySelector('.modal-body #fin').value = ""
    })

    editarmodal.addEventListener('hide.bs.modal', event => {
        editarmodal.querySelector('.modal-body #alias').value = ""
        editarmodal.querySelector('.modal-body #inicio').value = ""
        editarmodal.querySelector('.modal-body #fin').value = ""
    })

    editarmodal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')

        let inputId = editarmodal.querySelector('.modal-body #id')
        let inputAlias = editarmodal.querySelector('.modal-body #alias')
        let inputInicio= editarmodal.querySelector('.modal-body #inicio')
        let inputFin = editarmodal.querySelector('.modal-body #fin')

        let url = "../../../app/Controllers/Periodos/getPeriodos.php"
        let formData = new FormData()
        formData.append('id', id)

        fetch(url, {
                method: "POST",
                body: formData
            }).then(response => response.json())
            .then(data => {

                inputId.value = data.id
                inputAlias.value = data.alias
                inputInicio.value = data.inicio
                inputFin.value = data.fin
            }).catch(err => console.log(err))
    })

    eliminamodal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')

        eliminamodal.querySelector('.modal-footer #id').value = id
    })
</script>