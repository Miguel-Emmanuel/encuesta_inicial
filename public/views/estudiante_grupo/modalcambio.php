<link rel="stylesheet" href="../../css/virtual-select.min.css">
<!-- Modal -->
<div class="modal fade" id="cambiomodal" tabindex="-1" aria-labelledby="cambiomodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="cambiomodalLabel">Cambio de Grupo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="../../../app/Controllers/Estudiante_grupo/registro_estudiante_grupo.php" method="POST" enctype="multipart/form-data">
                    <div class="md-4">
                        <label for="validationCustom04" class="form-label">Estudiante</label>
                        <select multiple data-search="true" data-silent-initial-value-set="true" id="estudiante_id" name="estudiante_id[]" required>
                            <?php while ($row_estu = $estu->fetch_assoc()) { ?>
                                <option value="<?php echo $row_estu["id"] ?>"><?= $row_estu["nombre"] ?> <?= $row_estu["apellido_paterno"] ?> <?= $row_estu["apellido_materno"] ?></option>
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
                        <label for="tutor_nombre_completo" class="form-label">Tutor del grupo</label>
                        <input type="text" class="form-control" id="tutor_nombre_completo" name="tutor_nombre_completo" readonly>
                        <input type="hidden" id="tutor_id" name="tutor_id">
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

<script type="text/javascript" src="../../js/jquery.min.js"></script>
<script type="text/javascript" src="../../js/virtual-select.min.js"></script>

<script type="text/javascript">
    VirtualSelect.init({
        ele: '#estudiante_id'
        // ele: 'select'
    });
</script>