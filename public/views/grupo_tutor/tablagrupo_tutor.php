<?php
require '../../../database/conexion.php';

$sqlGruTuto = "SELECT gt.id, u.nombre, u.apellido_paterno, u.apellido_materno, g.nomenclatura AS nombreg, gt.activo, p.alias FROM grupo_tutor AS gt
INNER JOIN tutores as tuto ON tuto.id = gt.tutor_id 
INNER JOIN usuarios AS u ON u.id = tuto.usuario_id
INNER JOIN t_grupos AS g ON g.id = gt.grupo_id
INNER JOIN periodos_escolar AS p ON p.id = gt.periodo_id";

$grututo = $conexion->query($sqlGruTuto);
?>

<head>
    <title>Grupo_Tutor</title>
</head>

<div class="container py-3">
    <h2 class="text-center">Grupo_Tutor</h2>
    <div class="row justify-content-end">
        <div class="col-auto">
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevomodal">Nuevo Registro</a>
        </div>
    </div>
    <table class="table table-sm table-striped table-hover mt-4">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre del tutor</th>
                <th>Grupo</th>
                <th>Periodo Educativo</th>
                <th>Estado</th>
                <th>Acciones </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row_grututo = $grututo->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row_grututo['id']; ?></td>
                    <td><?= $row_grututo['nombre']; ?> <?= $row_grututo['apellido_paterno']; ?> <?= $row_grututo['apellido_materno']; ?></td>
                    <td> <?= $row_grututo['nombreg']; ?></td>
                    <td> <?= $row_grututo['alias']; ?></td>
                    <td><?= $row_grututo['activo'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
                    <td>
                        <a href="" class="btn btn-small btn-warning" data-bs-toggle="modal" data-bs-target="#editarmodal" data-bs-id="<?= $row_grututo['id']; ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="" class="btn btn-small btn-danger" data-bs-toggle="modal" data-bs-target="#eliminamodal" data-bs-id="<?= $row_grututo['id']; ?>"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
            <?php } ?>

        </tbody>
    </table>
    <?php
    $sqltuto = "SELECT tut.id, u.nombre, u.apellido_paterno, u.apellido_materno FROM tutores AS tut 
    INNER JOIN usuarios AS u ON u.id = tut.usuario_id";
    $tuto = $conexion->query($sqltuto);

    $sqlGrupos = "SELECT id, nomenclatura FROM t_grupos WHERE id NOT IN (SELECT grupo_id FROM grupo_tutor)";
    $grupos = $conexion->query($sqlGrupos);

    $sqlGruposE = "SELECT id, nomenclatura FROM t_grupos";
    $gruposE = $conexion->query($sqlGruposE);

    $sqlPer = "SELECT id, alias FROM periodos_escolar";
    $per = $conexion->query($sqlPer);
    ?>
</div>


<?php include "modalcreate.php"; ?>

<?php
$tuto->data_seek(0);
$grupos->data_seek(0);
$gruposE->data_seek(0);
$per->data_seek(0);
?>

<?php include "modaledit.php"; ?>
<?php include "modalelimina.php"; ?>

<script>
    let nuevomodal = document.getElementById('nuevomodal')
    let editarmodal = document.getElementById('editarmodal')
    let eliminamodal = document.getElementById('eliminamodal')

    nuevomodal.addEventListener('hide.bs.modal', event => {
        nuevomodal.querySelector('.modal-body #tutor_id ').value = ""
        nuevomodal.querySelector('.modal-body #grupo_id').value = ""
        nuevomodal.querySelector('.modal-body #periodo_id').value = ""
    })

    editarmodal.addEventListener('hide.bs.modal', event => {
        editarmodal.querySelector('.modal-body #tutor_id ').value = ""
        editarmodal.querySelector('.modal-body #grupo_id').value = ""
        editarmodal.querySelector('.modal-body #periodo_id').value = ""
    })

    editarmodal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')

        let inputId = editarmodal.querySelector('.modal-body #id')
        let inputTutor = editarmodal.querySelector('.modal-body #tutor_id ')
        let inputGrupo = editarmodal.querySelector('.modal-body #grupo_id')
        let inputPerio = editarmodal.querySelector('.modal-body #periodo_id')

        let url = "../../../app/Controllers/Grupo_tutor/getgrupo_tutor.php"
        let formData = new FormData()
        formData.append('id', id)

        fetch(url, {
                method: "POST",
                body: formData
            }).then(response => response.json())
            .then(data => {

                inputId.value = data.id
                inputTutor.value = data.tutor_id 
                inputGrupo.value = data.grupo_id
                inputPerio.value = data.periodo_id

            }).catch(err => console.log(err))
    })

    eliminamodal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')

        eliminamodal.querySelector('.modal-footer #id').value = id
    })
</script>