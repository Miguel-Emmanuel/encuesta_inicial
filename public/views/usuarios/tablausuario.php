<?php
require '../../../database/conexion.php';

$sqlUsuarios = "SELECT u.id, u.nombre, u.apellido_paterno, u.apellido_materno, u.activo, r.nombre AS rol_id FROM usuarios AS u 
INNER JOIN roles as r ON u.rol_id=r.id WHERE u.rol_id IN (1,2,4) ORDER BY u.id ASC";
$users = $conexion->query($sqlUsuarios);

$sqlEstudiantes = "SELECT u.id, u.nombre, u.apellido_paterno, u.apellido_materno, u.activo, r.nombre AS rol_id FROM usuarios AS u 
INNER JOIN roles as r ON u.rol_id=r.id WHERE u.rol_id = 3 ORDER BY u.id ASC";
$estu = $conexion->query($sqlEstudiantes);
?>

<head>
    <title>Usuarios</title>
</head>

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
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row_users = $users->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row_users['id']; ?></td>
                    <td><?= $row_users['nombre']; ?></td>
                    <td><?= $row_users['apellido_paterno']; ?> <?= $row_users['apellido_materno']; ?></td>
                    <td><?= $row_users['rol_id']; ?></td>
                    <td><?= $row_users['activo'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
                    <td>
                        <a href="" class="btn btn-small btn-warning" data-bs-toggle="modal" data-bs-target="#editarmodal" data-bs-id="<?= $row_users['id']; ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="" class="btn btn-small btn-danger" data-bs-toggle="modal" data-bs-target="#eliminamodal" data-bs-id="<?= $row_users['id']; ?>"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
            <?php } ?>

        </tbody>
    </table>
    <h2 class="text-center">Usuarios Estudiantes</h2>
    <table class="table table-sm table-striped table-hover mt-4">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row_estu = $estu->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row_estu['id']; ?></td>
                    <td><?= $row_estu['nombre']; ?></td>
                    <td><?= $row_estu['apellido_paterno']; ?> <?= $row_estu['apellido_materno']; ?></td>
                    <td><?= $row_estu['rol_id']; ?></td>
                    <td><?= $row_estu['activo'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
                    <td>
                        <a href="" class="btn btn-small btn-danger" data-bs-toggle="modal" data-bs-target="#eliminamodal" data-bs-id="<?= $row_estu['id']; ?>"><i class="fa-solid fa-trash"></i></a>
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
        nuevomodal.querySelector('.modal-body #email').value = ""
        nuevomodal.querySelector('.modal-body #pass').value = ""
        nuevomodal.querySelector('.modal-body #rol_id').value = ""
    })

    editarmodal.addEventListener('hide.bs.modal', event => {
        editarmodal.querySelector('.modal-body #nombre_edita').value = ""
        editarmodal.querySelector('.modal-body #apellido_paterno_edita').value = ""
        editarmodal.querySelector('.modal-body #apellido_materno_edita').value = ""
        editarmodal.querySelector('.modal-body #email_edita').value = ""
        editarmodal.querySelector('.modal-body #pass_edita').value = ""
        editarmodal.querySelector('.modal-body #rol_id_edita').value = ""



    })

    editarmodal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')

        let inputId = editarmodal.querySelector('.modal-body #id_edita')
        let inputNombre = editarmodal.querySelector('.modal-body #nombre_edita')
        let inputApp = editarmodal.querySelector('.modal-body #apellido_paterno_edita')
        let inputApm = editarmodal.querySelector('.modal-body #apellido_materno_edita')
        let inputEmail = editarmodal.querySelector('.modal-body #email_edita')
        let inputPass = editarmodal.querySelector('.modal-body #pass_edita')
        let inputRol = editarmodal.querySelector('.modal-body #rol_id_edita')
        let inputRolHidden = editarmodal.querySelector('.modal-body #rol_id_hidden')

        let inputClave_sp = editarmodal.querySelector('.modal-body #clave_sp_edita')
        let inputTele = editarmodal.querySelector('.modal-body #tele')

        let url = "../../../app/Controllers/Usuarios/getUsers.php"
        let formData = new FormData()
        formData.append('id', id)

        fetch(url, {
                method: "POST",
                body: formData
            }).then(response => response.json())
            .then(data => {

                inputId.value = data.id
                inputNombre.value = data.nombre
                inputApp.value = data.apellido_paterno
                inputApm.value = data.apellido_materno
                inputEmail.value = data.email
                inputPass.value = data.pass
                inputRol.value = data.rol_id

                inputRolHidden.value = data.rol_id;

                if (data.rol_id == 2) {
                    inputClave_sp.value = data.clave_sp
                    inputTele.value = data.tele

                    document.getElementById('additionalFieldsTutor').style.display = 'block'
                    inputClave_sp.setAttribute('required', 'required');
                    inputTele.setAttribute('required', 'required');

                } else {
                    document.getElementById('additionalFieldsTutor').style.display = 'none'
                    inputClave_sp.removeAttribute('required');
                    inputTele.removeAttribute('required');
                }

            }).catch(err => console.log(err))
    })

    eliminamodal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')

        eliminamodal.querySelector('.modal-footer #id').value = id
    })
</script>