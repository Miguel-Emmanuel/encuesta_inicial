<!-- Modal -->
<div class="modal fade" id="editarmodal" tabindex="-1" aria-labelledby="editarmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editarmodalLabel">Editar Usuarios</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="../../../app/Usuarios/actualiza.php" method="post" enctype="multipart/form-data">
                <input type="hidden" id="id" name="id">
                <div class="col-md-4">
                        <label for="validationDefault01" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="col-md-4">
                        <label for="validationDefault02" class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required>
                    </div>
                    <div class="col-md-4">
                        <label for="validationDefault02" class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" class="form-control" id="pass" name="pass">
                    </div>
                    <div class="mb-6">
                        <label for="exampleInputPassword1" class="form-label">Matricula</label>
                        <input type="text" class="form-control" id="matricula" name="matricula">
                    </div>
                    <div class="mb-6">
                        <label for="exampleInputEmail1" class="form-label">Carrera</label>
                        <input type="text" class="form-control" id="carrera" name="carrera" aria-describedby="emailHelp">
                    </div>
                    <div class="col-md-6">
                        <label for="validationCustom04" class="form-label">Rol del usuario</label>
                        <select class="form-select" id="rol_id" name="rol_id" required>
                            <option value="">Seleccionar...</option>
                            <?php while($row_roles = $roles->fetch_assoc()){ ?>
                           <option value="<?php echo $row_roles["id"]?>"><?= $row_roles["nombre"] ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>