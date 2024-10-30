<!-- Modal -->
<div class="modal fade" id="nuevomodal" tabindex="-1" aria-labelledby="nuevomodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="nuevomodalLabel">Registro de Grupo_Tutor</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="../../../app/Controllers/Grupo_tutor/registro_grupo_tutor.php" method="POST" enctype="multipart/form-data">
                    <div class="md-4">
                        <label for="validationCustom04" class="form-label">Tutor</label>
                        <select class="form-select" id="tutor_id" name="tutor_id" required>
                            <option value="">Seleccionar...</option>
                            <?php while ($row_tuto = $tuto->fetch_assoc()) { ?>
                                <option value="<?php echo $row_tuto["id"] ?>"><?= $row_tuto["nombre"] ?> <?= $row_tuto["apellido_paterno"] ?> <?= $row_tuto["apellido_materno"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="md-4">
                        <label for="validationCustom04" class="form-label">Grupo</label>
                        <select class="form-select" id="grupo_id" name="grupo_id" required>
                            <option value="">Seleccionar...</option>
                            <?php while ($row_grupos = $grupos->fetch_assoc()) { ?>
                                <option value="<?php echo $row_grupos["id"] ?>"><?= $row_grupos["nomenclatura"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="md-4">
                        <label for="validationCustom04" class="form-label">Periodo escolar</label>
                        <select class="form-select" id="periodo_id" name="periodo_id" required>
                            <option value="">Seleccionar...</option>
                            <?php while ($row_per = $per->fetch_assoc()) { ?>
                                <option value="<?php echo $row_per["id"] ?>"><?= $row_per["alias"] ?></option>
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