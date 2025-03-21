<?php
// Aquí ya tienes la conexión a MongoDB, así que obtenemos los respaldos desde la colección en MongoDB
include("../../../database/mongo_conexion.php");

// Obtener los respaldos desde MongoDB
$respaldos = $collection->find([], ['sort' => ['fecha_creacion' => -1]]);
?>

<!-- Modal -->
<?php foreach ($respaldos as $backup): ?>
  <div class="modal fade" id="importBackdrop_<?php echo $backup['_id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Importar Respaldo del Dump a la Base de Datos</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Advertencia antes de importar -->
          <p class="text-danger">
            <strong>¡Advertencia!</strong> Esta acción es única e irreversible. Si no ha hecho un respaldo adecuado de su base de datos, podría perder información importante.
          </p>
          
          <table style="width: 100%;">
            <tr style="text-align:center;">
              <td>
                <form action="../../../database/exportar/importar.php" method="post" id="importForm_<?php echo $backup['_id'] ?>">
                  <input type="hidden" value="<?php echo $backup['_id'] ?>" name="id">
                  <input type="hidden" value="data" name="accion">
                  <button type="submit" class="btn btn-success w-75" onclick="confirmImport(<?php echo $backup['_id'] ?>)">Importar <?php echo $backup['nombre'] ?></button>
                </form>
              </td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>

<script>
  // Función para mostrar la confirmación
  function confirmImport(backupId) {
    const confirmation = confirm("¿Está seguro de que desea importar este respaldo? Esta acción es irreversible.");
    if (confirmation) {
      // Si se confirma, enviar el formulario
      document.getElementById("importForm_" + backupId).submit();
    }
  }
</script>
