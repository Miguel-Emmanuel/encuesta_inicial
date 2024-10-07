<!-- Modal -->
<div class="modal fade" id="editarmodal" tabindex="-1" aria-labelledby="editarmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editarmodalLabel">Editar Estudiante_Grupo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="../../../app/Controllers/Estudiante_grupo/actualiza.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id">
                    <div class="md-4">
                        <label for="validationCustom04" class="form-label">Estudiante</label>
                        <select class="form-select" id="estudiante_id" name="estudiante_id" required>
                            <option value="">Seleccionar...</option>
                            <?php while ($row_estu = $estuE->fetch_assoc()) { ?>
                                <option value="<?php echo $row_estu["id"] ?>"><?= $row_estu["nombre"] ?> <?= $row_estu["apellido_paterno"] ?> <?= $row_estu["apellido_materno"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="md-4">
                        <label for="validationCustom04" class="form-label">Grupo</label>
                        <select class="form-select" id="grupo_id" name="grupo_id" required>
                            <option value="">Seleccionar...</option>
                            <?php while ($row_grupos = $grupos->fetch_assoc()) { ?>
                                <option value="<?php echo $row_grupos["id"] ?>"><?= $row_grupos["nombre"] ?></option>
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