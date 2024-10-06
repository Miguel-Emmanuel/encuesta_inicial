<!-- Modal -->
<div class="modal fade" id="editarmodal" tabindex="-1" aria-labelledby="editarmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editarmodalLabel">Editar Usuarios</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="../../../app/Controllers/Usuarios/actualiza.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id">
                    <div class="col-md-4">
                        <label for="validationDefault01" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre_edita" name="nombre" required>
                    </div>
                    <div class="col-md-4">
                        <label for="validationDefault02" class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" id="apellido_paterno_edita" name="apellido_paterno" required>
                    </div>
                    <div class="col-md-4">
                        <label for="validationDefault02" class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" id="apellido_materno_edita" name="apellido_materno" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email_edita" name="email" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" class="form-control" id="pass_edita" name="pass">
                    </div>
                    <div class="col-md-6">
                        <label for="validationCustom04" class="form-label">Rol del usuario</label>
                        
                        <select class="form-select" id="rol_id_edita" name="rol_id" required>
                            <option value="">Seleccionar...</option>
                            <?php while ($row_roles = $roles->fetch_assoc()) { ?>
                                <option value="<?php echo $row_roles["id"] ?>"><?= $row_roles["nombre"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div id="additionalFieldsEstudiante" style="display:none;">
                        <div class="col">
                            <label for="validationDefault01" class="form-label">Matricula:</label>
                            <input type="text" class="form-control" id="matricula_edita" name="matricula" required>
                        </div>
                        <div class="col">
                            <label for="validationDefault02" class="form-label">Telefono:</label>
                            <input type="text" class="form-control" id="telefono_edita" name="telefonoE" required>
                        </div>
                        <div class="col">
                            <label for="validationCustom04" class="form-label">Grupo:</label>
                            <select class="form-select" id="grupos_v_edita" name="grupos_v" required>
                                <option value="">Seleccionar...</option>
                                <?php while ($row_gruposv = $gruposv->fetch_assoc()) { ?>
                                    <option value="<?php echo $row_gruposv["id"] ?>"><?= $row_gruposv["nombregv"] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="validationDefault01" class="form-label">Genero:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="genero" id="generoH_edita" value="1">
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Hombre
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="genero" id="generoM_edita" value="2">
                                <label class="form-check-label" for="flexRadioDefault2">
                                    Mujer
                                </label>
                            </div>

                        </div>
                        <div class="col">
                            <label for="validationCustom04" class="form-label">I_Genero:</label>
                            <select class="form-select" id="i_genero_edita" name="i_genero" required>
                                <option value="">Seleccionar...</option>
                                <?php while ($row_igenero = $igenero->fetch_assoc()) { ?>
                                    <option value="<?php echo $row_igenero["id"] ?>"><?= $row_igenero["nombreig"] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div id="additionalFieldsTutor" style="display: none;">
                        <div class="mb-6">
                            <label for="exampleInputPassword1" class="form-label">Clave_SP</label>
                            <input type="text" class="form-control" id="clave_sp_edita" name="clave_sp">
                        </div>
                        <div class="col">
                            <label for="validationDefault01" class="form-label">Telefono:</label>
                            <input type="text" class="form-control" id="tele" name="telefono" required>
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
