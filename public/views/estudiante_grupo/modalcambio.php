<?php
$sqlEstuGrup_cambio = "SELECT e.id, e.activo, u.nombre, u.apellido_paterno, u.apellido_materno, g.nomenclatura AS nombreg, p.alias FROM estudiante_grupo AS e
INNER JOIN estudiantes as estu ON estu.id = e.estudiante_id
INNER JOIN usuarios AS u ON u.id = estu.usuario_id
INNER JOIN t_grupos AS g ON g.id = e.grupo_id
INNER JOIN periodos_escolar AS p ON p.id = e.periodo_id";

$estugrup_cambio = $conexion->query($sqlEstuGrup_cambio);

$sqlGrupos = "SELECT g.id, g.nomenclatura 
    FROM t_grupos g 
    INNER JOIN grupo_tutor  gt ON g.id = gt.grupo_id";
$grupos = $conexion->query($sqlGrupos);

$sqlPer = "SELECT id, alias FROM periodos_escolar";
$per = $conexion->query($sqlPer);
?>

<link rel="stylesheet" href="../../css/virtual-select.min.css">
<!-- Modal -->
<div class="modal fade" id="cambiomodal" tabindex="-1" aria-labelledby="cambiomodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="cambiomodalLabel">Cambio de Estudiante_Grupo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="../../../app/Controllers/Estudiante_grupo/cambio_estudiante_grupo.php" method="POST" enctype="multipart/form-data">
                    <div class="md-4">
                        <label for="validationCustom04" class="form-label">Estudiante</label>
                        <select multiple data-search="true" data-silent-initial-value-set="true" id="estudiante_cambio_id" name="estudiante_cambio_id[]" required>
                            <?php while ($estugrup = $estugrup_cambio->fetch_assoc()) { ?>
                                <option value="<?php echo $estugrup["id"] ?>"><?= $estugrup["nombre"] ?> <?= $estugrup["apellido_paterno"] ?> <?= $estugrup["apellido_materno"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="md-4">
                        <label for="validationCustom04" class="form-label">Grupo</label>
                        <select class="form-select" id="grupo_id" name="grupo_id" required>
                            <option value="">Seleccionar...</option>
                            <?php while ($row_grupos_cambio = $grupos->fetch_assoc()) { ?>
                                <option value="<?php echo $row_grupos_cambio["id"] ?>"><?= $row_grupos_cambio["nomenclatura"] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="md-4">
                        <label for="validationCustom04" class="form-label">Periodo escolar</label>
                        <select class="form-select" id="periodo_id" name="periodo_id" required>
                            <option value="">Seleccionar...</option>
                            <?php while ($row_per_cambio = $per->fetch_assoc()) { ?>
                                <option value="<?php echo $row_per_cambio["id"] ?>"><?= $row_per_cambio["alias"] ?></option>
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
        ele: '#estudiante_cambio_id',
        // Agregar más opciones de personalización si es necesario
    });
</script>
