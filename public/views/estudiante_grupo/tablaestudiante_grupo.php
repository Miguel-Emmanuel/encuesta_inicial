<?php
require '../../../database/conexion.php';

$sqlEstuGrup = "SELECT e.id, e.activo, u.nombre, u.apellido_paterno, u.apellido_materno, g.nomenclatura AS nombreg, p.alias 
                FROM estudiante_grupo AS e
                INNER JOIN estudiantes AS estu ON estu.id = e.estudiante_id
                INNER JOIN usuarios AS u ON u.id = estu.usuario_id
                INNER JOIN t_grupos AS g ON g.id = e.grupo_id
                INNER JOIN periodos_escolar AS p ON p.id = e.periodo_id
                WHERE e.activo = 1";


$estugrup = $conexion->query($sqlEstuGrup);
?>

<head>
    <title>Estudiante_Grupo</title>
</head>

<div class="container py-3">
    <h2 class="text-center">Estudiante_Grupo</h2>
    <div class="row justify-content-end">
        <div class="col-auto">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cambioGrupoModal">
    Cambio de Grupo
</button>

        </div>
        <div class="col-auto">
            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevomodal">Nuevo Registro</a>
        </div>
    </div>
    <?php
    //Mensaje de registro exitoso
    if(isset($_REQUEST['e'])){ ?>
	<div class="row pt-3">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Registro éxitoso!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <?php } ?>
    <table class="table table-sm table-striped table-hover mt-4">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre Estudiante</th>
                <th>Grupo</th>
                <th>Periodo Educativo</th>
                <th>Estado</th>
                <th>Acciones </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row_estugrup = $estugrup->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row_estugrup['id']; ?></td>
                    <td><?= $row_estugrup['nombre']; ?> <?= $row_estugrup['apellido_paterno']; ?> <?= $row_estugrup['apellido_materno']; ?></td>
                    <td> <?= $row_estugrup['nombreg']; ?></td>
                    <td> <?= $row_estugrup['alias']; ?></td>
                    <td><?= $row_estugrup['activo'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
                    <td>
                        <a href="" class="btn btn-small btn-warning" data-bs-toggle="modal" data-bs-target="#editarmodal" data-bs-id="<?= $row_estugrup['id']; ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="" class="btn btn-small btn-danger" data-bs-toggle="modal" data-bs-target="#eliminamodal" data-bs-id="<?= $row_estugrup['id']; ?>"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
            <?php } ?>

        </tbody>
    </table>
    <?php
    $sqlestuA = "SELECT estu.id, u.nombre, u.apellido_paterno, u.apellido_materno FROM estudiantes AS estu 
    INNER JOIN usuarios AS u ON u.id = estu.usuario_id 
    WHERE estu.id NOT IN (SELECT estudiante_id FROM estudiante_grupo)";
    $estu = $conexion->query($sqlestuA);

    $sqlestuE = "SELECT estu.id, u.nombre, u.apellido_paterno, u.apellido_materno FROM estudiantes AS estu 
    INNER JOIN usuarios AS u ON u.id = estu.usuario_id";
    $estuE = $conexion->query($sqlestuE);

    $sqlGrupos = "SELECT g.id, g.nomenclatura 
    FROM t_grupos g 
    INNER JOIN grupo_tutor  gt ON g.id = gt.grupo_id";
    $grupos = $conexion->query($sqlGrupos);

    $sqlPer = "SELECT id, alias FROM periodos_escolar";
    $per = $conexion->query($sqlPer);
    ?>
</div>


<?php include "modalcreate.php"; ?>
<?php include "modalcambio2.php"; ?>

<?php
$estu->data_seek(0);
$estuE->data_seek(0);
$grupos->data_seek(0);
$per->data_seek(0);
?>

<?php include "modaledit.php"; ?>
<?php include "modalelimina.php"; ?>

<script>
    let nuevomodal = document.getElementById('nuevomodal')
    let cambiomodal = document.getElementById('cambiomodal')
    let editarmodal = document.getElementById('editarmodal')
    let eliminamodal = document.getElementById('eliminamodal')

    nuevomodal.addEventListener('hide.bs.modal', event => {
        nuevomodal.querySelector('.modal-body #estudiante_id').value = ""
        nuevomodal.querySelector('.modal-body #grupo_id').value = ""
        nuevomodal.querySelector('.modal-body #periodo_id').value = ""
    })

    cambiomodal.addEventListener('hide.bs.modal', event => {
        cambiomodal.querySelector('.modal-body #estudiante_id').value = ""
        cambiomodal.querySelector('.modal-body #grupo_id').value = ""
        cambiomodal.querySelector('.modal-body #periodo_id').value = ""
    })

    editarmodal.addEventListener('hide.bs.modal', event => {
        editarmodal.querySelector('.modal-body #estudiante_id').value = ""
        editarmodal.querySelector('.modal-body #grupo_id').value = ""
        editarmodal.querySelector('.modal-body #periodo_id').value = ""
    })

    editarmodal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')

        let inputId = editarmodal.querySelector('.modal-body #id');
        let inputEstudi = editarmodal.querySelector('.modal-body #estudiante_id');
        let inputGrupo = editarmodal.querySelector('.modal-body #grupo_id');
        let inputPerio = editarmodal.querySelector('.modal-body #periodo_id');
        let inputTutorNombreCompleto = editarmodal.querySelector('.modal-body #tutor_nombre_completo');
        let inputTutorId = editarmodal.querySelector('.modal-body #tutor_id');

        let url = "../../../app/Controllers/Estudiante_Grupo/getestudiante_grupo.php"
        let formData = new FormData()
        formData.append('id', id)

        fetch(url, {
                method: "POST",
                body: formData
            }).then(response => response.json())
            .then(data => {

                inputId.value = data.id
                inputEstudi.value = data.estudiante_id
                inputGrupo.value = data.grupo_id
                inputPerio.value = data.periodo_id

                fetchTutorPorGrupo(data.grupo_id);

            }).catch(err => console.log(err))

            inputGrupo.addEventListener('change', function(){
                fetchTutorPorGrupo(this.value);
            });

            function fetchTutorPorGrupo(grupoId){
                if (grupoId){
                    let formData = new FormData();
                    formData.append('grupo_id',grupoId);

                    fetch('../../../app/Controllers/Estudiante_Grupo/getTutorPorGrupo.php',{
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(!data.tutor_nombre){
                            let tutorNombreCompleto = `${data.nombre} ${data.apellido_paterno} ${data.apellido_paterno}`;
                            inputTutorNombreCompleto.value = tutorNombreCompleto;
                            inputTutorId.value = data.id;
                        }else{
                            inputTutorNombreCompleto.value = '';
                            inputTutorId.value = '';
                            console.error(data.tutor_nombre);
                        }
                    })
                    .catch(err => console.error(err));
                }else{
                    inputTutorNombreCompleto.value = '';
                    inputTutorId.value = '';
                }
            }
    });

    eliminamodal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')

        eliminamodal.querySelector('.modal-footer #id').value = id
    })

    document.getElementById('grupo_id').addEventListener('change', function() {
        let grupoId = this.value;

        if (grupoId) {
            let formData = new FormData();
            formData.append('grupo_id', grupoId);

            fetch('../../../app/Controllers/Estudiante_grupo/getTutorPorGrupo.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.error) {
                        let tutorNombreCompleto = `${data.nombre} ${data.apellido_paterno} ${data.apellido_materno}`;
                        document.getElementById('tutor_nombre_completo').value = tutorNombreCompleto;

                        document.getElementById('tutor_id').value = data.id;

                    } else {
                        document.getElementById('tutor_nombre_completo').value = '';
                        document.getElementById('tutor_id').value = '';
                        console.error(data.error);
                    }
                })
                .catch(err => console.error(err));
        } else {
            document.getElementById('tutor_nombre_completo').value = '';
            document.getElementById('tutor_id').value = '';
        }
    });
</script>