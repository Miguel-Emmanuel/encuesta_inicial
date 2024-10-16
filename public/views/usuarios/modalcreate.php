<!-- Modal -->
<div class="modal fade" id="nuevomodal" tabindex="-1" aria-labelledby="nuevomodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="nuevomodalLabel">Registro de usuario</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="../../../app/Controllers/Usuarios/registro_usuarios.php" method="POST" enctype="multipart/form-data">
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
                    <div class="col-md-3">
                        <label for="validationCustom04" class="form-label">Rol del usuario</label>
                        <select class="form-select" id="rol_id" name="rol_id" onchange="showAdditionalFields()" required>
                            <option value="">Seleccionar...</option>
                            <?php while ($row_roles = $roles->fetch_assoc()) { ?>
                                <option value="<?php echo $row_roles["id"]?>"><?= $row_roles["nombre"] ?></option>
                            <?php } ?>

                        </select>
                    </div>
                    <input type="hidden" id="usuario_id" name="usuario_id" value="<?php echo $usuario_id; ?>">

                    <div id="additionalFieldsRol3" style="display: none;">
                        <div class="col">
                            <label for="validationDefault01" class="form-label">Matricula:</label>
                            <input type="text" class="form-control" id="matricula" name="matricula" required>
                        </div>

                    </div>

                    <div id="additionalFieldsRol2" style="display: none;">
                        <div class="mb-6">
                            <label for="exampleInputPassword1" class="form-label">Clave_SP</label>
                            <input type="text" class="form-control" id="clave_sp" name="clave_sp">
                        </div>
                        <div class="mb-6">
                            <label for="exampleInputPassword1" class="form-label">Telefono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono">
                        </div>
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
<script>
    function showAdditionalFields() {
        var rol = document.getElementById("rol_id").value;
        var additionalFieldsRol2 = document.getElementById("additionalFieldsRol2");

        var additionalFieldsRol3 = document.getElementById("additionalFieldsRol3");

        var additionalInputsRol2 = additionalFieldsRol2.querySelectorAll('input, select');
        var additionalInputsRol3 = additionalFieldsRol3.querySelectorAll('input, select');

        if (rol == 2) {
            additionalFieldsRol2.style.display = "block";
            additionalFieldsRol3.style.display = "none";

            additionalInputsRol2.forEach(function(input) {
                input.setAttribute('required', 'required');
            });

            additionalInputsRol3.forEach(function(input) {
                input.removeAttribute('required');
            });
        } else if (rol == 3) {
            additionalFieldsRol3.style.display = "block";
            additionalFieldsRol2.style.display = "none";

            additionalInputsRol3.forEach(function(input) {
                input.setAttribute('required', 'required');
            });

            additionalInputsRol2.forEach(function(input) {
                input.removeAttribute('required');
            });
        } else {
            additionalFieldsRol2.style.display = "none";
            additionalFieldsRol3.style.display = "none";

            additionalInputsRol2.forEach(function(input) {
                input.removeAttribute('required');
            });
            additionalInputsRol3.forEach(function(input) {
                input.removeAttribute('required');
            });
        }
    }
</script>