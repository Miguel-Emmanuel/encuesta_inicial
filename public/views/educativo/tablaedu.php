<?php
require '../../../database/conexion.php';

$sqlEducativo = "SELECT id, nombre, clave FROM programa_edu";
$educa = $conexion->query($sqlEducativo);
?>
<div class="container py-3">
    <h2 class="text-center">Programa Educativo</h2>
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
                <th>Clave</th>
                <th>Acciones </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row_educa = $educa->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row_educa['id']; ?></td>
                    <td><?= $row_educa['nombre']; ?></td>
                    <td><?= $row_educa['clave']; ?></td>
                    <td>
                        <a href="" class="btn btn-small btn-warning" data-bs-toggle="modal" data-bs-target="#editarmodal" data-bs-id="<?= $row_educa['id']; ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                        <a href="" class="btn btn-small btn-danger" data-bs-toggle="modal" data-bs-target="#eliminamodal" data-bs-id="<?= $row_educa['id']; ?>"><i class="fa-solid fa-trash"></i></a>
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
        nuevomodal.querySelector('.modal-body #grado').value = ""
        nuevomodal.querySelector('.modal-body #nombre').value = ""
        nuevomodal.querySelector('.modal-body #clave').value = ""
    })

    editarmodal.addEventListener('hide.bs.modal', event => {
        editarmodal.querySelector('.modal-body #grado').value = ""
        editarmodal.querySelector('.modal-body #nombre').value = ""
        editarmodal.querySelector('.modal-body #clave').value = ""
    })

    editarmodal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')

        let inputId = editarmodal.querySelector('.modal-body #id')
        let inputGrado = editarmodal.querySelector('.modal-body #grado')
        let inputNombre = editarmodal.querySelector('.modal-body #nombre')
        let inputClave = editarmodal.querySelector('.modal-body #clave')

        let url = "../../../app/Educativo/getEduca.php"
        let formData = new FormData()
        formData.append('id', id)

        fetch(url, {
                method: "POST",
                body: formData
            }).then(response => response.json())
            .then(data => {

                inputId.value = data.id
                inputGrado.value = data.grado
                inputNombre.value = data.nombre
                inputClave.value = data.clave
            }).catch(err => console.log(err))
    })

    eliminamodal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')

        eliminamodal.querySelector('.modal-footer #id').value = id
    })
</script>