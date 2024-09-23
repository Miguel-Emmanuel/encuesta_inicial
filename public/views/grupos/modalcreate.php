<!-- Modal -->
<div class="modal fade" id="nuevomodal" tabindex="-1" aria-labelledby="nuevomodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="nuevomodalLabel">Registro de grupos</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="../../../app/Grupos/registro_grupos.php" method="POST" enctype="multipart/form-data">
                    <div class="md-3">
                        <label for="validationDefault01" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="md-3">
                        <label for="validationCustom04" class="form-label">Programa educativo</label>
                        <select class="form-select" id="programa_e" name="programa_e" required>
                            <option value="">Seleccionar...</option>
                            <?php while ($row_programas = $programas->fetch_assoc()) { ?>
                                <option value="<?php echo $row_programas["id"] ?>"><?= $row_programas["nombre"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="md-3">
                        <label for="validationDefault02" class="form-label">Nomenclatura</label>
                        <input type="text" class="form-control" id="nomenclatura" name="nomenclatura" required>
                    </div>
                    <div class="md-3">
                        <label for="validationCustom04" class="form-label">Tutor</label>
                        <select class="form-select" id="tutor" name="tutor" required>
                            <option value="">Seleccionar...</option>
                            <?php while ($row_tutores = $tutores->fetch_assoc()) { ?>
                                <option value="<?php echo $row_tutores["id"] ?>"><?= $row_tutores["nombre"] ?> <?= $row_tutores["apellido_p"] ?> <?= $row_tutores["apellido_m"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="md-3">
                        <label for="validationCustom04" class="form-label">Periodo Escolar</label>
                        <select class="form-select" id="periodo_e" name="periodo_e" required>
                            <option value="">Seleccionar...</option>
                            <?php while ($row_periodos = $periodos->fetch_assoc()) { ?>
                                <option value="<?php echo $row_periodos["id"] ?>"><?= $row_periodos["inicio"] ?> / <?= $row_periodos["fin"] ?></option>
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