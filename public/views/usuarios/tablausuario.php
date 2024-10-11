<?php
require '../../../app/Models/conexion.php';

$sqlUsuarios = "SELECT u.id, u.nombre, u.apellido_paterno, u.apellido_materno, r.nombre AS rol_id FROM usuarios AS u 
INNER JOIN roles as r ON u.rol_id=r.id ORDER BY u.id ASC";
$users = $conexion->query($sqlUsuarios);
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

    $sqlGruposa = "SELECT id, nombregv FROM gruposv";
    $gruposv = $conexion->query($sqlGruposa);

    $sqlIgenero = "SELECT id, nombreig FROM i_genero";
    $igenero = $conexion->query($sqlIgenero);
    ?>
</div>


<?php include "modalcreate.php"; ?>

<?php
$roles->data_seek(0);
$gruposv->data_seek(0);
$igenero->data_seek(0);
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

        let inputId = editarmodal.querySelector('.modal-body #id')
        let inputNombre = editarmodal.querySelector('.modal-body #nombre_edita')
        let inputApp = editarmodal.querySelector('.modal-body #apellido_paterno_edita')
        let inputApm = editarmodal.querySelector('.modal-body #apellido_materno_edita')
        let inputEmail = editarmodal.querySelector('.modal-body #email_edita')
        let inputPass = editarmodal.querySelector('.modal-body #pass_edita')
        let inputRol = editarmodal.querySelector('.modal-body #rol_id_edita')

        let inputMatricula = editarmodal.querySelector('.modal-body #matricula_edita')
        let inputTelefono = editarmodal.querySelector('.modal-body #telefono_edita')
        let inputGruposV = editarmodal.querySelector('.modal-body #grupos_v_edita')
        let inputGenero = editarmodal.querySelector('.modal-body #genero_edita')
        let inputIGenero = editarmodal.querySelector('.modal-body #i_genero_edita')

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

                if (data.rol_id == 3) {
                    inputMatricula.value = data.matricula
                    inputTelefono.value = data.telefono
                    inputGruposV.value = data.grupos_v
                    if (data.genero == 0) {
                        document.getElementById('generoH_edita').checked = true; // Hombre
                    } else if (data.genero == 1) {
                        document.getElementById('generoM_edita').checked = true; // Mujer
                    }
                    inputIGenero.value = data.i_genero

                    document.getElementById('additionalFieldsEstudiante').style.display = 'block'
                    inputMatricula.setAttribute('required', 'required');
                    inputTelefono.setAttribute('required', 'required');
                    inputGruposV.setAttribute('required', 'required');
                    inputIGenero.setAttribute('required', 'required');


                    document.getElementById('additionalFieldsTutor').style.display = 'none'
                    inputClave_sp.removeAttribute('required');
                    inputTele.removeAttribute('required');

                } else if (data.rol_id == 2) {
                    inputClave_sp.value = data.clave_sp
                    inputTele.value = data.tele

                    document.getElementById('additionalFieldsTutor').style.display = 'block'
                    inputClave_sp.setAttribute('required', 'required');
                    inputTele.setAttribute('required', 'required');

                    document.getElementById('additionalFieldsEstudiante').style.display = 'none'
                    inputMatricula.removeAttribute('required');
                    inputTelefono.removeAttribute('required');
                    inputGruposV.removeAttribute('required');
                    inputIGenero.removeAttribute('required');

                } else {
                    document.getElementById('additionalFieldsEstudiante').style.display = 'none'
                    document.getElementById('additionalFieldsTutor').style.display = 'none'

                    inputMatricula.removeAttribute('required');
                    inputTelefono.removeAttribute('required');
                    inputGruposV.removeAttribute('required');
                    inputIGenero.removeAttribute('required');

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